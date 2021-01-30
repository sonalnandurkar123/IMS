<?php
session_start();
include_once "conn.php";
if(isset($_SESSION['admin_username']))
{
  //header("Location:homepage");
}
else
{
  
}
  if(isset($_POST['login']))
  {
		  try {
		    $u=$_POST['username'];
		    $p=$_POST['password'];
            $stmt = $conn->prepare("SELECT * FROM login_details WHERE username=? and password=?");
            $stmt->bindValue(1,$u , PDO::PARAM_STR);
		    $stmt->bindValue(2,$p, PDO::PARAM_STR);
		    $stmt->execute();
		    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
  		    
     if($row)
     {
           $_SESSION["admin_username"]=$_POST['username'];
           //header("Location:dashboard");
           echo "<script>window.location.href='pages/dashboard';</script>";
     }
  }
		catch(PDOException $e) {
		    echo "Error: " . $e->getMessage();
		}
	}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Institute Management System</title>

    <!-- Bootstrap -->
    <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="vendors/animate.css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="build/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
            <form method="post" action="">
              <h1>Login Form</h1>
              <div>
                <input type="text" class="form-control" placeholder="Username" name="username" required="" />
              </div>
              <div>
                <input type="password" class="form-control" placeholder="Password" name="password" required="" />
              </div>
              <div>
				<button type="submit" class="btn btn-primary submit"  name="login" >LOGIN</button>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                
                <p>Develope By</p>
				<a href="www.softgrowthinfotech.com"><h3>Softgrowth Infotech</h3></a>
                <p></p>

                <div class="clearfix"></div>
                <br />

                <div>
                  <p>©<script>document.write(new Date().getFullYear());</script> All Rights Reserved.Privacy and Terms</p>
                </div>
              </div>
            </form>
          </section>
        </div>

        <div id="register" class="animate form registration_form">
          <section class="login_content">
            <form>
              <h1>Create Account</h1>
              <div>
                <input type="text" class="form-control" placeholder="Username" required="" />
              </div>
              <div>
                <input type="email" class="form-control" placeholder="Email" required="" />
              </div>
              <div>
                <input type="password" class="form-control" placeholder="Password" required="" />
              </div>
              <div>
                <a class="btn btn-default submit" href="pages/dashboard">Submit</a>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <p class="change_link">Already a member ?
                  <a href="#signin" class="to_register"> Log in </a>
                </p>

                <div class="clearfix"></div>
                <br/>
                <div>
                  <p>©<script>document.write(new Date().getFullYear());</script> All Rights Reserved.Privacy and Terms</p>
                </div>
              </div>
            </form>
          </section>
        </div>
      </div>
    </div>
  </body>
</html>
