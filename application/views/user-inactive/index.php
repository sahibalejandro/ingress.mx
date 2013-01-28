<?php
$this->header('Cuenta inactiva', false);
?>
<div class="page-header">
  <h3>Cuenta inactiva</h3>
</div>
<p>Agente <strong><?php echo $this->User->user; ?></strong> su cuenta en ingress.mx
se encuentra inactiva por alguna de las siguientes razones:</p>
<ul>
  <li>Su cuenta se encuentra en proceso de verificación, esto es normal y demora un
  par de horas, usted será notificado en el momento que su cuenta sea activada.</li>
  <li>Usted ha tenido una conducta inapropiada en la comunidad y ha sido expulsado.</li>
</ul>
<p>Si usted cree que su cuenta debería ser activada, por favor
envíe un e-mail a <strong>admin@ingress.mx</strong></p>
<?php $this->footer();
