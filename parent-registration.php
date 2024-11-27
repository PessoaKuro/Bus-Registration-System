<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Registration</title>
    <link rel="stylesheet" href="style2.css" />
    <!--Font Awesome CDN link-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
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

      // Check if the form is submitted
      if (isset($_POST['save'])) {
        $parent_num = mysqli_real_escape_string($connect, $_POST['parent_num']);
        $name = mysqli_real_escape_string($connect, $_POST['name']);
        $surname = mysqli_real_escape_string($connect, $_POST['surname']);
        $email = mysqli_real_escape_string($connect, $_POST['email']);
        $number = mysqli_real_escape_string($connect, $_POST['number']);
        $password = mysqli_real_escape_string($connect, $_POST['password']);
        $cpassword = mysqli_real_escape_string($connect, $_POST['cpassword']);

       

        // Add the credentials to the parent table
        if ($password == $cpassword) {
            $query = "INSERT INTO parent (PARENT_NUM, NAME, SURNAME, PASSWORD, CELLPHONE_NUMBER, EMAIL)
                      VALUES ('$parent_num', '$name', '$surname', '$password', '$number', '$email')";

            if (mysqli_query($connect, $query)) {
            
              // Email setup
            $to = $email;
            $subject = 'Registration Successful';
            $message = "Hello $name,\n\nThank you for registering with us. Your registration was successful.\n\nBest regards,\nOnline Bus Registration Team";
            $headers = "From: lwando.jay@gmail.com\r\n";
            $headers .= "Reply-To: support@onlinebusregistration.com\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();  
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/plain; charset=UTF-8\r\n";          }
             else {
                echo "Error: " . mysqli_error($connect);
            }
            if (mail($to, $subject, $message, $headers)) {
              // Redirect with a success message
              header("Location: intermediate.php?message=" . urlencode('An email confirmation has been sent to ' . $email));
              exit();
          } else {
              // Redirect with an error message
              header("Location: intermediate.php?message=" . urlencode('Failed to send the email. Please check your mail configuration.'));
              exit();
          }
          

        } else {
            echo "Passwords do not match.";
        }
      }
      ?>
      <form action="" method="POST">
        <h1>Registration Form</h1>
        <p id="description">Please complete this form.</p><br>
        <label class="input-box" for="parent_num">Parent Number
          <input id="parent_num" type="text" name="parent_num" placeholder="Enter your parent number..." required>
        </label><br><br> 
        <label class="input-box" for="name">First Name
          <input id="fname" type="text" name="name" placeholder="Enter your first name..." required>
        </label><br><br>
        <label class="input-box" for="surname">Last Name
          <input id="lname" type="text" name="surname" placeholder="Enter your last name..." required>
        </label><br><br>
        <label class="input-box" for="email">Email Address
          <input id="email" type="email" name="email" placeholder="Enter your email address..." required>
        </label><br><br>
        <label class="input-box" for="number">Phone Number
          <input id="number" type="text" name="number" placeholder="Enter your phone number..." required>
        </label><br><br>
        <label class="input-box" for="password">Password
          <input id="password" type="password" name="password" placeholder="Enter your password..." required>
        </label><br><br>
        <label class="input-box" for="cpassword">Confirm Password
          <input id="cpassword" type="password" name="cpassword" placeholder="Confirm your password..." required>
        </label><br><br>
        <button type="submit" name="save" class="btn">Register</button>
      </form>
    </div>
  </header>
</body>
</html>
