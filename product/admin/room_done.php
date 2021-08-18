<?php
session_start();
require_once (dirname(__FILE__).'/../ExternalFiles/util.inc.php');
require_once (dirname(__FILE__).'/../ExternalFiles/Model/Model.php');
require_once (dirname(__FILE__).'/../ExternalFiles/Model/Room.php');
require_once (dirname(__FILE__).'/../ExternalFiles/util.php');
$isSended = null;
$isEdited = null;
//新規登録
if (!empty($_POST['send'])) {
    $room = new Room();
    $room->roomRegister($_POST['name']);
    $isSended = 1;
}
//編集
if (!empty($_POST['send-edit'])) {
    $date = new DateTime();
    $date->setTimeZone( new DateTimeZone('Asia/Tokyo'));
    $today = $date->format('Y-m-d H:i:s');
    $updated_at = $today;
    $room = new Room();
    $error = $room->roomEdit($_POST['name'], $_POST['detail'][0]['id'], $updated_at);
    $isEdited = 1;
}
?>
<?php require_once('header.php')?>
        <main>
        <div class="room_done-container">
        <div class="getPage"><?php getPage() ;?></div>
        <?php if (isset($error)):?>
            <h3 class="error"><?=$error?></h3>
        <?php elseif(isset($isEdited)) :?>
            <h3 class="done-message">編集完了しました。</h3>
        <?php elseif(isset($isSended)) :?>
            <h3 class="done-message">登録完了しました。</h3>
        <?php endif;?>
        </div>
        </main>
        <footer class="done-footer">
            <p><small>2021 ebacorp.inc</small></p>
        </footer>
    </div>
</body>
</html>