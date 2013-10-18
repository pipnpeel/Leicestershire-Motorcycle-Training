<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'LMT_WP');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'tun:f{D$D` SWI6iXJze[Uv3Z(7cXc 9pjS/W]GtwS7`>DHLF7eHa_4i5/1GJ*f,');
define('SECURE_AUTH_KEY',  ')XA(f6MO30mZ|BSHPvy xX@wT,e7p<8_%?-BYCe=,8z;ET*q^_0kgKd]<LUL&bBZ');
define('LOGGED_IN_KEY',    'WQwZ?(1P29RjUK>NlYNZ=shlDy-N@RHs=gF0C v%R]#7+E<c{lV@r}npDRL^.VOg');
define('NONCE_KEY',        'I.X@,z{vE>:)Ucyf|@188OcQ[,S)3$x@T&4D9xsQ9WqIq%862$lxlt^& QpQwT~1');
define('AUTH_SALT',        'o:W&BvFUS LTxl(UA>ir,Iq?qH,|tMrGCTcI5 &cXWwP-=iVj 7hVT7BAUX>,EJS');
define('SECURE_AUTH_SALT', 'h$K0CAokRWi3hVUs)vD= _6?i8nNfUlE7l;85g/][KUrcrY=&bo!KHz)ecRSH%Ac');
define('LOGGED_IN_SALT',   '<6B{zxtja+zen?HL=0h43J4Rf9IRGr?C.V.^@}B#IMOo/r6pjpnBYeyOEGHG#tQU');
define('NONCE_SALT',       'CF8>E:iSlt;,TtF7yt1f$rBe|m812a5a(IBXlYd~`t08jKrJfUO1!t7Vsd@TV+r,');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
