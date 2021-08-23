<?php
class Room extends Model
{
    //index.phpにて部屋情報表示
    public function showRoom()
    {
        try {
            $this->connect();
            $sql = 'SELECT * FROM room WHERE delete_flg = 0';
            $stmt = $this->dbh->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception();
        }
    }

    //room_edit.phpにて部屋情報詳細を表示。灰色のボックス内に表示の情報を取得
    public function showRoomName($id)
    {
        try {
            $this->connect();
            $sql = 'SELECT * FROM room WHERE id = ?';
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception();
        }
    }

    //部屋情報詳細表示
    public function showRoomDetail($id)
    {
        try {
            $this->connect();
            $sql = 'SELECT * FROM room_detail WHERE room_id = ? AND delete_flg = 0';
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception();
        }
    }

    //index.phpにて部屋詳細情報表示
    public function showRoomDetailForIndex()
    {
        try {
            $this->connect();
            $sql = 'SELECT room_id, room_detail.* FROM room_detail WHERE delete_flg = 0';
            $stmt = $this->dbh->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_GROUP);
        } catch (Exception $e) {
            throw new Exception();
        }
    }

    //一覧表示とソート機能
    public function sortRoom()
    {
        $this->connect();
        if (!empty($_GET['sort'])) {
            $sorted_item = $_GET['sort'];
            $asc_or_desc = $_GET['order'];
            $sql = 'SELECT * FROM room WHERE delete_flg = 0 ORDER BY ' . $sorted_item . (($sorted_item == 'name' || $sorted_item == 'updated_at') && $asc_or_desc == 'asc' ? ' IS NULL ASC,' . $sorted_item . ' ASC' : ' ' . $asc_or_desc);
        } else {
            $sql = 'SELECT * FROM room WHERE delete_flg = 0 ORDER BY created_at DESC';
        }
            $stmt = $this->dbh->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //客室新規登録
    public function registerRoom($name, $detail)
    {
        try {
            $this->connect();

            $date = new DateTime();
            $date->setTimeZone(new DateTimeZone('Asia/Tokyo'));
            $time = $date->format('Y-m-d H:i:s');

            $sql = 'INSERT INTO room(name, created_at) VALUES(:name, :created_at)';
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':name', $name, PDO::PARAM_NULL);
            $stmt->bindValue(':created_at', $time, PDO::PARAM_NULL);
            $stmt->execute();

            $sql = 'SELECT * FROM room WHERE name = ? ';
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute([$name]);
            $room = $stmt->fetch(PDO::FETCH_ASSOC);

            foreach ($detail as $value) {
                $sql = 'INSERT INTO room_detail(room_id, capacity, remarks, price) VALUES(?, ?, ?, ?)';
                $stmt = $this->dbh->prepare($sql);
                $stmt->execute([$room['id'], $value['capacity'], $value['remarks'], $value['price']]);
            }

        } catch (Exception $e) {
            return 'エラーが発生しました。<br>CICACU辻井迄ご連絡ください。080-1411-4095(辻井) info@cicacu.jp';
        }
    }

    //客室編集
    public function editRoom($name, $room_detail_id, $detail)
    {
        try {
            $date = new DateTime();
            $date->setTimeZone( new DateTimeZone('Asia/Tokyo'));
            $today = $date->format('Y-m-d H:i:s');

            $this->connect();

            $sql = 'SELECT * FROM room_detail WHERE id = ? ';
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute([$room_detail_id]);
            $room_edit_done = $stmt->fetch(PDO::FETCH_ASSOC);

            $sql = 'UPDATE room SET name = :name, updated_at = :updated_at WHERE id = :room_id ';
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':name', $name, PDO::PARAM_NULL);
            $stmt->bindValue(':updated_at', $today, PDO::PARAM_NULL);
            $stmt->bindValue(':room_id', $room_edit_done['room_id'], PDO::PARAM_NULL);
            $stmt->execute();

            $sql = 'UPDATE room_detail SET delete_flg = 1 WHERE room_id = ? ';
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute([$room_edit_done['room_id']]);

            foreach ($detail as $value) {
                $sql = 'INSERT INTO room_detail(room_id, capacity, price, remarks) VALUES (?, ?, ?, ?)';
                $stmt = $this->dbh->prepare($sql);
                $stmt->execute([$room_edit_done['room_id'], $value['capacity'], $value['price'], $value['remarks']]);
            }
        } catch (Exception $e) {
            return '編集エラーが発生しました。<br>CICACU辻井迄ご連絡ください。080-1411-4095(辻井) info@cicacu.jp';
        }
    }

    //客室論理削除
    public function deleteRoom($id)
    {
        try {
            $this->connect();
            $sql = 'UPDATE room SET delete_flg = 1 WHERE id = ? ';
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute([$id]);

            $sql = 'UPDATE room_detail SET delete_flg = 1 WHERE room_id = ? ';
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute([$id]);
            header('Location: room_list.php');
            exit;
        } catch (Exception $e) {
            return 'エラーが発生しました。<br>CICACU辻井迄ご連絡ください。080-1411-4095(辻井) info@cicacu.jp';
        }
    }

    //reservation_conf.phpにて使用。部屋情報を検索
    public function findRoomDetailId($id)
    {
        try{
            $this->connect();
            $sql = 'SELECT * FROM room_detail WHERE id = ?';
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            throw new Exception();
        }
    }
}
