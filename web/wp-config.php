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
define('AUTH_KEY',         '|@B^ygfa~8=co#9eut56E1c,h};Fp|}PAO|xBi~L%nS$8.9/)%#QDO}N(!Y-E^zF');
define('SECURE_AUTH_KEY',  '-#_zHGwIjYA]^k~oh%+(./b-5H=xZ_]~1F0;8~42x}| <-[29ScC--CxBidE<037');
define('LOGGED_IN_KEY',    'XUwR6)&G&~g7q)L!qZ; ]D&AMZhL8-g`lW5j(3iw@ieSjqowbs)6)6@M.v+v9te6');
define('NONCE_KEY',        '<Y%GwtqGPP^IIDUF1%b4g|stz*1S0h;&:y!elJL:9%y8j1mmWyI1+hygr>Fc{JS-');
define('AUTH_SALT',        'T+xaTz-Gb8o7ATOm|&1ad^|EwKk$Lgg2>-r&e4NxS2E|EoA!?!t},l57O}KYdDyI');
define('SECURE_AUTH_SALT', '7@zB<Sk&LL|j2a; q/e8:Y<t%=kD|w[~}sIz@.wz-y=):TA|Y|$o9+?@qk>AE3iF');
define('LOGGED_IN_SALT',   '4||jkfL|%Co/4ck1-76DJ_E~jL4nP#?R&$8qD;*Uy7fctq{WhjL%wn**sugpY>Jl');
define('NONCE_SALT',       '$WC<aor+L=-e^iV-;-4G41!mirb1aT~2niRS+7A{gE|dX/V=E+adsH(IE/(lmn|S');


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
