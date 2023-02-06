<?php 
include('core/Helpers.php');
include('core/db.php');
function marked_type($dbconn, $date, $user_id)
{
    $user_id = intval($user_id);
    $query = "SELECT * FROM attendacne WHERE staff_id = ".$user_id." AND DATE(date_) = '".$date."'";
  
    $result = $dbconn->query($query);

      $number_of_presents = null;
      if (mysqli_num_rows($result) > 0) {
        $cols = $result->fetch_assoc();
        return $cols['attendance'];
      } else{
        return 'not_marked';
      }
}

echo marked_type($conn, '2023-02-02', 1);

