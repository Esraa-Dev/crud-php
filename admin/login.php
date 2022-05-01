<?php
session_start();
include("init.php");
if($_SERVER['REQUEST_METHOD'] == "POST"){
if(isset($_POST['admin-login'])){

  $password=$_POST['password'] ; 
    $email=$_POST['email'] ;
    $hashedpassword=sha1($password);

    //check if there is user has data like that in DB
$check = $connection-> prepare
('SELECT * FROM users WHERE `email` = ? And `password` = ?');
 $check-> execute(array($email,$hashedpassword));
$checkrow =$check-> rowcount(); 

 if ($checkrow > 0 ){ 
    // the person exists in database if this condition true
    $fetchData=$check->fetch();
    // check if the person is admin

    if($fetchData['role'] =='admin'){
        $_SESSION['admin']=$fetchData['username'];
         $_SESSION['admin_id']=$fetchData['id'];
         header('location:dashboard.php');
                  exit(); 
    }
         else{
             echo 'sorry your are not admin';
         }
}
else{
    echo "this user doesn't exist in DB";
}




}
}
?>


<div class="admin-login-page">
    <div class="container">
        <h1 class="text-center text-light">Admin Login</h1>
        <div class="row">
            <form class="col-md-12 col-md-offset-2" method="post"
                action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
                <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input type="email" name="email" class="form-control" id="exampleInputEmail1"
                        aria-describedby="emailHelp">
                    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone
                        else.</small>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" name="password" class="form-control" id="exampleInputPassword1">
                </div>

                <button type="submit" name="admin-login" class="btn btn-primary">Submit</button>
            </form>
        </div>

    </div>



</div>









<?php

include('includes/templates/footer.php');
?>