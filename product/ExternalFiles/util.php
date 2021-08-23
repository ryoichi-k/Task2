<?php
function h(?string $string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
function getPage()
{
    $item_array = array(
        'room' => '客室管理'
    );
    $oreration_array = array(
        'new' => '登録',
        'edit' => '編集',
    );
    $page_array = array(
        'list' => 'リスト',
        'conf' => '確認',
        'done' => '完了',
        'edit' => '',
    );
    $curUrl = $_SERVER['SCRIPT_NAME'];
    $basename = basename($curUrl, '.php');
    $removed_underbar = explode('_' , $basename);

    echo '<button>' . $item_array[$removed_underbar[0]] . (isset($_GET['type']) ? $oreration_array[$_GET['type']] : '' ). $page_array[$removed_underbar[1]] . '</button>';
}
