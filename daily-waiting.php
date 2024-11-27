  <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waiting List-Daily Report</title>
    <link rel="stylesheet" href="style3.css" />
    <!--Font Awesome Cdn link-->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
</head>
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
<body>
  <div class="main--content">
    <div class="header--wrapper">
        <div class="header--title">
            <h2>Dashboard</h2>
            <span>Waiting List Daily Report</span>
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
    <div class="functions--wrapper">
        <form method="POST" action="daily-waiting.php">
            <div class="form-row"> 
                <!-- Bus Selection -->
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

                <!-- Date Selection -->
                <div class="form-group">
                    <label class="control-label">Date</label>
                    <input type="date" class="form-control" name="date" value="2025-02-03" min="2025-02-02" max="2025-11-24">
                </div>

                <!-- Submit Button -->
                <div class="form-group submit-button">
                    <input type="submit" name="submit" class="btn btn-primary" id="submit" value="Submit">
                </div>
            </div> 
        </form>
    </div>
</div>
      <hr>
        <div class="row">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Bus Number</th>
                        <th scope="col">Learner Number</th>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <form method="POST" action="" onsubmit="return submitForm(this);" >
                        <th scope="col">Move</th>
                        </form>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $limit = 5;
                        $getQuery = "SELECT * FROM bus"; 
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
                            $getQuery = "SELECT waiting_list.bus_num, waiting_list.learner_num, waiting_list.name, waiting_list.surname
                                            FROM
                                                waiting_list
                            ";

                            getData($getQuery);
                        } else if (isset($_POST['submit'])) {
                            $getQuery = "SELECT 
                                            waiting_list.bus_num, waiting_list.learner_num, waiting_list.name, waiting_list.surname
                                            FROM
                                                waiting_list";
                                            
                            getData($getQuery);
                            
                        }
                    ?>
                </tbody>
            </table>
            <div>
                <?php 
                for ($page_number = 1; $page_number <= $total_pages; $page_number++) 
                {
                    echo '<a href="daily-wait.php?page=' . $page_number . '" class="pagination-link">' . $page_number . '</a>';
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
            <td>' . htmlspecialchars($row['bus_num']) . '</td>
            <td>' . htmlspecialchars($row['learner_num']) . '</td>
            <td>' . htmlspecialchars($row['name']) . '</td>
            <td>' . htmlspecialchars($row['surname']) . '</td>
            <td>
            <form method="POST" action="move_student.php" onsubmit="return confirm(\'Are you sure you want to move this student to their respective bus?\');">
            <input type="hidden" name="learner_num" value="' . htmlspecialchars($row['learner_num']) . '"/>
            <input type="submit" name="move" class="btn btn-danger" value="Move"/>
            </form>
                </td>
            </tr>';
        }
    }
}
?>
