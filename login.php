<?php
    session_start();
    require_once ('UserModel.php');
    require_once ('admin/util.php');

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CICACU | ログイン</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.0/css/bootstrap-reboot.min.css">
    <link rel="stylesheet" href="css/userstyle.css">
</head>
<body>
    <div class="wrapper">
        <main>
            <h1 class="login-h1">CICACUログイン画面</h1>
            <div class="login-container">
                <form action="" method="post">
                    <table class="login-table">
                        <?php if (isset($error)):?>
                            <p class="error"><?=$error?></p>
                        <?php endif; ?>
                        <tr>
                            <th>ログインID：</th>
                            <td><input type="text" name="id" value="<?=!empty($_POST['id']) ? h($_POST['id']) : ''?>" id="login-id" class="form-control" autofocus></td>
                        </tr>
                        <tr>
                            <th>パスワード：</th>
                            <td><input type="password" name="pass" value="" id="login-pass" class="form-control" required></td>
                        </tr>
                    </table>
                    <input type="submit" name="btn-auth" value="認証" class="login-button">
                </form>
            </div>
        </main>
        <footer class="gl-footer">
            <p><small>2021 ebacorp.inc</small></p>
        </footer>
    </div>
</body>
</html>