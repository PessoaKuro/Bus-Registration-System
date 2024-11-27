<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Usage Report</title>
    <link rel="stylesheet" href="style3.css" />
    <!--Font Awesome Cdn link-->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <style>
        .pagination-link {
            background: #337ab7;
            text-decoration: none;
            margin-top: 7px;
            margin-right: 1px;
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            display: inline-block;
        }
        .form-check-label {
            margin-right: 10px;
        }
    </style>
</head>
<body>
     
<div class="sidebar">
    <div class="logo"></div>
    <ul class="menu">
        <li class="active">
            <a href="#">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="#">
                <i class="fas fa-user"></i>
                <span>Profile</span>
            </a>
        </li>
      <li><a href="approved.php">
            <i class="fas fa-check"></i>
            <span>Approved</span>
        </a></li>
        <li><a href="index.php">
                <i class="fas fa-bus"></i>
                <span>Bus Usage</span>
            </a></li>
            <li><a href="daily-waiting.php">
                <i class="fas fa-clock"></i>
                <span>Waiting</span>
            </a></li>
            <li><a href="daily.php">
                <i class="fas fa-map-marker-alt"></i>
                <span>Bus Stop</span>
            </a></li>
            <li><a href="weekly.php">
                <i class="fas fa-calendar"></i>
                <span>Weekly</span>
            </a></li>

        <li class="logout">
            <a href="login.php">
                <i class="fas fa-sign-out-alt" ></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
  </div>  
    
  <div class="main--content">
    <div class="header--wrapper">
        <div class="header--title">
            <h2>Dashboard</h2>
            <span>Daily Bus Usage Report</span>
        </div>
        <div class="user--info">
        <a href="approved.php">
            <i class="fas fa-check"></i>
            <span>Approved Students</span>
        </a>
        <a href="index.php">
                <i class="fas fa-bus"></i>
                <span>Daily Bus Usage</span>
            </a>
            <a href="daily-waiting.php">
                <i class="fas fa-clock"></i>
                <span>Waiting List</span>
            </a>
            <a href="daily.php">
                <i class="fas fa-map-marker-alt"></i>
                <span>Bus Stop Usage</span>
            </a>
            <a href="weekly.php">
                <i class="fas fa-calendar"></i>
                <span>Weekly Usage</span>
            </a>    
            <img src="img/bus-stop3.jpg" alt=""/>
        </div>
    </div>
    <div class="functions--container">
        <br>
    <?php include('config/db.php'); ?>
    <div class="container">
        <form method="POST" action="index.php"> 
            <div class="row"> 
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Choose the Bus</label>
                        <select class="form-control" name="bus">
                            <option value="">Select</option>
                            <?php
                                $query = "SELECT * FROM bus";
                                $result = mysqli_query($conn, $query) or die('error');
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) { ?>
                                        <option value="<?php echo $row['ROUTE']; ?>"><?php echo $row['ROUTE']; ?></option>
                                    <?php }
                                }
                            ?>
                        </select>
                    </div> 
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Date</label>
                        <input type="date" class="form-control" name="date" value="2025-01-01" min="2025-02-2" max="2025-11-24">
                    </div> 
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label"></label>
                        <div class="form-check form-check-inline">
                        <label class="form-check-label" for="morning">Morning</label><input class="form-check-input" type="radio" name="morningorafternoon" id="morning" value="morning">
                            
                        </div>
                        <div class="form-check form-check-inline">
                        <label class="form-check-label" for="afternoon">Afternoon</label>
                            <input class="form-check-input" type="radio" name="morningorafternoon" id="afternoon" value="afternoon">
                        </div>   
                    </div>        
                </div>

                <div class="col-md-3">
                    <div class="form-group" style="padding-top: 24px;"> 
                        <input type="submit" name="submit" class="btn btn-primary" id="submit" value="submit">
                    </div>
                </div>
            </div>
        </form>
        
        <div class="row">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Student No.</th>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Mobile</th>
                        <th scope="col">Grade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $limit = 5;
                        $getQuery = "SELECT * FROM learner"; 
                        $result = mysqli_query($conn, $getQuery);
                        $total_rows = mysqli_num_rows($result);
                        $total_pages = ceil($total_rows / $limit); 
                        if (!isset($_GET['page'])){
                            $page_number = 1;
                        } else {
                            $page_number = $_GET['page'];
                        }
                        $initial_page = ($page_number - 1) * $limit;

                        if (!isset($_POST['submit'])) {
                            $getQuery = "SELECT learner.learner_num, learner.name, learner.surname, learner.cellphone_num, learner.grade 
                            FROM learner  
                            LIMIT $initial_page, $limit";

                            getData($getQuery);
                        } else if (isset($_POST['submit']) && !empty($_POST['bus']) && !empty($_POST['date']) && !empty($_POST['morningorafternoon'])) {
                            $bus = $_POST['bus'];
                            $date = $_POST['date'];
                            $timeOfDay = $_POST['morningorafternoon'];

                            if ($timeOfDay == 'morning') {
                                $getQuery = "SELECT learner.learner_num, learner.name, learner.surname, learner.cellphone_num, learner.grade, morning_pickup.date 
                                FROM learner 
                                JOIN morning_pickup ON morning_pickup.Learner_Num = learner.learner_num 
                                WHERE morning_pickup.route = '$bus' 
                                AND morning_pickup.date = '$date'";
                            } else if ($timeOfDay == 'afternoon') {
                                $getQuery = "SELECT learner.learner_num, learner.name, learner.surname, learner.cellphone_num, learner.grade, afternoon_dropoff.date 
                                FROM learner 
                                JOIN afternoon_dropoff ON afternoon_dropoff.Learner_Num = learner.learner_num 
                                WHERE afternoon_dropoff.route = '$bus' 
                                AND afternoon_dropoff.date = '$date'";
                            }
                            getData($getQuery);
                        }
                    ?>
                </tbody>
            </table>
            <div>
                <?php 
                for ($page_number = 1; $page_number <= $total_pages; $page_number++) 
                {
                    echo '<a href="index.php?page=' . $page_number . '" class="pagination-link">' . $page_number . '</a>';
                }
                ?>
            </div>
        </div>
    </div>   
</body>
</html>

<?php 
function getData($sql){
    include('config/db.php');

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            echo '<tr>
            <td>' . htmlspecialchars($row['learner_num']) . '</td>
            <td>' . htmlspecialchars($row['name']) . '</td>
            <td>' . htmlspecialchars($row['surname']) . '</td>
            <td>' . htmlspecialchars($row['cellphone_num']) . '</td>
            <td>' . htmlspecialchars($row['grade']) . '</td>
            </tr>';
        }
    }
}
?>
