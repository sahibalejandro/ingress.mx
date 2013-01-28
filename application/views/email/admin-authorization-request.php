<?php
/**
 * Esta vista es el mensaje de email que se envia al administrador para autorizar
 * la cuenta de un nuevo usuario
 */
?>Agente: <?php echo $User->user; ?>

Faction: <?php echo $User->faction_name; ?>

Entidad: <?php echo $User->State->name; ?>

Ciudad: <?php echo $User->City->name; ?>


Para proceder con la autorización o denegación de la cuenta visite el siguiente enlace:
<?php echo $this->QuarkURL->getURL('admin-users/authorize-account/'.$User->id.'/'.$auth_token); ?>
