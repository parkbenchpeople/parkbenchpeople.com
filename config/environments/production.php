<?php

use function Env\env;

Config::define('WP_ENV', 'production');
Config::define('WP_DEBUG', false);
Config::define('SCRIPT_DEBUG', false);
Config::define('DISALLOW_FILE_EDIT', true);

// Redis object cache
Config::define('WP_REDIS_HOST', env('REDIS_HOST') ?: '127.0.0.1');
Config::define('WP_REDIS_PORT', env('REDIS_PORT') ?: 6379);
Config::define('WP_REDIS_DATABASE', env('REDIS_DATABASE') ?: 0);
Config::define('WP_CACHE', true);

// WooCommerce: cart/checkout/account must stay out of full-page cache.
// Handled at the reverse-proxy/Cloudflare Page Rule / Cache Rule level, not in PHP —
// see infra/cloudflare-cache-rules.md once that's set up.

// Force SSL for admin + logins behind Cloudflare
Config::define('FORCE_SSL_ADMIN', true);
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
}

Config::apply();
