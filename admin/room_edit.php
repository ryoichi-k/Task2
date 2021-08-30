<?php
session_start();
// require_once (dirname(__FILE__).'/../ExternalFiles/util.inc.php');
// require_once (dirname(__FILE__).'/../ExternalFiles/Model/Model.php');
// require_once (dirname(__FILE__).'/../ExternalFiles/Model/Room.php');
// require_once (dirname(__FILE__).'/../ExternalFiles/util.php');
require_once('util.inc.php');
require_once('Model/Model.php');
require_once ('Model/Room.php');
require_once('util.php');

$img   = '';
$imgError = '';

//新規登録データ用配列
$room = [];
$room_details = [];
$merged_array = [];

//編集ボタン押下
if ($_GET['type'] == 'edit') {
    try {
        $room1 = new Room();
        $room = $room1->showRoomName($_GET['id']);
        $room_details = $room1->showRoomDetail($_GET['id']);
        $count_room_details = count($room_details);
        $add_key_room_details = ['detail' => $room_details];
        $merged_array = array_merge($room, $add_key_room_details);
    } catch (Exception $e) {
        $error = '予期せぬエラーが発生しました。<br>CICACU辻井迄ご連絡ください。080-1411-4095(辻井) info@cicacu.jp';
    }
}

$united_array = array_merge($merged_array, $_POST);

if (isset($_POST['add-box'])) {
    array_push($united_array['detail'], array('capacity' => null, 'remarks' => '', 'price' => null));
}

if (isset($_POST['delete-box'])) {
    array_pop($united_array['detail']);
}

$count = (!empty($united_array['detail'])) ? count($united_array['detail']) : 1;

//imgアップロード
if (!empty($_POST['up-img-btn'])) {
    if ($_FILES['upfile']['error'] == UPLOAD_ERR_OK) {
        $img = mb_convert_encoding($_FILES['upfile']['name'], 'cp932', 'utf8');
        if (!move_uploaded_file($_FILES['upfile']['tmp_name'], IMAGE_PATH . $img)) {
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
        chmod('../images', 0777);
        $sql_img = 'UPDATE room SET img = ? WHERE id = ?';
        $stmt = $model->dbh->prepare($sql_img);
        $stmt->execute([$img, $id]);
        chmod('../images', 0755);
    } catch (PDOException $e) {
        header('Content-Type: text/plain; charset=UTF-8', true, 500);
        exit($e->getMessage());
    } catch (Exception $e) {
        return 'エラーが発生しました。<br>CICACU辻井迄ご連絡ください。080-1411-4095(辻井) info@cicacu.jp';
    }
}
?>
<?php require_once('header.php')?>
<main>
    <div class="room_edit-container">
        <form action="" method="post">
            <input type="hidden" name="token" value="<?=getToken()?>">
            <div class="getPage"><?php getPage() ;?></div>
            <?php if (isset($error)) :?>
                <p class="error"><?=$error?></p>
            <?php endif ;?>
            <table class="room_edit-table">
                <?php if ($_GET['type'] == 'edit') :?>
                    <tr>
                        <th>ID</th>
                        <td colspan="3">　<?=isset($united_array['id']) ? h($united_array['id']) : ''?></td>
                    </tr>
                <?php endif ;?>
                <tr>
                    <th>部屋名</th>
                    <td colspan="3"><input type="text" id="room_edit-room-name-input" name="name" value="<?=isset($united_array['name']) ? h($united_array['name']) : ''?>"></td>
                </tr>
                <th rowspan="3">宿泊人数と価格</th>
                <tr>
                    <td>
                        <?php for ($i = 0; $i < $count; $i++) :?>
                            <input type="hidden" name="detail[<?=$i?>][id]" value="<?=isset($united_array['detail'][$i]['id']) ? h($united_array['detail'][$i]['id']) : ''?>">
                            <p class="p-box">
                                　人数：<input class="room_edit-input-capacity" type="text" name="detail[<?=$i?>][capacity]" value="<?=!empty($united_array['detail']) ? h($united_array['detail'][$i]['capacity']) : ''?>">人
                                </div>
                                　追記：<input class="room_edit-input-remarks" type="text" name="detail[<?=$i?>][remarks]" value="<?=!empty($united_array['detail']) ? h($united_array['detail'][$i]['remarks']) : ''?>"></div>
                                　価格：<input class="room_edit-input-price" type="text" name="detail[<?=$i?>][price]" value="<?=!empty($united_array['detail']) ? h($united_array['detail'][$i]['price']) : ''?>">円（税込）</div>
                            </p>
                        <?php endfor ;?>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <?php if (empty($count) || $count < 5) :?>
                            <input type="submit" name="add-box" value="BOX追加" formaction="room_edit.php<?=isset($united_array['id']) ?  '?id=' . $united_array['id'] : '?type=new'?><?=isset($room['id']) ? '&type=edit' : ''?>">
                        <?php endif ;?>
                        <?php if ($count > 1) :?>
                            <input type="submit" name="delete-box" value="BOX削除" formaction="room_edit.php<?=isset($united_array['id']) ?  '?id=' . $united_array['id'] : '?type=new'?><?=isset($room['id']) ? '&type=edit' : ''?>">
                        <?php endif ;?>
                    </td>
                </tr>
                </table>
                    <p>
                        <input type="submit" name="add-room-detail" value="確認画面へ" class="to-conf-btn" formaction="room_conf.php<?=isset($united_array['id']) ? '?id=' . $united_array['id'] . '&type=edit' : '?type=new'?>">
                    </p>
        </form>
    <?php if ($_GET['type'] == 'edit') :?>
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
                        <?php if ($room['img']) :?>
                            <img src="<?=IMAGE_PATH . h($room['img'])?>">
                            <p><?=h($room['img'])?></p>
                        <?php endif ;?>
                    </td>
                </tr>
            </table>
            <p class="upload-message">半角英数字のファイルのみアップロード可能です。</p>
        <input class="up-img-btn" type="submit" name="up-img-btn" value="アップロード" onclick="return confirm('本当に画像をアップロードしますか？')">
        </form>
    <?php endif; ?>
    </div>
</main>
<?php require_once('footer.php')?>