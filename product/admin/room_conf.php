<?php
session_start();
require_once (dirname(__FILE__).'/../ExternalFiles/util.inc.php');
require_once (dirname(__FILE__).'/../ExternalFiles/getPage.php');
require_once (dirname(__FILE__).'/../ExternalFiles/Model/Model.php');
require_once (dirname(__FILE__).'/../ExternalFiles/util.php');

if (!empty($_POST)) {
    $count_box = count($_POST['detail']);
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
    <div class="conf-wrapper">
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
                        <?php for ($i = 0; $i < $count_box; $i++):?>
                            <p>
                                人数：<?=h($_POST['detail'][$i]['capacity'])?>人
                                価格：<?=h($_POST['detail'][$i]['price'])?>円（税込み）
                                追記：<?=h($_POST['detail'][$i]['remarks'])?>
                            </p>
                        <?php endfor;?>
                </td>
                </tr>
            </table>
                <form action="" method="post">
                <?php for ($i = 0; $i < $count_box; $i++):?>
                    <input type="hidden" name="detail[<?=$i?>][id]" value="<?=h($_POST['detail'][$i]['id'])?>">
                    <input type="hidden" name="name" value="<?=h($_POST['name'])?>">
                    <input type="hidden" name="detail[<?=$i?>][capacity]" value="<?=h($_POST['detail'][$i]['capacity'])?>">
                    <input type="hidden" name="detail[<?=$i?>][price]" value="<?=h($_POST['detail'][$i]['price'])?>">
                    <input type="hidden" name="detail[<?=$i?>][remarks]" value="<?=h($_POST['detail'][$i]['remarks'])?>">
                    <input type="hidden" name="token" value="<?=getToken()?>">
                <?php endfor;?>
                    <p><input type="submit" value="修正" formaction="room_edit.php<?=isset($_GET['id']) ? '?id=' . $_GET['id'] : '' ?><?=isset($_GET['id']) ? '&type=edit' : '?type=new'?>" name="cancel-edit" class="conf-cancel-btn">
                <?php if(isset($_GET['id'])):?>
                    <input class="conf-submit" name="send-edit" type="submit" value="編集完了" formaction="room_done.php?type=edit"></p>
                <?php else:?>
                    <input class="conf-submit" name="send" type="submit" value="登録完了" formaction="room_done.php?type=new"></p>
                <?php endif;?>
                </form>
        </div>
        </main>
        <footer class="gl-footer">
            <p><small>2021 ebacorp.inc</small></p>
        </footer>
    </div>
</body>
</html>