<?php
session_start();
require_once ('../util.inc.php');
require_once ('../Model/Model.php');
require_once ('../getPage.php');
require_once ('../util.php');
$name  = '';
$capacity   = null;
$price = null;
$remarks   = '';
$isEdited = false;
if (!empty($_POST['add-new-room-detail'])) {
    $name          = $_POST['name'];
    $capacity      = $_POST['capacity'];
    $price         = $_POST['price'];
    $remarks       = $_POST['remarks'];
    $token         = $_POST['token'];
    // array_merge($_POST, $)
    echo '<pre>';
    print_r($_POST[room]);
    echo '</pre>';
}
// if (!empty($_POST['edit-room-detail'])) {
//     $name       = $_POST['name'];
//     $capacity   = $_POST['capacity'];
//     $price      = $_POST['price'];
//     $remarks    = $_POST['remarks'];
//     $token      = $_POST['token'];
//     $isEdited = true;
// }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CICACU | 確認 管理</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.0/css/bootstrap-reboot.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="wrapper">
        <header class="gl-header">
            <p class="top-p">ログイン名[<?=h($_SESSION['admin']['name']);?>]さん、ご機嫌いかがですか？</p>
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
        <div class="room_conf-container">
            <h2>確認画面</h2>
            <div class="getPage"><?php getPage(); ?></div>
            <table class="room_edit-table">
                <tr>
                    <th>客室名<span>（必須）</span></th>
                    <td><?=h($name)?></td>
                </tr>
                <tr>
                    <th>人数</th>
                    <td><?=h($capacity)?>人</td>
                </tr>
                <tr>
                    <th>価格</th>
                    <td><?=h($price)?>円（税込み）</td>
                </tr>
                <tr>
                    <th>追記</th>
                    <td><?=h($remarks)?></td>
                </tr>
            </table>
            <?php if($isEdited == true):?>
                <form action="" method="post">
                <input type="hidden" name="name" value="<?=h($name)?>">
                <input type="hidden" name="capacity" value="<?=h($capacity)?>">
                <input type="hidden" name="price" value="<?=$price?>">
                <input type="hidden" name="remarks" value="<?=$remarks?>">
                <input type="hidden" name="updated_at" value="<?=h($updated_at)?>">
                <p><input class="conf-submit" name="send-edit" type="submit" value="編集完了" formaction="room_done.php?type=edit"></p>
            </form>
            <?php else:?>
            <form action="" method="post">
                <input type="hidden" name="name" value="<?=h($name)?>">
                <input type="hidden" name="capacity" value="<?=h($capacity)?>">
                <input type="hidden" name="price" value="<?=$price?>">
                <input type="hidden" name="remarks" value="<?=$remarks?>">
                <p><input class="conf-submit" name="send" type="submit" value="登録完了" formaction="room_done.php"></p>
            </form>
            <?php endif;?>
            <?php if($isEdited == true):?>
                <form action="" method="post">
                    <input type="hidden" name="name" value="<?=$name?>">
                    <input type="hidden" name="capacity" value="<?=$capacity?>">
                    <input type="hidden" name="price" value="<?=$price?>">
                    <input type="hidden" name="remarks" value="<?=$remarks?>">
                    <input type="hidden" name="token" value="<?=getToken()?>">
                    <p><input type="submit" value="修正" formaction="room_edit.php" name="cancel-edit"></p>
                </form>
            <?php else:?>
                <form action="" method="post">
                    <input type="hidden" name="name" value="<?=$name?>">
                    <input type="hidden" name="capacity" value="<?=$capacity?>">
                    <input type="hidden" name="price" value="<?=$price?>">
                    <input type="hidden" name="remarks" value="<?=$remarks?>">
                    <input type="hidden" name="token" value="<?=getToken()?>">
                    <p><input type="submit" value="修正" formaction="room_edit.php" name="cancel"></p>
                </form>
            <?php endif;?>
        </div>
        </main>
        <footer class="gl-footer">
            <p><small>2021 ebacorp.inc</small></p>
        </footer>
    </div>
</body>
</html>