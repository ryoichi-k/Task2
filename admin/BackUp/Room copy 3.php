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
            //$a = $_GET['sort'] . (($_GET['sort'] == 'name' || $_GET['sort'] == 'updated_at') && $_GET['order'] == 'asc' ? ' IS NULL ASC,' . $_GET['sort'] . ' ASC' : ' ' . $_GET['order']);
            //$b= 'created_at DESC';
            $sql = 'SELECT * FROM room WHERE delete_flg = 0 ORDER BY '  . ((!empty($_GET['sort']) ? $_GET['sort'] . (($_GET['sort'] == 'name' || $_GET['sort'] == 'updated_at') && $_GET['order'] == 'asc' ? ' IS NULL ASC,' . $_GET['sort'] . ' ASC' : ' ' . $_GET['order']) : 'created_at DESC'));
            // $a = $_GET['sort'] . (($_GET['sort'] == 'name' || $_GET['sort'] == 'updated_at') && $_GET['order'] == 'asc' ? ' IS NULL ASC,' . $_GET['sort'] . ' ASC' : ' ' . $_GET['order']);
            // $b= 'created_at DESC';
            // $sql = 'SELECT * FROM room WHERE delete_flg = 0 ORDER BY '  . ((!empty($_GET['sort']) ? $a : $b));
            echo $sql;
            $stmt = $this->dbh->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

    //部屋検索
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
            if ($name === '') {
                $stmt->bindValue(':name', $name, PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(':name', $name, PDO::PARAM_STR);
            }
            $stmt->execute();

            //nameから部屋名取得
            $sql = 'SELECT * FROM room WHERE name = (:name)';
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':name', $name);
            $stmt->execute();
            $room = $stmt->fetch(PDO::FETCH_ASSOC);

            foreach ($detail as $value) {
                $sql = 'INSERT INTO room_detail(room_id, capacity, remarks, price) VALUES(:room_id, :capacity, :remarks, :price)';
                $stmt = $this->dbh->prepare($sql);
                $stmt->bindValue(':room_id', $room['id']);

                //空のカラムがあった場合はnullにする
                $stmt->bindValue(':capacity', $value['capacity'], (empty($value['capacity']) ? PDO::PARAM_NULL : PDO::PARAM_INT));
                $stmt->bindValue(':remarks', $value['remarks'], (empty($value['remarks']) ? PDO::PARAM_NULL : PDO::PARAM_STR));
                $stmt->bindValue(':price', $value['price'], (empty($value['price']) ? PDO::PARAM_NULL : PDO::PARAM_INT));
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

            if ($name === '') {
                $stmt->bindValue(':name', $name, PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(':name', $name, PDO::PARAM_STR);
            }
            $stmt->bindValue(':updated_at', $today);
            $stmt->bindValue(':room_id', $room_edit_done['room_id'], PDO::PARAM_INT);
            $stmt->execute();

            $sql = 'UPDATE room_detail SET delete_flg = 1 WHERE room_id = ? ';
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute([$room_edit_done['room_id']]);

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
}
