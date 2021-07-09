<?php
session_start();
require_once ('util.inc.php');
require_once ('Model/Model.php');
require_once ('getPage.php');
require_once ('util.php');

const IMAGE_PATH = '../images/';

$name  = '';
$capacity   = null;
$price = null;
$remarks   = '';
$img   = '';
$isEdited = false;
$imgError = '';
$edit_id = null;
if (isset($_GET['type'])) {
    $edit_id   = $_GET['id'];
    $isEdited = true;
    try {
        //room
        $model = new Model();
        $model->connect();
        $sql_edit = 'SELECT * FROM room WHERE id = ?';
        $stmt = $model->dbh->prepare($sql_edit);
        $stmt->execute([$edit_id]);
        $room_edit = $stmt->fetch(PDO::FETCH_ASSOC);
        //room_detail
        $sql_edit_detail = 'SELECT * FROM room_detail WHERE room_id = ?';
        $stmt = $model->dbh->prepare($sql_edit_detail);
        $stmt->execute([$edit_id]);
        //testで１行取得にしている後でfetchAllにする
        $room_edit_details = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        header('Content-Type: text/plain; charset=UTF-8', true, 500);
        exit($e -> getMessage());
    }
}
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
if (!empty($_POST['cancel'])) {
    $name       = $_POST['name'];
    $capacity   = $_POST['capacity'];
    $price      = $_POST['price'];
    $remarks    = $_POST['remarks'];
    $token      = $_POST['token'];
}
if (!empty($_POST['cancel-edit'])) {
    $name       = $_POST['name'];
    $capacity   = $_POST['capacity'];
    $price      = $_POST['price'];
    $remarks    = $_POST['remarks'];
    $token      = $_POST['token'];
    try {
        //room
        $model = new Model();
        $model->connect();
        $sql_edit = 'SELECT * FROM room WHERE id = ?';
        $stmt = $model->dbh->prepare($sql_edit);
        $stmt->execute([$edit_id]);
        $room_edit = $stmt->fetch(PDO::FETCH_ASSOC);
        //room_detail
        $sql_edit_detail = 'SELECT * FROM room_detail WHERE room_id = ?';
        $stmt = $model->dbh->prepare($sql_edit_detail);
        $stmt->execute([$edit_id]);
        //testで１行取得にしている後でfetchAllにする
        $room_edit_details = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = array_merge($room_edit_details, $_POST);
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
        <div class="room_edit-container">
            <form action="" method="post">
                <input type="hidden" name="token" value="<?=getToken()?>">
                <div class="getPage"><?php getPage(); ?></div>
                <table class="room_edit-table">
                    <?php if($isEdited == true):?>
                    <tr>
                        <th>ID</th>
                        <td colspan="3"><?=$room_edit['id']?></td>
                    </tr>
                    <?php endif;?>
                    <tr>
                        <th>部屋名<span>（必須）</span></th>
                        <td colspan="3"><input type="text" id="room_edit-room-name-input" name="name" value="<?=$isEdited == true ? h($room_edit['name']) : h($name)?>"></td>
                    </tr>
                    <th rowspan="3">宿泊人数と価格</th>
                    <tr>
                        <?php if($isEdited == true):?>
                        <div class="box">
                            <td id="room_edit-td-capacity"><div class="indent">人数：</div><br><input class="room_edit-input-capacity" type="text" name="capacity" value="<?=h($room_edit_details['capacity'])?>">人</td>
                            <td id="room_edit-td-remarks"><div class="indent">追記：</div><br><input class="room_edit-input-remarks" type="text" name="remarks" value="<?=h($room_edit_details['remarks'])?>"></td>
                            <td id="room_edit-td-price"><div class="indent">価格：</div><br><input class="room_edit-input-price" type="text" name="price" value="<?=h($room_edit_details['price'])?>">円（税込）</td>
                        </div>
                        <?php else:?>
                            <div class="box">
                            <td id="room_edit-td-capacity"><div class="indent">人数：</div><br><input class="room_edit-input-capacity" type="text" name="capacity" value="<?=h($capacity)?>">人</td>
                            <td id="room_edit-td-remarks"><div class="indent">追記：</div><br><input class="room_edit-input-remarks" type="text" name="remarks" value="<?=h($remarks)?>"></td>
                            <td id="room_edit-td-price"><div class="indent">価格：</div><br><input class="room_edit-input-price" type="text" name="price" value="<?=h($price)?>">円（税込）</td>
                        </div>
                        <?php endif;?>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <input type="submit" name="add-box" value="BOX追加" formaction="room_edit.php">
                            <input type="submit" name="delete-box" value="BOX削除" formaction="room_edit.php">
                        </td>
                    </tr>
                </table>
                <p>
                <?php if($isEdited == true):?>
                    <input type="submit" name="edit-room-detail" value="確認画面へ" class="to-conf-btn" formaction="room_conf.php?type=edit">
                <?php else:?>
                    <input type="submit" name="add-new-room-detail" value="確認画面へ" class="to-conf-btn" formaction="room_conf.php">
                <?php endif;?>
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
                        <?php if($room_edit['img']):?>
                            <img src="<?= h(IMAGE_PATH . $room_edit['img']) ?>" alt="">
                            <p><?=$room_edit['img']?></p>
                        <?php else:?>
                            <img src="../images/01.jpg" alt="">
                        <?php endif;?>
                        </td>
                    </tr>
                </table>
                <p>半角英数字のファイルのみアップロード可能です。</p>
                <input type="submit" name="up-img-btn" value="アップロード" onclick="return confirm('本当に画像をアップロードしますか？')">
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