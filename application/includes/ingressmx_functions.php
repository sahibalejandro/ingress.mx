<?php
/**
 * Funciones de ayuda
 */

/**
 * Devuelve un objeto PHPMailer previamente configurado
 * 
 * @return PHPMailer
 */
function ingressmx_create_phpmailer()
{
  require_once 'application/classes/phpmailer/class.phpmailer.php';
  
  $PHPMailer = new PHPMailer(true);
  $PHPMailer->IsSMTP();
  $PHPMailer->SMTPAuth = true;
  $PHPMailer->CharSet  = 'utf-8';
  $PHPMailer->Host     = INGRESSMX_EMAIL_SMTP_HOST;
  $PHPMailer->Port     = INGRESSMX_EMAIL_SMTP_PORT;
  $PHPMailer->Username = INGRESSMX_EMAIL_ACCOUNT;
  $PHPMailer->Password = INGRESSMX_EMAIL_PASSWORD;
  $PHPMailer->SetFrom(INGRESSMX_EMAIL_ACCOUNT, INGRESSMX_EMAIL_ACCOUNT_NAME);

  return $PHPMailer;
}


/**
 * Genera un token de autorización de cuenta para el usuario especificado, este token
 * deberá ser validado con la función ingressmx_check_auth_token()
 * 
 * @param User $User
 * @return string
 */
function ingressmx_generate_auth_token($User)
{
  /* El auth token esta formado por el md5 del resultado de concatenar el nombre
   * agente con su dirección de email */
  return md5($User->user.$User->email);
}

/**
 * Verifica si el token de autorización es válido.
 * 
 * @param string $auth_token
 * @return bool
 */
function ingressmx_check_auth_token($user_id, $auth_token) {

  $User = User::query()->findByPk($user_id);
  
  if (!$User) {
    return false;
  }

  if (md5($User->user.$User->email) != $auth_token) {
    return false;
  }

  return true;
}
