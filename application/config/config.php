<?php
/*
 * Constrants
 */

// Google API access
define('INGRESSMX_GAPI_CLIENT_ID', '711947259237.apps.googleusercontent.com');
define('INGRESSMX_GAPI_CLIENT_SECRET', 'pD6bpXKmhxJx8BWxUjESWWOC');
define('INGRESSMX_GAPI_REDIRECT_URI', 'http://localhost/ingressmx/auth');
define('INGRESSMX_GAPI_SCOPE', 'https://www.googleapis.com/auth/userinfo.email');

// Paths
define('INGRESSMX_PATH_SCREENSHOTS', 'application/public/uploads/screenshots');

// Misc
define('INGRESSMX_DEFAULT_ROLE_ID', 3); // Agent role by default
define('INGRESSMX_RENDER_STYLE_FRONT_PAGE', 1);
define('INGRESSMX_RENDER_STYLE_TEASER', 2);
define('INGRESSMX_RENDER_STYLE_FULL', 4);
define('INGRESSMX_RENDER_STYLE_COMMENT', 8);

/*
 * Framework
 */
$config['session_name'] = 'ingressmx';
$config['error_magic_quotes_gpc'] = false;

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
