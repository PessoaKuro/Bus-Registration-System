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
        <li>
            <a href="#">
                <i class="fas fa-star"></i>
                <span>Student Info</span>
            </a>
        </li>
        <li class="logout">
            <a href="#">
                <i class="fas fa-sign-out-alt" ></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
  </div>  
  <div class="main--content">
    <div class="header--wrapper">
        <div class="header--title">
            <span>Bus System</span>
            <h2>Dashboard</h2>
        </div>
        <div class="user--info">
        
            
            <img src="img/bus-stop3.jpg" alt=""/>
        </div>
    </div>
    <body>
   <form id="survey-form">
 
      <fieldset>
         <h1 id="title">Registration Form</h1>
    <p id="description">Please complete this form.</p>
   <br><label for="name" id="fname-label">First Name
      <input id="fname" type="text" placeholder="Enter your first name. . ." required>
      </label>
      <label for="lname" id="lname-label">Last Name
      <input id="lname" type="text" required placeholder="Enter your last name. . .">
      </label>
      <label for="email" id="email-label">Student Number
      <input id="email" type="email" required placeholder="Enter your email address. . .">
      </label>
    </fieldset>
    
      <input type="submit" value="Submit" id="submit" />
    </form>
</body>
</html>