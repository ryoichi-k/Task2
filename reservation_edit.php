<?php
session_start();
require_once('UserModel.php');
require_once('User_UserAuth.php');
require_once('admin/util.php');

if (empty($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
try {
    $model = new UserModel();
    $model->connect();

    $sql = 'SELECT * FROM room INNER JOIN room_detail ON room.id = room_detail.room_id WHERE room.delete_flg = 0 AND room_detail.delete_flg = 0';
    $stmt = $model->dbh->query($sql);
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo 'rooms<pre>';
    print_r($rooms);
    echo '</pre>';

    $sql = 'SELECT * FROM m_payment';
    $stmt = $model->dbh->query($sql);
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = 'エラーが発生しました。<br>CICACU辻井迄ご連絡ください。080-1411-4095(辻井) info@cicacu.jp';
}
?>
<?php include 'doctype_header_user.php'?>
    <title>CICACU | 予約画面</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.0/css/bootstrap-reboot.min.css">
    <link rel="stylesheet" href="css/reservation.css">
</head>
<body>
    <div class="wrapper">
        <p class="top-p"><?=h($_SESSION['user']['name']);?>さん、ご機嫌いかがですか？</p>
        <div class="logout-link"><a href="logout.php">ログアウトする</a></div>
        <h1>予約画面</h1>
        <div class="reservation-container">
            <table class="reservation-table">
            <?php if (isset($error)) :?>
                <p class="error"><?=$error?></p>
            <?php endif ;?>
                <form action="reservation_conf.php" method="post">
                    <tr>
                        <th>
                            <h3>宿泊されるお部屋：</h3>
                        </th>
                        <td>
                            <select name="room_detail_id">
                                <?php foreach ($rooms as $index => $value) :?>
                                    <option value="<?=$rooms[$index]['id']?>"><?=$rooms[$index]['name']?>(<?=$rooms[$index]['capacity']?>人部屋)</option>
                                <?php endforeach ;?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <h3>ご宿泊期間：</h3>
                        </th>
                        <td><input type="date" name="date"></input></td>
                    </tr>
                    <tr>
                        <th>
                            <h3>人数：</h3>
                        </th>
                        <td>
                            <select name="number">
                                <?php for ($i = 1; $i < 5; $i++) :?>
                                    <option value="<?=$i?>"><?=$i?>名様</option>
                                <?php endfor ;?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <h3>お支払い方法：</h3>
                        </th>
                        <td>
                            <select name="payment">
                                <?php for ($i = 0; $i < count($payments); $i++) :?>
                                    <option value="<?=$payments[$i]['name']?>"><?=$payments[$i]['name']?></option>
                                <?php endfor ;?>
                            </select>
                        </td>
                    </tr>
            </table>
            <p><input type="submit" value="確認画面へ" class="submit-button"></p>
            </form>
        </div>
        <footer class="reservation-footer">
            <p><small>2021 ebacorp.inc</small></p>
        </footer>
    </div>
</body>
</html>