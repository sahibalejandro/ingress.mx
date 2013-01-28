<?php $this->header('Administración'); ?>
<div class="page-header">
  <h3>Administración</h3>
</div>
<div class="alert alert-warning">
  <strong>MENSAJE IMPORTANTE</strong><br />
  Agente <?php echo $this->User->user; ?>, usted ha sido elegido para fomar parte del
  pequeño grupo de administración en ingress.mx<br />
  Se le exige lealtad, compromiso y discreción, evite ser expulsado.
</div>
<p>A continuación se listan los apartados que puede administrar:<p>
<ul>
  <li><a href="<?php echo $this->QuarkURL->getURL('admin-users'); ?>">Usuarios</a></li>
  <li><a href="<?php echo $this->QuarkURL->getURL('admin-categories'); ?>">Categorías</a></li>
</ul>
<?php
$this->footer();
