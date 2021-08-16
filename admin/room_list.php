<?php
session_start();
require_once('Model/Model.php');
require_once('Model/Room.php');
require_once('util.php');

$room = new Room();
$rooms = $room->roomSelectList();
$model = new Model();
$model->connect();

if (!empty($_GET['sort_id'])) {
    //id sort desc
    if ($_GET['sort_id'] == '▼') {
        $sql_sort = 'SELECT * FROM room WHERE delete_flg = 0 ORDER BY id DESC';
        $stmt = $model->dbh->query($sql_sort);
        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    //id sort asc
    if ($_GET['sort_id'] == '▲') {
        $sql_sort_asc = 'SELECT * FROM room WHERE delete_flg = 0 ORDER BY id ASC';
        $stmt = $model->dbh->query($sql_sort_asc);
        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

if (!empty($_GET['sort_name'])) {
    //name sort desc
    if ($_GET['sort_name'] == '▼') {
        $sql_sort = 'SELECT * FROM room WHERE delete_flg = 0 ORDER BY name DESC';
        $stmt = $model->dbh->query($sql_sort);
        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    //name sort asc
    if ($_GET['sort_name'] == '▲') {
        $sql_sort = 'SELECT * FROM room WHERE delete_flg = 0 ORDER BY name IS NULL ASC, name ASC';
        $stmt = $model->dbh->query($sql_sort);
        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

if (!empty($_GET['sort_updated_at'])) {
    //updated_at sort desc
    if ($_GET['sort_updated_at'] == '▼') {
        $sql_sort = 'SELECT * FROM room WHERE delete_flg = 0 ORDER BY updated_at DESC';
        $stmt = $model->dbh->query($sql_sort);
        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    //updated_at sort asc
    if ($_GET['sort_updated_at'] == '▲') {
        try {
            $sql_sort = 'SELECT * FROM room WHERE delete_flg = 0
        ORDER BY updated_at IS NULL ASC, updated_at ASC';
            $stmt = $model->dbh->query($sql_sort);
            $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            header('Content-Type: text/plain; charset=UTF-8', true, 500);
            exit($e->getMessage());
        }
    }
}

//論理削除処理
if (!empty($_POST['delete'])) {
    $room->roomDelete($_POST['id']);
}
?>
<?php include 'doctype_header.php'?>
<main>
    <div class="room_list-container">
        <div class="getPage"><?php getPage();?></div>
        <table class="room_list-table" border="1">
            <tr>
                <th>
                    <form action="" method="get">
                        <input class="sort" type="submit" name="sort_id" value="▲"><br>
                        ID<br>
                        <input class="sort" type="submit" name="sort_id" value="▼">
                    </form>
                </th>
                <th>
                    <form action="" method="get">
                        <input class="sort" type="submit" name="sort_name" value="▲"><br>
                        部屋名<br>
                        <input class="sort" type="submit" name="sort_name" value="▼">
                    </form>
                </th>
                <th>画像</th>
                <th>登録日時</th>
                <th>
                    <form action="" method="get">
                        <input class="sort" type="submit" name="sort_updated_at" value="▲"><br>
                        更新日時<br>
                        <input class="sort" type="submit" name="sort_updated_at" value="▼">
                    </form>
                </th>
                <th>
                    <form action="" method="post">
                        <input type="button" value="新規登録" class="new-btn" onclick="location.href='./room_edit.php?type=new'">
                    </form>
                </th>
            </tr>
            <?php foreach ($rooms as $room):?>
                <tr>
                    <td><?=h($room['id'])?></td>
                    <td><?=h($room['name'])?></td>
                    <td>
                        <?php if ($room['img']):?>
                            <img src="<?= h(IMAGE_PATH . $room['img'])?>" width="64" height="64" alt="">
                        <?php endif;?>
                    </td>
                    <td><?=h((new Datetime($room['created_at']))->format('Y年m月d日H時i分s秒'))?></td>
                    <td><?=h((new Datetime($room['updated_at']))->format('Y年m月d日H時i分s秒'))?></td>
                    <td>
                        <div class="flex-room_list">
                            <div class="flex-room_list_div">
                                <form action="room_edit.php?id=<?=h($room['id'])?>&type=edit" method="post">
                                    <input type="submit" value="編集" class="edit-btn" onclick="location.href='./room_edit.php?id=<?=h($room['id'])?>&type=edit'">
                            </div>
                            </form>
                            <div class="flex-room_list_div">
                                <form action="" method="post">
                                    <input type="hidden" name="id" value="<?=h($room['id'])?>">
                                    <input type="submit" name="delete" class="delete-btn" value="削除" onclick="return confirm('本当に削除しますか？')">
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</main>
<?php include 'footer.html'?>
</div>
</body>
</html>