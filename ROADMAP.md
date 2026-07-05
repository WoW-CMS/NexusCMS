# NexusCMS Roadmap — 0.1.6 → 0.2.0

> Solo-developer focused. Phases are ordered by dependency, not by date.
> Every release ships a usable, non-broken version on a fresh install.

---

## The Arc

```
Anvil → Vault → Hearth → Spire → Nexus
 forge   hoard   gather   watch   connect
```

| Version | Codename    | Theme                                  | Headline                                                |
|---------|-------------|----------------------------------------|---------------------------------------------------------|
| 0.1.6   | **Anvil**   | Foundation. Shape the metal.            | Stability, refunds, hardened middleware, real tests.    |
| 0.1.7   | **Vault**   | Store of value. Hold it safely.         | Multi-gateway donations + real Store CRUD.              |
| 0.1.8   | **Hearth**  | Gathering place. The warm spot.         | Real-time notifications, profiles, 2FA, dark mode.      |
| 0.1.9   | **Spire**   | High vantage. The watchtower.           | Operational dashboard, backups 2.0, alert webhooks.     |
| 0.2.0   | **Nexus**   | The crossing point. The platform.       | Public REST API v1 + OAuth2 + theming + multi-tenant.   |

---

## Guiding Principles

1. **Ship a complete phase, not half of two.** Every release is usable end-to-end on a fresh install.
2. **Refactors before features.** Pay down debt as you go so the headline feature lands on solid ground.
3. **One big thing per milestone.** Smaller releases are mostly quality; one release carries the headline.
4. **Modules stay opt-in.** Nothing in a module becomes a blocker for the core install.
5. **No paid infrastructure required.** Every headline feature must work on a single VPS. Optional integrations (Discord, S3, etc.) are pluggable.

---

## 🟢 0.1.6 — **Anvil**

> Foundation. Where metal is shaped into something reliable.

### Stability & bug fixes

- Refunds flow for `DonationTransaction` (model column, controller method, admin button, `DonationPolicy`).
- `User->dp` / `User->vp` consistency — confirm columns exist, add accessor + cast if missing.
- Replace hardcoded dashboard counters in `app/Modules/Admin/Resources/views/index.blade.php` with real queries.
- Sanity sweep: every `@push('styles')` is well-formed; no inline `style=""` left; no duplicate `@section('content')` anywhere.

### Quality

- Mail on successful donation (`DonationReceiptMail` + queue).
- Rate-limit `donate.checkout` (`throttle:5,1`).
- PDF receipt via a queued Artisan command (`php artisan donate:receipt-pdf {tx}`).
- CSRF token meta + `axios` interceptor already in place — verify it's used everywhere there's AJAX (menu manager, route picker).
- Strengthen `SecurityHeaders` middleware: add `Permissions-Policy`, harden `Content-Security-Policy` with nonce for inline scripts.

### Testing

- Feature tests for `DonateController` (with mocked Braintree).
- Feature tests for `MenuManagerController` (CRUD + reorder).
- Feature tests for `UpdateService` (with mocked GitHub).
- Feature tests for `SecurityHeaders` middleware.
- Optional: browser tests (Pest + Laravel Dusk) for menu drag & drop and donate checkout.

### DX

- One-command install + seed: `php artisan nexus:install --demo`.
- `php artisan nexus:health` (uses `SystemInfoService` and reports issues).

### Done when

- All `must` and `should` items above are merged.
- No `alpha` / `beta` tag in `config/app.php` — just `0.1.6`.
- `CHANGELOG.md` lists every closed issue.

---

## 🔵 0.1.7 — **Vault**

> Store of value. Where money and modules accumulate safely.

### Headline: Multi-gateway donations with a unified interface

- Move `GatewayManager` behind a proper `DonationService` that handles:
  - Gateway selection.
  - Transaction lifecycle: `pending → completed / failed / refunded`.
  - Idempotency key.
  - Webhook routing.
- Add **Stripe** gateway (`StripeGateway implements PaymentGatewayInterface`).
- Add **PayPal** gateway (IPN + REST).
- Per-gateway settings tabs in `Settings → Payment`.
- Gateway enable/disable + sandbox toggle per gateway.

### Store: real persistence

- Replace JSON product list with a proper admin CRUD backed by `store_products` table (migration already exists).
- Admin UI: list, create, edit, archive products. Reuse the existing `GenericCrudController` machinery.
- Frontend `store.blade.php` reads from DB and filters by realm / type / category.
- Purchase history per user.

### Forum v2

- Thread subscriptions + email notifications.
- Reactions (curated emoji set, no third-party).
- Soft-delete with "deleted by" + restore for moderators.
- Per-forum permissions — finish what's already partially in place.

### Admin polish

- Audit log: who did what, when, from where. New `audit_logs` table + observer on every admin controller.
- Bulk actions on Users: ban, role change, send password reset.
- Bulk actions on Realms: test connection, sync characters.
- Saved filters on Users / Transactions.

### Refactor

- Extract a real `PermissionService` so policies stop reading settings directly.
- Centralize flash messages via a Blade component: `<x-flash type="success">…</x-flash>`.

### Done when

- A donation can be started in Braintree sandbox and completed in Stripe sandbox with the same UX.
- The admin can create, edit, archive, and unarchive a store product through the UI.
- Every admin write action appears in `audit_logs`.

---

## 🟣 0.1.8 — **Hearth**

> Gathering place. Where the community comes back to.

### Headline: Real-time notifications with Laravel Reverb

- Bell icon in the layout becomes functional.
- Channels:
  - New forum reply.
  - New donation received (admin).
  - New comment on a followed news post.
  - System alert.
- Server-side: broadcasting events.
- Client-side: a small JS listener that fetches and renders.
- Per-user preferences: in-app, email, both, none.

### News & content

- Featured image, gallery, tags.
- Scheduled publishing (`publish_at` + scheduler).
- RSS feed per category.
- Full-text search on news + forum threads (SQLite FTS5 or MySQL FULLTEXT).

### User-facing account

- Profile page: linked realms, donation history, forum threads, news comments.
- Notification center (in-app history).
- Two-factor auth (TOTP) — opt-in per user, mandatory for admins.

### Frontend

- Dark mode toggle with persisted preference.
- Locale switcher that respects URL (`/es/...`, `/en/...`) and user preference.
- PWA basics: manifest, service worker, offline fallback.

### Refactor

- Move all hardcoded English strings into `lang/en` + `lang/es` (donation and store views have a lot).
- Standardize icons: one set (FontAwesome subset or Lucide) across all modules.

### Done when

- An admin sees a notification within ~2 seconds of a new donation, both in the bell icon and via the optional Discord webhook.
- A forum subscriber receives an email and an in-app notification when a reply is posted.
- Dark mode preference survives a logout.

---

## 🟠 0.1.9 — **Spire**

> High vantage point. The watchtower over the whole operation.

### Headline: Operational dashboard & alerting

- Replace the current admin dashboard with a real ops view:
  - Error rate.
  - p95 response time.
  - Donation / hour.
  - Online players / realm.
  - Queue depth.
- Optional integrations:
  - Discord webhooks (admin alerts, new donations).
  - Telegram bot.
  - Email digests.
- SLO tracking: configurable thresholds, breach notifications.

### Backups 2.0

- Scheduled backups (Laravel scheduler).
- Off-site destinations: S3, Backblaze B2, FTP/SFTP.
- Encrypted backups (per-environment key).
- One-click restore to a chosen environment with a dry-run preview.

### Updates 2.0

- Pre-flight check before applying:
  - PHP version.
  - Required extensions.
  - Migration compatibility.
  - Disk space.
- Staged rollout: download → verify checksum → migrate → swap symlinks → rollback on failure.
- Update channels: `stable`, `beta`, `nightly`.

### Security

- IP allowlist for `/acp` (optional).
- Force password reset on next login for users flagged in a breach.
- Session hardening dashboard: active sessions, kill remote.

### DX

- `php artisan nexus:smoke` — boots the app, hits key routes, verifies DB + cache + queue, reports health.
- Generated OpenAPI spec for the internal admin API.
- Telemetry opt-in (anonymous version + module count for project stats).

### Done when

- A scheduled backup runs at 03:00 every night, ships to S3, and is restorable in a dry run.
- A breach of an SLO threshold pings a Discord channel within one minute.
- `nexus:smoke` exits non-zero when any dependency is broken.

---

## 🔴 0.2.0 — **Nexus**

> The crossing point. Where NexusCMS stops being a CMS and becomes a platform.

### Headline: Public REST API v1

- Versioned, OAuth2-protected (Passport or first-party OAuth via Sanctum).
- Resources:
  - Users (scoped).
  - Realms.
  - Characters.
  - News.
  - Forum threads.
  - Donations (read-only).
  - Store products.
- Webhooks:
  - `donation.completed`
  - `donation.refunded`
  - `user.created`
  - `forum.thread.created`
- Rate limiting per token.
- Public OpenAPI 3.1 spec served at `/api/docs` (using Scribe or Spectacle).
- Client SDKs: PHP, JavaScript / TypeScript (auto-generated from OpenAPI).
- Sandbox keys + live keys, per-key scopes.

### Theming & white-label

- Theme = Blade views + CSS + JSON config, packaged as a module.
- Three reference themes shipped: `default-dark`, `default-light`, `esports`.
- Theme marketplace metadata (manifest, preview image, version). Distribution itself is 0.3.x.

### Multi-tenant light

- Single installation serves multiple isolated realm communities, each with its own domain / subdomain, theme, and admin team.
- Tenant-aware cache keys, queue names, log channels.
- Cross-tenant prevention is test-covered.

### Breaking-change prep

- Drop Laravel 11 support, require Laravel 12.
- Drop PHP 8.2 support, require PHP 8.3+.
- Public API v1 contract frozen; deprecation policy documented.

### Refactor

- Extract core into `NexusCMS\Core` package consumable by both the monolithic app and a future slim headless install.
- Move admin module behind a feature flag so a headless install can run without it.

### Done when

- A partner can register an OAuth app, get a token, and list donations with one curl command.
- Two tenants on the same install cannot see each other's data, even through the admin panel.
- The OpenAPI spec builds and ships green from CI.

---

## ⚪ Stretch / Post-0.2.0 (parking lot)

These are real candidates but **explicitly out** of the 0.1.6 → 0.2.0 line. They get evaluated after 0.2.0 ships.

- **Theme marketplace** — distribution + payments for community themes.
- **Plugin marketplace** — discovery + signed distribution of community modules.
- **Mobile app** (React Native or Flutter) consuming the public API.
- **AI assistant module** — admin-side natural-language queries over the DB.
- **Mobile push** via the same Reverb channel, bridged through APNs / FCM.
- **Donation store frontends** as embeddable widgets (`<nexus-store realm="..."></nexus-store>`).

---

## Decision Log (assumptions made)

1. **Solo-dev throughput**: each phase is a few weeks of focused work, not months. Anything that doesn't fit in one phase gets split or parked.
2. **WoW private-server context stays primary**: the realm model, TrinityCore integration, and SOAP account creator stay first-class. Multi-game expansion is out of scope.
3. **No paid infrastructure required**: every headline feature works on a single VPS. Optional integrations are pluggable.
4. **Backwards compatibility within 0.1.x**: data and config migrations only when truly necessary. Schema-breaking changes are reserved for 0.2.0.
5. **English-first, Spanish-mirrored**: copy lives in `lang/en`, with `lang/es` updated as part of the same PR. No machine-translation pipelines in core.
6. **Solo-dev sanity**: each phase's "Done when" must be checkable by one person in one sitting. If it isn't, the phase is too big.

---

## How to use the codenames

### In `CHANGELOG.md`

```markdown
## 0.2.0 "Nexus" — Headless & Extensible

Public REST API v1 with OAuth2...
```

### In commit messages and PR titles

```
feat(api): release public REST v1 (0.2.0 "Nexus")
```

### In `config/app.php`

```php
'version'   => env('APP_VERSION', '0.2.0'),
'codename'  => env('APP_CODENAME', 'Nexus'),
```

The `SystemInfoService` already exposes the version; wire it to also expose the codename so the admin dashboard reads:

```
NexusCMS 0.2.0 · Nexus
```

---

## Micro-release convention

If a phase grows too large for one release slot, split it into a `.1` and `.2` keeping the same codename suffix:

| Release     | Codename        |
|-------------|-----------------|
| 0.1.7.0     | Vault           |
| 0.1.7.1     | Vault Patch     |
| 0.1.7.2     | Vault Reforge   |

Use the suffix only when the work is recognizably inside the same codename's theme. If you have to explain why a patch fits, it doesn't — cut a new minor instead.
