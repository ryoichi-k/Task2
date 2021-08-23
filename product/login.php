<?php
session_start();

require_once (dirname(__FILE__).'/ExternalFiles/Model/UserModel.php');
require_once (dirname(__FILE__).'/ExternalFiles/Model/User_UserAuth.php');
require_once (dirname(__FILE__).'/ExternalFiles/util.php');


if (isset($_SESSION['user'])) {
    unset($_SESSION['user']);
}
if (!empty($_POST['btn-auth'])) {
    if ($_POST['id'] === '' || $_POST['pass'] === '') {
        $error = 'IDかパスワードが入力されていません';
    }else{
        $user_userAuth = new User_UserAuth();
        $error = $user_userAuth->auth($_POST['id'], $_POST['pass']);
    }
}
?>
<?php require_once (dirname(__FILE__).'/header_user.php');?>
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
                        <?php if (isset($error)) :?>
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
        <?php require_once (dirname(__FILE__).'/admin/footer.php');?>