<?php
$this->header('Acceso Denegado', false);
?>
<form action="https://accounts.google.com/o/oauth2/auth" method="get">
  <fieldset>
    <?php $this->renderView('layout/oauth2-fields.php'); ?>
    
    <legend>
      Autentificación de Agente
    </legend>
    <?php if (defined('OAUTH_ERROR')): ?>
    <div class="alert alert-error">
      <strong>Oh no!</strong> <?php echo OAUTH_ERROR ?>
    </div>
    <?php endif; ?>
    <p>
      Para acceder al contenido de este sitio debes autenticarte con tu
      cuenta de Google.
    </p>
    <div class="form-actions">
      <button type="submit" class="btn btn-primary btn-large">Autenticarme con Google Accounts</button>
    </div>
  </fieldset>
</form>
<?php $this->footer();
