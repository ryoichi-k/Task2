<?php
function getPage(){
    $btnName = '初期値';
    $itemName = '';
    $operation = '';
    $confOrDone = '';
    $curUrl = $_SERVER['REQUEST_URI'];//現在のページのURL取得
    //$itemName
    if (mb_ereg_match(".*(room)+\.*", $curUrl)) {
        $itemName = "客室管理";
    }
    //$operation
    if (mb_ereg_match(".*(_list)+\.*", $curUrl)) {
        $operation = "リスト";
    }elseif(mb_ereg_match(".*(type=edit)", $curUrl)){
        $operation = "編集";
    }else{
        $operation = "登録";
    }
    //$confOrDone
    if (mb_ereg_match(".*(conf).*", $curUrl)) {
        $confOrDone = "確認";
    }elseif(mb_ereg_match(".*(done)+\.*", $curUrl)){
        $confOrDone = "完了";
    }
    $button = "<button onclick=\"location.href='#'\">" . $itemName . $operation . $confOrDone . "</button>";
    echo ($button);
}