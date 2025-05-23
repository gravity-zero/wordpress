<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en « wp-config.php » et remplir les
 * valeurs.
 *
 * Ce fichier contient les réglages de configuration suivants :
 *
 * Réglages MySQL
 * Préfixe de table
 * Clés secrètes
 * Langue utilisée
 * ABSPATH
 *
 * @link https://fr.wordpress.org/support/article/editing-wp-config-php/.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define( 'DB_NAME', 'data' );

/** Utilisateur de la base de données MySQL. */
define( 'DB_USER', 'root' );

/** Mot de passe de la base de données MySQL. */
define( 'DB_PASSWORD', 'pass' );

/** Adresse de l’hébergement MySQL. */
define( 'DB_HOST', 'wp_db' );

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/**
 * Type de collation de la base de données.
 * N’y touchez que si vous savez ce que vous faites.
 */
define( 'DB_COLLATE', '' );

/**#@+
 * Clés uniques d’authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clés secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'W-PF|vG/kS)=BJ&5f{.hXh):u!A>Y8mhlrlp Oc_%1^p5g=c,x?e s>_}oD{Me#g' );
define( 'SECURE_AUTH_KEY',  '<64]|y)xxB!,P2+n*!p$!?)#2N.| 3}&6[[|efL>lIa.AKLvar%M%c:*y!xb7IJ.' );
define( 'LOGGED_IN_KEY',    'Xq6g3x;2j}V/mut=q?8qSS|=})kLKLvKy-/agI_G5le3ra=>4}7aK[As[P-$B%lw' );
define( 'NONCE_KEY',        '?@?ovK~vq +wm I^xT+<B<HNy p h@~YgWsSNZlq+Ee0GcL>?3;Mgl7qPO$>pAtZ' );
define( 'AUTH_SALT',        'n16,zuN,/~fS<}oZ6N}|*<Ks*>-c();!r%+#3DpO3aNvE]9xFZ*<j18,-pO-0~#n' );
define( 'SECURE_AUTH_SALT', '.3*xs<2YTQhX0)JCo[Qd5eP:~vz/:L ^,(#MWHy0Q5hEH9==_S}K2qT,oNdjyQ>&' );
define( 'LOGGED_IN_SALT',   '|kw_Kp)gh,{7Xu`0f45&I;+/skBv_+!eDs#f40 !WDYoo0w0SFVRHDp -5Mc4j3)' );
define( 'NONCE_SALT',       'xr2xVqqGRvX_>&Za;|TnxN6bPcLQ1KuFg:P9[FB<#<|#yS,jfJkHNUtn<5/r`.VX' );
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix = 'wp_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l’affichage des
 * notifications d’erreurs pendant vos essais.
 * Il est fortement recommandé que les développeurs d’extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d’information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 *
 * @link https://fr.wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', true );

/* C’est tout, ne touchez pas à ce qui suit ! Bonne publication. */

/** Chemin absolu vers le dossier de WordPress. */
if ( ! defined( 'ABSPATH' ) )
  define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once( ABSPATH . 'wp-settings.php' );