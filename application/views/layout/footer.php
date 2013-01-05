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
  <?php $this->prependJsFiles('bootstrap.min.js')->includeJsFiles(); ?>
</body>
</html>
