<?php
session_start();
require_once ('Model/Model.php');
require_once ('util.php');

if (empty($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CICACU | トップ 管理</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.0/css/bootstrap-reboot.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="wrapper">
        <header class="gl-header">
                <p class="top-p">ログイン名[<?=h($_SESSION['admin']['name']);?>]さん、ご機嫌いかがですか？</p><div class="logout-link"><a href="logout.php">ログアウトする</a></div>
                <h1>CICACU</h1>
                <nav class="gl-nav">
                    <ul>
                        <li><a href="top.php"> top</a></li>
                        <li><a href="room_list.php"> ○○管理</a></li>
                        <li><a href="#"> ○○管理</a></li>
                        <li><a href="#"> ○○管理</a></li>
                        <li><a href="#"> ○○管理</a></li>
                        <li><a href="#"> ○○管理</a></li>
                    </ul>
                </nav>
        </header>
        <main>
        </main>
        <footer class="gl-footer">
            <p><small>2021 ebacorp.inc</small></p>
        </footer>
    </div>
</body>
</html>