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
    $cur_url_p = $_SERVER['REQUEST_URI'];//現在のページのURL取得

    $url_p = explode("/" , $cur_url_p);
    $str = str_replace('.php', '', $url_p[4]);
    $url_p2 = explode("_" , $str);
    $url_p3 = explode("?" , $url_p2[1]);

    if (isset($_GET['type'])) {
        $button = "<button>" . $itemarray[$url_p2[0]] . $orerationarray[$_GET['type']] . $pagearray[$url_p3[0]] . "</button>";
    } else {
        $button = "<button>" . $itemarray[$url_p2[0]] . $pagearray[$url_p3[0]] . "</button>";
    }
    echo ($button);
}