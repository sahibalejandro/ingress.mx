<?php
$this->appendCssFiles('admin-users/index.css')
  ->header('Administración de usuarios'); ?>
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
<table class="table table-bordered table-condensed">
  <thead>
    <tr>
      <th>Agente</th>
      <th>Faction</th>
      <th>Fecha de registro</th>
      <th>Role</th>
      <th>Acción</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($users as $User): ?>
    <tr class="<?php echo $User->faction == 'R' ? 'resistance' : 'enligthened'; ?>">
      <td><?php echo $this->QuarkStr->esc($User->user); ?></td>
      <td class="cell_faction"><?php echo $User->faction_name; ?></td>
      <td class="cell_join_date"><?php echo $this->formatDateTime($User->join_date); ?></td>
      <td class="cell_role"><?php echo $User->Role->name; ?></td>
      <td class="cell_actions"><?php
      if ($User->authorized == '0'): ?>
      <a href="<?php
        echo $this->QuarkURL->getURL(
        'admin-users/authorize-account/'
        .$User->id
        .'/'
        .ingressmx_generate_auth_token($User)
      ); ?>" class="btn">Autorizar</a>
      <?php elseif ($User->active == '1'): ?>
      <button class="btn">Bloquear</button>
      <button class="btn">Cambiar role</button>
      <?php else: ?>
      <button class="btn">Activar</button>
      <?php endif;?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php
$this->footer();
