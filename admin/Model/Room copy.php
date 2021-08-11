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
        } catch (PDOException $e) {
            header('Content-Type: text/plain; charset=UTF-8', true, 500);
            echo '情報の読み込みに失敗しました。しばらくたってから再度アクセスしてください。' . $e->getMessage();
            exit;
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
        } catch (PDOException $e) {
            header('Content-Type: text/plain; charset=UTF-8', true, 500);
            echo '情報の読み込みに失敗しました。しばらくたってから再度アクセスしてください。' . $e->getMessage();
            exit;
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
        } catch (PDOException $e) {
            header('Content-Type: text/plain; charset=UTF-8', true, 500);
            echo '情報の読み込みに失敗しました。しばらくたってから再度アクセスしてください。' . $e->getMessage();
            exit;
        }
    }

    //客室新規登録
    public function roomRegister($name)
    {
        try {
            $this->connect();
            $date = new DateTime();
            $date->setTimeZone( new DateTimeZone('Asia/Tokyo'));
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
            } catch (PDOException $e) {
            header('Content-Type: text/plain; charset=UTF-8', true, 500);
            exit($e->getMessage());
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
                                                SET name = ?,
                                                updated_at = ?
                                                WHERE id = ? ';
            $stmt = $this->dbh->prepare($sql_room_edit_done);
            $stmt->execute([$name, $updated_at, $room_id]);
            $sql_room_detail_delete_flg_to_one = 'UPDATE room_detail
                                                                    SET delete_flg = 1
                                                                    WHERE room_id = ? ';
            $stmt = $this->dbh->prepare($sql_room_detail_delete_flg_to_one);
            $stmt->execute([$room_id]);
            foreach ($_POST['detail'] as $value) {
                $sql_room_detail_edit_done ='INSERT INTO room_detail(room_id, capacity, price, remarks) VALUES (?, ?, ?, ?)';
                $stmt = $this->dbh->prepare($sql_room_detail_edit_done);
                $stmt->execute([$room_id, $value['capacity'], $value['price'], $value['remarks']]);
            }
            $isEdited = 1;
            return $isEdited;
        } catch (PDOException $e) {
            header('Content-Type: text/plain; charset=UTF-8', true, 500);
            exit($e->getMessage());
        }

    }
}
