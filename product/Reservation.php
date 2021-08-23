<?php
class Reservation extends UserModel
{
    public function reservationDetailId($id)
    {
        try{
            $this->connect();
            
            $sql_select_reservation = 'SELECT * FROM reservation WHERE room_detail_id = ?';
            $stmt = $this->dbh->prepare($sql_select_reservation);
            $stmt->execute([$id]);
            $reservation = $stmt->fetch(PDO::FETCH_ASSOC);
            return $reservation;
        } catch (Exception $e) {
            throw new Exception();
        }
    }
}
