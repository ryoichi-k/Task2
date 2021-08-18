<?php
require_once (dirname(__FILE__).'/ExternalFiles/Model/Model.php');
require_once (dirname(__FILE__).'/ExternalFiles/Model/Room.php');
require_once (dirname(__FILE__).'/ExternalFiles/util.php');

$room = new Room();

try {
    $rooms = $room->roomSelect();
} catch (Exception $e) {
    $error = 'エラーが発生しました。<br>CICACU辻井迄ご連絡ください。080-1411-4095(辻井) info@cicacu.jp';
    $rooms = [];
}
try {
    $room_details = $room->room_detailSelect();
} catch (Exception $e) {
    $error = 'エラーが発生しました。<br>CICACU辻井迄ご連絡ください。080-1411-4095(辻井) info@cicacu.jp';
    $room_details = [];
}

?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>CICACU</title>
    <meta name="description" content="CICACU(シカク)">
    <meta name="keywords" content="CICACU,cafe饗茶庵,鹿沼,ゲストハウス,民宿">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!--スマホ用に見れるように-->
    <meta name="robots" content="noindex,nofollow,noarchive">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/sp.css">
    <link rel="stylesheet" href="./css/flexslider.css">
    <link rel="stylesheet" href="./css/drawer.css">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <!--[if lt IE 9]>
<script src="./js/html5shiv.min.js"></script>
<![endif]-->
    <!--[if lt IE 9]>
  <script src="./js/respond.js"></script>
<![endif]-->
    <!--ドロワーメニュー-->
    <script src="./js/drawer.js"></script>
    <script src="./js/iscroll.js"></script>
    <!--script>
$(function() {
  $('.navToggle').click(function() {
		  $(this).toggleClass('active');
		  if ($(this).hasClass('active')) {
			  $('.globalMenuSp').addClass('active');
		  } else {
			  $('.globalMenuSp').removeClass('active');
      }
      $(document).ready(function() {
       $('#nav-content').drawer();
       $('.menutext li').on('click', function() {
           $('#nav-drawer').drawer('close');
         });
       });
     });
   });
</script-->
    <script>
        $(document).ready(function() {
            $(".drawer").drawer();
            $('.drawer-nav li').on('click', function() {
                $('.drawer').drawer('close');
            });
        });
    </script>
    <!--ドロワーメニュー-->
    <!--ギャラリー-->
    <script src="./js/jquery.flexslider.js"></script>
    <script>
        $(window).load(function() {
            $('.flexslider').flexslider({
                slideshowSpeed: 6000,
                animation: "slide",
            });
        });
    </script>
    <!--ギャラリー-->
    <!--google map-->
    <script src="./js/map.js"></script>
    <script src="http://maps.google.com/maps/api/js?key=AIzaSyCussjCXGkGz7sLCUowj36i4IuawxxMe6w"></script>
    <!--google map-->
    <!--ページTOPボタン-->
    <script>
        //■page topボタン
        $(function() {
            var topBtn = $('#pageTop');
            topBtn.hide();
            //◇ボタンの表示設定
            $(window).scroll(function() {
                if ($(this).scrollTop() > 80) {
                    //---- 画面を80pxスクロールしたら、ボタンを表示する
                    topBtn.fadeIn();
                } else {
                    //---- 画面が80pxより上なら、ボタンを表示しない
                    topBtn.fadeOut();
                }
            });
            // ◇ボタンをクリックしたら、スクロールして上に戻る
            topBtn.click(function() {
                $('body,html').animate({
                    scrollTop: 0
                }, 500);
                return false;
            });
        });
    </script>
</head>
<body class="drawer drawer--left">
    <!--サイドメニュー-->
    <aside>
        <div class="menu_sp">
            <button type="button" class="drawer-toggle drawer-hamburger">
                <span class="sr-only"></span>
                <span class="drawer-hamburger-icon"></span>
            </button>
            <div class="drawer-nav">
                <ul class="drawer-menu menutext">
                    <li><img src="./img/logo-s.png"></li>
                    <li><a href="#">CICACU</a></li>
                    <li><a href="#about">栃木県鹿沼市</a></li>
                    <li><a href="#history">成り立ち</a></li>
                    <li><a href="#name">由来</a></li>
                    <li><a href="#lodging">宿泊</a></li>
                    <li><a href="reservation_edit.php">ご予約</a></li>
                    <li><a href="#gallery">ギャラリー</a></li>
                    <li><a href="#access">アクセス</a></li>
                    <?php if (isset($_SESSION['user'])) :?>
                        <li>[<?=h($_SESSION['user']['name']);?>]さん</li>
                        <li><a href="logout.php">ログアウト</a></li>
                    <?php else : ?>
                        <li><a href="login.php">ログイン</a></li>
                    <?php endif; ?>
                    <?php if (isset($error)) :?>
                        <p class="error"><?=$error?></p>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <div class="menu_pc">
            <div id="nav-content">
                <ul class="menutext">
                    <li><img src="./img/logo-s.png"></li>
                    <li><a href="#">CICACU</a></li>
                    <li><a href="#about">栃木県鹿沼市</a></li>
                    <li><a href="#history">成り立ち</a></li>
                    <li><a href="#name">由来</a></li>
                    <li><a href="#lodging">宿泊</a></li>
                    <li><a href="reservation_edit.php">ご予約</a></li>
                    <li><a href="#gallery">ギャラリー</a></li>
                    <li><a href="#access">アクセス</a></li>
                    <?php if (isset($_SESSION['user'])) :?>
                        <li>[<?=h($_SESSION['user']['name']);?>]さん</li>
                        <li><a href="logout.php">ログアウト</a></li>
                    <?php else :?>
                        <li><a href="login.php">ログイン</a></li>
                    <?php endif ;?>
                    <?php if (isset($error)) :?>
                        <p class="error"><?=$error?></p>
                    <?php endif ;?>
                </ul>
            </div>
        </div>
    </aside>
    <!--ヘッダー-->
    <header>
        <div id="header-textbox">
            <div id="top-title">
                <h1>CICACU</h1>
            </div>
            <div id="top-text">
                <p>CICACU（シカク）は<br>
                    日光例幣使街道沿い、<br>
                    街の変遷と共に<br>
                    ひっそりと閉館した<br>
                    江戸時代創業の旅館を<br>
                    鹿沼の魅力を伝えるため<br>
                    再生させたゲストハウスです。<br>
                </p>
            </div>
        </div>
    </header>
    <!--メイン-->
    <main>
        <!--栃木県鹿沼市-->
        <div id="about">
            <div id="about-title">
                <h2>栃木県鹿沼市</h2>
            </div>
            <div id="about-text">
                <p>栃木県鹿沼市は、江戸時代、<br>
                    日光東照宮への勅使（日光例幣使）が通った<br>
                    日光例幣使街道、18番目の宿場町「鹿沼宿」です。<br>
                    鹿沼は日光東照宮造営の折、<br>
                    腕利きの職人たちが集結した伝統ある木工の町です。<br>
                    日光東照宮を代表とする社寺の建築技術から発展した<br>
                    鹿沼組子」や「日光彫」などの<br>
                    伝統工芸品が根付く歴史ある宿場町です。</p>
            </div>
        </div>
        <!--成り立ち-->
        <div id="history">
            <div id="history-title">
                <h2>成り立ち</h2>
            </div>
            <div id="history-text">
                <p>鹿沼にはかつて「中野屋」という<br>
                    江戸創業の老舗旅館がありましたが<br>
                    時の流れの中で旅館は廃業。<br>
                    それを惜しんだ人たちが、<br>
                    「日光例幣使街道・<br>
                    　鹿沼宿 旅館再生プロジェクト」を立ち上げ<br>
                    長い眠りについていた「中野屋」は新しい命を吹き込まれ<br>
                    ゲストハウス「CICACU（シカク）」として再生。<br>
                    鹿沼の＜鹿＞をマークに<br>
                    築100年の老舗旅館が瀟洒なゲストハウスとして<br>
                    新たな歴史を刻み始めました。</p>
            </div>
        </div>
        <!--由来-->
        <div id="name">
            <div id="name-text">
                <p>「CICACU（シカク）」の名の由来は<br>
                    “CIはCivic” で鹿沼の人々、<br>
                    “CAはCabin” でゲストハウスに泊まる旅行者、<br>
                    “CUはCulture・Curation” で文化・共有や編集を意味します。<br>
                    中野屋の歴史と伝統・文化を引き継ぐ「CICAKU」は<br>
                    まさにこの名のように、この地で鹿沼の人たちと旅人を繋ぎ、<br>
                    鹿沼の文化や様々な思いを共有し<br>
                    交流する場所として其処にあります。</p>
            </div>
        </div>
        <!--宿泊-->
        <div id="lodging">
            <div id="lodging-title">
                <h2>宿泊について</h2>
            </div>
            <div id="lodging-text01">
                <p>チェックイン 16：00～23：00まで<br>
                    チェックアウト　10：00まで<br>
                    チェックイン前、後にお荷物をお預かりすることができます。<br>
                    ご到着の時間をご連絡ください。<br>
                    深夜の外出、入室は近隣の方々のご迷惑にならないようお願いします。</p>
            </div>
            <?php foreach ($rooms as $value) :?>
                <div class="roomA">
                    <?php if ($value['img']) :?>
                        <img src="./images/<?=h($value['img'])?>" width="563.750" height="369.812">
                    <?php else :?>
                        <img src="./images/noimage.png" width="563.750" height="369.812" alt="">
                    <?php endif ;?>
                    <div class="roomA-text">
                        <h3><?=h($value['name'])?></h3>
                        <p>
                            <?php foreach ($room_details[$value['id']] as $v) :?>
                                <?=$v['capacity']?>名様<?=$v['remarks']?>：￥<?=number_format($v['price'])?><?=$v['capacity'] > 1 ? '（1名様' . number_format(floor($v['price'] / $v['capacity'])) . '円）' : '' ?><br>
                            <?php endforeach ;?>
                        </p>
                    </div>
                </div>
            <?php endforeach ;?>
            <div id="lodging-text02">
                <p>全室和室となっております。<br>
                    １部屋につき最大４名様まで宿泊が可能です。<br>
                    全室禁煙です。喫煙される方はキッチンをご利用ください。<br>
                    お食事の提供はございません。近隣の飲食店をご案内します。<br>
                    お支払いは現金のみ、チェックイン時の前払いとなります。<br>
                    あらかじめご了承ください。</p>
            </div>
        </div>
        <!--ご予約-->
        <div id="reservation">
            <div id="reservation-title">
                <h2>ご予約</h2>
            </div>
            <div id="reservation-text">
                <p>ご予約はメールよりお願いいたします。<br>
                    ご予約は３ヶ月先まで承っております。<br>
                    宿泊予定の２日前からキャンセル料が発生します。 <br><br>
                    ２日前、前日のキャンセル：50％<br>
                    当日のキャンセルまたは、不泊の場合：100％　頂戴いたします。</p>
            </div>
            <div id="reservation-mail">
                <a href=""><img src="./img/mail.png"></a>
            </div>
        </div>
        <!--ギャラリー-->
        <div id="gallery">
            <div id="gallery-title">
                <h2>ギャラリー</h2>
            </div>
            <div id="gallery-text">
                <p>「CICACU」の館内はとても広く、プチ浪漫散歩も楽しめます。<br>
                    その時はぜひ、足元や天井、壁にも注目してください。<br>
                    思わず唸る美しい細工の数々を楽しめます。<br>
                    日光東照宮造営の時にここ鹿沼に移り住んだ名工たちが生みだし、<br>
                    その子孫や弟子たちが伝えてきた鹿沼の文化と魂が、この「CICACU」には宿っています。</p>
            </div>
            <!--スライドショー-->
            <div class="flexslider">
                <ul class="slides">
                    <li><img src="./img/gallery/cicacu.jpg"></li>
                    <li><img src="./img/gallery/Reputation.jpg"></li>
                    <li><img src="./img/gallery/Entrance.jpg"></li>
                    <li><img src="./img/gallery/Dirt-floor.jpg"></li>
                    <li><img src="./img/gallery/Living-room1.jpg"></li>
                    <li><img src="./img/gallery/Living-room2.jpg"></li>
                    <li><img src="./img/gallery/Living-room3.jpg"></li>
                    <li><img src="./img/gallery/Corridor1.jpg"></li>
                    <li><img src="./img/gallery/Corridor2.jpg"></li>
                    <li><img src="./img/gallery/Washroom.jpg"></li>
                    <li><img src="./img/gallery/stairs1.jpg"></li>
                    <li><img src="./img/gallery/stairs2.jpg"></li>
                </ul>
            </div>
        </div>
        <!--アクセス-->
        <div id="access">
            <div id="access-title">
                <h2>アクセス</h2>
            </div>
            <div id="access-text01">
                <p>〒322-0067 栃木県鹿沼市天神町1704<br><br>
                    【電車】東武日光線「新鹿沼駅」より徒歩15分<br>
                    　　　　JR日光線「鹿沼駅」より徒歩20分<br>
                    【駐車場】「cafe饗茶庵」専用駐車場をご利用ください。</p>
            </div>
            <!--google map-->
            <div id="g_map"></div>
            <!--google map-->
            <div id="access-text02">
                <p>その他、お問い合わせはこちらまで<br>
                    &#9742;　080-1411-4095（辻井）　／　
                    &#9993;　info@cicacu.jp
                </p>
            </div>
        </div>
    </main>
    <!--フッター-->
    <footer>
        <div id="logo">
            <img src="./img/logo.png">
        </div>
        <small>
            <p>Copyright &copy; CICACU All rights reserved.</p>
        </small>
    </footer>
    <!--TOPに戻るボタン-->
    <div id="pageTop">
        <a href="#">&#9650;<br>TOP</a>
    </div>
    <!--TOPに戻るボタン-->
</body>
</html>