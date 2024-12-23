<?php
include('connection.php');

if (isset($_GET['mall_name'])) {
    $mall_name = $_GET['mall_name'];

    $mall_query = "SELECT id FROM malls WHERE mall_name = '$mall_name' LIMIT 1";
    $mall_result = mysqli_query($con, $mall_query);
    $mall_row = mysqli_fetch_assoc($mall_result);
    $mall_id = $mall_row['id'];

    $rate_query = "SELECT rate_type, rate FROM parking_rates WHERE mall_id = '$mall_id' LIMIT 1";
    $rate_result = mysqli_query($con, $rate_query);
    $rate_row = mysqli_fetch_assoc($rate_result);

    if ($rate_row) {
        echo json_encode($rate_row);
    } else {
        echo json_encode(['rate_type' => 'hourly', 'rate' => 0]); 
    }
}
?>
