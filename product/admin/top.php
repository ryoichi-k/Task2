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
        <main class="top-main">
        </main>
<?php require_once('footer.php')?>
    </div>
</body>
</html>