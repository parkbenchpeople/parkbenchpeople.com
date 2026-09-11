# Parkbench — Bedrock + Sage

Self-managed VPS deployment. Cloudflare (CDN + edge cache) in front of nginx,
Redis for object cache, WooCommerce for commerce.

## Stack
- **Bedrock** — WordPress project structure, Composer-managed, env-based config (`.env`, `config/environments/*.php`)
- **Sage 10** — theme, Blade templates, Tailwind CSS, Vite
- **WooCommerce** — commerce, live from day one
- **Redis** — object cache
- **Cloudflare** — CDN + full-page cache at the edge

## Caching zones (do not blur these)
- **Static-ish** (home, landing pages, articles, about): aggressive full-page cache.
- **Dynamic** (cart, checkout, my-account): excluded from page cache — Cloudflare Cache Rule bypass required. Never cache these routes.

## Performance rules
- Do not add client-side JavaScript unless the interaction genuinely requires it. Default is 0 JS per component.
- Prefer server-rendered Blade. Fetch data once in the controller/data layer — components render, they don't query.
- Do not add third-party JS libraries if a native browser API suffices.
- Never load block-specific or route-specific JS globally — split by `global.js` / `navigation.js` / `search.js` / etc., dynamic-import the rest.
- Use responsive WordPress image sizes (`srcset`/`sizes`) — never ship a full-res upload into a small card.
- Do not introduce render-blocking third-party scripts (analytics, chat, etc.) — defer/async by default.
- Keep public Gutenberg blocks server-rendered via Blade. React stays in the editor, not the frontend.
- Reuse existing Sage components before adding a new dependency.
- Host fonts locally as WOFF2, only the weights actually used.

## Deploy
`composer build-production` runs: theme `npm ci && npm run build` → `composer install --no-dev --optimize-autoloader` → `wp acorn optimize`.
See `.github/workflows/deploy.yml` for the CI pipeline (build → Lighthouse budget check → rsync to VPS → Cloudflare cache purge).

## Performance budgets (enforced in CI, see workflow)
- LCP < 2.0s, CLS < 0.05, INP < 150ms (homepage)
- Initial JS < 100KB gzip, Initial CSS < 75KB gzip
- Hero image < 250KB
