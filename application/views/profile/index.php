<?php
$this->appendJsFiles('profile/index.js');
$this->header('Perfil de usuario', false);
?>
<div class="page-header">
  <h1>Perfil de agente</h1>
</div>
<?php if ($this->User->user == null):
/*
 * The following HTML block is shown when the user has not completed his profile.
 */
?>
<div class="alert alert-warning">
  <strong>Perfil incompleto:</strong>
  Antes de todo debes completar tu información como agente de Ingress.
</div>
<?php if ($error_code == 'db'): ?>
<div class="alert alert-error">
  <strong>Oh noes!</strong> No se pudieron guardar tus datos, intenta de nuevo.
</div>
<?php endif; ?>
<form id="frm_profile" method="post" action="<?php echo $this->QuarkURL->getURL('profile'); ?>"
  enctype="multipart/form-data">
  <fieldset>
    <legend>Completa tu perfil</legend>
    
    <label for="user">Usuario (el que usas en Ingress):</label>
    <?php if ($error_code == 'user'): ?>
    <div class="alert alert-error">
      Escribe tu nombre de usuario
    </div>
    <?php endif; ?>
    <input type="text" id="user" name="user" maxlength="50"
    <?php if (isset($_POST['user'])): ?>
    value="<?php echo $this->QuarkStr->esc($_POST['user']); ?>"
    <?php endif; ?>
    />
    
    <label for="faction">Faction:</label>
    <?php if ($error_code == 'faction'): ?>
    <div class="alert alert-error">
      Selecciona Resistance o Enlightened
    </div>
    <?php endif; ?>
    <select name="faction" id="faction">
      <option value="-">-- Selecciona --</option>
      <option value="R"
      <?php if (isset($_POST['faction']) && $_POST['faction'] == 'R'): ?>
      selected="selected"
      <?php endif; ?>>RESISTANCE</option>
      <option value="E"
      <?php if (isset($_POST['faction']) && $_POST['faction'] == 'E'): ?>
      selected="selected"
      <?php endif; ?>>ENLIGHTENED</option>
    </select>
    
    <label for="states_id">Estado:</label>
    <?php if ($error_code == 'state'): ?>
    <div class="alert alert-error">
      Selecciona el estado y ciudad
    </div>
    <?php endif; ?>
    <select name="states_id" id="states_id">
      <option value="-">-- Selecciona --</option>
      <?php foreach (States::query()->find()->exec() as $State): ?>
      <option value="<?php echo $State->id; ?>"><?php echo $this->QuarkStr->esc($State->name); ?></option>
      <?php endforeach; ?>
    </select>
    
    <label for="cities_id">Ciudad:</label>
    <select name="cities_id" id="cities_id"></select>
    
    <label for="screenshot">Imagen de verificación:</label>
    <span class="help-block">
      Para confirmar tu identidad debes
      enviarnos una imagen (screenshot) de tu juego Ingress donde se vea tu nombre de usuario y el COMM abierto en la sección Faction.
    </span>
    <?php if ($error_code == 'nofile'): ?>
    <div class="alert alert-error">
      Selecciona tu imagen de verificación
    </div>
    <?php endif; ?>
    <?php if ($error_code == 'upload'): ?>
    <div class="alert alert-error">
      <strong>No se pudo guardar la imagen: </strong>
      <?php echo $this->QuarkStr->esc($UploadResult->error); ?>
    </div>
    <?php endif; ?>
    <input type="file" id="screenshot" name="screenshot" />
    <span class="help-block">Solo se acepta imagenes JPG o PNG</span>
    <div class="form-actions">
      <button type="submit" id="btn_submit" class="btn btn-primary">Completar perfil</button>
    </div>
  </fieldset>
</form>
<?php else:
/*
 * The following HTML block is shown when the user has completed his profile.
 */
?>
<?php if ($error_code == 'admin-email'): ?>
<div class="alert alert-error">
  <strong>Error:</strong>
  No se ha podido notificar al administrador sobre tu solicitud de activación, por favor envia un correo
  a <strong>admin@ingress.mx</strong> para acelerar el proceso de activación.
</div>
<?php endif; ?>
<div class="row-fluid">
  <div class="span2"><strong>Agente:</strong></div>
  <div class="span10"><?php echo $this->User->user; ?></div>
</div>
<div class="row-fluid">
  <div class="span2"><strong>Rol:</strong></div>
  <div class="span10"><?php echo $this->User->Role->name; ?></div>
</div>
<div class="row-fluid">
  <div class="span2"><strong>Faction:</strong></div>
  <div class="span10"><?php echo $this->User->faction_name; ?></div>
</div>
<div class="row-fluid">
  <div class="span2"><strong>EMail:</strong></div>
  <div class="span10"><?php echo $this->User->email; ?></div>
</div>
<div class="row-fluid">
  <div class="span2"><strong>Ubicación:</strong></div>
  <div class="span10"><?php
    echo $this->User->City->name
      .', '
      .$this->User->State->name;
  ?></div>
</div>
<div class="row-fluid">
  <div class="span2"><strong>Imagen de identificación:</strong></div>
  <div class="span10">
    <img id="user_screenshot" src="<?php echo INGRESSMX_PATH_SCREENSHOTS.'/'.$this->User->screenshot; ?>" alt="<?php echo $this->User->user; ?>">
  </div>
</div>
<?php endif;
/* END OF: if ($this->User->user == null) */

$this->footer();
