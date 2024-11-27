<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Form</title>
    <link rel="stylesheet" href="style2.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <header>
        <div class="wrapper">
            <?php
            // Enable error reporting for debugging
            error_reporting(E_ALL);
            ini_set('display_errors', 1);

            // Connect to the database
            $connect = mysqli_connect("localhost", "root", "", "onlinebusregistration");
            if (!$connect) {
                die("Connection Failed: " . mysqli_connect_error());
            }

            // Check if form is submitted
            if (isset($_POST['save'])) {
                $username = mysqli_real_escape_string($connect, $_POST['username']);
                $password = mysqli_real_escape_string($connect, $_POST['password']);
                
                // Debugging: Check that POST data is received
                echo "<pre>POST Data: "; var_dump($_POST); echo "</pre>";

                // Check in parent table
                $query_parent = "SELECT * FROM parent WHERE PARENT_NUM = '$username' AND password = '$password'";
                $result_parent = mysqli_query($connect, $query_parent);

                // Debugging: Check query execution and result
                if (!$result_parent) {
                    echo "Error with parent query: " . mysqli_error($connect);
                }

                if (mysqli_num_rows($result_parent) > 0) {
                    // Redirect to parent.php if credentials match in parent table
                    $parent = mysqli_fetch_assoc($result_parent);
                    $parent_name = $parent['NAME'];
                    $p_num = $parent['PARENT_NUM'];
                    header("Location: parent.php?name=" . urlencode('Welcome ' . $parent_name) . "&num=" . urlencode($p_num));
                    exit();

                    
                    }

                // Check in admin table if no match found in parent
                $query_admin = "SELECT * FROM admin WHERE ADMIN_NUM = '$username' AND password = '$password'";
                $result_admin = mysqli_query($connect, $query_admin);

                // Debugging: Check query execution and result
                if (!$result_admin) {
                    echo "Error with admin query: " . mysqli_error($connect);
                }

                if (mysqli_num_rows($result_admin) > 0) {
                    // Redirect to index.php if credentials match in admin table
                    // Redirect to parent.php if credentials match in parent table
                    $admin = mysqli_fetch_assoc($result_parent);
                    $admin_name = $admin['NAME'];
                    $a_num = $admin['ADMIN_NUM'];
                    header("Location: approved.php?name=" . urlencode('Welcome '.$admin_name))   ;
                     exit();
                }

                // Display error message if no match is found in either table
                echo "<p style='color:red;'>Incorrect Username or Password</p>";
            }
            ?>
            <form action="" method="POST">
                <h1>Login</h1>  
                <div class="input-box">
                    <input type="text" name="username" placeholder="Parent or Admin Username" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="password" name="password" placeholder="Password" required>
                    <i class='bx bxs-lock-alt'></i>
                </div>

                <div class="remember-forgot">
                    <label><input type="checkbox">Remember me</label>
                    <a href="#">Forgot password?</a>
                </div>

                <button type="submit" name="save" class="btn">Login</button>

                <div class="register-link">
                    <p>Don't have an account? <a href="parent-registration.php">Register</a></p>
                </div>
            </form>
        </div>    
    </header>
</body>        
<footer>
</footer>
