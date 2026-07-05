<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * Adds a hardened set of security response headers, including a
     * Content-Security-Policy with strict allowlists for scripts,
     * styles, fonts, images, frames and connections.
     *
     * Implementation notes:
     *  - A per-request nonce is generated and exposed as
     *    `$request->attributes->get('csp_nonce')` so views can opt in
     *    to a stricter CSP later by writing
     *    `<script nonce="{{ $csp_nonce }}">…</script>`. The nonce is
     *    **not** included in the CSP source list right now on purpose:
     *    per CSP spec, when a nonce is present, `'unsafe-inline'` is
     *    ignored — so until every inline script is migrated, the
     *    nonce would silently break the app.
     *  - `'unsafe-inline'` is currently allowed in script-src and
     *    style-src. It can be removed once every <script> and inline
     *    event handler is migrated to use the nonce (or bound via
     *    addEventListener).
     *  - `'unsafe-eval'` is whitelisted only in non-production because
     *    Vite's dev server ships ES modules that need it.
     *  - Cross-Origin-Opener-Policy (and the related COEP/CORP trio)
     *    are only emitted when the response's origin is a "potentially
     *    trustworthy origin" (HTTPS or localhost). On plain HTTP dev
     *    URLs the browser ignores them anyway and warns noisily in the
     *    console, so we just don't send the header.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Generate a per-request nonce even though we don't put it in
        // CSP yet, so views can start adopting it (`<script nonce="{{ $csp_nonce }}">`)
        // without a second middleware change.
        $nonce = Str::random(24);
        $request->attributes->set('csp_nonce', $nonce);

        /** @var Response $response */
        $response = $next($request);

        $isProduction     = app()->environment('production');
        $trustworthyOrigin = $this->isTrustworthyOrigin($request);

        // ─── Frame / sniffing / XSS basics ────────────────────────────────
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '0');
        // X-XSS-Protection is deprecated and can introduce vulnerabilities
        // in older browsers. Modern browsers honor CSP instead.
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // ─── Permissions Policy ──────────────────────────────────────────
        // Disable every powerful browser feature by default. Add back the
        // ones the app actually needs (none, currently).
        $response->headers->set('Permissions-Policy', implode(', ', [
            'accelerometer=()',
            'autoplay=()',
            'camera=()',
            'cross-origin-isolated=()',
            'display-capture=()',
            'encrypted-media=()',
            'fullscreen=(self)',
            'geolocation=()',
            'gyroscope=()',
            'keyboard-map=()',
            'magnetometer=()',
            'microphone=()',
            'midi=()',
            'payment=()',
            'picture-in-picture=()',
            'publickey-credentials-get=()',
            'screen-wake-lock=()',
            'sync-xhr=()',
            'usb=()',
            'xr-spatial-tracking=()',
        ]));

        // ─── Cross-origin isolation ──────────────────────────────────────
        // Browsers silently ignore these on non-trustworthy origins,
        // but they still log a console warning — so we don't send them
        // at all when serving plain HTTP from a non-localhost host.
        if ($trustworthyOrigin) {
            $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');
            $response->headers->set('Cross-Origin-Resource-Policy', 'same-origin');
        }

        // ─── HSTS — production only ──────────────────────────────────────
        // 1 year, include subdomains, eligible for preload. We only set
        // this in production because dev runs over plain HTTP.
        if ($isProduction) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains; preload'
            );
        }

        // ─── Content-Security-Policy ─────────────────────────────────────
        $csp = $this->buildContentSecurityPolicy($isProduction);
        $response->headers->set('Content-Security-Policy', $csp);

        // ─── Hide identifying headers ────────────────────────────────────
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        return $response;
    }

    /**
     * Build the Content-Security-Policy header value.
     *
     * Centralized so we can iterate on it without touching the rest of
     * the middleware. The list of allowed sources mirrors what the app
     * actually loads — nothing more, nothing less.
     *
     * TODO(security-hardening): the legacy menu manager, installer wizard,
     * admin update view, and donate checkout still rely on inline event
     * handlers and inline <script> blocks. Once those are migrated to
     * nonce'd scripts and addEventListener bindings, drop `'unsafe-inline'`
     * from `script-src` and `style-src` here. Then re-add `'nonce-…'`
     * (currently omitted to avoid the spec rule that says 'unsafe-inline'
     * is ignored when a nonce is present).
     *
     * CSP is intentionally still useful without removing 'unsafe-inline':
     * - `frame-ancestors 'self'` blocks clickjacking via iframe.
     * - `object-src 'none'` blocks Flash/Java applets (still required by PCI).
     * - `base-uri 'self'` blocks <base href> hijacking.
     * - `form-action 'self'` blocks form submission to attacker hosts.
     * - The per-origin allowlists for scripts/frames/connect prevent
     *   arbitrary third-party JS execution.
     */
    protected function buildContentSecurityPolicy(bool $isProduction): string
    {
        // Source list used by both production and dev CSP. The nonce is
        // intentionally NOT included right now (see class docblock) —
        // 'unsafe-inline' takes effect only when no nonce is present.
        $scriptSrc = [
            "'self'",
            // TODO(security-hardening): remove once every inline script
            // and inline event handler carries a nonce attribute. Until
            // then this is the load-bearing entry that keeps the app working.
            "'unsafe-inline'",
            // ── Third-party hosts actually used by the app ──────────────
            'https://unpkg.com',           // Alpine.js
            'https://cdn.ckeditor.com',    // CKEditor 5
            'https://cdnjs.cloudflare.com',// FontAwesome, etc.
            'https://kit.fontawesome.com', // FA kit
            'https://cdn.tailwindcss.com', // Tailwind CDN (maintenance page)
            'https://js.braintreegateway.com', // Braintree loader
            'https://assets.braintreegateway.com', // Braintree drop-in iframe scripts
        ];

        $styleSrc = [
            "'self'",
            // Tailwind via Vite emits inline styles in dev. Required
            // until the build pipeline inlines all stylesheets.
            "'unsafe-inline'",
            'https://fonts.googleapis.com',
            'https://cdnjs.cloudflare.com',
            'https://ka-f.fontawesome.com',
            'https://assets.braintreegateway.com', // Braintree drop-in CSS
        ];

        $fontSrc = [
            "'self'",
            'data:',
            'https://fonts.gstatic.com',
            'https://cdnjs.cloudflare.com',
            'https://ka-f.fontawesome.com',
        ];

        $imgSrc = [
            "'self'",
            'data:',
            'blob:',
            'https:',                       // Wowhead icons, ui-avatars, etc.
            'https://assets.braintreegateway.com', // Braintree drop-in card icons
        ];

        $connectSrc = [
            "'self'",
            // CKEditor may need to talk to its CDN for assets
            'https://cdn.ckeditor.com',
            // ── Braintree ───────────────────────────────────────────────
            // The drop-in UI loads in an iframe from assets.braintreegateway.com
            // and that iframe talks to the Braintree GraphQL endpoints below
            // for tokenization, 3DS, and PayPal flows. Without these in
            // connect-src the browser blocks those XHRs.
            'https://js.braintreegateway.com',
            'https://assets.braintreegateway.com',
            'https://payments.sandbox.braintree-api.com',
            'https://payments.braintree-api.com',
            'https://api.sandbox.braintreegateway.com',
            'https://api.braintreegateway.com',
            'https://client-analytics.sandbox.braintree-api.com',
            'https://client-analytics.braintree-api.com',
            // ── PayPal ──────────────────────────────────────────────────
            'https://www.paypal.com',
            'https://www.sandbox.paypal.com',
            // ── Stripe ──────────────────────────────────────────────────
            'https://api.stripe.com',
        ];

        $frameSrc = [
            "'self'",
            // Braintree hosted fields, PayPal smart buttons
            'https://js.braintreegateway.com',
            'https://assets.braintreegateway.com',
            'https://www.paypal.com',
            'https://hooks.stripe.com',
        ];

        $mediaSrc     = ["'self'"];
        $objectSrc    = ["'none'"];
        $baseUri      = ["'self'"];
        $formAction   = ["'self'"];
        $frameAncestors = ["'self'"];
        $manifestSrc  = ["'self'"];

        // Vite dev server needs unsafe-eval and the @vite host.
        if (!$isProduction) {
            $scriptSrc[] = "'unsafe-eval'";
            $scriptSrc[] = 'http://localhost:5173';
            $scriptSrc[] = 'ws://localhost:5173';
            $connectSrc[] = 'ws://localhost:5173';
            $connectSrc[] = 'http://localhost:5173';
        }

        $directives = [
            "default-src 'self'",
            "script-src " . implode(' ', $scriptSrc),
            "style-src " . implode(' ', $styleSrc),
            "font-src " . implode(' ', $fontSrc),
            "img-src " . implode(' ', $imgSrc),
            "connect-src " . implode(' ', $connectSrc),
            "frame-src " . implode(' ', $frameSrc),
            "media-src " . implode(' ', $mediaSrc),
            "object-src " . implode(' ', $objectSrc),
            "base-uri " . implode(' ', $baseUri),
            "form-action " . implode(' ', $formAction),
            "frame-ancestors " . implode(' ', $frameAncestors),
            "manifest-src " . implode(' ', $manifestSrc),
            $isProduction ? 'upgrade-insecure-requests' : '',
            'block-all-mixed-content',
        ];

        return implode('; ', array_filter($directives));
    }

    /**
     * Whether the response will be served from a "potentially trustworthy
     * origin" — i.e. one where the browser will honor headers like
     * Cross-Origin-Opener-Policy and Cross-Origin-Embedder-Policy.
     *
     * Per the HTML spec, trustworthy origins are:
     *  - HTTPS (any host)
     *  - localhost (any port)
     *  - 127.0.0.1 / ::1 (any port)
     *  - file:// (rare in web context)
     *
     * Reference: https://www.w3.org/TR/powerful-features/#potentially-trustworthy-origin
     */
    protected function isTrustworthyOrigin(Request $request): bool
    {
        if ($request->isSecure()) {
            return true;
        }

        $host = strtolower((string) $request->getHost());

        return in_array($host, ['localhost', '127.0.0.1', '[::1]', '::1'], true)
            || str_ends_with($host, '.localhost');
    }
}
