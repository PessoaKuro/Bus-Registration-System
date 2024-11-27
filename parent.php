<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Dashboard</title>
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
    <script src="sweetalert.min.js.txt" ></script>
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
      <li><a href="">
            <i class="fas fa-check"></i>
            <span>Students Info</span>
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
            <h2><?php
                    // Check if the name is passed in the URL
                    if (isset($_GET['name']) && isset($_GET['num'])) {
                        $parent_name = htmlspecialchars($_GET['name']);
                        $p_num = htmlspecialchars($_GET['num']);
                        echo "<h1> $parent_name!</h1>";
                    } else {
                        echo "<h1>Welcome!</h1>";
                    }
                    ?>

                </h2>
            <h1>Parent Dashboard</h1>
        </div>
        <div class="user--info">
        
            
            <img src="img/bus-stop3.jpg" alt=""/>
        </div>
    </div>
    <div class="functions--container">
  <br>
    <?php include('config/db.php'); ?>
    <div class="container">
        <form method="POST" action="index.php"> 
            
        </form>
        <hr>
        <div class="row">
            <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Student No.</th>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Mobile</th>
                        <th scope="col">Grade</th>
                        <form method="POST" action="" onsubmit="return submitForm(this);" >
                        <th scope="col">Remove</th>
                        </form>
                    </tr>
                </thead>
                <tbody>
                <?php 
                            if (isset($p_num)) {
                                $limit = 20;
                                $parent_num_safe = 
                                    mysqli_real_escape_string($conn, $p_num);
                                
                                $total_query = 
                                    "SELECT COUNT(*) FROM learner 
                                     WHERE parent_num = '$parent_num_safe'";
                                $total_result = mysqli_query($conn, $total_query);
                                $total_rows = mysqli_fetch_array($total_result)[0];
                                $total_pages = ceil($total_rows / $limit);

                                $page_number = isset($_GET['page']) && 
                                               is_numeric($_GET['page']) ? 
                                               (int)$_GET['page'] : 1;
                                $page_number = max(1, min($page_number, $total_pages));
                                $initial_page = ($page_number - 1) * $limit;

                                $getQuery = 
                                    "SELECT learner_num, name, surname, 
                                            cellphone_num, grade 
                                     FROM learner 
                                     WHERE parent_num = '$parent_num_safe' 
                                     LIMIT $initial_page, $limit";
                                     
                                getData($getQuery, $conn);
                            } else {
                                echo '<tr><td colspan="5">No parent data found.</td></tr>';
                            }
                        ?>
                    </tbody>
                </table>
                <a href="registration-form.php?parent_num=<?php echo urlencode($p_num) . '&name=' . urlencode($parent_name); ?>" class="btn btn-primary">Add Student</a><div>
                    <?php 
                        if (isset($total_pages)) {
                            for ($i = 1; $i <= $total_pages; $i++) {
                                echo '<a href="index.php?page=' . $i . 
                                     '&name=' . urlencode($parent_name) . 
                                     '&parent_num=' . urlencode($p_num) . 
                                     '" class="pagination-link">' . $i . '</a>'; 
                            }
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
            <td>
            <form method="POST" action="remove_parent.php" onsubmit="return confirm(\'Are you sure you want to remove this student?\');">
            <input type="hidden" name="learner_num" value="' . htmlspecialchars($row['learner_num']) . '"/>
            <input type="hidden" name="parent_num" value="' . htmlspecialchars($GLOBALS['p_num']) . '"/>
            <input type="hidden" name="parent_name" value="'.htmlspecialchars($GLOBALS['parent_name']).'"/>
            <input type="submit" name="remove" class="btn btn-danger" value="Remove"/>
            </form>
                </td>
            </tr>';
        }
    }
}
?>
