<?php
// 配列の初期化
$array = array();
$id_array = array();
$age_array = array();

$array = array(
    array(
        'id' => 1,
        'name' => '柴犬',
        'age' => 8,
        'regist_datetime' => '2017-02-01 10:00'
    ),
    array(
        'id' => 2,
        'name' => 'ポメラニアン',
        'age' => 3,
        'regist_datetime' => '2017-02-15 10:00'
    ),
    array(
        'id' => 5,
        'name' => 'ゴールデンレトリバー',
        'age' => 8,
        'regist_datetime' => '2017-02-16 15:00'
    ),
    array(
        'id' => 6,
        'name' => 'グレイハウンド',
        'age' => 6,
        'regist_datetime' => '2017-02-16 16:30'
    ),
    array(
        'id' => 8,
        'name' => 'シベリアンハスキー',
        'age' => 3,
        'regist_datetime' => '2017-02-16 19:00'
    )
);
// ソートの基準となる「id」と「age」を配列に入れる
foreach( $array as $value) {
    $id_array[] = $value['id'];
    $age_array[] = $value['age'];
}
echo '<pre>';
var_dump($array);
echo '</pre>';
array_multisort( $age_array,
    SORT_ASC, SORT_NUMERIC,
    $id_array, SORT_DESC,
    SORT_NUMERIC, $array);
echo '<pre>';
var_dump($array);
echo '</pre>';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CICACU | リスト 管理</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.0/css/bootstrap-reboot.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

</body>
</html>