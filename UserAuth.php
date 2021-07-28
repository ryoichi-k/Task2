<?php
class UserAuth {

    public function auth($id, $pass){
        if ($id === '' || $pass === '') {
            $error = 'IDかパスワードが入力されていません';
        } else {
            try {
                $model = new Model();
                $model->connect();
                $sql = 'SELECT * FROM admin_user WHERE login_id = ? AND delete_flg = ?';
                $stmt = $model->dbh->prepare($sql);
                $stmt->execute([$id, 0]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($result && password_verify($pass, $result['login_pass'])) {
                    session_regenerate_id();
                    $_SESSION['admin']['authenticated'] = 1;
                    $_SESSION['admin']['login_id']      = $result['login_id'];
                    $_SESSION['admin']['name']          = $result['name'];
                    header('Location: top.php');
                    exit('処理が中断されました');
                    die('エラーが発生したので終了します');
                } else {
                    $error = 'IDかパスワードが間違っています';
                }

            } catch (PDOException $e) {
                header('Content-Type: text/plain; charset=UTF-8', true, 500);
                echo 'アクセスに失敗しました。しばらくたってから再度アクセスしてください。' . $e->getMessage();
                exit;
            }
        }return $error;
    }





}