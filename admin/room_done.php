<?php
session_start();
require_once ('Model/Model.php');
require_once ('getPage.php');
require_once ('util.php');
require_once ('util.inc.php');
$isSended = null;
//新規登録
if (!empty($_POST['send'])) {
    $name       = $_POST['name'];
    $capacity   = $_POST['capacity'];
    $price      = $_POST['price'];
    $remarks    = $_POST['remarks'];
    try {
        $model = new Model();
        $model->connect();
        //入力されたnameからroomテーブルのidをなんとか取得する
        $sql_room = 'SELECT * FROM room WHERE name = ? ';
        $stmt = $model->dbh->prepare($sql_room);
        $stmt->execute([$name]);
        $room = $stmt->fetch(PDO::FETCH_ASSOC);//id,name,cre,up,
        //id=room_idのため、取得したidをroom_idに代入してINSERTでroom_detailテーブルに挿入
        $room_id = $room['id'];
        $sql_room_detail = 'INSERT INTO room_detail(room_id, capacity, remarks, price) VALUES(?, ?, ?, ?)';
        $stmt = $model->dbh->prepare($sql_room_detail);
        $stmt->execute([$room_id, $capacity, $remarks, $price]);
        $isSended = 1;
        //test
        $sql_test = 'SELECT * FROM room_detail JOIN room ON room_detail.room_id = room.id ORDER BY room_detail.id DESC';
        $stmt = $model->dbh->query($sql_test); //dbhプロパティにpdoが格納されているので、dbhにアクセスしないとprepareメソッドは使えない
        $rooms_test = $stmt->fetchAll(PDO::FETCH_ASSOC); //$resultにユーザー情報全員分が格納されているORDER BY id DESC
    } catch (PDOException $e) {
        header('Content-Type: text/plain; charset=UTF-8', true, 500);
        exit($e->getMessage());
    }
}
//編集
if (!empty($_POST['send-edit'])) {
    $name       = $_POST['name'];
    $capacity   = $_POST['capacity'];
    $price      = $_POST['price'];
    $remarks    = $_POST['remarks'];
    $updated_at    = $_POST['updated_at'];
    $date = new DateTime();
    $date->setTimeZone( new DateTimeZone('Asia/Tokyo'));
    $today = $date->format('Y-m-d H:i:s');
    $updated_at = $today;
    try {
        $model = new Model();
        $model->connect();
        //入力されたnameからroomテーブルのidを取得する
        $sql_room = 'SELECT * FROM room WHERE name = ? ';
        $stmt = $model->dbh->prepare($sql_room);
        $stmt->execute([$name]);
        $room_edit_done = $stmt->fetch(PDO::FETCH_ASSOC);
        $room_id = $room_edit_done['id'];
        //roomテーブルに部屋名を上書き保存
        $sql_room_edit_done = 'UPDATE room
                                SET name = ?,
                                updated_at = ?
                                WHERE id = ? ';
        $stmt = $model->dbh->prepare($sql_room_edit_done);
        $stmt->execute([$name, $updated_at, $room_id]);
        //詳細に部屋情報を上書き保存ここでエラーでてる→SETに,が足りなかった。
        $sql_room_detail_edit_done = 'UPDATE room_detail
                                        SET capacity = ?,
                                        price = ?,
                                        remarks = ?
                                        WHERE room_id = ? ';
        $stmt = $model->dbh->prepare($sql_room_detail_edit_done);
        $stmt->execute([$capacity, $price, $remarks, $room_id]);
        $isSended = 1;
        //test
        $sql_test = 'SELECT * FROM room_detail JOIN room ON room_detail.room_id = room.id ORDER BY room_detail.id DESC';
        $stmt = $model->dbh->query($sql_test); //dbhプロパティにpdoが格納されているので、dbhにアクセスしないとprepareメソッドは使えない
        $rooms_test = $stmt->fetchAll(PDO::FETCH_ASSOC); //$resultにユーザー情報全員分が格納されているORDER BY id DESC
    } catch (PDOException $e) {
        header('Content-Type: text/plain; charset=UTF-8', true, 500);
        exit($e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CICACU | 編集完了 管理</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.0/css/bootstrap-reboot.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="wrapper">
        <header class="gl-header">
                <p class="top-p">ログイン名[<?=h($_SESSION['admin']['name']);?>]さん、ご機嫌いかがですか？</p><div class="logout-link"><a href="logout.php">ログアウトする</a></div>
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
        <div class="getPage"><?php getPage(); ?></div>
        <?php if(isset($isSended)):?>
            <h3 class="done-message">登録完了しました。</h3>
            <table class="room_list-table test-table" border="1">
                <tr>
                    <th>ID</th>
                    <th>room_id</th>
                    <th>人数</th>
                    <th>追記</th>
                    <th>価格</th>
                    <th>部屋名</th>
                </tr>
                <?php foreach ($rooms_test as $test):?>
                    <tr>
                        <td><?=h($test['id'])?></td>
                        <td><?=h($test['room_id'])?></td>
                        <td><?=h($test['capacity'])?></td>
                        <td><?=h($test['remarks'])?></td>
                        <td><?=h($test['price'])?></td>
                        <td><?=h($test['name'])?></td>
                    </tr>
                <?php endforeach;?>
            </table>
        <?php else:?>
            <h2></h2>
        <?php endif;?>
        </main>
        <footer class="gl-footer">
            <p><small>2021 ebacorp.inc</small></p>
        </footer>
    </div>
</body>
</html>