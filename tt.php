<?php
include("connection.php");
error_reporting(0);

session_start();
$userprofile = $_SESSION['user_name'];  
if (!$userprofile) {
    header('location:login.php');
    exit();
}
include("header/header.php");

$isAdmin = $_SESSION['is_admin']; 
$site_user_id = $_SESSION['site_id'];

$firstDayofMonth = date('01-m-Y');
$totaldaysInMonth = date('t', strtotime($firstDayofMonth));

if ($isAdmin == 1) {
  $fetchingQuery = "SELECT employee.*, site.site_name, site.id as site_id 
                    FROM employee
                    INNER JOIN site ON employee.e_location = site.id";
} else {
  $fetchingQuery = "SELECT employee.*, site.site_name, site.id as site_id 
                    FROM employee
                    INNER JOIN site ON employee.e_location = site.id 
                    WHERE site.id = '$site_user_id'";
}

$data = mysqli_query($conn, $fetchingQuery);
$numberOfStudents = mysqli_num_rows($data);
$idarray = array();
$sitearray = array();
$namearray = array();
$siteidarray = array();
$counter = 0;

while($row = mysqli_fetch_array($data)){
    $idarray[] = $row['id'];
    $namearray[] = $row['e_name'];
    $sitearray[] = $row['site_name'];
    $siteidarray[] = $row['site_id'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Attendance Sheet</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
  <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
      background: #f4f4f4;
    }

    header {
      background-color: #337ab7;
      color: white;
      padding: 20px 0;
      text-align: center;
      margin-bottom: 20px;
    }

    h1 {
      margin: 0;
    }

    table {
      border-collapse: collapse;
      width: 100%;
      background: white;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    th, td {
      padding: 8px 12px;
      border: 1px solid #ddd;
      text-align: center;
      font-size: 14px;
    }

    th {
      background-color: #3f51b5;
      color: white;
      position: sticky;
      top: 0;
      z-index: 1;
    }
    
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    .action-col {
      position: sticky;
      right: 0;
      background: white;
      color: #000;
      z-index: 2;
      min-width: 100px;
    }

    .action-icon {
      /* color: #3f51b5; */
      font-size: 16px;
      cursor: pointer;
      transition: 0.3s;
    }
    
    .action-icon:hover {
      color: #1a237e;
      transform: scale(1.2);
    }
    .status-P {
      background-color: green;
      color: white;
    }
    .status-A {
      background-color: red;
      color: white;
    }
    .status-L {
      background-color: yellow;
      color: black;
    }
    .sticky-col {
      position: sticky;
      left: 0;
      background: #fff;
      color: #000;
      z-index: 2;
    }
    .name-col {
      left: 50px;
    }
    .site-col {
      left: 120px;
    }
    td:nth-child(1), td:nth-child(2), td:nth-child(3) {
      font-weight: bold;
    }
    .table-container {
      overflow-x: auto;
    }
  </style>
</head>
<body>

<!-- <h2>Employee Monthly Attendance</h2> -->

<header>
        <h1>Employee Attendance Sheet</h1>
        <h3>Employee Attendance Month: <u><?php echo date("F"); ?></u></h3>
        <?php
            
            if (isset($_SESSION['attendance_message'])) {
                echo $_SESSION['attendance_message'];
                unset($_SESSION['attendance_message']);
            }
            ?>

<div style="text-align: right;">
  <h3>Mark Attendance : <a href="test.php"><i class="fa fa-edit"></i></a></h3>
</div>

    </header>

<div class="table-container">
  <table>
    <?php
      for($i=1; $i <= $numberOfStudents+2; $i++){
        if($i == 1){
            echo "<tr>";
            echo "<th rowspan='2' class='sticky-col'>ID</th>";
            echo "<th rowspan='2' class='sticky-col name-col'>Name</th>";
            echo "<th rowspan='2' class='sticky-col site-col'>Site</th>";
            for($j=1; $j<=$totaldaysInMonth; $j++){
              echo "<th>$j</th>";
            }
            echo "<th rowspan='2' class='action-col'>Action</th>";
            echo "</tr>";
        } elseif($i == 2){
            echo "<tr>";
            for($j=0; $j<$totaldaysInMonth; $j++){
              echo "<th>". date("D", strtotime("+$j days", strtotime($firstDayofMonth))) ."</th>";
            }
            echo "</tr>";
        } else {
            echo "<tr>";
            echo "<td class='sticky-col'>".$idarray[$counter]."</td>";
            echo "<td class='sticky-col name-col'>".$namearray[$counter]."</td>";
            echo "<td class='sticky-col site-col'>".$sitearray[$counter]."</td>";

            for($j=1; $j<=$totaldaysInMonth; $j++){
              $dateOfAttendance = date("Y-m-$j");
              $fetchingAtt = mysqli_query($conn, "SELECT attendence FROM update_material WHERE e_id = '{$idarray[$counter]}' AND curr_date = '$dateOfAttendance'");
              $isAttendance = mysqli_num_rows($fetchingAtt);

              if($isAttendance > 0){
                $attendance = mysqli_fetch_array($fetchingAtt);
                $att = $attendance['attendence'];
                echo "<td class='status-{$att}'>$att</td>";
              } else {
                echo "<td>-</td>";
              }
            }

            echo "<td class='action-col'>";
            if ($isAdmin == 1 || $siteidarray[$counter] == $site_user_id) {
            ?>
              <a href="#" class="action-icon icon-remark" data-id="<?php echo $idarray[$counter]; ?>" data-site="<?php echo $siteidarray[$counter]; ?>" title="Add Remark/Update" data-toggle="modal" data-target="#exampleModalCenter">
                <i class="fa fa-comment"></i>
              </a>

              <a href='#' class='action-icon icon-view' data-id="<?php echo $idarray[$counter]; ?>" data-site="<?php echo $siteidarray[$counter]; ?>" title='View Attendance' data-toggle='modal' data-target='#exampleModal'>
                <i class='fas fa-eye'></i>
              </a>
              <a href="profile.php?id=<?php echo $idarray[$counter]; ?>" title='View Profile'><i class="fas fa-play"></i></a>
            <?php
            }
            echo "</td>";


            echo "</tr>";
            $counter++;
        }
      }
    ?>
  </table>

 
<!-- Modal remark -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      
    </div>
  </div>
</div>

    <!-- view modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content modal-view">
      
    </div>
  </div>
</div>
</div>

<script>
  $(document).ready(function(){
    $('.action-icon.icon-remark').on('click', function(){
      var id = $(this).data('id');
      var site = $(this).data('site');
      // alert(id + " " + site);

      $.ajax({
        url: 'testdemo.php',
        type: 'POST',
        data: { id: id, site: site},
        success: function(data){
                    $(".modal-content").html(data);
                }
      })
    })

    $('.action-icon.icon-view').on('click', function(){
      var id = $(this).data('id');
      var site = $(this).data('site');
      // alert(id + " " + site);

      $.ajax({
        url: 'testview.php',
        type: 'GET',
        data: { id: id, site: site},
        success: function(data){
                    $(".modal-view").html(data);
                    $('#exampleModal').modal('show');
                }
      })
    })
  })
</script>


</body>
</html>
