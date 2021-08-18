<?php
session_start();
require_once('Model/Model.php');
require_once('Model/Room.php');
require_once('util.php');

$room = new Room();

//論理削除処理
if (!empty($_POST['delete'])) {
    $room->roomDelete($_POST['id']);
}

try {
    $rooms = $room->roomSelectList();
} catch (Exception $e) {
    $error = 'エラーが発生しました。<br>CICACU辻井迄ご連絡ください。080-1411-4095(辻井) info@cicacu.jp';
    $rooms = [];
}

$model = new Model();
$model->connect();

if (!empty($_GET['sort'])) {
    try {
        $rooms = $room->roomSort($_GET['sort'], $_GET['value']);
    } catch (Exception $e) {
        $error = 'エラーが発生しました。<br>CICACU辻井迄ご連絡ください。080-1411-4095(辻井) info@cicacu.jp';
    }
}
?>
<?php require_once('header.php')?>
<main>
    <div class="room_list-container">
        <div class="getPage"><?php getPage() ;?></div>
        <table class="room_list-table" border="1">
            <?php if (isset($error)):?>
                <p class="error"><?=$error?></p>
            <?php endif; ?>
            <tr>
                <th>
                    <a href="room_list.php?sort=id&value=asc" class="sort" name="sort" value="asc">▲</a><br>
                        ID<br>
                    <a href="room_list.php?sort=id&value=desc" class="sort" name="sort" value="desc">▼</a><br>
                </th>
                <th>
                    <a href="room_list.php?sort=name&value=asc" class="sort" name="sort" value="asc">▲</a><br>
                        部屋名<br>
                    <a href="room_list.php?sort=name&value=desc" class="sort" name="sort" value="desc">▼</a><br>
                </th>
                <th>画像</th>
                <th>登録日時</th>
                <th>
                    <a href="room_list.php?sort=updated_at&value=asc" class="sort" name="sort" value="asc">▲</a><br>
                        更新日時<br>
                    <a href="room_list.php?sort=updated_at&value=desc" class="sort" name="sort" value="desc">▼</a><br>
                </th>
                <th>
                    <form action="" method="post">
                        <input type="button" value="新規登録" class="new-btn" onclick="location.href='./room_edit.php?type=new'">
                    </form>
                </th>
            </tr>
            <?php foreach ($rooms as $room) :?>
                <tr>
                    <td><?=$room['id']?></td>
                    <td><?=h($room['name'])?></td>
                    <td>
                        <?php if ($room['img']) :?>
                            <img src="<?=IMAGE_PATH . h($room['img'])?>" width="64" height="64">
                        <?php endif ;?>
                    </td>
                    <td><?=(new Datetime($room['created_at']))->format('Y年m月d日H時i分s秒')?></td>
                    <td><?=(new Datetime($room['updated_at']))->format('Y年m月d日H時i分s秒')?></td>
                    <td>
                        <div class="flex-room_list">
                            <div class="flex-room_list_div">
                                <form action="room_edit.php?id=<?=$room['id']?>&type=edit" method="post">
                                    <input type="submit" value="編集" class="edit-btn" onclick="location.href='./room_edit.php?id=<?=$room['id']?>&type=edit'">
                            </div>
                            </form>
                            <div class="flex-room_list_div">
                                <form action="" method="post">
                                    <input type="hidden" name="id" value="<?=$room['id']?>">
                                    <input type="submit" name="delete" class="delete-btn" value="削除" onclick="return confirm('本当に削除しますか？')">
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach ;?>
        </table>
    </div>
</main>
<?php require_once('footer.php')?>