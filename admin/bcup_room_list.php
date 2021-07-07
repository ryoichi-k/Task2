<?php
session_start();
require_once ('Model.php');
require_once ('getPage.php');
require_once ('util.php');

try {
    $model = new Model();
    $model->connect();
    $sql = 'SELECT * FROM room ORDER BY created_at DESC';
    $stmt = $model->dbh->query($sql); //dbhプロパティにpdoが格納されているので、dbhにアクセスしないとprepareメソッドは使えない
    $rooms = $stmt->fetchAll(); //$resultにユーザー情報一人分が格納されている

    // if (isset($_POST['delete'])) {
    //     $sql = 'DELETE FROM room WHERE id = ?';
    //     $stmt = $model->dbh->query($sql);
    //     $stmt->execute([$id]);
    //     header('Location: room_done.php');
    //     exit;
    // }

} catch (PDOException $e) {
    header('Content-Type: text/plain; charset=UTF-8', true, 500);
    exit($e->getMessage());
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
                            <input type="submit" name="asc" value="▲"><br>
                                ID<br>
                            <input type="submit" name="desc" value="▼">
                        </form>
                    </th>
                    <th>部屋名</th>
                    <th>画像</th>
                    <th>登録日時</th>
                    <th>更新日時</th>
                    <th><button onclick="location.href='./room_edit.php'">新規登録</button></th>
                </tr>
                <?php foreach ($rooms as $room):?>
                    <tr>
                        <td><?=h($room['id'])?></td>
                        <td><?=h($room['name'])?></td>
                        <td>画像</td>
                        <td><?=h($room['created_at'])?></td>
                        <td><?=h($room['updated_at'])?></td>
                        <td>
                            <a href="room_edit.php?id=<?=h($room['id'])?>">編集</a>
                            <form action="" method="post">
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