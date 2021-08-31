<?php
session_start();
require_once ('UserModel.php');
require_once ('User_UserAuth.php');
require_once ('admin/util.php');

?>
<?php include 'doctype_header_user.php'?>
    <title>練習画面</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.0/css/bootstrap-reboot.min.css">
    <link rel="stylesheet" href="css/userstyle.css">
</head>
<body>
    <div class="wrapper">
        <main>
            <h1 class="login-h1">js練習画面</h1>
            <div class="login-container">
                <script>
                    let tei = window.prompt('三角形の底辺は？');
                    let taka = window.prompt('三角形の高さは？');
                    let area = tei * taka / 2;
                    document.write('三角形の面積は' + area + 'です。');
                </script>
            </div>
        </main>
        <footer class="gl-footer">
            <p><small>2021 ebacorp.inc</small></p>
        </footer>
    </div>
</body>
</html>