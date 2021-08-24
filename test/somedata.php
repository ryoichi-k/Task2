<?php
//reservation_conf.php

 // echo 'res<pre>';
    // print_r($reservation);
    // echo '</pre>';
    // echo 'post<pre>';
    // print_r($_POST);
    // echo '</pre>';
    // echo 'capa<pre>';
    // print_r($room_detail['capacity']);
    // echo '</pre>';
    // echo 'rd<pre>';
    // print_r($room_detail);
    // echo '</pre>';
    // echo 'sta<pre>';
    // print_r($reservation['status']);
    // echo '</pre>';
    //$torf = $reservation;
    //$reservations = new Reservation();
    //$reservations->reservationValidation($reservation, $_POST['number'], $room_detail['capacity'], $reservation['status'], $next_day, $three_month_lator);

// $model = new Model();
        // $model->connect();
        // $sql_select_room_detail = 'SELECT * FROM room_detail WHERE id = ?';
        // $stmt = $model->dbh->prepare($sql_select_room_detail);
        // $stmt->execute([$_POST['room_detail_id']]);
        // $room_detail = $stmt->fetch(PDO::FETCH_ASSOC);

        // $model = new Model();
        // $model->connect();
        // $sql_select_reservation = 'SELECT * FROM reservation WHERE room_detail_id = ?';
        // $stmt = $model->dbh->prepare($sql_select_reservation);
        // $stmt->execute([$_POST['room_detail_id']]);
        // $reservation = $stmt->fetch(PDO::FETCH_ASSOC);




    //     uti.php
    //     $curUrl = $_SERVER['SCRIPT_NAME'];
    // $basename = basename($curUrl, ".php");
    // $removed_underbar = explode('_' , $basename);

    //echo "<button>" . $item_array[$removed_underbar[0]] . (isset($_GET['type']) ? $operation_array[$_GET['type']] : '' ) . $page_array[$removed_underbar[1]] . "</button>";

//room_list

    // //初期表示
// try {
//     $rooms = $room->showRoomList();
// } catch (Exception $e) {
//     $error = 'エラーが発生しました。<br>CICACU辻井迄ご連絡ください。080-1411-4095(辻井) info@cicacu.jp';
//     $rooms = [];
// }


    // //リスト表示降順room_list.phpにて初期表示
    // public function showRoomList()
    // {
    //     try {
    //         $this->connect();
    //         $sql = 'SELECT * FROM room WHERE delete_flg = 0 ORDER BY created_at DESC';
    //         $stmt = $this->dbh->query($sql);
    //         return $stmt->fetchAll(PDO::FETCH_ASSOC);
    //     } catch (Exception $e) {
    //         throw new Exception();
    //     }
    // }

    // //ソート機能
    // public function sortRoom($sorted_item, $asc_or_desc)
    // {
    //     $sql = "SELECT * FROM room WHERE delete_flg = 0 ORDER BY" . ' ' . $sorted_item . ' ' . (($sorted_item == 'name' || $sorted_item == 'updated_at') && $asc_or_desc == 'asc' ? "IS NULL ASC," . $sorted_item . ' ' . "ASC" : $asc_or_desc . '');
    //     $stmt = $this->dbh->query($sql);
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }



    //    //一覧表示とソート機能
    //    public function sortRoom()
    //    {
    //        $this->connect();
    //        if (!empty($_GET['sort'])) {
    //            $sorted_item = $_GET['sort'];
    //            $asc_or_desc = $_GET['order'];
    //            $sql = "SELECT * FROM room WHERE delete_flg = 0 ORDER BY" . ' ' . $sorted_item . ' ' . (($sorted_item == 'name' || $sorted_item == 'updated_at') && $asc_or_desc == 'asc' ? "IS NULL ASC," . $sorted_item . ' ' . "ASC" : $asc_or_desc . '');
    //        } else {
    //            $sql = 'SELECT * FROM room WHERE delete_flg = 0 ORDER BY created_at DESC';
    //        }
    //            $stmt = $this->dbh->query($sql);
    //            return $stmt->fetchAll(PDO::FETCH_ASSOC);
    //    }


     // try {
    //     $model = new Model();
    //     $model->connect();
    //     $stmt = $model->dbh->prepare('SELECT * FROM room WHERE name LIKE :search_name AND delete_flg = 0');
    //     $stmt->bindValue(':search_name', '%' . addcslashes($_POST['search_name'], '\_%') . '%');
    //     $stmt->execute();
    //     $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // } catch (Exception $e) {
    //     $error = 'エラーが発生しました。<br>CICACU辻井迄ご連絡ください。080-1411-4095(辻井) info@cicacu.jp';
    // }