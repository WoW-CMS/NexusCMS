import axios from 'axios';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Read the CSRF token from the <meta name="csrf-token"> tag.
// Laravel emits this in the layout; without it, every POST/PUT/DELETE
// from axios would fail with 419. We log a warning (not throw) if the
// meta tag is missing so the page still loads — the warning is enough
// to catch the issue during development.
const csrfMeta = document.head.querySelector('meta[name="csrf-token"]');

if (csrfMeta && csrfMeta.content) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfMeta.content;
} else {
    // eslint-disable-next-line no-console
    console.warn(
        '[NexusCMS] <meta name="csrf-token"> not found. '
        + 'Any axios POST/PUT/DELETE will return 419. '
        + 'Make sure your layout includes @csrf or the meta tag explicitly.'
    );
}
