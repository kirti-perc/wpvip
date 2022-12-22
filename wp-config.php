<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wp_demo_6' );

/** Database username */
define( 'DB_USER', 'admin' );

/** Database password */
define( 'DB_PASSWORD', 'admin' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

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
define( 'AUTH_KEY',         'q/jo Yp1J f%=lcTp,JHU8bGL1F<T`VOHhbl}s=@uCs~pcwmRm=ik!DD)C-b?u%a' );
define( 'SECURE_AUTH_KEY',  'U?N8K&Zq#H|y@CBT]*.<fICHun}XJb7rFobyviWH}9 VaJP/lbd3u[1.|x5pj3w#' );
define( 'LOGGED_IN_KEY',    'D!)V2RX:xe^Cl[QdGEHVZ[`ghMSW|j9a;[}PXbGc7lp<o)=fX,!zgQ8#NH~l.iz^' );
define( 'NONCE_KEY',        ',cL]]DseYk0^o#ML/D02h$qH5Vw6S3}*0,wv;:0cWh*k`>g_J@[*<*[O%-=p_-@G' );
define( 'AUTH_SALT',        'K{sm+wcDL]6x1ak@>8k:1$?UGQkU=;i`p0]vwO6{{F7C6PJw|@fh2d|;V>fcy&]g' );
define( 'SECURE_AUTH_SALT', '<NK$CFnQ}SI:YnuvI,O}m5Og(%FExS_%SVgB5@,n,3](TjoRfh8ByY;yLpCP1LtO' );
define( 'LOGGED_IN_SALT',   'cyiT(DRLI0Y>FC1e)(q!qs `ChHc:VsSK}! 4D^HSn8oOmJvoI>osp~#gKosY.6y' );
define( 'NONCE_SALT',       'sh/8Cppq@{*U+xi~v<(+pM4EmG#Vf:1y@f;0O{[WS4+rHe/kg/k.h.gGVN!<>3-d' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */

define( 'FS_METHOD', 'direct' );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
