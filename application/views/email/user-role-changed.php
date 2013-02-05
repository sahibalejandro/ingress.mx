<?php
/**
 * Esta vista es el mensaje que se envia al usuario cuando su role es
 * modificado por un administrador.
 */
?>Saludos agente <?php echo $User->user; ?>


Este mensaje es para informarle que su role dentro del sitio ingress.mx ha sido
modificado.

Su nuevo role es: <?php echo $User->Role->name; ?>


Atte: ingress.mx
