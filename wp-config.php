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
define('DB_NAME', 'smartzero');

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
define('AUTH_KEY',         'mN>BnE&MP55w>(j!lh2_}Y``;)l//y&t?|1nyWTznA@}+YpJhxp>C4K(xEn!U4]!');
define('SECURE_AUTH_KEY',  '-p%xsL:aTO(Dcb4!bs7_+z?:-_C>>P*JP)iFKV+FQdXH=vEc}AW6LsxQr+DZmSH*');
define('LOGGED_IN_KEY',    'y{7^-CTXT+rp&A!+K%O~.gggaZ[8}4|@*W6uJDOJiYu_)`,WOgavY=TxBbF$bN$=');
define('NONCE_KEY',        'klWVTO~okx*^(|G%b={f 9R{A7_~*LC,W[:)PLBz|yT01+o=cp6It 2L/]XpfBU|');
define('AUTH_SALT',        '>Ev;JZ15BB;C}{vHnE. ^(;K?!?|ix~o>kpFE CQFu7<>%i@04:$S&_ SX?z$$3j');
define('SECURE_AUTH_SALT', ']p-Ts`N`}!Nd26ba`Qgz*<1:Qp;p>c&oyx]16FLs Gk)bOd@VqF|@Idn7bZjZF~w');
define('LOGGED_IN_SALT',   'BB/qwRhzW}Wbg-v>e%izy|G0km86&T~-`L&O:n^r4a9m)4/V.u}|bE(:,T1+Y]$1');
define('NONCE_SALT',       'D*TI`L%i`1/Yvw3O6ji1q$$C+k?Z8iq7^Q5fxcHA*D|G`.L?Jle6sLly{vkcX^Eg');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
