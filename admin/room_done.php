<?php
session_start();
require_once ('Model/Model.php');
require_once ('Model/Room.php');
require_once ('util.php');
require_once ('util.inc.php');
$isSended = null;
$isEdited = null;
//新規登録
if (!empty($_POST['send'])) {
    $room = new Room();
    $isSended = $room->roomRegister($_POST['name']);
}
//編集
if (!empty($_POST['send-edit'])) {
    $date = new DateTime();
    $date->setTimeZone( new DateTimeZone('Asia/Tokyo'));
    $today = $date->format('Y-m-d H:i:s');
    $updated_at = $today;
    $room = new Room();
    $isEdited = $room->roomEdit($_POST['name'], $_POST['detail'][0]['id'], $updated_at);
}
?>
<?php require_once('header.php')?>
        <main>
        <div class="room_done-container">
        <div class="getPage"><?php getPage() ;?></div>
        <?php if(isset($isEdited)) :?>
            <h3 class="done-message">編集完了しました。</h3>
        <?php endif;?>
        <?php if(isset($isSended)) :?>
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