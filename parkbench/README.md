# Parkbench — Bedrock + Sage

WordPress rebuild of parkbenchpeople.com on [Bedrock](https://roots.io/bedrock/) (project structure)
+ [Sage 10](https://roots.io/sage/) (Blade/Tailwind/Vite theme), deployed to a self-managed VPS
behind Cloudflare, with Redis object caching and WooCommerce.

## Local setup
```bash
composer install
cp .env.example .env   # fill in DB + salts (https://roots.io/salts.html)
cd web/app/themes/sage && composer install && npm install && npm run dev
```

## Structure
```
composer.json                     Bedrock deps (WP core, plugins via WPackagist, Sage, Acorn)
config/environments/production.php   Env-specific WP config (Redis, SSL, debug flags)
web/app/themes/sage/               Sage theme (Blade + Tailwind + Vite) — added via `composer create-project roots/sage`
web/app/mu-plugins/                Must-use plugins
.github/workflows/deploy.yml       CI: build → Lighthouse budget gate → rsync to VPS → Cloudflare purge
infra/cloudflare-cache-rules.md    Manual Cloudflare dashboard config (page cache bypass for cart/checkout/account)
CLAUDE.md                          Performance + architecture rules for AI-assisted changes
```

## Required GitHub Secrets (Settings → Environments → production)
| Secret | Purpose |
|---|---|
| `VPS_HOST` | VPS IP/hostname |
| `VPS_USER` | SSH deploy user |
| `VPS_SSH_KEY` | Private key for deploy user |
| `VPS_DEPLOY_PATH` | Absolute path to the site root on the VPS |
| `CLOUDFLARE_ZONE_ID` | Zone ID for parkbenchpeople.com |
| `CLOUDFLARE_API_TOKEN` | Token with Cache Purge permission |

## Not yet scaffolded
- Sage theme itself (`composer create-project roots/sage web/app/themes/sage`) — pulls from Packagist, run this once repo is live
- VPS provisioning (nginx, PHP-FPM, MySQL, Redis install) — see `infra/` for that once we get to it
- Cloudflare Cache Rules — manual dashboard setup, documented in `infra/cloudflare-cache-rules.md`
