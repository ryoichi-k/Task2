<?php
class UserAuth extends Model
{
    public function auth($id, $pass)
    {
        try {
            $this->connect();
            $sql = 'SELECT * FROM admin_user WHERE login_id = ? AND delete_flg = 0';
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result && password_verify($pass, $result['login_pass'])) {
                session_regenerate_id();
                $_SESSION['admin']['authenticated'] = 1;
                $_SESSION['admin']['id'] = $result['id'];
                $_SESSION['admin']['name'] = $result['name'];
                header('Location: top.php');
                exit;
            }
            return 'IDかパスワードが間違っています';
        } catch (Exception $e) {
            return 'エラーが発生しました。<br>CICACU辻井迄ご連絡ください。080-1411-4095(辻井) info@cicacu.jp';
        }
    }
}
