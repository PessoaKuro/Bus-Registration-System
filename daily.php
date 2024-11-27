<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Bus-Stop Usage Report</title>
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
            margin-right: 1px;
            color: #fff;
            padding: 5px 10px;
            border-radius: 3px;
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
            <span>Daily Bus Stop Usage</span>
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
<body>
    <nav class="navbar navbar-expand-sm bg-light navbar-light">
        <div class="container-fluid">
            <div class="navbar-header col-lg-10">
            </div>
        </div>
    </nav>
    <br>
    <?php include('config/db.php'); ?>
    <div class="container">
        <form method="POST" action="daily.php">
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
                        <label class="control-label">Date</label><br>
                        <input type="date" class="form-control" name="date" value="2025-01-01" min="2025-02-02"
                            max="2025-11-24">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group" style="padding-top: 24px;">
                        <input type="submit" name="submit" class="btn btn-primary" id="submit" value="Submit">
                    </div>
                </div>
            </div>
        </form>
        <hr>
        <div class="row">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Bus No.</th>
                        <th scope="col">Capacity</th>
                        <th scope="col">Pick-Up Count</th>
                        <th scope="col">Drop-Off Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $limit = 5;
                    $getQuery = "SELECT * FROM bus";
                    $result = mysqli_query($conn, $getQuery);
                    $total_rows = mysqli_num_rows($result);
                    $total_pages = ceil($total_rows / $limit);
                    if (!isset($_GET['page'])) {
                        $page_number = 1;
                    } else {
                        $page_number = $_GET['page'];
                    }
                    $initial_page = ($page_number - 1) * $limit;

                    if (!isset($_POST['submit'])) {
                        $getQuery = "SELECT route, capacity 
                            FROM bus  
                            LIMIT $initial_page, $limit";

                        getData($getQuery);
                    } else if (isset($_POST['submit']) && !empty($_POST['bus']) && !empty($_POST['date'])) {
                        $bus = $_POST['bus'];
                        $date = $_POST['date'];

                        $getQuery = "SELECT 
                                            bus.route, 
                                            bus.capacity, 
                                            COUNT(DISTINCT morning_pickup.Learner_Num) AS pickup_count, 
                                            COUNT(DISTINCT afternoon_dropoff.Learner_Num) AS dropoff_count 
                                         FROM 
                                            bus 
                                         LEFT JOIN 
                                            morning_pickup ON morning_pickup.route = bus.route AND morning_pickup.date = '$date'
                                         LEFT JOIN 
                                            afternoon_dropoff ON afternoon_dropoff.route = bus.route AND afternoon_dropoff.date = '$date'
                                         WHERE 
                                            bus.route = '$bus'
                                         GROUP BY 
                                            bus.route, bus.capacity";

                        getData($getQuery);
                    }
                    ?>
                </tbody>
            </table>
            <div>
                <?php
                for ($page_number = 1; $page_number <= $total_pages; $page_number++) {
                    echo '<a href="daily.php?page=' . $page_number . '" class="pagination-link">' . $page_number . '</a>';
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>

<?php
function getData($sql)
{
    include('config/db.php');

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            echo '<tr>
            <td>' . htmlspecialchars($row['route'] ?? 0) . '</td>
            <td>' . htmlspecialchars($row['capacity'] ?? 0) . '</td>
            <td>' . htmlspecialchars($row['pickup_count'] ?? 0) . '</td>
            <td>' . htmlspecialchars($row['dropoff_count']?? 0) . '</td> 
            </tr>';
        }
    }
}
?>