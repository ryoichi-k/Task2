<?php
function h(?string $string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
function getPage()
{
    $itemarray = array(
        "room" => "客室管理"
    );
    $orerationarray = array(
        "new" => "登録",
        "edit" => "編集",
    );
    $pagearray = array(
        "list" => "リスト",
        "conf" => "確認",
        "done" => "完了",
        "edit" => "",
    );
    $curUrl = $_SERVER['REQUEST_URI'];//現在のページのURL取得
    $url = explode("/" , $curUrl);
    $str = str_replace('.php', '', $url[3]);
    $url2 = explode("_" , $str);
    $url3 = explode("?" , $url2[1]);

    if (isset($_GET['type'])) {
        $button = "<button>" . $itemarray[$url2[0]] . $orerationarray[$_GET['type']] . $pagearray[$url3[0]] . "</button>";
    } else {
        $button = "<button>" . $itemarray[$url2[0]] . $pagearray[$url3[0]] . "</button>";
    }
    echo ($button);
}