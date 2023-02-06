<?php 

class Helpers{

    public function get_sundays($year, $month)
    {
        // $month = 2; // February
        // $year = 2022;
        $num_of_days = date("t", strtotime("$year-$month-01"));
        $sundays = 0;

        for ($i = 1; $i <= $num_of_days; $i++) {
            $date = "$year-$month-$i";
            $day_of_week = date("l", strtotime($date));
            if ($day_of_week == "Sunday") {
                $sundays++;
            }
        }

        return $sundays;
    }

    function get_weekdays_in_month($month, $year) 
    {
        $num_of_days = date("t", strtotime("$year-$month-01"));
        $sundays = 0;
    
        for ($i = 1; $i <= $num_of_days; $i++) {
            $date = "$year-$month-$i";
            $day_of_week = date("l", strtotime($date));
            if ($day_of_week == "Sunday") {
                $sundays++;
            }
        }
    
        return $num_of_days - $sundays;
    }

    public function tableLastID($conn, $table_name)
    {
        
        $sql_last_row = "SELECT id FROM ".$table_name." ORDER BY id DESC LIMIT 1";


        $last_row = $conn->query($sql_last_row);
        $last_id_of_attendance = null;
        if ($last_row->num_rows > 0) {
            $row = $last_row->fetch_assoc();
            $last_id = $row["id"];
            $last_id_of_attendance = intval($last_id);
            // echo "Last ID: " . $last_id;
        } else {
            $last_id_of_attendance = 0;
        }
        return $last_id_of_attendance;
    }

    public function process_url()
    {
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
        $url = "https://";   
        else  
        $url = "http://";   
        // Append the host(domain name, ip) to the URL.   
        $url.= $_SERVER['HTTP_HOST'];   

        // Append the requested resource location to the URL   
        $url.= $_SERVER['REQUEST_URI'];    


        $url_arr = explode("/", $url);
        return $url_arr;
    }

    public function marked($dbconn, $date, $user_id)
    {
        $user_id = intval($user_id);
        $query = "SELECT * FROM attendacne WHERE staff_id = ".$user_id." AND DATE(date_) = '".$date."'";
      
        $result = $dbconn->query($query);
    
          $number_of_presents = null;
          if (mysqli_num_rows($result) > 0) {
            return true;
          } else{
            return false;
          }
    }



    public function marked_type($dbconn, $date, $user_id)
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
}