<?php
class User_UserAuth extends UserModel
{
    public function auth($id, $pass)
    {
        try {
            $this->connect();
            $sql = 'SELECT * FROM user WHERE login_id = ? AND delete_flg = 0';
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            echo '<pre>';
            print_r($result);
            echo '</pre>';
            if ($result && password_verify($pass, $result['login_pass'])) {
                echo 'in';
                session_regenerate_id();
                $_SESSION['user']['authenticated'] = 1;
                $_SESSION['user']['id'] = $result['id'];
                $_SESSION['user']['name'] = $result['name'];
                header('Location: reservation_edit.php');
                exit;
            }
            echo 'out';
            return 'あなたのIDかパスワードが間違っています';
        } catch (Exception $e) {
            return 'エラーが発生しました。<br>CICACU辻井迄ご連絡ください。080-1411-4095(辻井) info@cicacu.jp';
        }
    }
}