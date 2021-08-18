<?php
class Reservation extends UserModel
{
    // public function reservationValidation($reservation, $number, $capacity, $status, $next_day, $three_month_lator)
    // {
    //     if ($reservation == false) {//新規予約
    //         //宿泊人数が客室に登録されている人数を超えていないこと
    //         if ($number > $capacity) {
    //             $error = '客室の宿泊可能人数を超えています。別のお部屋をお選びください。';
    //         }
    //         //宿泊終了日が3か月後以前であること
    //         if ($next_day > $three_month_lator) {
    //             $error = '3ヶ月以降のご予約はできません。';
    //         }
    //     } else {//予約済の場合
    //         //予約しようとしている客室が予約済みでないこと
    //         if (isset($status) && $status == 1) {
    //             $error = '満室です。別の部屋を選んでください。';
    //         }
    //     }
    //     return $error;
    // }

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
