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
define('DB_NAME', 'db104199_alabs');

/** MySQL database username */
define('DB_USER', 'db104199_afri');

/** MySQL database password */
define('DB_PASSWORD', 'citoxe22');

/** MySQL hostname */
define('DB_HOST', 'internal-db.s104199.gridserver.com');

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
define('AUTH_KEY',         '%}4HS_>7-+SdGcXc(+`C4Z7IvS6et+DLt`{UC.?E S3<j_$)wpyk^x|=R:={O|eU');
define('SECURE_AUTH_KEY',  '%h_vB$6c3. Y`e5FV5rv`?Atidg 0$I*U+JeVFE175qS;P9l<A;jI-05OW->8SkH');
define('LOGGED_IN_KEY',    '+MEt|@_Tq-9q!zr 2a>0H7UIxUz(n1sQ?-Fno{28->R|WIe_VT.P+=L?B8_Zfb7g');
define('NONCE_KEY',        'xka!6:/FK(5WVVq5Ui+M9@mhO.^7vHs&UrwWiMKR5&kA5Z]Q/$4bR_?w<UNzAS=[');
define('AUTH_SALT',        '2wt,cYZm*.Z;U5&1]1b<aBz$?>LL$B<=}A91M^Q/En&|?.Iq_<~PFRT*;VK6@ES:');
define('SECURE_AUTH_SALT', 'NJ-n==oW^@?;E3[]?T}6T @zQe{1RN1VeE>OWR1[0hEUNxtfEZ %,4GeAI|Tih,.');
define('LOGGED_IN_SALT',   'O]ppW7+{rADf+{:marY-Rl jmPEO7&/W/}B.9S(1 >l|`bEh#b|QE1NbgK`>-y,9');
define('NONCE_SALT',       'M@|_Ps;^J:|(GMQ7sq3(W{h+s$I wET6=ETqzeU%5kFFdOq:Ge3r)>2A2Ci+k$NZ');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'afr2_';

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
