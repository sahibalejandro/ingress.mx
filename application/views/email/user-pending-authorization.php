<?php
/**
 * Esta vista es el mensaje de e-mail que se envia al usuario que acaba de
 * completar su perfil, para notificarle que su cuenta esta pendiente de
 * autorización.
 */
?>Saludos agente <?php echo $User->user; ?>


Le informamos que su cuenta en ingress.mx está en proceso de verificación, este proceso es rápido y usted será informado en el momento que su cuenta sea activada.

--- Solicitud ---
Faction: <?php echo $User->faction_name; ?>

Entidad: <?php echo $User->State->name; ?>

Ciudad: <?php echo $User->City->name; ?>


Atte: ingress.mx
