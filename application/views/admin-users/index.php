<?php $this->header('Administración de usuarios'); ?>
<div class="page-header">
  <h3>Administración de usuarios</h3>
  <p>Acontinuación se muestra una lista de usuarios registrados en el sitio, iniciando
  con todos los usuaros que aun no han sido autorizados.<p>
  <ul>
    <li><strong>Autorizar a un usuario:</strong> haga click en el botón "Verificar",
    revise la imagen de autorización y proceda.</li>
    <li><strong>Bloquear a un usuario:</strong> Haga click en el botón "Bloquear", especifique
    la razón del bloqueo y proceda.</li>
    <li><strong>Cambiar role a un usuario:</strong> Haga click en el botón "Role", seleccione
    el nuevo role y proceda.</li>
  </ul>
</div>
<h4>Usuarios pendientes de autorización:</h4>
<div id="unauthorized_users_list">
  <table class="table">
    <thead>
      <th></th>
    </thead>
  </table>
</div>
<h4>Usuarios autorizados:</h4>
<div id="authorized_users_list">
  
</div>
<?php
$this->footer();
