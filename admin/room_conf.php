<?php
session_start();
require_once(dirname(__FILE__) . '/../ExternalFiles/util.inc.php');
require_once(dirname(__FILE__) . '/../ExternalFiles/Model/Model.php');
require_once(dirname(__FILE__) . '/../ExternalFiles/util.php');

?>
<?php require_once('header.php')?>
<main>
    <div class="room_conf-container">
        <div class="getPage"><?php getPage() ;?></div>
        <table class="room_edit-table">
            <?php if ($_GET['type'] == 'edit') :?>
                <tr>
                    <th>ID</th>
                    <td colspan="3"><?=h($_GET['id'])?></td>
                </tr>
            <?php endif ;?>
            <tr>
                <th>客室名<span>（必須）</span></th>
                <td><?=h($_POST['name'])?></td>
            </tr>
            <tr>
                <th>詳細</th>
                <td>
                    <?php for ($i = 0; $i < count($_POST['detail']); $i++) :?>
                        <p>
                            人数：<?=h($_POST['detail'][$i]['capacity'])?>人
                            価格：<?=h($_POST['detail'][$i]['price'])?>円（税込み）
                            追記：<?=h($_POST['detail'][$i]['remarks'])?>
                        </p>
                    <?php endfor ;?>
                </td>
            </tr>
        </table>
        <form action="room_done.php?type=<?=h($_GET['type'])?><?=isset($_GET['id']) ? '&id=' . h($_GET['id']) : ''?>" method="post">
            <input type="hidden" name="name" value="<?=h($_POST['name'])?>">
            <?php for ($i = 0; $i < count($_POST['detail']); $i++) :?>
                <input type="hidden" name="detail[<?=$i?>][capacity]" value="<?=h($_POST['detail'][$i]['capacity'])?>">
                <input type="hidden" name="detail[<?=$i?>][price]" value="<?=h($_POST['detail'][$i]['price'])?>">
                <input type="hidden" name="detail[<?=$i?>][remarks]" value="<?=h($_POST['detail'][$i]['remarks'])?>">
            <?php endfor ;?>
            <p>
                <input type="submit" value="修正" formaction="room_edit.php?type=<?=h($_GET['type'])?><?=isset($_GET['id']) ? '&id=' . h($_GET['id']) : ''?>" class="conf-cancel-btn">
                <input class="conf-submit" type="submit" value="<?=OPERATION_ARRAY[$_GET['type']]?>完了">
            </p>
        </form>
    </div>
</main>
<?php require_once('footer.php')?>