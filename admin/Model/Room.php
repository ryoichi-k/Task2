<?php
class Room extends Model
{
    public function roomSelect()
    {
        try {
            $this->connect();
            $sql = 'SELECT * FROM room WHERE delete_flg = 0';
            $stmt = $this->dbh->query($sql);
            $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $rooms;
        } catch (Exception $e) {
            return 'エラーが発生しました。<br>CICACU辻井迄ご連絡ください。080-1411-4095(辻井) info@cicacu.jp';
        }
    }

    public function room_detailSelect()
    {
        try {
            $this->connect();
            $sql = 'SELECT room_id, room_detail.* FROM room_detail WHERE delete_flg = 0';
            $stmt = $this->dbh->query($sql);
            $room_details = $stmt->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_GROUP);
            return $room_details;
        } catch (Exception $e) {
            return 'エラーが発生しました。<br>CICACU辻井迄ご連絡ください。080-1411-4095(辻井) info@cicacu.jp';
        }
    }

    //リスト表示降順
    public function roomSelectList()
    {
        try {
            $this->connect();
            $sql = 'SELECT * FROM room WHERE delete_flg = 0 ORDER BY created_at DESC';
            $stmt = $this->dbh->query($sql);
            $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $rooms;
        } catch (Exception $e) {
            return 'エラーが発生しました。<br>CICACU辻井迄ご連絡ください。080-1411-4095(辻井) info@cicacu.jp';
        }
    }

    //客室新規登録
    public function roomRegister($name)
    {
        try {
            $this->connect();
            $date = new DateTime();
            $date->setTimeZone(new DateTimeZone('Asia/Tokyo'));
            $time = $date->format('Y-m-d H:i:s');
            $sql_room = 'INSERT INTO room(name, created_at) VALUES(?, ?)';
            $stmt = $this->dbh->prepare($sql_room);
            $stmt->execute([$name, $time]);
            $sql_room = 'SELECT * FROM room WHERE name = ? ';
            $stmt = $this->dbh->prepare($sql_room);
            $stmt->execute([$name]);
            $room = $stmt->fetch(PDO::FETCH_ASSOC);
            $room_id = $room['id'];
            foreach ($_POST['detail'] as $value) {
                $sql_room_detail = 'INSERT INTO room_detail(room_id, capacity, remarks, price) VALUES(?, ?, ?, ?)';
                $stmt = $this->dbh->prepare($sql_room_detail);
                $stmt->execute([$room_id, $value['capacity'], $value['remarks'], $value['price']]);
            }
            $isSended = 1;
            return $isSended;
            unset($value);
        } catch (Exception $e) {
            return 'エラーが発生しました。<br>CICACU辻井迄ご連絡ください。080-1411-4095(辻井) info@cicacu.jp';
        }
    }

    //客室編集
    public function roomEdit($name, $id, $updated_at)
    {
        try {
            $this->connect();
            $sql_room = 'SELECT * FROM room_detail WHERE id = ? ';
            $stmt = $this->dbh->prepare($sql_room);
            $stmt->execute([$id]);
            $room_edit_done = $stmt->fetch(PDO::FETCH_ASSOC);
            $room_id = $room_edit_done['room_id'];
            $sql_room_edit_done = 'UPDATE room
                                                . SET name = ?,
                                                . updated_at = ?
                                                . WHERE id = ? ';
            $stmt = $this->dbh->prepare($sql_room_edit_done);
            $stmt->execute([$name, $updated_at, $room_id]);
            $sql_room_detail_delete_flg_to_one = 'UPDATE room_detail
                                                                    . SET delete_flg = 1
                                                                    . WHERE room_id = ? ';
            $stmt = $this->dbh->prepare($sql_room_detail_delete_flg_to_one);
            $stmt->execute([$room_id]);
            foreach ($_POST['detail'] as $value) {
                $sql_room_detail_edit_done = 'INSERT INTO room_detail(room_id, capacity, price, remarks) VALUES (?, ?, ?, ?)';
                $stmt = $this->dbh->prepare($sql_room_detail_edit_done);
                $stmt->execute([$room_id, $value['capacity'], $value['price'], $value['remarks']]);
            }
            $isEdited = 1;
            return $isEdited;
        } catch (Exception $e) {
            return 'エラーが発生しました。<br>CICACU辻井迄ご連絡ください。080-1411-4095(辻井) info@cicacu.jp';
        }
    }

    //客室論理削除
    public function roomDelete($id)
    {
        try {
            $this->connect();
            $sql_room_delete_flg = 'UPDATE room
                                . SET delete_flg = 1
                                . WHERE id = ? ';
            $stmt = $this->dbh->prepare($sql_room_delete_flg);
            $stmt->execute([$id]);
            $sql_room_delete_flg = 'UPDATE room_detail
                                . SET delete_flg = 1
                                . WHERE room_id = ? ';
            $stmt = $this->dbh->prepare($sql_room_delete_flg);
            $stmt->execute([$id]);
            header('Location: room_list.php');
            exit;
        } catch (Exception $e) {
            return 'エラーが発生しました。<br>CICACU辻井迄ご連絡ください。080-1411-4095(辻井) info@cicacu.jp';
        }
    }

    //ソート機能
    public function roomSort($sorted_item, $asc_or_desc)
    {
        if (($sorted_item == 'name' || $sorted_item == 'updated_at') && $asc_or_desc == 'asc') {
            $sql = "SELECT * FROM room WHERE delete_flg = 0 ORDER BY" . ' ' . $sorted_item . ' ' . "IS NULL ASC," . $sorted_item . ' ' . "ASC";
            $stmt = $this->dbh->query($sql);
            $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $rooms;
        } else {
            $sql = "SELECT * FROM room WHERE delete_flg = 0 ORDER BY" . ' ' . $sorted_item . ' ' . $asc_or_desc . '';
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute();
            $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $rooms;
        }
    }
}
