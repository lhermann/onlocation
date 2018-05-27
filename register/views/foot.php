    <div style="padding-top: 6em;"></div>

    <?php if (DEBUG): ?>
        <div class="container">
            <div class="card">
              <h5 class="card-header">Debug</h5>
              <div class="card-body">
                <?php
                    var_dump($route);
                    if (isset($reglist)) var_dump($reglist);
                    if (isset($reg)) var_dump($reg);
                ?>
              </div>
            </div>
        </div>
    <?php endif ?>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/register/assets/js/jquery-3.3.1.min.js"></script>
    <script src="/register/assets/js/bootstrap.min.js?v=4.1.1"></script>

</body>
</html>
