<?php
session_start();
require_once '../util.inc.php';
require_once '../Model/Model.php';
require_once 'getPage.php';
require_once ('../htmlspecialchars.php');

if (isset($_SESSION['edit'])) {
    $edit        = $_SESSION['edit'];
    $name        = $edit['name'];
    $capacity    = $edit['capacity'];
    $price       = $edit['price'];
    $remarks     = $edit['remarks'];
    $token       = $edit['token'];
    if ($token !== getToken()) { //両者にハッシュが帰ってくる
        header('Location: room_edit.php');
        exit;
        // var_dump($contact);
    }
} else { //urlの直打ちで訪れたときはお問い合わせに飛ばされる
    header('Location: room_edit.php');
    exit;
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // unset($_SESSION['edit']);
    header('Location: room_done.php');
    exit;
}
/**
 * XSS対策の参照名省略
 *
 * @param string string
 * @return string
 *
 */
function h(?string $string): string
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CICACU | 確認 管理</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.0/css/bootstrap-reboot.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="wrapper">
        <header class="gl-header">
            <p class="top-p">ログイン名[<?= ($_SESSION['admin']['name']); ?>]さん、ご機嫌いかがですか？</p>
            <div class="logout-link"><a href="logout.php">ログアウトする</a></div>
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
            <h2>確認画面</h2>
            <?php getPage();?>
            <table class="room_edit-table">
                <tr>
                    <th>客室名<span>（必須）</span></th>
                    <td><?= h($name) ?></td>
                </tr>
                <tr>
                    <th>人数</th>
                    <td><?= h($capacity) ?>人</td>
                </tr>
                <tr>
                    <th>価格</th>
                    <td><?= h($price) ?>円（税込み）</td>
                </tr>
                <tr>
                    <th>追記</th>
                    <td><?= h($remarks) ?></td>
                </tr>
            </table>
            <form action="" method="post">
                <p><input class="conf-submit" type="submit" value="登録完了"></p>
                <button type="submit" class="conf-cancel" formaction="room_edit.php">修正</button>
            </form>
        </main>
        <footer class="gl-footer">
            <p><small>2021 ebacorp.inc</small></p>
        </footer>
    </div>
</body>
</html>