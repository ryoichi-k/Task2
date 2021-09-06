<?php
session_start();
// require_once(dirname(__FILE__) . '/../ExternalFiles/util.inc.php');
// require_once(dirname(__FILE__) . '/../ExternalFiles/Model/Model.php');
// require_once(dirname(__FILE__) . '/../ExternalFiles/Model/Room.php');
// require_once(dirname(__FILE__) . '/../ExternalFiles/util.php');
require_once ('Model/Model.php');
require_once ('Model/Room.php');
require_once ('util.php');
require_once ('util.inc.php');

$room = new Room();

if ($_GET['type'] == 'new') {
    $error = $room->registerRoom($_POST['name'], $_POST['detail']);
} elseif ($_GET['type'] == 'edit') {
    $error = $room->editRoom($_POST['name'], $_POST['detail']);
}
?>
<?php require_once('header.php')?>
<main>
    <div class="room_done-container">
        <div class="getPage"><?php getPage() ;?></div>
        <?php if (isset($error)) :?>
            <h3 class="error"><?=$error?></h3>
        <?php elseif(empty($error)) :?>
            <h3 class="done-message"><?=OPERATION_ARRAY[$_GET['type']]?>完了しました。</h3>
        <?php endif ;?>
    </div>
</main>
<?php require_once('footer.php')?>