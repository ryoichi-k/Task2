<?php
session_start();
require_once ('util.inc.php');
require_once ('Model/Model.php');
require_once ('getPage.php');
require_once ('util.php');
$name  = '';
$capacity   = null;
$price = null;
$remarks   = '';
$isEdited = false;
if (!empty($_POST['add-new-room-detail'])) {
    $c      = $_POST['c'];
    echo '<pre>';
    print_r($_POST);
    echo '</pre>';
}
//編集→確認
if (!empty($_POST['edit-room-detail'])) {
    $name       = $_POST['name'];
    $token      = $_POST['token'];
    $count_edited_box = count($_POST['detail']);
    echo '<li>editから送られてきた$_POST<pre>';
    print_r($_POST);
    echo '</pre></li>';
    echo '<li>配列$_POSTdetail<pre>';
    print_r($_POST['detail']);
    echo '</pre></li>';
    $isEdited = true;
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
            <div class="getPage"><?php getPage();?></div>
            <table class="room_edit-table">
                <tr>
                    <th>客室名<span>（必須）</span></th>
                    <td><?=h($_POST['name'])?></td>
                </tr>
                <tr>
                    <th>詳細</th>
                    <td>
                    <?php if($isEdited == true):?>
                        <?php for ($i = 0; $i < $count_edited_box; $i++):?>
                            <p>
                                人数：<?=h($_POST['detail'][$i]['capacity'])?>人
                                価格：<?=h($_POST['detail'][$i]['price'])?>円（税込み）
                                追記：<?=h($_POST['detail'][$i]['remarks'])?>
                            </p>
                        <?php endfor;?>
                    <?php else:?>
                        <?php for ($i = 0; $i < $c; $i++):?>
                            <p>
                                人数：<?=h($_POST['detail'][$i]['capacity'])?>人
                                価格：<?=h($_POST['detail'][$i]['price'])?>円（税込み）
                            追記：<?=h($_POST['detail'][$i]['remarks'])?>
                            </p>
                        <?php endfor;?>
                    <?php endif;?>
                </td>
                </tr>
            </table>
            <?php if($isEdited == true):?>
                <form action="" method="post">
                <?php for ($i = 0; $i < $count_edited_box; $i++):?>
                    <input type="hidden" name="detail[<?=$i?>][id]" value="<?=h($_POST['detail'][$i]['id'])?>">
                    <input type="hidden" name="name" value="<?=h($_POST['name'])?>">
                    <input type="hidden" name="detail[<?=$i?>][capacity]" value="<?=h($_POST['detail'][$i]['capacity'])?>">
                    <input type="hidden" name="detail[<?=$i?>][price]" value="<?=h($_POST['detail'][$i]['price'])?>">
                    <input type="hidden" name="detail[<?=$i?>][remarks]" value="<?=h($_POST['detail'][$i]['remarks'])?>">

                <?php endfor;?>
                <p><input class="conf-submit" name="send-edit" type="submit" value="編集完了" formaction="room_done.php?type=edit"></p>
            </form>
            <?php else:?>
            <form action="" method="post">
                <?php for ($i = 0; $i < $c; $i++):?>
                    <input type="hidden" name="name" value="<?=h($_POST['name'])?>">
                    <input type="hidden" name="detail[<?=$i?>][capacity]" value="<?=h($_POST['detail'][$i]['capacity'])?>">
                    <input type="hidden" name="detail[<?=$i?>][price]" value="<?=h($_POST['detail'][$i]['price'])?>">
                    <input type="hidden" name="detail[<?=$i?>][remarks]" value="<?=h($_POST['detail'][$i]['remarks'])?>">
                <?php endfor;?>
                <p><input class="conf-submit" name="send" type="submit" value="登録完了" formaction="room_done.php"></p>
            </form>
            <?php endif;?>
            <?php if($isEdited == true):?>
                <form action="" method="post">
                    <input type="hidden" name="name" value="<?=h($_POST['name'])?>">
                <?php for ($i = 0; $i < $count_edited_box; $i++):?>
                    <input type="hidden" name="detail[<?=$i?>][id]" value="<?=h($_POST['detail'][$i]['id'])?>">
                    <input type="hidden" name="detail[<?=$i?>][capacity]" value="<?=h($_POST['detail'][$i]['capacity'])?>">
                    <input type="hidden" name="detail[<?=$i?>][price]" value="<?=h($_POST['detail'][$i]['price'])?>">
                    <input type="hidden" name="detail[<?=$i?>][remarks]" value="<?=h($_POST['detail'][$i]['remarks'])?>">
                    <input type="hidden" name="token" value="<?=getToken()?>">
                <?php endfor;?>
                    <p><input type="submit" value="修正" formaction="room_edit.php?id=<?=$_GET['id']?>&type=edit" name="cancel-edit"></p>
                </form>
            <?php else:?>
                <form action="" method="post">
                    <input type="hidden" name="name" value="<?=h($_POST['name'])?>">
                    <input type="hidden" name="c" value="<?=h($_POST['c'])?>">
                    <?php for ($i = 0; $i < $c; $i++):?>
                    <input type="hidden" name="detail[<?=$i?>][capacity]" value="<?=h($_POST['detail'][$i]['capacity'])?>">
                    <input type="hidden" name="detail[<?=$i?>][price]" value="<?=h($_POST['detail'][$i]['price'])?>">
                    <input type="hidden" name="detail[<?=$i?>][remarks]" value="<?=h($_POST['detail'][$i]['remarks'])?>">
                    <?php endfor;?>
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