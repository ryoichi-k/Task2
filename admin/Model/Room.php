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
            $sql = 'SELECT * FROM room WHERE id = :id';
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
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
            $sql = 'SELECT * FROM room_detail WHERE room_id = :id AND delete_flg = 0';
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
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
    public function getAllRoom()
    {
        $this->connect();

        if (!empty($_GET['sort'])) {

            if (($_GET['sort'] == 'name' || $_GET['sort'] == 'updated_at') && $_GET['order'] == 'asc') {
                $sort = $_GET['sort'] . ' IS NULL ASC,' . $_GET['sort'] . ' ASC';
            } else {
                $sort = $_GET['sort'] . ' IS NULL ASC,' . $_GET['sort'] . ' ' . $_GET['order'];
            }
            
        } else {
            $sort = 'created_at DESC';
        }
        $sql = 'SELECT * FROM room WHERE delete_flg = 0 ORDER BY '  . $sort;
        $stmt = $this->dbh->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //部屋検索（部分検索）
    public function searchRoom($search_name)
    {
        try {
            $this->connect();
            $stmt = $this->dbh->prepare('SELECT * FROM room WHERE name LIKE :search_name AND delete_flg = 0');
            $stmt->bindValue(':search_name', '%' . addcslashes($search_name, '\_%') . '%');
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception();
        }
    }

    //客室新規登録
    public function registerRoom($name, $detail)
    {
        try {
            $this->connect();

            $sql = 'INSERT INTO room(name) VALUES(:name)';
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':name', $name, (!empty($name) ? PDO::PARAM_STR : PDO::PARAM_NULL));
            $stmt->execute();

            //部屋id取得
            $id = $this->dbh->lastInsertId();

            foreach ($detail as $value) {
                $sql = 'INSERT INTO room_detail(room_id, capacity, remarks, price) VALUES(:room_id, :capacity, :remarks, :price)';
                $stmt = $this->dbh->prepare($sql);
                $stmt->bindValue(':room_id', $id);

                //空のカラムがあった場合はnullにする
                $stmt->bindValue(':capacity', $value['capacity'], (!empty($value['capacity']) ? PDO::PARAM_INT : PDO::PARAM_NULL));
                $stmt->bindValue(':remarks', $value['remarks'], (!empty($value['remarks']) ? PDO::PARAM_STR : PDO::PARAM_NULL));
                $stmt->bindValue(':price', $value['price'], (!empty($value['price']) ? PDO::PARAM_INT : PDO::PARAM_NULL));
                $stmt->execute();
            }
        } catch (Exception $e) {
            return 'エラーが発生しました。<br>CICACU辻井迄ご連絡ください。080-1411-4095(辻井) info@cicacu.jp';
        }
    }

    //客室編集
    public function editRoom($name, $room_detail_id, $detail)
    {
        try {
            $date_time = new DateTime();
            $date_time->setTimeZone( new DateTimeZone('Asia/Tokyo'));
            $date = $date_time->format('Y-m-d H:i:s');

            $this->connect();

            $sql = 'SELECT * FROM room_detail WHERE id = :id ';
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':id', $room_detail_id);
            $stmt->execute();
            $room_edit_done = $stmt->fetch(PDO::FETCH_ASSOC);

            $sql = 'UPDATE room SET name = :name, updated_at = :updated_at WHERE id = :room_id ';
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':name', $name, (!empty($name) ? PDO::PARAM_STR : PDO::PARAM_NULL));
            $stmt->bindValue(':updated_at', $date);
            $stmt->bindValue(':room_id', $room_edit_done['room_id'], PDO::PARAM_INT);
            $stmt->execute();

            $sql = 'UPDATE room_detail SET delete_flg = 1 WHERE room_id = :id ';
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':id', $room_edit_done['room_id']);
            $stmt->execute();

            foreach ($detail as $value) {
                $sql = 'INSERT INTO room_detail(room_id, capacity, remarks, price) VALUES(:room_id, :capacity, :remarks, :price)';
                $stmt = $this->dbh->prepare($sql);
                $stmt->bindValue(':room_id', $room_edit_done['room_id']);

                //空のカラムがあった場合はnullにする
                $stmt->bindValue(':capacity', $value['capacity'], (empty($value['capacity']) ? PDO::PARAM_NULL : PDO::PARAM_INT));
                $stmt->bindValue(':remarks', $value['remarks'], (empty($value['remarks']) ? PDO::PARAM_NULL : PDO::PARAM_STR));
                $stmt->bindValue(':price', $value['price'], (empty($value['price']) ? PDO::PARAM_NULL : PDO::PARAM_INT));
                $stmt->execute();
            }

        } catch (Exception $e) {
            return '編集エラーが発生しました。<br>CICACU辻井迄ご連絡ください。080-1411-4095(辻井) info@cicacu.jp';
        }
    }

    //客室論理削除
    public function deleteRoom($id)
    {
        $date_time = new DateTime();
        $date_time->setTimeZone( new DateTimeZone('Asia/Tokyo'));
        $date = $date_time->format('Y-m-d H:i:s');
        try {
            $this->connect();
            $sql = 'UPDATE room SET delete_flg = 1, updated_at = :updated_at WHERE id = :id ';
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':updated_at', $date);
            $stmt->execute();

            $sql = 'UPDATE room_detail SET delete_flg = 1 WHERE room_id = :id ';
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
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
