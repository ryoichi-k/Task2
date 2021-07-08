<?php
//id sort desc
if (!empty($_POST['id-desc'])) {
    $id_array = array();
    foreach( $rooms as $value) {
        $id_array[] = $value['id'];
    }
        array_multisort(
                $id_array,
                SORT_DESC,
                SORT_NUMERIC,
                $rooms,
                );
    echo '<pre>';
    var_dump($rooms);
    echo '</pre>';
}
//id sort asc
if (!empty($_POST['id-asc'])) {
    $id_array = array();
    foreach( $rooms as $value) {
        $id_array[] = $value['id'];
    }
        array_multisort(
                $id_array,
                SORT_ASC,
                SORT_NUMERIC,
                $rooms,
                );
    echo '<pre>';
    var_dump($rooms);
    echo '</pre>';
}
//name sort desc
if (!empty($_POST['name-desc'])) {
    $id_array = array();
    $name_array = array();
    foreach($rooms as $value) {
        $id_array[] = $value['id'];
        $name_array[] = $value['name'];
    }
    array_multisort(
        $name_array,
        SORT_DESC,
        SORT_STRING,
        $id_array,
        SORT_DESC,
        SORT_NUMERIC,
        $rooms,
        );
}
//name sort asc
if (!empty($_POST['name-asc'])) {
    $id_array = array();
    $name_array = array();
    foreach($rooms as $value) {
        $id_array[] = $value['id'];
        $name_array[] = $value['name'];
    }
    array_multisort(
        $name_array,
        SORT_ASC,
        SORT_STRING,
        $id_array,
        SORT_DESC,
        SORT_NUMERIC,
        $rooms,
        );
}
//updated_at sort desc
if (!empty($_POST['updated_at-desc'])) {
    $id_array = array();
    $updated_at_array = array();
    foreach($rooms as $value) {
        $id_array[] = $value['id'];
        $updated_at_array[] = $value['updated_at'];
    }
    array_multisort(
        $updated_at_array,
        SORT_DESC,
        SORT_NUMERIC,
        $id_array,
        SORT_DESC,
        SORT_NUMERIC,
        $rooms,
        );
}
//updated_at sort asc
if (!empty($_POST['updated_at-asc'])) {
    $id_array = array();
    $updated_at_array = array();
    foreach($rooms as $value) {
        $id_array[] = $value['id'];
        $updated_at_array[] = $value['updated_at'];
    }
    array_multisort(
        $updated_at_array,
        SORT_ASC,
        SORT_NUMERIC,
        $id_array,
        SORT_DESC,
        SORT_NUMERIC,
        $rooms,
        );
}
