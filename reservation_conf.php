<?php
session_start();
require_once('UserModel.php');
require_once('admin/Model/Model.php');
require_once('admin/Model/Room.php');
require_once('Reservation.php');
require_once('User_UserAuth.php');
require_once('admin/util.php');
require_once('util.inc.php');

// 二重送信防止用トークンの発行
$token = uniqid('', true);

//トークンをセッション変数にセット
$_SESSION['token'] = $token;

if (empty($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
if (!empty($_POST['payment'])) {

    //現在日時から3ヶ月後の日付を取得
    $date = new DateTime();
    $three_month_lator = $date->modify('+3 months')->format('Y-m-d');

    //宿泊予約日の翌日（宿泊終了日）を計算
    $day = new DateTime($_POST['date']);
    $next_day = $day->modify('+1 days')->format('Y-m-d');

    try {
        //部屋情報詳細を検索→画面表示あり
        try {
            $room2 = new Room();
            $room_detail = $room2->findRoomDetailId($_POST['room_detail_id']);
        } catch (Exception $e) {
            $error = 'エラーが発生しました。<br>CICACU辻井迄ご連絡ください。080-1411-4095(辻井) info@cicacu.jp';
            $room_detail = [];
            $room_detail['capacity'] = '';
        }
        //既存の予約内容を検索
        try {
            $reservations = new Reservation();
            $reservation = $reservations->searchReservation($_POST['room_detail_id']);
        } catch (Exception $e) {
            $error = 'エラーが発生しました。<br>CICACU辻井迄ご連絡ください。080-1411-4095(辻井) info@cicacu.jp';
            $reservation = [];
            $reservation['status'] = '';
        }
    } catch (Exception $e) {
        $error = 'エラーが発生しました。<br>CICACU辻井迄ご連絡ください。080-1411-4095(辻井) info@cicacu.jp';
    }

    if ($reservation == false) {//DB内にない新規予約
        //宿泊人数が客室に登録されている人数を超えていないこと
        if ($_POST['number'] > $room_detail['capacity']) {
            $error = '客室の宿泊可能人数を超えています。別のお部屋をお選びください。';
        }
        //宿泊終了日が3か月後以前であること
        if ($next_day > $three_month_lator) {
            $error = '3ヶ月以降のご予約はできません。';
        }
    } else {//予約済の場合
        //予約しようとしている客室が予約済みでないこと
        if (isset($reservation['status']) && $reservation['status'] == 1) {
            $error = '満室です。別の部屋を選んでください。';
        }
    }

    try {
        $model = new Model();
        $model->connect();

        $sql_room_name = 'SELECT * FROM room INNER JOIN room_detail ON room.id = room_detail.room_id WHERE room_detail.id = ?';
        $stmt = $model->dbh->prepare($sql_room_name);
        $stmt->execute([$_POST['room_detail_id']]);
        $room_detail = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $error = 'エラーが発生しました。<br>CICACU辻井迄ご連絡ください。080-1411-4095(辻井) info@cicacu.jp';
    }
}
?>
<?php include 'doctype_header_user.php'?>
    <title>CICACU | 予約確認画面</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.0/css/bootstrap-reboot.min.css">
    <link rel="stylesheet" href="css/reservation.css">
</head>
<body>
    <div class="wrapper">
        <p class="top-p"><?=h($_SESSION['user']['name']);?>さん、ご機嫌いかがですか？</p>
        <div class="logout-link"><a href="logout.php">ログアウトする</a></div>
        <h1>確認画面</h1>
        <div class="reservation-container">
            <?php if (isset($error)) :?>
                <h3 class="error"><?=$error?></h3>
            <?php endif ;?>
            <table class="reservation-table">
                <form action="" method="post">
                    <tr>
                        <th>
                            <h3>部屋：</h3>
                        </th>
                        <td>
                            <h3><?=$room_detail['name']?></h3>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <h3>宿泊日：</h3>
                        </th>
                        <td>
                            <h3><?=(new Datetime($_POST['date']))->format('Y年m月d日')?></h3>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <h3>人数：</h3>
                        </th>
                        <td>
                            <h3><?=$_POST['number']?>名様</h3>
                        </td>
                    </tr>
                    <input type="hidden" name="token" value="<?=$token?>">
                    <input type="hidden" name="reservation_room_detail_id" value="<?=h($_POST['room_detail_id'])?>">
                    <input type="hidden" name="reservation_date" value="<?=h($_POST['date'])?>">
                    <input type="hidden" name="reservation_number" value="<?=h($_POST['number'])?>">
                    <input type="hidden" name="reservation_room_detail_name" value="<?=h($room_detail['name'])?>">
                    <input type="hidden" name="reservation_total_price" value="<?=h($room_detail['price'])?>">
                    <input type="hidden" name="reservation_post_payment" value="<?=h($_POST['payment'])?>">
                    <tr>
                        <th>
                            <h3>お支払い総額：</h3>
                        </th>
                        <td>
                            <h3><?=$room_detail['price']?>円</h3>
                        </td>
                    </tr>
            </table>
            <input class="submit-button" name="send" type="submit" value="送信" formaction="reservation_done.php"></p>
            </form>
        </div>
        <footer class="reservation-footer">
            <p><small>2021 ebacorp.inc</small></p>
        </footer>
    </div>
</body>
</html>