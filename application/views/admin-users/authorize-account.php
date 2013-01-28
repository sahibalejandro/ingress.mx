<?php
$this->appendCssFiles('admin-users/authorize-account.css')
  ->appendJsFiles('admin-users/authorize-account.js')
  ->header('Autorizar cuenta');
?>
<div class="page-header">
  <h3>Autorizar cuenta</h3>
</div>
<?php if (!$auth_token_valid):?>
<div class="alert alert-error">
  Token de autorización inválido.
</div>
<?php elseif ($User->authorized == 1): ?>
<div class="alert alert-warning">
  La cuenta del agente <?php echo $this->QuarkStr->esc($User->user); ?> ya ha sido autorizada con anterioridad.
</div>
<?php else: ?>

<!-- User info -->
<div class="row-fluid">
  <div class="span2">Agente:</div>
  <div class="span10"><?php echo $this->QuarkStr->esc($User->user); ?></div>
</div>
<div class="row-fluid">
  <div class="span2">Faction:</div>
  <div class="span10"><?php echo $User->faction_name; ?></div>
</div>
<div class="row-fluid">
  <div class="span2">Imagen de autorización:</div>
  <div class="span10"><img src="<?php echo INGRESSMX_PATH_SCREENSHOTS.'/'.$User->screenshot; ?>" alt=""></div>
</div>
<!-- // User info -->

<!-- Authorization form -->
<h4>Seleccionar opción de autorización:</h4>
<form id="frm_authorize" action="javascript:;" method="post">
  <input type="hidden" name="user_id" value="<?php echo $User->id; ?>"/>
  <input type="hidden" name="auth_token" value="<?php echo $auth_token; ?>"/>
  <label class="radio">
    <input type="radio" id="rdo_authorize" name="authorize" value="1" checked="checked" /> Autorizar
  </label>
  <label class="radio">
    <input type="radio" id="rdo_unauthorize" name="authorize" value="0" /> Denegar
  </label>
  <label for="reason_denial">Razon de denegación:</label>
  <textarea name="reason_denial" id="reason_denial" disabled="disabled" placeholder="Escriba la razón de denegación..."></textarea>
  <div class="alert alert-error" id="authorize_error_msg"></div>
  <div class="alert alert-warning" id="done_msg">
    Los cambios han sido aplicados.
    <a href="<?php echo $this->QuarkURL->getURL('admin-users'); ?>">Ir a Administración de usuarios</a>
  </div>
  <div class="form-actions">
    <button class="btn" type="submit" id="btn_submit">Proceder</button>
  </div>
</form>
<!-- // Authorization form -->
<?php endif;
$this->footer();
