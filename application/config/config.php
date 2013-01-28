<?php
/*
 * Constrants
 */

// Email contact
define('INGRESSMX_EMAIL_SMTP_HOST', 'mail.ingress.mx');
define('INGRESSMX_EMAIL_SMTP_PORT', 26);
define('INGRESSMX_EMAIL_ACCOUNT', 'admin@ingress.mx');
define('INGRESSMX_EMAIL_ACCOUNT_NAME', 'ingress.mx');
define('INGRESSMX_EMAIL_PASSWORD', 'enlamadre');

// Google API access
define('INGRESSMX_GAPI_CLIENT_ID', '711947259237.apps.googleusercontent.com');
define('INGRESSMX_GAPI_CLIENT_SECRET', 'pD6bpXKmhxJx8BWxUjESWWOC');
define('INGRESSMX_GAPI_REDIRECT_URI', 'http://localhost/ingressmx/auth');
define('INGRESSMX_GAPI_SCOPE', 'https://www.googleapis.com/auth/userinfo.email');

// Paths
define('INGRESSMX_PATH_SCREENSHOTS', 'application/public/uploads/screenshots');

// Roles ID
define('INGRESSMX_ROLE_ADMIN', 1);
define('INGRESSMX_ROLE_PUBLISHER', 2);
define('INGRESSMX_ROLE_AGENT', 3);

// Post render styles
define('INGRESSMX_RENDER_STYLE_FRONT_PAGE', 1);
define('INGRESSMX_RENDER_STYLE_TEASER', 2);
define('INGRESSMX_RENDER_STYLE_FULL', 4);

/*
 * Framework
 */
$config['session_name'] = 'ingressmx';
$config['error_magic_quotes_gpc'] = false;
$config['auto_includes'] = array('ingressmx_functions.php');

$db_config['default']['host']     = 'localhost';
$db_config['default']['database'] = 'ingressmx';
$db_config['default']['user']     = 'root';
$db_config['default']['password'] = 'rootsql';
$db_config['default']['options']  = array();
$db_config['default']['charset']  = 'UTF8';

/*
 * Routes
 */
$routes = array(
  // Redirect index pagination to home/index/page
  'page(/([0-9]+))?' => 'home/index/$2',
  'categories(/([0-9]+)(/([0-9]+))?)' => 'categories/view/$2/$4',
  'post(/([0-9]+)(/([0-9]+))?)' => 'post/read/$2/$4',
);
