<?php
/*
 * Don't show deprecations
 */
error_reporting( E_ALL ^ E_DEPRECATED );

/**
 * Set root path
 */
$rootPath = realpath( __DIR__ . '/..' );

/**
 * Include the Composer autoload
 */
require_once( $rootPath . '/vendor/autoload.php' );

/**
 * Disallow on server file edits
 */
define( 'DISALLOW_FILE_EDIT', true );
define( 'DISALLOW_FILE_MODS', true );

/**
 * Force SSL
 */
define( 'FORCE_SSL_ADMIN', false );

/**
 * Limit post revisions
 */
define( 'WP_POST_REVISIONS', 3 );

/**
 * Define site and home URLs
 */
// HTTP is still the default scheme for now.

if ( defined( 'WP_CLI' ) && WP_CLI ) {
  $_SERVER['HTTP_HOST'] = $_SERVER['SERVER_NAME'] = 'betterwp';
}

$scheme = 'http';
// If we have detected that the end use is HTTPS, make sure we pass that
// through here, so <img> tags and the like don't generate mixed-mode
// content warnings.
if ( isset( $_SERVER['HTTP_USER_AGENT_HTTPS'] ) && $_SERVER['HTTP_USER_AGENT_HTTPS'] == 'ON' ) {
	$scheme = 'https';
}

$site_url = getenv( 'WP_HOME' ) !== false ? getenv( 'WP_HOME' ) : $scheme . '://' . $_SERVER['HTTP_HOST'] . '/';
define( 'WP_HOME', $site_url );
define( 'WP_SITEURL', $site_url . 'wp/' );

/**
 * Set Database Details
 */
// Include docksal settings.php when appropriate.
if (isset($_SERVER['DOCKSAL_LOCAL'])) {
	define( 'DB_NAME', getenv('MYSQL_DATABASE') );
	define( 'DB_USER', getenv('MYSQL_USER') );
	define( 'DB_PASSWORD', getenv('MYSQL_PASSWORD') );
	define( 'DB_HOST', getenv('MYSQL_HOST') );
	// Pass "https" protocol from reverse proxies
	if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
		$_SERVER['HTTPS'] = 'on';
	}
}
// Heroku settings.
elseif ($db_url_string = getenv('JAWSDB_URL')) {
	$url = parse_url($db_url_string);
	define( 'DB_NAME', ltrim($url['path'], '/') );
	define( 'DB_USER', $url['user'] );
	define( 'DB_PASSWORD', $url['pass'] );
	define( 'DB_HOST', $url['host'] );
}
else {
	define( 'DB_NAME', getenv( 'DB_NAME' ) );
	define( 'DB_USER', getenv( 'DB_USER' ) );
	define( 'DB_PASSWORD', getenv( 'DB_PASSWORD' ) !== false ? getenv( 'DB_PASSWORD' ) : '' );
	define( 'DB_HOST', getenv( 'DB_HOST' ) );
}
/**
 * Set debug modes
 */
define( 'WP_DEBUG', getenv( 'WP_DEBUG' ) === 'true' ? true : false );
define( 'IS_LOCAL', getenv( 'IS_LOCAL' ) !== false ? true : false );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         getenv( 'AUTH_KEY' ));
define('SECURE_AUTH_KEY',  getenv( 'SECURE_AUTH_KEY' ));
define('LOGGED_IN_KEY',    getenv( 'LOGGED_IN_KEY' ));
define('NONCE_KEY',        getenv( 'NONCE_KEY' ));
define('AUTH_SALT',        getenv( 'AUTH_SALT' ));
define('SECURE_AUTH_SALT', getenv( 'SECURE_AUTH_SALT' ));
define('LOGGED_IN_SALT',   getenv( 'LOGGED_IN_SALT' ));
define('NONCE_SALT',       getenv( 'NONCE_SALT' ));

require_once(dirname( __FILE__ ) . '/s3-config.php');

/*
* Define wp-content directory outside of WordPress core directory
*/
define( 'WP_CONTENT_DIR', dirname( __FILE__ ) . '/wp-content' );
define( 'WP_CONTENT_URL', WP_HOME . '/wp-content' );

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = getenv( 'DB_PREFIX' ) !== false ? getenv( 'DB_PREFIX' ) : 'wp_';

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}
/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
