<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="style.css" />
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body>
  <div class="sidebar">
    <div class="logo"></div>
    <ul class="menu">
        <li class="active"><a href="#"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
        <li><a href="#"><i class="fas fa-user"></i><span>Profile</span></a></li>
        <li><a href="#"><i class="fas fa-star"></i><span>Student Info</span></a></li>
        <li class="logout"><a href="#"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
    </ul>
  </div>
  
  <div class="main--content">
    <div class="header--wrapper">
        <div class="header--title">
            <span>Bus System</span>
            <h2>Dashboard</h2>
        </div>
        <div class="user--info">
            <img src="img/bus-stop3.jpg" alt="User Image" />
        </div>
    </div>

    <header>
    <div class="wrapper">
    <?php 
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;

    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';
    require 'config.php';

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
        $learner_num = mysqli_real_escape_string($connect, $_POST['learner_num']);
        $name = mysqli_real_escape_string($connect, $_POST['name']);
        $surname = mysqli_real_escape_string($connect, $_POST['surname']);
        $number = mysqli_real_escape_string($connect, $_POST['number']);
        $grade = mysqli_real_escape_string($connect, $_POST['grade']);
        $bus = mysqli_real_escape_string($connect, $_POST['bus']);
        $date = date("Y-m-d");
        
        $parent_num = isset($_GET['parent_num']) ? htmlspecialchars($_GET['parent_num']) : die("Error: parent number not set");
     //  $parent_name = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : die("Error: parent name not set");
        // Retrieve parent email
        $sql = "SELECT email FROM parent WHERE parent_num ='$parent_num'";
        $result2 = mysqli_query($connect, $sql);
        if (mysqli_num_rows($result2) > 0) {
            $row = mysqli_fetch_assoc($result2);
            $email = $row['email'];
        } else {
            echo "Email not found!";
            exit();
        }

        // Check bus capacity
        $queryZ = "SELECT COUNT(*) AS total_count FROM APPROVED_LIST WHERE BUS_NUM = '$bus' GROUP BY BUS_NUM";
        $queryX = "SELECT CAPACITY FROM bus WHERE BUS_NUM ='$bus'";
        $result = mysqli_query($connect, $queryZ);
        $result2 = mysqli_query($connect, $queryX);

        $total_count = $result ? mysqli_fetch_assoc($result)['total_count'] : 0;
        $capacity = $result2 ? mysqli_fetch_assoc($result2)['CAPACITY'] : 0;

        if ($total_count < $capacity) {
            $query = "
            START TRANSACTION;
            INSERT INTO LEARNER (LEARNER_NUM, NAME, SURNAME, CELLPHONE_NUM, GRADE, PARENT_NUM)
            VALUES ('$learner_num', '$name', '$surname', '$number', '$grade', '$parent_num');
            INSERT INTO APPROVED_LIST (LEARNER_NUM, NAME, SURNAME, BUS_NUM, APPROVED_DATE)
            VALUES ('$learner_num', '$name', '$surname', '$bus', '$date');
            COMMIT;
            ";

            if (mysqli_multi_query($connect, $query)) {
                do {
                    if ($result = mysqli_store_result($connect)) {
                        mysqli_free_result($result);
                    }
                } while (mysqli_next_result($connect));

                function sendMail($email, $subject, $message) {
                    $mail = new PHPMailer(true);
                    $mail->isSMTP();
                    $mail->SMTPAuth = true;
                    $mail->Host = MAILHOST;
                    $mail->Username = USERNAME;
                    $mail->Password = PASSWORD;
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;
                    $mail->setFrom(SEND_FROM, SEND_FROM_NAME);
                    $mail->addAddress($email);
                    $mail->addReplyTo(REPLY_TO, REPLY_TO_NAME);
                    $mail->isHTML(true);
                    $mail->Subject = $subject;
                    $mail->Body = $message;
                    $mail->AltBody = $message;
                    $mail->SMTPDebug = 2; 
                    return $mail->send();
                }

                if (sendMail($email, 'Registration Confirmation', 'You have been registered successfully.')) {
                    header("Location: intermediate.php?message=" . urlencode('An email confirmation has been sent to ' . $email) );
                    exit();
                } else {
                    echo "Error sending email.";
                }
            } else {
                echo "Error: " . mysqli_error($connect);
            }
        } else {
            // Add to waiting list if bus is full
            $query = "
            START TRANSACTION;
            INSERT INTO LEARNER (LEARNER_NUM, NAME, SURNAME, CELLPHONE_NUM, GRADE, PARENT_NUM)
            VALUES ('$learner_num', '$name', '$surname', '$number', '$grade', '$parent_num');
            INSERT INTO waiting_list (BUS_NUM, LEARNER_NUM, NAME, SURNAME, DATE_ADDED)
            VALUES ('$bus', '$learner_num', '$name', '$surname', '$date');
            COMMIT;
            ";

            if (mysqli_multi_query($connect, $query)) {
                do {
                    if ($result = mysqli_store_result($connect)) {
                        mysqli_free_result($result);
                    }
                } while (mysqli_next_result($connect));

                function sendMail($email, $subject, $message) {
                  $mail = new PHPMailer(true);
                  $mail->isSMTP();
                  $mail->SMTPAuth = true;
                  $mail->Host = MAILHOST;
                  $mail->Username = USERNAME;
                  $mail->Password = PASSWORD;
                  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                  $mail->Port = 587;
                  $mail->setFrom(SEND_FROM, SEND_FROM_NAME);
                  $mail->addAddress($email);
                  $mail->addReplyTo(REPLY_TO, REPLY_TO_NAME);
                  $mail->isHTML(true);
                  $mail->Subject = $subject;
                  $mail->Body = $message;
                  $mail->AltBody = $message;

                  return $mail->send();
              }

              if (sendMail($email, 'Registration Confirmation', 'Registration successful! Your child has been placed on the waiting list.')) {
                  header("Location: intermediate.php?message=" . urlencode('An email confirmation has been sent to ' . $email));
                  exit();
              } else {
                  echo "Error sending email.";
              }
          } else {
              echo "Error: " . mysqli_error($connect);
          }
            }
        }
    
    ?>
      <form id="survey-form" method="post" action="">
        <fieldset>
          <h1 id="title">Student Registration Form</h1>
          <p id="description">Please complete this form before or on 1 November 2024 if a learner wishes to register for bus transport in 2025.</p>
          <br>
          <label for="name">Student First Name
            <input id="name" name="name" type="text" placeholder="Enter your student first name..." required>
          </label>
          <label for="surname">Student Last Name
            <input id="surname" name="surname" type="text" required placeholder="Enter student last name...">
          </label>
          <label for="learner_num">Student Number
            <input id="learner_num" name="learner_num" type="text" required placeholder="Enter student number...">
          </label>
          <label for="number">Student Phone Number
            <input id="number" name="number" type="text" required placeholder="Enter student phone number...">
          </label>
          <label for="grade">Grade
            <input id="grade" name="grade" type="number" min="8" max="12" placeholder="Grade" required>
          </label>
          <label for="dropdown">Please Select Bus/Route
            <select name="bus" required>
              <option value="">Select</option>
              <option value="BUS_1">Bus 1</option>
              <option value="BUS_2">Bus 2</option>
              <option value="BUS_3">Bus 3</option>
            </select>
          </label>
        </fieldset>
        <fieldset>
          <label for="yes">Please Select Pick Up Point<br>
            <input type="radio" name="endorsement" id="yes" class="inline" value="yes" checked>A
          </label>
          <label for="no" class="inline">
            <input type="radio" name="endorsement" id="no" class="inline" value="no">B
          </label>
        </fieldset>
        <input type="submit" name="save" value="Submit">
      </form>
    </div>
    </header>
  </div>
</body>
</html>
