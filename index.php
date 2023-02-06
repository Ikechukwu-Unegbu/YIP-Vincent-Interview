<!doctype html>
<?php
include('core/db.php');
include('core/Helpers.php');
include('core/flash.php');
$helper = new Helpers();
$query = "SELECT * FROM `staffs`;";
$month = 2;
$year = 2023;
if(isset($_GET['year']) && isset($_GET['month']))
{
  $month = $_GET['month'];
  $year = $_GET['year'];
}



// FETCHING DATA FROM DATABASE
$result = $conn->query($query);
$result = $result->fetch_all();
// var_dump($result);die;
  function calculate_salar($dbconn, $user_id, $year, $month){
   $helper = new Helpers();
    $workdays = $helper->get_weekdays_in_month($month, $year);

    //calculate daily rate 
    $daily_pay = 10000/$workdays;
    //check number of days staff worked for the month
  ;
    $query = "SELECT * FROM attendacne WHERE staff_id = $user_id AND  attendance = 'present' AND YEAR(date_) = $year AND MONTH(date_) = $month";
    // "SELECT * FROM attendacne WHERE staff_id = 2 AND YEAR(date_) = 2023 AND MONTH(date_) = 02 AND attendance = 'present';

    $result = $dbconn->query($query);
    // var_dump($result);die;

      $number_of_presents = null;
      if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // var_dump($row);
            $number_of_presents = mysqli_num_rows($result);
        }
      } else {
        $number_of_presents = 0;
      }

      $monthly_pay = $daily_pay * $number_of_presents;
      return round($monthly_pay, 2);
    

  }

?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="static_asset/css/style.css">
    <style>
      .absent{
        background-color: red;
      }
    </style>
  </head>
  <body>
    <header>
        <nav class="navbar navbar-expand-lg bg-light">
            <div class="container-fluid">
              <a class="navbar-brand" href="#">Navbar</a>
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                  <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Home</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                  </li>
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                      Dropdown
                    </a>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="#">Action</a></li>
                      <li><a class="dropdown-item" href="#">Another action</a></li>
                      <li><hr class="dropdown-divider"></li>
                      <li><a class="dropdown-item" href="#">Something else here</a></li>
                    </ul>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link disabled">Disabled</a>
                  </li>
                </ul>
                <form class="d-flex" role="search">
                  <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                  <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
              </div>
            </div>
          </nav>
    </header>

    <main class="container">
      <div class="mt-5 mb-3">
        <?php 
          display_flash_message('done');
        ?>
      </div>
      <form action="" method="get">
        <div class="form-group">
          <input type="year" placeholder="2022" class="form-control" name="year">
        </div>
        <div class="form-group">
          <input type="text" name="month" placeholder="8" class="form-control">
        </div>
        <div class="form-group">
          <button class="btn btn-primary">Go</button>
        </div>
      </form>
        <div class="table-responsive mt-5">
            <table class="table">
              <thead>
                <h4 class="text-center">Staff Attendance</h4>
              </thead>
                <thead>
                  <tr>
                    <th scope="col">Date</th>
                    <?php 
                      foreach($result as $r){
                          // var_dump($r[1]);
                          // print('<br/>');
                          echo '<th scope="col">'.$r[1].' ';

                          echo 'NGN '.calculate_salar($conn, $r[0], 2023, 2);
                          echo '</th>';
                      }
                    ?>
                    <!-- <th scope="col">Attendance</th> -->
                    <!-- <th scope="col">Handle</th> -->
                  </tr>
                </thead>
                <tbody>
                 <?Php 
                    $list=array();
                    // $list[];
                    for($d=1; $d<=31; $d++)
                    {
                        $time=mktime(12, 0, 0, $month, $d, $year);          
                        if (date('m', $time)==$month)       
                            $list[]=date('Y-m-d', $time);
                    
                    }

                    foreach($list as $l){
                     echo  '<tr>';
                      echo '<th scope="row">'.$l.'</th>';
                        foreach($result as $r){
                          if($helper->marked_type($conn, $l, $r[0]) == 'present'){
                            echo '<th class='.'marked'.'> 
                              <a class='.'present'.'  href=core/mark.php/'.$r[0].'/'.$l.'/present'.'> present<a/>
                              <a  href=core/mark.php/'.$r[0].'/'.$l.'/present'.'> absent<a/> 
                              </th>';
                            // echo '<th> <a  class='.'marked'.' href=core/mark.php/'.$r[0].'/'.$l.'>absent<a/> </th>';
                          }elseif($helper->marked_type($conn, $l, $r[0]) == 'absent'){
                            echo '<th  class='.'absent'.' style='.'background-color:red !important;'.'> 
                                <a  href=core/mark.php/'.$r[0].'/'.$l.'/present'.'>present<a/>
                                <a   href=core/mark.php/'.$r[0].'/'.$l.'/absent'.'>absent<a/>
                              </th>';
                            // echo '<th> <a href=core/mark.php/'.$r[0].'/'.$l.'>present<a/> </th>';
                          }
                          else{
                            echo '<th  class='.'not'.'> 
                            <a  href=core/mark.php/'.$r[0].'/'.$l.'/present'.'>present<a/>
                            <a   href=core/mark.php/'.$r[0].'/'.$l.'/absent'.'>absent<a/>
                          </th>';
                          }
                        }
                     
                      echo '</tr>';
                    }
                 ?>
                
                </tbody>
              </table>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  </body>
</html>

