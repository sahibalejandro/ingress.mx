<?php
$this->header('Perfil de usuario');
?>
<div class="page-header">
  <h1>Perfil de agente</h1>
</div>
<?php if ($this->User->fraction == null): ?>
<div class="alert alert-warning">
  <strong>Perfil incompleto:</strong>
  Antes de todo debes completar tu información como agente de Ingress.
</div>
<form method="post" action="<?php echo $this->QuarkURL->getURL('profile'); ?>"
  >
  <fieldset>
    <legend>Completa tu perfil</legend>
    
    <label for="user">Usuario (el que usas en Ingress):</label>
    <?php if ($error_code == 1): ?>
    <div class="alert alert-error">
      Escribe tu nombre de usuario
    </div>
    <?php endif; ?>
    <input type="text" id="user" name="user" maxlength="50" />
    
    <label for="team">Fraction:</label>
    <?php if ($error_code == 2): ?>
    <div class="alert alert-error">
      Selecciona Resistance o Enlightened
    </div>
    <?php endif; ?>
    <select name="fraction" id="fraction">
      <option value="-">-- Selecciona --</option>
      <option value="R">RESISTANCE</option>
      <option value="E">ENLIGHTENED</option>
    </select>
    
    <label for="screenshot">Imagen de verificación:</label>
    <span class="help-block">
      Para confirmar tu identidad debes
      enviarnos una imagen (screenshot) de tu juego Ingress donde se vea que tu nombre de usuario y fraction son
      los mismos que llenaste en este formulario.
    </span>
    <?php if ($error_code == 3): ?>
    <div class="alert alert-error">
      Selecciona tu imagen de verificación
    </div>
    <?php endif; ?>
    <input type="file" id="screenshot" name="screenshot" />
    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Completar perfil</button>
    </div>
  </fieldset>
</form>
<?php endif; ?>
<?php $this->footer();
