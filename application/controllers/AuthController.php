<?php
class AuthController extends IngressMXController
{
  public function __construct()
  {
    parent::__construct();
    $this->setDefaultAccessLevel(0);
  }

  /**
   * Callback called from Google OAuth2
   * 
   * If the users fails to authenticate define an error message to show into the
   * "quark-access-denied.php" wich will be rendered if authorization fails.
   * 
   * If user is successfully authenticated we look for a record of that user in the
   * database, if not found then a new record is created, the correct session access
   * level is defined, the user info is saved in session and the user is redirected
   * to the page that was trying to view.
   */
  public function index()
  {
    if (!isset($_GET) || empty($_GET)) {
      header('Location:'.$this->QuarkURL->getBaseURL());
    } else {

      $access_denied = false;

      if (isset($_GET['error'])) {
        if ($_GET['error'] == 'access_denied') {
          $access_denied = 'Debes autorizar el acceso de Ingress MX a tu cuenta de Google';
        } else {
          $access_denied = 'Error de autentificación: '.$_GET['error'];
        }
      } else {
        
        /*
         * Redeem the access token using the authorization code
         */
        $curl = curl_init('https://accounts.google.com/o/oauth2/token');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, array(
          'code'          => $_GET['code'],
          'client_id'     => INGRESSMX_GAPI_CLIENT_ID,
          'client_secret' => INGRESSMX_GAPI_CLIENT_SECRET,
          'redirect_uri'  => INGRESSMX_GAPI_REDIRECT_URI,
          'grant_type'    => 'authorization_code'
        ));
        $AccessToken = json_decode(curl_exec($curl));

        if (isset($AccessToken->error)) {
          $access_denied = 'Error al obtener llave de acceso: '.$AccessToken->error;
        } else {
          /*
           * Get user info
           */
          curl_setopt($curl, CURLOPT_HTTPGET, true);
          curl_setopt($curl, CURLOPT_URL,
            'https://www.googleapis.com/oauth2/v1/userinfo?access_token='
              .$AccessToken->access_token
          );
          
          $UserInfo = json_decode(curl_exec($curl));

          if (isset($UserInfo->error)) {
            $access_denied = 'Error al obtener los datos de tu perfil: '
              .$UserInfo->error->code.' '.$UserInfo->error->message;
          }
        }

        curl_close($curl);
      }

      if ($access_denied != false) {
        define('OAUTH_ERROR', $access_denied);
        $this->__quarkAccessDenied();
      } else {
        /*
         * Check if the user is already registered, or create the new user record
         * into data base.
         */
        $User = User::query()
          ->findOne()
          ->where(array('email' => $UserInfo->email))
          ->exec();
        
        if (!$User) {
          $User = new User();
          $User->email      = $UserInfo->email;
          $User->active     = 0;
          $User->authorized = 0;
          $User->roles_id   = INGRESSMX_ROLE_USER;
          $User->save();
        }

        /*
         * Save user info in session and redirect to previous page
         */
        $this->QuarkSess->set('logged_user_id', $User->id);
        $this->QuarkSess->setAccessLevel(1);
        header('location:'.$_GET['state']);
      }
    }
  }
}
