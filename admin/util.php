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
    
    $page_array = array(
        'list' => 'リスト',
        'conf' => '確認',
        'done' => '完了',
        'edit' => '',
    );

    $basename = basename($_SERVER['SCRIPT_NAME'], '.php');

    $removed_underbar = explode('_' , $basename);

    echo '<button>' . $item_array[$removed_underbar[0]] . (isset($_GET['type']) ? OPERATION_ARRAY[$_GET['type']] : '' ) . $page_array[end($removed_underbar)] . '</button>';
}