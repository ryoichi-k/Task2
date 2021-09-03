<?php
class Room extends Model
{
    //index.phpにて部屋情報表示
    public function getRoom()
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
    public function getRoomName($id)
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
    public function getRoomDetail($id)
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
    public function getRoomDetailForIndex()
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
        try {
            $this->connect();

            if (!empty($_GET['sort']) && !empty($_GET['order'])) {//指摘あり
                $sort = $_GET['sort'] . ' IS NULL ASC,' . $_GET['sort'] . ' ' . $_GET['order'];
            } else {
                $sort = 'id DESC';
            }
            $sql = 'SELECT * FROM room WHERE delete_flg = 0 ORDER BY '  . $sort;
            $stmt = $this->dbh->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception();
        }
    }

    //部屋検索（部分検索と全体一致検索）
    public function searchRoom($search_name)
    {
        try {
            $this->connect();
            if ($_GET['search'] == 1) {
                $stmt = $this->dbh->prepare('SELECT * FROM room WHERE name LIKE :search_name AND delete_flg = 0');
                $stmt->bindValue(':search_name', '%' . addcslashes($search_name, '\_%') . '%');
            }
            if ($_GET['search'] == 2) {
                $stmt = $this->dbh->prepare('SELECT * FROM room WHERE name = :search_name AND delete_flg = 0');
                $stmt->bindValue(':search_name', $search_name);
            }
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

            $this->dbh->beginTransaction();

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

            $this->dbh->commit();

        } catch (Exception $e) {
            $this->dbh->rollBack();
            return 'エラーが発生しました。<br>CICACU辻井迄ご連絡ください。080-1411-4095(辻井) info@cicacu.jp';
        }
    }

    //客室編集
    public function editRoom($name, $detail)
    {
        try {
            $date_time = new DateTime();
            $date_time->setTimeZone( new DateTimeZone('Asia/Tokyo'));
            $date = $date_time->format('Y-m-d H:i:s');

            $this->connect();

            $this->dbh->beginTransaction();

            $sql = 'UPDATE room SET name = :name, updated_at = :updated_at WHERE id = :room_id ';
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':name', $name, (!empty($name) ? PDO::PARAM_STR : PDO::PARAM_NULL));
            $stmt->bindValue(':updated_at', $date);
            $stmt->bindValue(':room_id', $_GET['id'], PDO::PARAM_INT);
            $stmt->execute();

            $sql = 'UPDATE room_detail SET delete_flg = 1 WHERE room_id = :id ';
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':id', $_GET['id']);
            $stmt->execute();

            foreach ($detail as $value) {
                $sql = 'INSERT INTO room_detail(room_id, capacity, remarks, price) VALUES(:room_id, :capacity, :remarks, :price)';
                $stmt = $this->dbh->prepare($sql);
                $stmt->bindValue(':room_id', $_GET['id']);

                //空のカラムがあった場合はnullにする
                $stmt->bindValue(':capacity', $value['capacity'], (empty($value['capacity']) ? PDO::PARAM_NULL : PDO::PARAM_INT));
                $stmt->bindValue(':remarks', $value['remarks'], (empty($value['remarks']) ? PDO::PARAM_NULL : PDO::PARAM_STR));
                $stmt->bindValue(':price', $value['price'], (empty($value['price']) ? PDO::PARAM_NULL : PDO::PARAM_INT));
                $stmt->execute();
            }

            $this->dbh->commit();

        } catch (Exception $e) {
            $this->dbh->rollBack();
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

            $this->dbh->beginTransaction();

            $sql = 'UPDATE room SET delete_flg = 1, updated_at = :updated_at WHERE id = :id ';
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':updated_at', $date);
            $stmt->execute();

            $sql = 'UPDATE room_detail SET delete_flg = 1 WHERE room_id = :id ';
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            $this->dbh->commit();

            header('Location: room_list.php');
            exit;
        } catch (Exception $e) {

            $this->dbh->rollBack();

            return 'エラーが発生しました。<br>CICACU辻井迄ご連絡ください。080-1411-4095(辻井) info@cicacu.jp';
        }
    }

    //部屋画像をアップロード
    public function uploadImage($id)
    {


        try {
            if ($_FILES['upfile']['error'] != UPLOAD_ERR_OK) {
                throw new Exception();
            }
            $this->connect();

            $this->dbh->beginTransaction();

            $date_time = new DateTime();
            $date_time->setTimeZone( new DateTimeZone('Asia/Tokyo'));
            $date = $date_time->format('YmdHis');

            $img = mb_convert_encoding($date . $_FILES['upfile']['name'], 'cp932', 'utf8');

            $date = $date_time->format('Y-m-d H:i:s');
            $sql = 'UPDATE room SET img = ?,updated_at = ? WHERE id = ?';
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute([$img, $date, $id]);
            system('sudo chmod 0777 ../images/room');

            if (!move_uploaded_file($_FILES['upfile']['tmp_name'], IMAGE_PATH . $img)) {
                $this->dbh->rollBack();
                system('sudo chmod 0755 ../images/room');
                throw new Exception();
            }
            system('sudo chmod 0755 ../images/room');

            $this->dbh->commit();

        } catch (PDOException $e) {
            $this->dbh->rollBack();
            return 'エラーが発生しました。<br>CICACU辻井迄ご連絡ください。080-1411-4095(辻井) info@cicacu.jp';
        } catch (Exception $e) {
            return 'アップロードに失敗しました。<br>CICACU辻井迄ご連絡ください。080-1411-4095(辻井) info@cicacu.jp';
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
