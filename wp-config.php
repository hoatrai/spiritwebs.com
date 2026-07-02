<?php

/**

 * The base configuration for WordPress

 *

 * The wp-config.php creation script uses this file during the installation.

 * You don't have to use the website, you can copy this file to "wp-config.php"

 * and fill in the values.

 *

 * This file contains the following configurations:

 *

 * * Database settings

 * * Secret keys

 * * Database table prefix

 * * ABSPATH

 *

 * This has been slightly modified (to read environment variables) for use in Docker.

 *

 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/

 *

 * @package WordPress

 */


// IMPORTANT: this file needs to stay in-sync with https://github.com/WordPress/WordPress/blob/master/wp-config-sample.php

// (it gets parsed by the upstream wizard in https://github.com/WordPress/WordPress/blob/f27cb65e1ef25d11b535695a660e7282b98eb742/wp-admin/setup-config.php#L356-L392)


// a helper function to lookup "env_FILE", "env", then fallback

if (!function_exists('getenv_docker')) {

	// https://github.com/docker-library/wordpress/issues/588 (WP-CLI will load this file 2x)
	//test

	function getenv_docker($env, $default) {

		if ($fileEnv = getenv($env . '_FILE')) {

			return rtrim(file_get_contents($fileEnv), "\r\n");

		}

		else if (($val = getenv($env)) !== false) {

			return $val;

		}

		else {

			return $default;

		}

	}

}


// ** Database settings - You can get this info from your web host ** //

/** The name of the database for WordPress */

define( 'DB_NAME', getenv("WORDPRESS_DB_NAME") ?: "wordpress" );


/** Database username */

define( 'DB_USER', getenv("WORDPRESS_DB_USER") ?: "user" );


/** Database password */

define( 'DB_PASSWORD', getenv("WORDPRESS_DB_PASSWORD") ?: "pass" );


/**

 * Docker image fallback values above are sourced from the official WordPress installation wizard:

 * https://github.com/WordPress/WordPress/blob/1356f6537220ffdc32b9dad2a6cdbe2d010b7a88/wp-admin/setup-config.php#L224-L238

 * (However, using "example username" and "example password" in your database is strongly discouraged.  Please use strong, random credentials!)

 */


/** Database hostname */

define( 'DB_HOST', getenv("WORDPRESS_DB_HOST") ?: "mysql" );


/** Database charset to use in creating database tables. */

define( 'DB_CHARSET', getenv_docker('WORDPRESS_DB_CHARSET', 'utf8') );


/** The database collate type. Don't change this if in doubt. */

define( 'DB_COLLATE', getenv_docker('WORDPRESS_DB_COLLATE', '') );


/**#@+

 * Authentication unique keys and salts.

 *

 * Change these to different unique phrases! You can generate these using

 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.

 *

 * You can change these at any point in time to invalidate all existing cookies.

 * This will force all users to have to log in again.

 *

 * @since 2.6.0

 */

define( 'AUTH_KEY',         getenv_docker('WORDPRESS_AUTH_KEY',         '1cc314290487137b1cd7d5f9624bb9334f0b6d57') );

define( 'SECURE_AUTH_KEY',  getenv_docker('WORDPRESS_SECURE_AUTH_KEY',  '084d4f94045d69b407eb7cfffd4143be7440138d') );

define( 'LOGGED_IN_KEY',    getenv_docker('WORDPRESS_LOGGED_IN_KEY',    '5b0262625e63cf8ba08806e70dd0588b14fcf701') );

define( 'NONCE_KEY',        getenv_docker('WORDPRESS_NONCE_KEY',        '1436f10e5400187013511a26f8be363c3748fa35') );

define( 'AUTH_SALT',        getenv_docker('WORDPRESS_AUTH_SALT',        '1addc1ab88cce2437b33bcde21c86926f9eef89d') );

define( 'SECURE_AUTH_SALT', getenv_docker('WORDPRESS_SECURE_AUTH_SALT', '303c8c3e0fb96fda5e3a5c29471174773469764f') );

define( 'LOGGED_IN_SALT',   getenv_docker('WORDPRESS_LOGGED_IN_SALT',   '2e3d1ef1a2bee51c33c764fd142797e4b43109d0') );

define( 'NONCE_SALT',       getenv_docker('WORDPRESS_NONCE_SALT',       '57ca7280e849c41d5cfdab631242add1cfcf8e2b') );

// (See also https://wordpress.stackexchange.com/a/152905/199287)


/**#@-*/


/**

 * WordPress database table prefix.

 *

 * You can have multiple installations in one database if you give each

 * a unique prefix. Only numbers, letters, and underscores please!

 *

 * At the installation time, database tables are created with the specified prefix.

 * Changing this value after WordPress is installed will make your site think

 * it has not been installed.

 *

 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix

 */

$table_prefix = getenv_docker('WORDPRESS_TABLE_PREFIX', 'wp_');


/**

 * For developers: WordPress debugging mode.

 *

 * Change this to true to enable the display of notices during development.

 * It is strongly recommended that plugin and theme developers use WP_DEBUG

 * in their development environments.

 *

 * For information on other constants that can be used for debugging,

 * visit the documentation.

 *

 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/

 */

define('WP_DEBUG', true);

define('WP_DEBUG_LOG', true);      // ghi log vào wp-content/debug.log

define('WP_DEBUG_DISPLAY', false);  // không hiện ra màn hình

define('DISABLE_WP_CRON', true);


/* Add any custom values between this line and the "stop editing" line. */


// If we're behind a proxy server and using HTTPS, we need to alert WordPress of that fact

// see also https://wordpress.org/support/article/administration-over-ssl/#using-a-reverse-proxy

if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strpos($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false) {

	$_SERVER['HTTPS'] = 'on';

}

// (we include this by default because reverse proxying is extremely common in container environments)


if ($configExtra = getenv_docker('WORDPRESS_CONFIG_EXTRA', '')) {

	eval($configExtra);

}

define('JWT_AUTH_SECRET_KEY', '28XZjCDW5TixLiCZbJanfCXG4Xp2EgsS');

define('JWT_AUTH_CORS_ENABLE', true);

// ===== SpiritWebs Configuration =====
define('SPIRIT_WEB_HOST', 'spiritwebs.okinawanew.com');
define('SPIRIT_SOCKET_HOST', 'socket.okinawanew.com');

define('SPIRIT_WEB_URL', 'https://' . SPIRIT_WEB_HOST);
define('SPIRIT_SOCKET_URL', 'https://' . SPIRIT_SOCKET_HOST);

define('WP_HOME', 'https://spiritwebs.okinawanew.com');
define('WP_SITEURL', 'https://spiritwebs.okinawanew.com');

/* That's all, stop editing! Happy publishing. */


/** Absolute path to the WordPress directory. */

if ( ! defined( 'ABSPATH' ) ) {

	define( 'ABSPATH', __DIR__ . '/' );

}


/** Sets up WordPress vars and included files. */

require_once ABSPATH . 'wp-settings.php';

