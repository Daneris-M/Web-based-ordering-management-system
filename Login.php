<?php     
  $page = 'notLogin';
  include('method/checkIfAccountLoggedIn.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Log in</title>

  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
  <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script>  
  <script type="text/javascript" src="js/bootstrap.min.js"></script>

</head>
<body class="bg-light">

<div class="container">
  <div class="row justify-content-center">
    <form  method="post" class="form1">
      <h1 class="font-weight-normal mt-5 mb-4 text-center">Log in to your account</h1>
      <input class="form-control form-control-lg mb-3" type="text" name="username" placeholder="Username" required>
      <input class="form-control form-control-lg mb-3" type="password" name="password" placeholder="Password" required>
      <div class="mb-3">
        <a href="forgetPassword.php" class="pass text-muted">Forgot Password?</a>
      </div>
      <button class="btn btn-primary btn-lg col-12 mb-3" type="submit" name="Login" value="Login">Login</button>
      <div class="text-center text-muted mb-5">
        Dont'have an account yet? <a href="register.php" class="signup_link text-muted">Sign up</a>
      </div>
    </form>

    <!-- otp (Bootstrap MODAL) -->
    <div class="modal fade" id="otpModal" role="dialog" >
      <div class="modal-dialog">
        <div class="modal-content container">
          <div class="modal-body">
            <form method="post" class="form-group">
              <h3 class="font-weight-normal mb-3">Please Enter Your OTP</h3>
              <input type="text" class="form-control form-control-lg mb-3" placeholder="OTP" name="otp" >  
              <input type="submit" value="Verify" name="Verify" class="btn btn-success btn-lg col-12 mb-1">
              <!-- <input type="submit" value="Resend" name="Resend" class="btn btn-secondary btn-lg col-12 mb-1"> -->
              <input data-dismiss="modal" type="submit" value="Cancel" name="Cancel" class="btn btn-danger btn-lg col-12 mb-1">
            </form>
          </div>
        </div>
      </div>
    </div>

    <?php
        include_once('connection.php');
        include('method/query.php');
        if(isset($_POST['Login'])){
            $_SESSION["username"]  = $_POST['username'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            //user block
            $query = "select * from WEBOMS_user_tb where username = '$username'";
            $resultSet = getQuery($query);
            if(($resultSet && $resultSet->num_rows)  > 0){
                foreach($resultSet as $row){
                    $valid = password_verify($password, $row['password'])?true:false;
                    $accountType = $row['accountType'];
                    $user_id = $row['user_id'];
                }
                if($valid){
                  //setting credential if valid
                  $query = "select * from WEBOMS_userInfo_tb where user_id = '$user_id'";
                  $resultSet = getQuery($query);
                  foreach($resultSet as $row){
                    $_SESSION['name'] = $row['name'];
                    $otp = $row['otp'];
                  }
                  $_SESSION['user_id'] = $user_id;
                  $_SESSION['accountType'] = $accountType;
                  $_SESSION['username'] = $username;
                  $_SESSION['account'] = 'valid';
                  switch($accountType){
                    case ('admin'):
                      echo "<script> window.location.replace('admin.php');</script>";
                    break;

                    case ('manager'):
                      echo "<script> window.location.replace('admin.php');</script>";
                    break;

                    case 'cashier';
                      echo "<script> window.location.replace('adminPos.php');</script>";
                    break;

                    case 'customer':
                      //if customer account is valid
                      if($valid && $otp == ''){
                        echo "<SCRIPT> window.location.replace('customer.php');  </SCRIPT>";
                      }
                      //if customer account need to validate first via otp
                      else if($valid && $otp != ""){
                        echo "<script>$('#otpModal').modal('show');</script>";
                      }
                      //if customer password is wrong
                      else{
                        echo "<script>alert('incorrect username or password!');</script>";
                      }
                    break;
                  }
                }
                else{
                    echo "<script>alert('incorrect username or password!');</script>";
                }
            }
            else{
                echo "<script>alert('incorrect username or password!');</script>";
            }
        }
        if(isset($_POST['Verify'])){
            $username = $_SESSION["username"];
            $otp = $_POST['otp'];
            $user_id = $_SESSION['user_id'];
            $query = "select * from WEBOMS_userInfo_tb where user_id = '$user_id' && otp = '$otp' ";
            $resultSet = getQuery($query);
            if($resultSet != null){
                $updateQuery = "UPDATE WEBOMS_userInfo_tb SET otp='' WHERE otp='$otp'";
                if(Query($updateQuery)){
                    echo "<SCRIPT> window.location.replace('customer.php'); </SCRIPT>";
                    $_SESSION['username'] = $username;
                    $_SESSION['account'] = 'valid';
                    $_SESSION['accountType'] = 'customer';
                }
            }
            else
              echo  '<script>alert("Incorrect Otp!"); </script>';
        }
    ?>
</body>
</html>