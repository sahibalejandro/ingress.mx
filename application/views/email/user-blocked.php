<?php
/**
 * Esta vista es el mensaje que se envia al usuario cuando su cuenta
 * ha sido re-activada
 */
?>Saludos agente <?php echo $User->user; ?>


Este mensaje es para informarle que su cuenta en ingress.mx ha sido desactivada por la siguiente razón:

<?php echo $block_reason; ?>


Atte: ingress.mx
