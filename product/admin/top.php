<?php
session_start();
require_once (dirname(__FILE__) . '/../ExternalFiles/Model/Model.php');
require_once (dirname(__FILE__) . '/../ExternalFiles/util.php');

if (empty($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
?>
<?php require_once('header.php')?>
        <main>
        </main>
        <footer class="top-footer">
            <p><small>2021 ebacorp.inc</small></p>
        </footer>
    </div>
</body>
</html>