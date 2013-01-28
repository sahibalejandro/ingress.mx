<?php
/**
 * Esta vista es el mensaje para notificarle a un usuario que su cuenta ya
 * está autorizada.
 */?>Saludos agente <?php echo $User->user; ?>


Este mensaje es para notificarle que su cuenta en ingress.mx NO ha sido autorizada por la siguiente razón:

<?php echo $reason_denial; ?>


Para solicitar una nueva autorización visite: http://www.ingress.mx/
