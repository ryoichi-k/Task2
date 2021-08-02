<?php
session_start();
require_once ('UserModel.php');
require_once ('User_UserAuth.php');
require_once ('admin/util.php');
if (empty($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
if (!empty($_POST['payment'])) {
echo '<pre>';
print_r($_POST);
echo '</pre>';
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CICACU | 予約確認画面</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.0/css/bootstrap-reboot.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
        <p class="top-p"><?=h($_SESSION['user']['name']);?>さん、ご機嫌いかがですか？</p>
        <h1>確認画面</h1>
        <h3>お支払い総額：</h3>
    <footer>
        <p><small>2021 ebacorp.inc</small></p>
    </footer>
</body>
</html>