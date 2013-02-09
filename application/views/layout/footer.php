  <?php if (!$sidebar): ?>
  </div>
  <!-- .container -->
  <?php else: ?>
      </div>
      <!-- // #content -->
    </div>
  </div>
  <!-- // .container-fluid -->
  <?php endif; ?>
  <div id="footer">
    <strong>ingress.mx</strong> es una comunidad en linea sin animo de lucro, hecha y mantenida por entusiastas mexicanos del juego Ingress, y no es un sitio oficial de <strong>NianticLabs@Google</strong> o <strong>Google</strong>.<br />
    Ingress y el logo de Ingress pertenecen a <strong>NianticLabs@Google</strong> y tienen derechos reservados.
  </div>
  <?php $this->prependJsFiles(
    'bootstrap.min.js',
    'jquery.elapsedtime.js',
    'ingressmx.js'
  )->includeJsFiles(); ?>
</body>
</html>
