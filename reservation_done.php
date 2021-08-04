<?php
session_start();
require_once ('UserModel.php');
require_once ('User_UserAuth.php');
require_once ('admin/util.php');
if (empty($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
if (!empty($_POST['send'])) {
    echo '<pre>';
    print_r($_POST);
    echo '</pre>';
    $user_id = $_SESSION['user']['id'];
    try {
        $model = new UserModel();
        $model->connect();

        //セッションデータからユーザー情報を抽出
        $sql_select_user = 'SELECT * FROM user WHERE id = ?';
        $stmt = $model->dbh->prepare($sql_select_user);
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        echo '<pre>';
        print_r($user);
        echo '</pre>';
        //reservation_post_paymentから支払い方法のidを取得
        $sql_select_m_payment = 'SELECT * FROM m_payment WHERE name = ?';
        $stmt = $model->dbh->prepare($sql_select_m_payment);
        $stmt->execute([$_POST['reservation_post_payment']]);
        $payment = $stmt->fetch(PDO::FETCH_ASSOC);

        //reservationテーブルに予約内容と、ユーザー情報を同時にinsert
        $sql_reservation = 'INSERT INTO reservation
                                    (room_detail_id, user_id, name,
                                    name_kana, mail, tel1, tel2, tel3,
                                    number, total_price, payment_id)
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $stmt = $model->dbh->prepare($sql_reservation);
        $stmt->execute([$_POST['reservation_room_detail_id'], $user['id'], $user['name'], $user['name_kana'], $user['mail'], $user['tel1'], $user['tel2'], $user['tel3'], $_POST['reservation_number'], $_POST['reservation_total_price'], $payment['id']]);

        //メール送付処理
        

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
    <title>CICACU | 予約完了画面</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.0/css/bootstrap-reboot.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
        <p class="top-p"><?=h($_SESSION['user']['name']);?>さん、ご機嫌いかがですか？</p>
        <h1>予約完了です。確認メールを送付しましたのでご確認ください。</h1>
        <input type="button" value="トップへ戻る" onclick="location.href='./index.php'">
    <footer>
        <p><small>2021 ebacorp.inc</small></p>
    </footer>
</body>
</html>