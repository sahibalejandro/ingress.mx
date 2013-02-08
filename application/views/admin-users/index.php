<?php
$this
  ->appendCssFiles('admin-users/index.css')
  ->appendJsFiles('admin-users/index.js')
  ->header('Administración de usuarios'); ?>
<div class="page-header">
  <h3>Administración de usuarios</h3>
  <p>Acontinuación se muestra una lista de usuarios registrados en el sitio, iniciando
  con todos los usuaros que aun no han sido autorizados.<p>
  <ul>
    <li><strong>Activar a un usuario:</strong> Haga click en el botón "Activar".</li>
    <li><strong>Autorizar a un usuario:</strong> haga click en el botón "Autorizar",
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
    <tr id="user<?php echo $User->id; ?>"
      class="<?php echo $User->faction == 'R' ? 'resistance' : 'enligthened'; ?>">
      <td><?php echo $this->QuarkStr->esc($User->user); ?></td>
      <td class="cell_faction"><?php echo $User->faction_name; ?></td>
      <td class="cell_join_date"><?php echo $this->formatDateTime($User->join_date); ?></td>
      <td class="cell_role"><?php echo $User->Role->name; ?></td>
      <td class="cell_actions">
        <div class="controls_user_unauthorized <?php echo $User->authorized == 0 ? '' : 'hide'; ?>">
          <a href="<?php
            echo $this->QuarkURL->getURL(
            'admin-users/authorize-account/'
            .$User->id
            .'/'
            .ingressmx_generate_auth_token($User)
          ); ?>" class="btn">Autorizar</a>
        </div>
        <div class="controls_user_active <?php
          echo $User->active == 1 ? '' : 'hide'; ?>">
          <button class="btn btn_show_block_dialog"
            data-user-id="<?php echo $User->id; ?>"
            data-user-name="<?php echo $this->QuarkStr->esc($User->user); ?>">Bloquear</button>
          <button class="btn btn_change_role"
            data-user-id="<?php echo $User->id; ?>"
            data-user-name="<?php echo $this->QuarkStr->esc($User->user); ?>">Cambiar role</button>
        </div>
        <div class="controls_user_inactive
          <?php echo ($User->active == 0 && $User->authorized == 1) ? '' : 'hide'; ?>">
          <button class="btn btn_show_unblock_dialog"
            data-user-id="<?php echo $User->id; ?>"
            data-user-name="<?php echo $this->QuarkStr->esc($User->user); ?>">Activar</button>
        </div>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- Dialogo para bloquear usuario -->
<div id="modal_block_user" class="modal hide fade">
  <form action="javascript:;" id="frm_block_user">
    <input type="hidden" name="user_id" id="block_user_id" value="0" />
    <div class="modal-header">
      <h4>Bloquear usuario</h4>
    </div>
    <div class="modal-body">
      Especifique la razón para bloquear al usuario <span id="block_user_name"></span>
      <textarea id="block_reason" name="block_reason"></textarea>
      <div id="block_error_msg" class="alert alert-error"></div>
    </div>
    <div class="modal-footer">
      <button id="btn_block_submit" class="btn" type="submit">Activar</button>
      <button id="btn_block_close" class="btn" type="button" data-dismiss="modal">Cancelar</button>
    </div>
  </form>
</div>
<!-- // Dialogo para bloquear usuario -->

<!-- Dialogo para activar usuario -->
<div id="modal_unblock_user" class="modal hide fade">
  <form action="javascript:;" id="frm_unblock_user">
    <input type="hidden" name="user_id" id="unblock_user_id" value="0" />
    <div class="modal-header">
      <h4>Activar usuario</h4>
    </div>
    <div class="modal-body">
      ¿Desea re-activar al usuario <span id="unblock_user_name"></span>?
      <div id="unblock_error_msg" class="alert alert-error"></div>
    </div>
    <div class="modal-footer">
      <button id="btn_unblock_submit" class="btn" type="submit">Activar</button>
      <button id="btn_unblock_close" class="btn" type="button" data-dismiss="modal">Cancelar</button>
    </div>
  </form>
</div>
<!-- // Dialogo para activar usuario -->

<!-- Dialogo para seleccinar role -->
<div id="modal_change_role" class="modal hide fade">
  <form action="javascript:;" id="frm_change_user_role">
    <input type="hidden" name="user_id" id="change_role_user_id" value="0" />
    <div class="modal-header">
      <h4>Cambiar role</h4>
    </div>
    <div class="modal-body">
      <label for="roles_id">
        Selecciona el nuevo role para el usuario <span id="change_role_user"></span>:
      </label>
      <select name="roles_id" id="roles_id">
        <?php foreach ($roles as $Role): ?>
        <option value="<?php echo $Role->id; ?>"><?php echo $this->QuarkStr->esc($Role->name); ?></option>
        <?php endforeach; ?>
      </select>
      <div id="change_role_error_msg" class="alert alert-error"></div>
    </div>
    <div class="modal-footer">
      <button type="submit" id="btn_change_role_submit" class="btn">Aceptar</button>
      <button type="button" id="btn_change_role_close" class="btn" data-dismiss="modal">Cancelar</button>
    </div>
  </form>
</div>
<!-- // Dialogo para seleccinar role -->

<?php
$this->footer();
