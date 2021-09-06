<?php
session_start();
require_once('UserModel.php');
require_once('User_UserAuth.php');
require_once('admin/util.php');
require_once('util.inc.php');

if (empty($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
if (!empty($_POST['send'])) {
    $user_id = $_SESSION['user']['id'];
    $token = isset($_POST['token']) ? $_POST['token'] : '';
    $session_token = isset($_SESSION['token']) ? $_SESSION['token'] : '';
    unset($_SESSION['token']);

    // POSTされたトークンとセッション変数のトークンの比較→二重送信防止
    if ($token == '' || $token != $session_token) {
        header('Location: reservation_edit.php');
        exit;
    }

    try {
        $model = new UserModel();
        $model->connect();

        //セッションデータからユーザー情報を抽出
        $sql = 'SELECT * FROM user WHERE id = ?';
        $stmt = $model->dbh->prepare($sql);
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        //reservation_post_paymentから支払い方法のidを取得
        $sql = 'SELECT * FROM m_payment WHERE name = ?';
        $stmt = $model->dbh->prepare($sql);
        $stmt->execute([$_POST['reservation_post_payment']]);
        $payment = $stmt->fetch(PDO::FETCH_ASSOC);

        //reservationテーブルに予約内容と、ユーザー情報を同時にinsert
        $sql = 'INSERT INTO reservation (room_detail_id, user_id, name, name_kana, mail, tel1, tel2, tel3, number, total_price, payment_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $stmt = $model->dbh->prepare($sql);
        $stmt->execute([$_POST['reservation_room_detail_id'], $user['id'], $user['name'], $user['name_kana'], $user['mail'], $user['tel1'], $user['tel2'], $user['tel3'], $_POST['reservation_number'], $_POST['reservation_total_price'], $payment['id']]);

        //select reservation
        $sql = 'SELECT * FROM reservation WHERE name = ? AND delete_flg = 0';
        $stmt = $model->dbh->prepare($sql);
        $stmt->execute([$user['name']]);
        $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

        //insert reservation_detail
        $sql = 'INSERT INTO reservation_detail (reservation_id, date, price) VALUES (?, ?, ?)';
        $stmt = $model->dbh->prepare($sql);
        $stmt->execute([$reservation['id'], $_POST['reservation_date'], $_POST['reservation_total_price']]);

        //メール送信用DBレコード検索
        // $sql_select_reservation_for_send_mail = 'SELECT * FROM reservation JOIN reservation_detail ON reservation.id = reservation_detail.reservation_id WHERE name = ? AND delete_flg = 0';
        // $stmt = $model->dbh->prepare($sql_select_reservation_for_send_mail);
        // $stmt->execute([$user['name']]);
        // $reservation_for_send_mail = $stmt->fetch(PDO::FETCH_ASSOC);

        //メール送信処理
        mb_language('Japanese');
        mb_internal_encoding('UTF-8');
        $email = 'test@example.com';
        $to = 'kazyuapple99@gmail.com';
        $subject = '送信テストcicacu予約完了';
        //本文ここから
        $body = 'これはテストです。\n予約が完了しました！\r\n
                        ※このメールはシステムからの自動返信です\r\n
                        お世話になっております。\r\n
                        ご予約ありがとうございました。\r\n
                        以下の内容で予約を受け付けいたしました。\r\n
                        ━━━━━━□■□　ご予約内容　□■□━━━━━━\r\n
                        予約内容\n・宿泊日：' . $_POST['reservation_date'] . '\r\n
                        宿泊人数：' . $_POST['reservation_number'] . '\r\n
                        ━━━━━━━━━━━━━━━━━━━━━━━━━━━━\r\n
                        ーーーーーーーーーーーーーーーーーーーーーーーーーーーー\r\n
                        CICACU\r\n
                        担当：辻井\r\n
                        TEL：080-1411-4095\r\n
                        メール：info@cicacu.jp\r\n
                        アクセス：〒322-0067 栃木県鹿沼市天神町1704\r\n
                        【電車】東武日光線「新鹿沼駅」より徒歩15分\r\n
                        JR日光線「鹿沼駅」より徒歩20分\r\n
                        【駐車場】「cafe饗茶庵」専用駐車場をご利用ください\r\n
                        ーーーーーーーーーーーーーーーーーーーーーーーーーーーー\r\n
                        '; // 本文ここまで
        $header = 'From:' . $email . '\nReply-To: ' . $email . '\r\n';
        $from = 'r.kanou@ebacorp.jp';
        $pfrom   = '-f $from';

        mb_send_mail($to, $subject, $body, $header, $pfrom);

    } catch (Exception $e) {
        $error = 'エラーが発生しました。※予約が完了できませんでした。<br>CICACU予約担当辻井迄ご連絡ください。080-1411-4095(辻井) info@cicacu.jp';
    }
}
?>
<?php include 'doctype_header_user.php'?>
<title>CICACU | 予約完了画面</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.0/css/bootstrap-reboot.min.css">
<link rel="stylesheet" href="css/reservation.css">
</head>
<body>
    <div class="wrapper">
        <p class="top-p"><?=h($_SESSION['user']['name']);?>さん、ご機嫌いかがですか？</p>
        <h1>予約完了です。確認メールを送付しましたのでご確認ください。</h1>
        <?php if (isset($error)) :?>
            <p class="error"><?=$error?></p>
        <?php endif ;?>
        <div class="reservation-container">
            <input class="submit-button" type="button" value="トップへ戻る" onclick="location.href='./index.php'">
        </div>
        <footer class="reservation-footer">
            <p><small>2021 ebacorp.inc</small></p>
        </footer>
    </div>
</body>
</html>