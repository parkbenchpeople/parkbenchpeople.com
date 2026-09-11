# Cloudflare Cache Rules — required manual setup

These live in the Cloudflare dashboard (Rules → Cache Rules), not in code, but are
required for the caching architecture described in CLAUDE.md.

## Rule 1 — Bypass cache on dynamic WooCommerce routes
Match:
```
(http.request.uri.path contains "/cart") or
(http.request.uri.path contains "/checkout") or
(http.request.uri.path contains "/my-account") or
(http.request.uri.path contains "/wp-admin") or
(http.request.uri.path contains "/wp-json")
```
Action: **Bypass cache**

## Rule 2 — Aggressive edge cache on everything else
Match: `hostname eq "parkbenchpeople.com"` (catch-all, lower priority than Rule 1)
Action: **Eligible for cache**, Edge TTL: respect origin headers or set 1 hour+ for static pages.

## Rule 3 — Long-lived cache for fingerprinted Vite assets
Match: `http.request.uri.path matches "^/app/themes/sage/public/build/.*"`
Action: Edge TTL 1 year, Browser TTL 1 year (filenames are content-hashed, safe to cache indefinitely).

## Notes
- Redis handles object caching server-side (see `config/environments/production.php`); Cloudflare handles the full-page/edge layer. These are two separate caches — don't conflate them.
- The deploy workflow purges the full Cloudflare cache on every release. Once traffic grows, switch to purge-by-tag instead of purge-everything to avoid cold-cache spikes after every deploy.
