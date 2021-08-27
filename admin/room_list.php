<?php
session_start();
// require_once (dirname(__FILE__).'/../ExternalFiles/Model/Model.php');
// require_once (dirname(__FILE__).'/../ExternalFiles/Model/Room.php');
// require_once (dirname(__FILE__).'/../ExternalFiles/util.php');
require_once('Model/Model.php');
require_once('Model/Room.php');
require_once('util.php');

$room = new Room();

//論理削除処理
if (!empty($_POST['delete'])) {
    $room->deleteRoom($_POST['id']);
}

//初期表示とソート
try {
    $rooms = $room->sortRoom();
} catch (Exception $e) {
    $error = 'システムエラーが発生しました。<br>CICACU辻井迄ご連絡ください。080-1411-4095(辻井) info@cicacu.jp';
}

//部屋名検索機能→GETに変更する
if (isset($_POST['search'])) {
    if (empty($_POST['search_name'])) {
        $error = '検索項目が未入力です。';
    }
    try {
        $rooms = $room->searchRoom($_POST['search_name']);
    } catch (Exception $e) {
        $error = '検索エラーが発生しました。<br>CICACU辻井迄ご連絡ください。080-1411-4095(辻井) info@cicacu.jp';
    }
}

?>
<?php require_once('header.php')?>
<main>
    <div class="room_list-container">
        <div class="getPage_search">
            <div class="getPage"><?php getPage() ;?></div>
            <p class="search">
                <form action="" method="post">
                    <input type="text" name="search_name">
                    <input type="submit" name="search" value="検索">
                </form>
            </p>
        </div>
        <?php if (isset($error)) :?>
            <p class="error"><?=$error?></p>
        <?php endif ;?>
        <?php if ($rooms == false) :?>
            <p class="first-message">部屋データがありません。新規登録ボタンから部屋を登録してください。</p>
        <?php endif ;?>
        <table class="room_list-table" border="1">
            <tr>
                <th>
                    <a href="room_list.php?sort=id&order=asc" class="sort" name="sort" value="asc">▲</a><br>
                        ID<br>
                    <a href="room_list.php?sort=id&order=desc" class="sort" name="sort" value="desc">▼</a><br>
                </th>
                <th>
                    <a href="room_list.php?sort=name&order=asc" class="sort" name="sort" value="asc">▲</a><br>
                        部屋名<br>
                    <a href="room_list.php?sort=name&order=desc" class="sort" name="sort" value="desc">▼</a><br>
                </th>
                <th>画像</th>
                <th>登録日時</th>
                <th>
                    <a href="room_list.php?sort=updated_at&order=asc" class="sort" name="sort" value="asc">▲</a><br>
                        更新日時<br>
                    <a href="room_list.php?sort=updated_at&order=desc" class="sort" name="sort" value="desc">▼</a><br>
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
                                </form>
                            </div>
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