<?php
session_start();
require_once(dirname(__FILE__) . '/../ExternalFiles/util.inc.php');
require_once(dirname(__FILE__) . '/../ExternalFiles/Model/Model.php');
require_once(dirname(__FILE__) . '/../ExternalFiles/Model/Room.php');
require_once(dirname(__FILE__) . '/../ExternalFiles/util.php');

//新規登録データ用配列
$room_list = [];

//編集ボタン押下
if ($_GET['type'] == 'edit') {
    try {
        $room = new Room();

        //画像アップロード
        if (!empty($_POST['up_img_btn'])) {
            $error = $room->uploadImage($_GET['id']);
        }

        $room_list = $room->getRoomName($_GET['id']);
        $room_list['detail'] = $room->getRoomDetail($_GET['id']);

    } catch (Exception $e) {
        $error = 'システムエラーが発生しました。<br>CICACU辻井迄ご連絡ください。080-1411-4095(辻井) info@cicacu.jp';
    }
}

$room_list = $_POST + $room_list;

if (isset($_POST['add_box'])) {
    array_push($room_list['detail'], array('capacity' => null, 'remarks' => null, 'price' => null));
}

if (isset($_POST['delete_box'])) {
    array_pop($room_list['detail']);
}

$count = !empty($room_list['detail']) ? count($room_list['detail']) : 1;

?>
<?php require_once('header.php')?>
<main>
    <div class="room_edit-container">
        <form action="room_conf.php?type=<?=h($_GET['type'])?><?=isset($_GET['id']) ? '&id=' . h($_GET['id']) : ''?>" method="post">
            <div class="getPage"><?php getPage() ;?></div>
            <?php if (isset($error)) :?>
                <p class="error"><?=$error?></p>
            <?php endif ;?>
            <table class="room_edit-table">
                <?php if ($_GET['type'] == 'edit') :?>
                    <tr>
                        <th>ID</th>
                        <td colspan="3"><?=isset($room_list['id']) ? h($room_list['id']) : ''?></td>
                    </tr>
                <?php endif ;?>
                <tr>
                    <th>部屋名</th>
                    <td colspan="3"><input type="text" id="room_edit-room-name-input" name="name" value="<?=isset($room_list['name']) ? h($room_list['name']) : ''?>"></td>
                </tr>
                <th rowspan="3">宿泊人数と価格</th>
                <tr>
                    <td>
                        <?php for ($i = 0; $i < $count; $i++) :?>
                            <input type="hidden" name="detail[<?=$i?>][id]" value="<?=isset($room_list['detail'][$i]['id']) ? h($room_list['detail'][$i]['id']) : ''?>">
                            <p class="p-box">
                                　人数：<input class="room_edit-input-capacity" type="text" name="detail[<?=$i?>][capacity]" value="<?=!empty($room_list['detail']) ? h($room_list['detail'][$i]['capacity']) : ''?>">人
                                　追記：<input class="room_edit-input-remarks" type="text" name="detail[<?=$i?>][remarks]" value="<?=!empty($room_list['detail']) ? h($room_list['detail'][$i]['remarks']) : ''?>">
                                　価格：<input class="room_edit-input-price" type="text" name="detail[<?=$i?>][price]" value="<?=!empty($room_list['detail']) ? h($room_list['detail'][$i]['price']) : ''?>">円（税込）
                            </p>
                        <?php endfor ;?>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <?php if ($count < 5) :?>
                            <input type="submit" name="add_box" value="BOX追加" formaction="room_edit.php?type=<?=h($_GET['type'])?><?=isset($_GET['id']) ? '&id=' . h($_GET['id']) : ''?>">
                        <?php endif ;?>
                        <?php if ($count > 1) :?>
                            <input type="submit" name="delete_box" value="BOX削除" formaction="room_edit.php?type=<?=h($_GET['type'])?><?=isset($_GET['id']) ? '&id=' . h($_GET['id']) : ''?>">
                        <?php endif ;?>
                    </td>
                </tr>
            </table>
            <p>
                <input type="submit" value="確認画面へ" class="to-conf-btn">
            </p>
        </form>
        <?php if ($_GET['type'] == 'edit') :?>
            <hr>
            <form action="" method="post" enctype="multipart/form-data">
                <table class="room_edit-img-table" border="1">
                    <tr>
                        <th>サムネイル</th>
                        <td><input type="file" name="upfile"></td>
                    </tr>
                    <tr>
                        <th>トップページサムネイル</th>
                        <td>
                            <?php if ($room_list['img']) :?>
                                <img src="<?=IMAGE_PATH . h($room_list['img'])?>">
                                <p><?=h($room_list['img'])?></p>
                            <?php endif ;?>
                        </td>
                    </tr>
                </table>
                <p class="upload-message">半角英数字のファイルのみアップロード可能です。</p>
                <input class="up-img-btn" type="submit" name="up_img_btn" value="アップロード" onclick="return confirm('本当に画像をアップロードしますか？')">
            </form>
        <?php endif ;?>
    </div>
</main>
<?php require_once('footer.php')?>