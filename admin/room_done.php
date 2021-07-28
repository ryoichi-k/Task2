<?php
session_start();
require_once ('Model/Model.php');
require_once ('getPage.php');
require_once ('util.php');
require_once ('util.inc.php');
$isSended = null;
$isEdited = null;
//新規登録
if (!empty($_POST['send'])) {
    $name = $_POST['name'];
    try {
        $model = new Model();
        $model->connect();
        $date = new DateTime();
        $date->setTimeZone( new DateTimeZone('Asia/Tokyo'));
        $time = $date->format('Y-m-d H:i:s');
        $sql_room = 'INSERT INTO room(name, created_at) VALUES(?, ?)';
        $stmt = $model->dbh->prepare($sql_room);
        $stmt->execute([$name, $time]);
        $sql_room = 'SELECT * FROM room WHERE name = ? ';
        $stmt = $model->dbh->prepare($sql_room);
        $stmt->execute([$name]);
        $room = $stmt->fetch(PDO::FETCH_ASSOC);
        $room_id = $room['id'];
        foreach ($_POST['detail'] as $value) {
            $sql_room_detail = 'INSERT INTO room_detail(room_id, capacity, remarks, price) VALUES(?, ?, ?, ?)';
            $stmt = $model->dbh->prepare($sql_room_detail);
            $stmt->execute([$room_id, $value['capacity'], $value['remarks'], $value['price']]);
        }
        $isSended = 1;
        unset($value);
        } catch (PDOException $e) {
        header('Content-Type: text/plain; charset=UTF-8', true, 500);
        exit($e->getMessage());
    }
}
//編集
if (!empty($_POST['send-edit'])) {
    $name = $_POST['name'];
    $date = new DateTime();
    $date->setTimeZone( new DateTimeZone('Asia/Tokyo'));
    $today = $date->format('Y-m-d H:i:s');
    $updated_at = $today;
    try {
        $model = new Model();
        $model->connect();
        $sql_room = 'SELECT * FROM room WHERE name = ? ';
        $stmt = $model->dbh->prepare($sql_room);
        $stmt->execute([$name]);
        $room_edit_done = $stmt->fetch(PDO::FETCH_ASSOC);
        $room_id = $room_edit_done['id'];
        $sql_room_edit_done = 'UPDATE room
                                            SET name = ?,
                                            updated_at = ?
                                            WHERE id = ? ';
        $stmt = $model->dbh->prepare($sql_room_edit_done);
        $stmt->execute([$name, $updated_at, $room_id]);
        $sql_room_detail_delete_flg_to_one = 'UPDATE room_detail
                                                                SET delete_flg = 1
                                                                WHERE room_id = ? ';
        $stmt = $model->dbh->prepare($sql_room_detail_delete_flg_to_one);
        $stmt->execute([$room_id]);
        foreach ($_POST['detail'] as $value) {
            $sql_room_detail_edit_done ='INSERT INTO room_detail(room_id, capacity, price, remarks) VALUES (?, ?, ?, ?)';
            $stmt = $model->dbh->prepare($sql_room_detail_edit_done);
            $stmt->execute([$room_id, $value['capacity'], $value['price'], $value['remarks']]);
        }
        $isEdited = true;
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
        <?php if(isset($isEdited)):?>
            <h3 class="done-message">編集完了しました。</h3>
        <?php endif;?>
        <?php if(isset($isSended)):?>
                <h3 class="done-message">登録完了しました。</h3>
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