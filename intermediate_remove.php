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
  
      // Check if the message is passed in the URL
      if (isset($_GET['message'])) {
          echo "<p>" . htmlspecialchars($_GET['message']) . "</p>";
      }

      
      ?>
      <form action="" method="POST">
        <a href="approved.php">Home</a>

      </form>
    </div>
  </header>
</body>
</html>
