<?php
class Reservation extends UserModel
{
    //reservation_conf.phpで使用
    public function searchReservation($id)
    {
        try{
            $this->connect();

            $sql = 'SELECT * FROM reservation WHERE room_detail_id = ?';
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception();
        }
    }
}
