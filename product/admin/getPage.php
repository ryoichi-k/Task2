<?php
function getPage(){
    $btnName = 'テスト初期値';
    $curUrl = $_SERVER['REQUEST_URI'];//現在のページのURL取得
    //echo($curUrl);
    // echo gettype($curUrl)→string;
    if (mb_ereg_match(".*(room_list\.php)$", $curUrl)) {
        $btnName = "客室管理リスト";
        $button = "<button onclick=\"location.href='#'\">" . $btnName . "</button>";
        echo $button;
    }elseif(mb_ereg_match(".*(room_edit\.php)$", $curUrl)){
        $btnName = "客室管理編集";
        $button = "<button onclick=\"location.href='#'\">" . $btnName . "</button>";
        echo $button;
    }elseif(mb_ereg_match(".*(room_conf\.php)$", $curUrl)){
        $btnName = "客室登録確認";
        $button = "<button onclick=\"location.href='#'\">" . $btnName . "</button>";
        echo $button;
    }elseif(mb_ereg_match(".*(room_done\.php)$", $curUrl)){
        $btnName = "客室編集完了";
        $button = "<button onclick=\"location.href='#'\">" . $btnName . "</button>";
        echo $button;
    }
}