<?php
session_start();
require_once ('../Model/Model.php');
require_once ('../getPage.php');
require_once ('../util.php');
// require_once ('sort.php');

const IMAGE_PATH = '../images/';
try {
    $model = new Model();
    $model->connect();
    $sql = 'SELECT * FROM room ORDER BY created_at DESC';
    $stmt = $model->dbh->query($sql); //dbhプロパティにpdoが格納されているので、dbhにアクセスしないとprepareメソッドは使えない
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    header('Content-Type: text/plain; charset=UTF-8', true, 500);
    exit($e->getMessage());
}
//id sort desc
if (!empty($_POST['id-desc'])) {
    $id_array = array();
    foreach( $rooms as $value) {
        $id_array[] = $value['id'];
    }
        array_multisort(
                $id_array,
                SORT_DESC,
                SORT_NUMERIC,
                $rooms,
                );
    echo '<pre>';
    var_dump($rooms);
    echo '</pre>';
}
//id sort asc
if (!empty($_POST['id-asc'])) {
    $id_array = array();
    foreach( $rooms as $value) {
        $id_array[] = $value['id'];
    }
        array_multisort(
                $id_array,
                SORT_ASC,
                SORT_NUMERIC,
                $rooms,
                );
    echo '<pre>';
    var_dump($rooms);
    echo '</pre>';
}
//name sort desc
if (!empty($_POST['name-desc'])) {
    $id_array = array();
    $name_array = array();
    foreach($rooms as $value) {
        $id_array[] = $value['id'];
        $name_array[] = $value['name'];
    }
    array_multisort(
        $name_array,
        SORT_DESC,
        SORT_STRING,
        $id_array,
        SORT_DESC,
        SORT_NUMERIC,
        $rooms,
        );
}
//name sort asc
if (!empty($_POST['name-asc'])) {
    $id_array = array();
    $name_array = array();
    foreach($rooms as $value) {
        $id_array[] = $value['id'];
        $name_array[] = $value['name'];
    }
    array_multisort(
        $name_array,
        SORT_ASC,
        SORT_STRING,
        $id_array,
        SORT_DESC,
        SORT_NUMERIC,
        $rooms,
        );
}
//updated_at sort desc
if (!empty($_POST['updated_at-desc'])) {
    $id_array = array();
    $updated_at_array = array();
    foreach($rooms as $value) {
        $id_array[] = $value['id'];
        $updated_at_array[] = $value['updated_at'];
    }
    array_multisort(
        $updated_at_array,
        SORT_DESC,
        SORT_NUMERIC,
        $id_array,
        SORT_DESC,
        SORT_NUMERIC,
        $rooms,
        );
}
//updated_at sort asc
if (!empty($_POST['updated_at-asc'])) {
    $id_array = array();
    $updated_at_array = array();
    foreach($rooms as $value) {
        $id_array[] = $value['id'];
        $updated_at_array[] = $value['updated_at'];
    }
    array_multisort(
        $updated_at_array,
        SORT_ASC,
        SORT_NUMERIC,
        $id_array,
        SORT_DESC,
        SORT_NUMERIC,
        $rooms,
        );
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CICACU | リスト 管理</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.0/css/bootstrap-reboot.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="wrapper">
        <header class="gl-header">
            <p class="top-p">ログイン名[<?=h($_SESSION['admin']['name']);?>]さん、ご機嫌いかがですか？</p>
            <div class="logout-link"><a href="logout.php">ログアウトする</a></div>
            <h1>CICACU</h1>
            <nav class="gl-nav">
                <ul>
                    <li><a href="top.php">top</a></li>
                    <li><a href="room_list.php">○○管理</a></li>
                    <li><a href="#">○○管理</a></li>
                    <li><a href="#">○○管理</a></li>
                    <li><a href="#">○○管理</a></li>
                    <li><a href="#">○○管理</a></li>
                </ul>
            </nav>
        </header>
        <main>
        <?php getPage();?>
            <table class="room_list-table" border="1">
                <tr>
                    <th>
                        <form action="" method="post">
                            <input type="submit" name="id-asc" value="▲"><br>
                                ID<br>
                            <input type="submit" name="id-desc" value="▼">
                        </form>
                    </th>
                    <th>
                        <form action="" method="post">
                            <input type="submit" name="name-asc" value="▲"><br>
                                部屋名<br>
                            <input type="submit" name="name-desc" value="▼">
                        </form>
                    </th>
                    <th>画像</th>
                    <th>登録日時</th>
                    <th>
                        <form action="" method="post">
                            <input type="submit" name="updated_at-asc" value="▲"><br>
                            更新日時<br>
                            <input type="submit" name="updated_at-desc" value="▼">
                        </form>
                    </th>
                    <th><button onclick="location.href='./room_edit.php'">新規登録</button></th>
                </tr>
                <?php foreach ($rooms as $room):?>
                    <tr>
                        <td><?=h($room['id'])?></td>
                        <td><?=h($room['name'])?></td>
                        <?php if ($room['img']) : ?>
                        <td><img src="<?= h(IMAGE_PATH . $room['img']) ?>" width="64" height="64" alt=""></td>
                        <?php else : ?>
                        <td><img src="../images/01.jpg" width="64" height="64" alt=""></td>
                        <?php endif; ?>
                        <td><?=h($room['created_at'])?></td>
                        <td><?=h($room['updated_at'])?></td>
                        <td>
                            <a href="room_edit.php?id=<?=h($room['id'])?>&type=edit">編集</a>
                            <form action="" method="post">
                                <input type="hidden" name="id" value="<?=h($room['id'])?>">
                                <input type="submit" name="delete" id="delete-btn" value="削除">
                            </form>
                        </td>
                    </tr>
                <?php endforeach;?>
            </table>
         </main>
        <footer class="gl-footer">
            <p><small>2021 ebacorp.inc</small></p>
        </footer>
    </div>
    <script>
        const btn = document.querySelector('#delete-btn');
        function deleteMessage() {
            let con = confirm('本当に削除しますか？');
            if (con) {

            }else{

            }
        };
        btn.addEventListener('click', deleteMessage);
    </script>
</body>
</html>