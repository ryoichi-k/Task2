<?php
session_start();
require_once '../util.inc.php';
require_once '../Model/Model.php';
require_once 'getPage.php';
require_once ('../htmlspecialchars.php');

$name  = '';
$capacity   = null;
$price = null;
$remarks   = '';

if (!empty($_POST)) {

    $name       = $_POST['name'];
    $capacity   = $_POST['capacity'];
    $price      = $_POST['price'];
    $remarks    = $_POST['remarks'];
    $token      = $_POST['token'];
    try {
        $model = new Model();
        $model->connect();
        //入力されたnameからroomテーブルのidをなんとか取得する
        $sql_room = 'SELECT * FROM room WHERE name = ? ';
        $stmt = $model->dbh->prepare($sql_room);
        $stmt->execute([$name]);
        $room = $stmt->fetch();
        //id=room_idのため、取得したidをroom_idに代入してINSERTでroom_detailテーブルに挿入
        $room_id = $room['id'];
        $sql_room_detail = 'INSERT INTO room_detail(room_id, capacity, remarks, price) VALUES(?, ?, ?, ?)';
        $stmt = $model->dbh->prepare($sql_room_detail);
        $stmt->execute([$room_id, $capacity, $remarks, $price]);

        // $sql_room = 'INSERT INTO room(id, name) VALUES(?, ?)';
        // $stmt = $model->dbh->prepare($sql_room);
        // $stmt->execute([$room_id, $name]);

        // $name       = '';
        // $capacity   = null;
        // $price      = null;
        // $remarks    = '';
        // $room_id    = 0;

        $edit = array(
            'name'        => $name,
            'capacity'    => $capacity,
            'price'       => $price,
            'remarks'     => $remarks,
            'token'       => $token
        );
        $_SESSION['edit'] = $edit;
        header('Location: room_conf.php');
        exit;
    } catch (PDOException $e) {
        header('Content-Type: text/plain; charset=UTF-8', true, 500);
        exit($e->getMessage());
    }
}
/**
 * XSS対策の参照名省略
 *
 * @param string string
 * @return string
 *
 */
function h(?string $string): string
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
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
            <p class="top-p">ログイン名[<?= ($_SESSION['admin']['name']); ?>]さん、ご機嫌いかがですか？</p>
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
        <form action="" method="post">
        <input type="hidden" name="token" value="<?= getToken() ?>">
            <?php getPage();?>
            <table class="room_edit-table">
                <tr>
                    <th>客室名<span>（必須）</span></th>
                    <td><input type="text" name="name" value="<?= h($name) ?>"></td>
                </tr>
                <tr>
                    <th>人数</th>
                    <td><input type="text" name="capacity" value="<?= h($capacity) ?>"></td>
                </tr>
                <tr>
                    <th>価格</th>
                    <td><input type="text" name="price" value="<?= h($price) ?>"></td>
                </tr>
                <tr>
                    <th>追記</th>
                    <td><textarea name="remarks" cols="80" rows="5"><?= h($remarks) ?></textarea></td>
                </tr>
            </table>
            <p>
                <input type="submit" name="add" value="追加">
                <input type="submit" value="キャンセル" formaction="room_list.php">
            </p>
        </form>
        <main>
        </main>
        <footer class="gl-footer">
            <p><small>2021 ebacorp.inc</small></p>
        </footer>
    </div>
</body>
</html>