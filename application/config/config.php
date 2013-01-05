<?php
/*
 * Constantes
 */
define('GOOGLE_API_CLIENT_ID', '711947259237.apps.googleusercontent.com');
define('GOOGLE_API_CLIENT_SECRET', 'pD6bpXKmhxJx8BWxUjESWWOC');
define('GOOGLE_API_REDIRECT_URI', 'http://localhost/ingressmx/auth');
define('GOOGLE_API_SCOPE', 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email');

/*
 * Configuración del framework
 */
$config['session_name'] = 'ingressmx';
$config['error_magic_quotes_gpc'] = false;

$db_config['default']['host']     = 'localhost';
$db_config['default']['database'] = 'ingressmx';
$db_config['default']['user']     = 'root';
$db_config['default']['password'] = 'rootsql';
$db_config['default']['options']  = array();
$db_config['default']['charset']  = 'UTF8';

$routes = array(
  'profile' => 'home/profile',
  'logout' => 'home/logout'
);
