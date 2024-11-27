<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Utilization Report</title>
    <link rel="stylesheet" href="style3.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
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
            <span>Bus Weekly Usage</span>
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
    <br>
    <?php include('config/db.php'); ?>
    <div class="container">
        <form method="POST" action="weekly.php"> 
            <div class="row"> 
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Choose the Bus</label>
                        <select class="form-control" name="bus" title="bus">
                            <option value="">Select</option>
                            <?php
                                // Fetch buses
                                $query = "SELECT * FROM bus";
                                $result = mysqli_query($conn, $query) or die('Error fetching buses.');
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
                        <label class="control-label">Date From</label>
                        <input type="date" class="form-control" title="From" placeholder="Select from" name="fromDate" id="fromDate" value="2025-01-01" min="2025-02-02" max="2025-11-24">
                        <label class="control-label">Date To</label>
                        <input type="date" class="form-control" title="To" placeholder="Select to" name="toDate" id="toDate" value="2025-01-01" min="2025-02-02" max="2025-11-24">
                    </div> 
                </div>

                <div class="col-md-3">
                    <div class="form-group" style="padding-top: 24px;"> 
                        <input type="submit" title="submit" placeholder="Submit" name="submit" class="btn btn-primary" id="submit" value="Submit">
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
                        <th scope="col">Route</th>
                        <th scope="col">Total Morning Students</th>
                        <th scope="col">Total Afternoon Students</th>
                        <th scope="col">Average Morning Utilization</th>
                        <th scope="col">Average Afternoon Utilization</th>
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
                            // Initial query to fetch all buses
                            $getQuery = "SELECT route, capacity FROM bus LIMIT $initial_page, $limit";
                            getData($getQuery);
                        } else if (isset($_POST['submit']) && !empty($_POST['bus']) && !empty($_POST['fromDate']) && !empty($_POST['toDate'])) {
                            // Fetch data based on the selected bus and date range
                            $bus = $_POST['bus'];
                            $fromDate = $_POST['fromDate'];
                            $toDate = $_POST['toDate'];

                            $dateInterval = date_diff(date_create($fromDate), date_create($toDate))->days + 1;

                            $getQuery = "SELECT 
                                            bus.route, 
                                            bus.capacity, 
                                            COALESCE(SUM(morning.morning_count), 0) AS total_mstudents,
                                            COALESCE(SUM(afternoon.afternoon_count), 0) AS total_astudents,
                                            COALESCE((SUM(morning.morning_count)) / $dateInterval, 0) AS average_moccupancy,
                                            COALESCE((SUM(afternoon.afternoon_count)) / $dateInterval, 0) AS average_aoccupancy
                                         FROM 
                                            bus 
                                         LEFT JOIN (
                                            SELECT route, date, COUNT(DISTINCT Learner_Num) AS morning_count 
                                            FROM morning_pickup 
                                            WHERE date BETWEEN '$fromDate' AND '$toDate'
                                            GROUP BY route, date
                                         ) AS morning ON morning.route = bus.route
                                         LEFT JOIN (
                                            SELECT route, date, COUNT(DISTINCT Learner_Num) AS afternoon_count 
                                            FROM afternoon_dropoff 
                                            WHERE date BETWEEN '$fromDate' AND '$toDate'
                                            GROUP BY route, date
                                         ) AS afternoon ON afternoon.route = bus.route
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
                // Display pagination links
                for ($page_number = 1; $page_number <= $total_pages; $page_number++) {
                    echo '<a href="weekly.php?page=' . $page_number . '" class="pagination-link">' . $page_number . '</a>';
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
                <td>' . htmlspecialchars($row['route']) . '</td>
                <td>' . htmlspecialchars($row['route']) . '</td>
                <td>' . htmlspecialchars($row['total_mstudents'] ?? 0) . '</td>
                <td>' . htmlspecialchars($row['total_astudents'] ?? 0) . '</td>
                <td>' . htmlspecialchars($row['average_moccupancy'] ?? 0) . '</td>
                <td>' . htmlspecialchars($row['average_aoccupancy'] ?? 0) . '</td>
            </tr>';
        }
    } else {
        echo '<tr><td colspan="6">No data available for the selected criteria.</td></tr>';
    }
}
?>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript">
    var fromDate;
    $('#fromDate').on('change', function() {
        fromDate = $(this).val();
        $('#toDate').prop('min', function(){
            return fromDate;
        })
    });
    $('#toDate').on('change', function() {
        toDate = $(this).val();
        $('#fromDate').prop('max', function(){
            return toDate;
        })
    });
</script>
