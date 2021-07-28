<?php
session_start();
require_once ('util.inc.php');
require_once ('Model/Model.php');
require_once ('getPage.php');
require_once ('util.php');

const IMAGE_PATH = '../images/';
$name  = '';
$img   = '';
$isEdited = false;
$imgError = '';
$edit_id = null;
//新規登録データ用配列
$room = [];
$room_details = [];
$merged_array = [];

//編集ボタン押下
if (isset($_GET['type'])) {
    $edit_id   = $_GET['id'];
    $isEdited = true;
    try {
        $model = new Model();
        $model->connect();
        $sql_edit = 'SELECT * FROM room WHERE id = ?';
        $stmt = $model->dbh->prepare($sql_edit);
        $stmt->execute([$edit_id]);
        $room= $stmt->fetch(PDO::FETCH_ASSOC);
        $sql_edit_detail = 'SELECT * FROM room_detail WHERE room_id = ? AND delete_flg = 0';
        $stmt = $model->dbh->prepare($sql_edit_detail);
        $stmt->execute([$edit_id]);
        $room_details = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $count_room_details = count($room_details);
    } catch (PDOException $e) {
        header('Content-Type: text/plain; charset=UTF-8', true, 500);
        exit($e -> getMessage());
    }
}

$add_key_room_details = ['detail' => $room_details];

$merged_array = array_merge($room, $add_key_room_details);

$united_array = array_merge($merged_array, $_POST);

if(isset($_POST['add-box'])){
    array_push($united_array['detail'], array('capacity' => null, 'remarks' => '', 'price' => null));
}

if(isset($_POST['delete-box'])){
    array_pop($united_array['detail']);
}

$count = ($united_array['detail']) ? count($united_array['detail']) : 1;

//imgアップロード
if (!empty($_POST['up-img-btn'])) {
    if ($_FILES['upfile']['error'] == UPLOAD_ERR_OK) {
        $img = mb_convert_encoding($_FILES['upfile']['name'], 'cp932', 'utf8');
        if (!move_uploaded_file($_FILES['upfile']['tmp_name'], IMAGE_PATH . $img)){
            $imgError = 'アップロードに失敗しました';
        }
    } elseif ($_FILES['upfile']['error'] == UPLOAD_ERR_NO_FILE) {
        //no message
    } else {
        $imgError = 'アップロードに失敗しました';
    }
    $id = $_GET['id'];
    try {
        $model = new Model();
        $model->connect();
        chmod("../images", 0777);
        $sql_img = 'UPDATE room
                        SET img = ?
                        WHERE id = ?';
        $stmt = $model->dbh->prepare($sql_img);
        $stmt->execute([$img, $id]);
        chmod("../images", 0755);
    } catch (PDOException $e) {
        header('Content-Type: text/plain; charset=UTF-8', true, 500);
        exit($e -> getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CICACU | 編集 管理</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.0/css/bootstrap-reboot.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="edit-wrapper">
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
        <div class="room_edit-container">
            <form action="" method="post">
                <input type="hidden" name="token" value="<?=getToken()?>">
                <div class="getPage"><?php getPage(); ?></div>
                <table class="room_edit-table">
                    <?php if($isEdited == true):?>
                    <tr>
                        <th>ID</th>
                        <td colspan="3"><?=isset($united_array['id']) ? h($united_array['id']) : ''?></td>
                    </tr>
                    <?php endif;?>
                    <tr>
                        <th>部屋名<span>（必須）</span></th>
                        <td colspan="3"><input type="text" id="room_edit-room-name-input" name="name" value="<?=isset($united_array['name']) ? h($united_array['name']): '' ?>"></td>
                    </tr>
                    <th rowspan="3">宿泊人数と価格</th>
                    <tr>
                        <td>
                            <?php for ($i = 0; $i < $count; $i++):?>
                                <input type="hidden" name="detail[<?=$i?>][id]" value="<?=isset($united_array['detail'][$i]['id']) ? h($united_array['detail'][$i]['id']) : ''?>">
                                    <p class="p-box">
                                        人数：<input class="room_edit-input-capacity" type="text" name="detail[<?=$i?>][capacity]" value="<?=$united_array['detail'] ? h($united_array['detail'][$i]['capacity']) : ''?>">人</div>
                                        追記：<input class="room_edit-input-remarks" type="text" name="detail[<?=$i?>][remarks]" value="<?=$united_array['detail'] ? h($united_array['detail'][$i]['remarks']) : ''?>"></div>
                                        価格：<input class="room_edit-input-price" type="text" name="detail[<?=$i?>][price]" value="<?=$united_array['detail'] ? h($united_array['detail'][$i]['price']) : ''?>">円（税込）</div>
                                    </p>
                            <?php endfor;?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                                <?php if ($united_array['detail'] == false):?>
                                    <input type="submit" name="add-box" value="BOX追加" formaction="room_edit.php<?=isset($united_array['id']) ?  '?id=' . $united_array['id'] : ''?><?=isset($room['id']) ? '&type=edit' : ''?>">
                                <?php elseif(count($united_array['detail']) >= 1 && count($united_array['detail']) <=4):?>
                                    <input type="submit" name="add-box" value="BOX追加" formaction="room_edit.php<?=isset($united_array['id']) ?  '?id=' . $united_array['id'] : ''?><?=isset($room['id']) ? '&type=edit' : ''?>">
                                    <input type="submit" name="delete-box" value="BOX削除" formaction="room_edit.php<?=isset($united_array['id']) ?  '?id=' . $united_array['id'] : ''?><?=isset($room['id']) ? '&type=edit' : ''?>">
                                <?php elseif(count($united_array['detail']) == 5):?>
                                    <input type="submit" name="delete-box" value="BOX削除" formaction="room_edit.php<?=isset($united_array['id']) ?  '?id=' . $united_array['id'] : ''?><?=isset($room['id']) ? '&type=edit' : ''?>">
                                <?php endif;?>
                        </td>
                    </tr>
                </table>
                <p>
                    <input type="submit" name="add-room-detail" value="確認画面へ" class="to-conf-btn" formaction="room_conf.php<?=isset($united_array['id']) ?  '?id=' . $united_array['id'] . '&type=edit' : ''?>">
                    <input type="submit" value="キャンセル" formaction="room_list.php" class="cancel-btn">
                </p>
            </form>
                <?php if($isEdited == true):?>
                <form action="" method="post" enctype="multipart/form-data">
                <table class="room_edit-img-table" border="1">
                    <tr>
                        <th>サムネイル</th>
                        <td>
                            <input type="file" name="upfile">
                        </td>
                    </tr>
                    <tr>
                        <th>トップページサムネイル</th>
                        <td>
                        <?php if($room['img']):?>
                            <img src="<?= h(IMAGE_PATH . $room['img']) ?>" alt="">
                            <p><?=$room['img']?></p>
                        <?php else:?>
                            <img src="../images/noimage.png" alt="">
                        <?php endif;?>
                        </td>
                    </tr>
                </table>
                <p class="upload-message">半角英数字のファイルのみアップロード可能です。</p>
                <input class="up-img-btn" type="submit" name="up-img-btn" value="アップロード" onclick="return confirm('本当に画像をアップロードしますか？')">
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