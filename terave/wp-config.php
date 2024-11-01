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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'soyelco1_wp_z7kxn' );

/** Database username */
define( 'DB_USER', 'soyelco1_wp_nluzl' );

/** Database password */
define( 'DB_PASSWORD', '15QeKR_X2JDS!5wt' );

/** Database hostname */
define( 'DB_HOST', 'localhost:3306' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define('AUTH_KEY', 'A@A+]Ip+WqCWD0ui2XmeKM7bKIe3oWYmC[im72f)D5;S(@09WsR]~_E5Z:I~:3@J');
define('SECURE_AUTH_KEY', '45qF0TS4XC;A7_A81X9+f44_8*x+36ILxj2nB3HL5:1KK;)R:%SLW_;6*4XEBZ5O');
define('LOGGED_IN_KEY', 'S_w)7[f4rS%B1E(fB6@Ed]%1sia9&f3b3UN%SL/dHdZ4*uza3//wx%V!IpY8eWBu');
define('NONCE_KEY', 'U57d41!/vM/-H~]/!|m]sKiKJmNpW1~d1;#V)1Obqj8Fdl08]f17%ryA1|]&j(-5');
define('AUTH_SALT', 'w8k-~g[G8ow(0]&tGKC4Pc87fpZXR70-0Gv5%8x~]MwCa1RQ7x8&]&1z&f%69e+g');
define('SECURE_AUTH_SALT', 'Op/(bo;6_oxUZ0-h0O8!+joVK718e%]]]aRiga6nBJ5TL5#9|@TzH0qF3e0s3877');
define('LOGGED_IN_SALT', ']T11bI19Tg4CF#;j1C]U~yW#rK33:zQI|62XieXW069tT)dMA5Q5YD9!n|k/33j1');
define('NONCE_SALT', '4[72E~m)L5ulZz2rX1WU(VM94+-j3&8L:4Mb:CQq3y4B78_N9I)3g9KrNxY@PG73');


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'nZ0ReDgW_';


/* Add any custom values between this line and the "stop editing" line. */

define('WP_ALLOW_MULTISITE', true);
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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', true );
}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
