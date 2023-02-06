<?php
include('db.php');
include('Helpers.php');
include('flash.php');

$helper = new Helpers();

$url_arr = $helper->process_url();


$staff_id = $url_arr[6];
$date = $url_arr[7];
$type = $url_arr[8];
// /var_dump($type);die;
// echo $date;die;
$date = str_replace('"', '', $date);

if($helper->marked($conn, $date, $staff_id) == true){
    header("Location: /interviw/marked_already.php");
    exit;
}


$last_id = $helper->tableLastID($conn, 'attendacne');
$new_id = $last_id+1;
//mark this in db
$formattedDate = date("Y-m-d H:i:s", strtotime($date));
$sql = null;
if($type == 'present'){
   $sql = "INSERT INTO attendacne (id, staff_id, date_, attendance, hr_id)
VALUES ($new_id, '$staff_id', '$formattedDate', 'present', 2)";
}
else{
   $sql = "INSERT INTO attendacne (id, staff_id, date_, attendance, hr_id)
VALUES ($new_id, '$staff_id', '$formattedDate', 'absent', 2)";
}
if($conn->query($sql)){
    create_flash_message('done','Attendance successfully recorded.', 'success');
    $previous_url = $_SERVER['HTTP_REFERER'];
    header("Location: $previous_url");
    exit;
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
}
