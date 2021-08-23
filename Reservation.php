<?php
class Reservation extends UserModel
{
    //reservation_conf.phpで使用
    public function searchReservation($id)
    {
        try{
            $this->connect();

            $sql_select_reservation = 'SELECT * FROM reservation WHERE room_detail_id = ?';
            $stmt = $this->dbh->prepare($sql_select_reservation);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception();
        }
    }
}
