<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'sportstring' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '}H,e/yn{ #FC$b.c#?XSZ>@XSiqhm~-V81bgTeLcwj?8<QF`G0+_7xROiFzb//6X' );
define( 'SECURE_AUTH_KEY',  'L}<-T9NAlfoS+%bOP>LfKjnqJ*vozC7BiQLML@|Q[Ren.He#`Yp8WstI^6`m`8|I' );
define( 'LOGGED_IN_KEY',    'EK%DZfKjv?.S !@.X#[zjFJiVw{Sm9:6b(5P<>as4%`9KGPcurMgGb5)j3>=^%F_' );
define( 'NONCE_KEY',        'y L]y4J/@reT0eYxRpL7ooq=o2rt^<Z/_=@9}9Ld7*_7dU7+5X>aJ1|ma53<w2_+' );
define( 'AUTH_SALT',        ']kD>4dtpj6{wve$o!]HpfpPZ}jV$? K2rO:^B9YMiwz0O47F~ZTk7Z&i>IA-*}+j' );
define( 'SECURE_AUTH_SALT', 'f;o:mtyhS>ex,c;?O r`G)IZBuf,W,[i:&xb!.Mi*xmzIxpMNe&S.*sr9j&azUHf' );
define( 'LOGGED_IN_SALT',   '/Vr+J4g5>x1ZW6qhX7No0fz$d) /{V%nr8?2=>:CkbV5 Y}4Lp>k3X_.gQ#Q!=$j' );
define( 'NONCE_SALT',       'GRsY0O:bjo>K3,F&oB&F;&YF9DqtSlJ Oo^U;T!WE04_6m~A4/Sdab78mYECd|j;' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
