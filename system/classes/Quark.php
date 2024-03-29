<?php
/**
 * QuarkPHP Framework
 * Copyright (C) 2012 Sahib Alejandro Jaramillo Leo
 *
 * @link http://quarkphp.com
 * @license GNU General Public License (http://www.gnu.org/licenses/gpl.html)
 */

/**
 * Clase bootstrap
 * @author sahib
 */
class Quark
{
  /**
   * Directivas de configuración
   * @see Quark::getConfigVal()
   * @var array
   */
  private static $_config;

  private static $_db_config;

  private static $_routes;

  private static $_called_controller;

  private static $_called_action;

  const VERSION = '3.5.6';

  /**
   * Bootstrap
   */
  public static function bigBang()
  {
    ini_set('display_errors', 1);

    /**
     * @deprecated Used for backward compatibilty, will be removed on version 3.6
     */
    define('QUARK_VERSION', self::VERSION);

    /* --------------------------------------------------
     * Validar versión minima de PHP 5.1
     */
    $php_min_version = '5.1';
    if (version_compare(PHP_VERSION, $php_min_version) < 0) {
      header('Content-Type: text/plain');
      die('Sorry, QuarkPHP needs PHP version '. $php_min_version
        . ' or higher, your current PHP version is '. PHP_VERSION
      );
    }

    /* --------------------------------------------------
     * Autoload classes
     */
    function quarkphp_autoload($class_name)
    {
      foreach (Quark::getConfigVal('class_paths') as $path) {
        if (is_file("$path/$class_name.php")) {
          require "$path/$class_name.php";
          break;
        }
      }
    }
    
    // Use SPL Autload if is available
    if (function_exists('spl_autoload_register')) {
      spl_autoload_register('quarkphp_autoload');
    } else {
      function __autoload($class_name)
      {
        quarkphp_autoload($class_name);
      }
    }

    /* --------------------------------------------------
     * Inicializar output buffer handler
     */
    ob_start(array('Quark', 'obHandler'));
    
    /* --------------------------------------------------
     * Definir include paths para buscar archivos primero en "application" y
     * despues en "system"
     */
    $include_paths = PATH_SEPARATOR . 'application';
    $include_paths .= PATH_SEPARATOR . 'system';

    set_include_path(get_include_path() . PATH_SEPARATOR . $include_paths);

    /* --------------------------------------------------
     * Cargar configuración de system y sobre escribir por configuracion
     * de application
     */
    $config = $db_config = array();
    require 'system/config/config.php';
    if(is_file('application/config/config.php')){
      require 'application/config/config.php';
    }
    self::$_config    = $config;
    self::$_routes    = $routes;
    self::$_db_config = $db_config;


    /* --------------------------------------------------
     * Configurar el entorno
     */
    ini_set('error_prepend_string', '<quarkerror>');
    ini_set('error_append_string', '</quarkerror>');
    error_reporting(self::$_config['error_reporting']);
    date_default_timezone_set(self::$_config['time_zone']);

    if(self::$_config['lc_all'] !== null){
      setlocale(LC_ALL, self::$_config['lc_all']);
    } else {
      setlocale(LC_COLLATE  , self::$_config['lc_collate']);
      setlocale(LC_CTYPE    , self::$_config['lc_ctype']);
      setlocale(LC_MONETARY , self::$_config['lc_monetary']);
      setlocale(LC_NUMERIC  , self::$_config['lc_numeric']);
      setlocale(LC_TIME     , self::$_config['lc_time']);

      // Only available if PHP was compiled with libintl
      if(isset(self::$_config['lc_messages'])){
        setlocale(LC_MESSAGES , self::$_config['lc_messages']);
      }
    }

    /* --------------------------------------------------
     * Agregar class paths para __autoload()
     */
    self::$_config['class_paths'] = array_merge(array(
      'system/classes',
      'application/classes',
      'application/orm',
      'application/controllers',
      'system/controllers'), self::$_config['class_paths']);

    /* --------------------------------------------------
     * Definir algunas clases
     */
    $QuarkStr = new QuarkStr();

    /* --------------------------------------------------
     * Más constantes...
     */
    define('QUARK_ROOT_PATH', dirname($_SERVER['SCRIPT_FILENAME']));
    define('QUARK_APP_PATH', QUARK_ROOT_PATH . '/application');
    define('QUARK_SYS_PATH', QUARK_ROOT_PATH . '/system');
    define('QUARK_APP_DIR'
      , '/' . $QuarkStr->cleanPath(dirname($_SERVER['SCRIPT_NAME'])));
    
    define(
      'QUARK_AJAX',
      (isset($_POST['quark_ajax']) || isset($_GET['quark_ajax']))
    );
    define('QUARK_DEBUG', self::$_config['debug']);
    define('QUARK_MULTILANG', !empty(self::$_config['langs']));
    define('QUARK_FRIENDLY_URL', isset($_GET['quark_path_info']));
    define('QUARK_LANG_ON_SUBDOMAIN', self::$_config['lang_on_subdomain']);
    
    /* --------------------------------------------------
     * Magic quotes handle
     */
    if (function_exists('get_magic_quotes_gpc')) {
      if (self::$_config['error_magic_quotes_gpc'] && get_magic_quotes_gpc()) {
        trigger_error(
          "Magic quotes is activated. To ignore this message set \$config['error_magic_quotes_gpc'] to false in config.php file.",
          E_USER_ERROR
        );
      }
    }
    
    // Disable runtime magic quotes
    if (function_exists('set_magic_quotes_runtime')) {
      set_magic_quotes_runtime(0);
    }

    /* --------------------------------------------------
     * Leer path info
     */
    $PathInfo = self::inst('QuarkURL')->getPathInfo();

    /* --------------------------------------------------
     * Agregar archivos de "includes" automaticos.
     */
    self::$_config['auto_includes'][] = 'jsonwrapper/jsonwrapper.php';

    foreach (self::$_config['auto_includes'] as $include_file) {
      require_once 'includes/' . $include_file;
    }

    /* --------------------------------------------------
     * Instanciar controlador
     */
    $controller_name = $QuarkStr->toUpperCamelCase($PathInfo->controller);
    $action_name     = $QuarkStr->toLowerCamelCase($PathInfo->action);

    if (!file_exists(
      QUARK_APP_PATH . "/controllers/{$controller_name}Controller.php")) {
      // No existe el controlador solicitado, invocamos a QuarkController
      // para tener acceso a los metodos __quarkNotFound y __quarkAccessDenied
      $controller_name = 'Quark';
    }

    $controller_class_name = $controller_name . 'Controller';
    
    /**
     * @var $Controller QuarkController
     */
    $Controller = new $controller_class_name();

    // Modificar el action name si se ha modificado desde el constructor del
    // controller
    $new_action_name = $Controller->__getNewActionName();
    if($new_action_name !== false){
      $action_name = $new_action_name;
    }

    /* --------------------------------------------------
     * Invocar acción
     */
    if (strpos($action_name, '__') === 0
      || !is_callable(array($Controller, $action_name)) ) {
      $action_name = '__quarkNotFound';
    }

    if ($Controller->QuarkSess->getAccessLevel()
      < $Controller->__getActionAccessLevel($action_name)) {
      $action_name = '__quarkAccessDenied';
    }

    // Guardar el controller name y action name que seran invocados
    self::$_called_controller = $QuarkStr->unCamelCase($controller_name);
    self::$_called_action     = $QuarkStr->unCamelCase($action_name);

    /* --------------------------------------------------
     * Free memory before calling controller's method to
     * have the maximum possible memory.
     */
    unset($config);
    unset($db_config);
    unset($routes);
    unset($include_paths);
    unset($php_min_version);
    unset($QuarkStr);
    
    if (QUARK_AJAX) {
      /* Unset this to avoid bugs if user are using sizeof()
       * or count() over $_POST or $_GET */
      unset($_POST['quark_ajax'], $_GET['quark_ajax']);
    }

    // Call controller's method, pass the ball to developer!
    if (empty($PathInfo->arguments)) {
      $Controller->$action_name();
    } else {
      $arguments = array();
      foreach($PathInfo->arguments as $argument){
        if(!is_numeric($argument)){
          $argument = '"'.addslashes($argument).'"';
        }
        $arguments[] = $argument;
      }
      eval('$Controller->$action_name(' . implode(',', $arguments) . ');');
    }

    // Send AJAX response if request is in AJAX mode
    if (QUARK_AJAX) {
      $Controller->__sendAjaxResponse();
    }

    ob_end_flush();
  }

  public static function getCalledControllerName()
  {
    return self::$_called_controller;
  }

  public static function getCalledActionName()
  {
    return self::$_called_action;
  }

  /**
   * Manejador del buffer de salida, realiza una busqueda de mensajes
   * de error, en caso de encontrarlos modifica el buffer de salida
   * para mostrar un vistoso mensaje de error.
   *
   * @return string
   */
  public static function obHandler($buffer)
  {
    if (preg_match_all('/<quarkerror>(.*)<\/quarkerror>/Us', $buffer, $matches)
      == false) {
      return $buffer;
    } else {
      $error_messages = array_map(
        create_function('$e', 'return trim(strip_tags($e));'),
        $matches[1]
      );

      /* Log de mensajes de error */
      chdir(dirname($_SERVER['SCRIPT_FILENAME']));
      Quark::log(implode(PHP_EOL, $error_messages));

      if (QUARK_AJAX) {
        // Enviar solo el mensaje de error en la respuesta ajax, el objeto
        header('content-type:application/json;charset=utf-8');
        return '{"error":' . json_encode(implode(PHP_EOL, $error_messages)) . '}';
      } else {
        // Renderizar la vista de mensaje de error
        $old_buffer = ob_get_contents();
        require 'views/quark-error.php';
        return substr(ob_get_contents(), strlen($old_buffer));
      }
    }
  }

  /**
   * Envia un mensaje al archivo messages.log
   */
  public static function log($message)
  {
    if( is_writable('application/messages.log') ){
      $PathInfo = Quark::inst('QuarkURL')->getPathInfo();
      $full_message = '['.date('d-M-Y H:i:s').']';
      $full_message .= PHP_EOL.'lang='. $PathInfo->lang;
      $full_message .= ' controller='. self::$_called_controller;
      $full_message .= ' action='. self::$_called_action;
      $full_message .= ' arguments='. implode(', ', $PathInfo->arguments);
      $full_message .= PHP_EOL.$message.PHP_EOL.PHP_EOL;
      file_put_contents('application/messages.log', $full_message, FILE_APPEND);
    }
  }

  /**
   * Devuelve el valor de una directiva de configuración
   * especificada por $key.
   *
   * @return mixed
   */
  public static function getConfigVal($key)
  {
    return self::$_config[$key];
  }

  /**
   * Devuelve el array de configuracion $routes
   * @return [type] [description]
   */
  public static function getRoutes()
  {
    return self::$_routes;
  }

  /**
   * Devuelve el array de configuración $db_config
   * @return array
   */
  public static function getDBConfig()
  {
    return self::$_db_config;
  }

  /**
   * Devuelve una instancia de $class_name para poder utilizar  "quick code"
   * @return Object
   */
  public static function inst($class_name)
  {
    return new $class_name();
  }

  /**
   * Realiza un var_dump() con formato y sale del script.
   * 
   * @param mixed $var,...
   */
  public static function dump($var)
  {
    $args = func_get_args();
    echo '<pre>';
    foreach($args as $arg){
      var_dump($arg);
    }
    echo '</pre>';
    exit;
  }
}
