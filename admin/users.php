<?php
session_start();
include("init.php");
if (isset($_SESSION['admin'])) {
  // set a default of variable page and if it has a value it will be stored in it
  if (isset($_GET['page'])) {
    $page = $_GET['page'];
  } else {
    $page = "All";
  }
  //hold all data of table users 
  $statement = $connection->prepare("SELECT * FROM users WHERE id != ?");
  $statement->execute(array($_SESSION['admin_id']));
  $usercount = $statement->rowcount();
  $clients = $statement->fetchAll();
?>

  <!-- make a card or table to show data -->
  <div class="card mt-5">
    <div class="card-header text-light text-center">
      user Mangment<span class="badge badge-primary"><?php echo $usercount ?></span>
    </div>
    <div class="card-body">
      <a href="?page=adduser" class="btn btn-danger mb-3">Add new users</a>
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <?php
            if ($page == "All") {
            ?>
              <table class="table table-dark table-hover table-striped table-bordered text-center">
                <thead>
                  <tr>
                    <th scope="col">id</th>
                    <th scope="col">username</th>
                    <th scope="col">email</th>
                    <th scope="col">status</th>
                    <th scope="col">role</th>
                    <th scope="col">operation</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  if ($usercount > 0) { //check
                    foreach ($clients as $client) {
                  ?>

                      <tr>
                        <th scope="row"><?php echo $client['id'] ?></th>
                        <td><?php echo $client['username'] ?></td>
                        <td><?php echo $client['email'] ?></td>
                        <td><?php if ($client['status'] == 0) {
                              echo "<span class='badge bg-danger'>pending</span>";
                            } else {
                              echo "<span class='badge bg-info'>Approved</span>";
                            } ?></td>
                        <td><?php echo $client['role'] ?></td>
                        <td><a href="?page=showuser&userid=<?php echo $client['id'] ?>" class=" btn btn-primary">
                            <i class="fas fa-eye"></i></a>
                          <a href="?page=delete&userid=<?php echo $client['id'] ?>" class="btn btn-danger"> <i class="fas fa-trash"></i></a>
                        </td>
                      </tr>
                  <?php
                    }
                  }
                  ?>

                </tbody>
              </table>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php
            } elseif ($page == "showuser") {
              if (isset($_GET['userid']) && is_numeric($_GET['userid'])) {
                $userid = intval($_GET['userid']);
              } else {
                $userid = '';
              }
              // check if the user exist in DB
              $check = $connection->prepare("SELECT * FROM users WHERE id = ?");
              $check->execute(array($userid));
              $rows = $check->rowcount();
              if ($rows > 0) {
                $userinfo = $check->fetch();
              }

?>
  <h2> Edit user</h2>

  <form method="post" action="?page=updateduser">
    <div class="form-group">
      <label for="exampleInputEmail1">username</label>
      <input name="username" value="<?php echo $userinfo['username'] ?>" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
    </div>
    <div class="form-group">
      <label for="exampleInputEmail1">Email </label>
      <input name="email" type="email" class="form-control" value="<?php echo $userinfo['email'] ?>" id="
            exampleInputEmail1" aria-describedby="emailHelp">
    </div>
    <div class="form-group">
      <label for="exampleInputPassword1">Password</label>
      <input name="password" type="password" class="form-control" value="<?php echo $userinfo['password'] ?>" id="
            exampleInputPassword1">
    </div>
    <input type="hidden" name="userid" value="<?php echo $userinfo['id'] ?>">
    <div class="form-group">
      <label for="exampleInputPassword1">Role</label>
      <select name="role" class="form-control">
        <option readonly>--chooose role </option>
        <option <?php if ($userinfo['role'] === 'admin') {
                  echo "selected";
                } else {
                  echo "";
                } ?>value="admin"> admin</option>
        <option <?php if ($userinfo['role'] === 'user') {
                  echo "selected";
                } else {
                  echo "";
                } ?> value="user"> user</option>
      </select>
    </div>
    <div class="form-group">
      <label for="exampleInputPassword1">Status</label>
      <input <?php if ($userinfo['status'] === '0') {
                echo "checked";
              } else {
                echo "";
              } ?> type="radio" name="status" value="0">pending

      <input <?php if ($userinfo['status'] === '1') {
                echo "checked";
              } else {
                echo "";
              } ?> type="radio" name="status" value="1">Approved
      <button type="submit" class="btn btn-primary">Save</button>
  </form>
<?php
            } elseif ($page == 'updateduser') {
              if ($_SERVER['REQUEST_METHOD'] == "POST") {
                $usernameErr = $emailErr = $passwordErr = $roleErr = "";
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                $hasedpassword = sha1($password);
                $role = $_POST['role'];
                $Userid = $_POST['userid'];
                $status = $_POST['status'];



                $update_statment = $connection->prepare('UPDATE users SET `username`= ?, `email`= ?,
    `password`=?,`status`= ?, `role`= ?,`updated_at`=now() WHERE id =?');
                $update_statment->execute(array(
                  $username, $email, $hasedpassword, $status, $role,
                  $_POST['userid']
                ));

                if ($update_statment->rowcount() > 0) {
                  echo '<div class="alert alert-success" role="alert">
  user has been updated 
</div>';
                  header('refresh:3;url=users.php');
                  exit();
                } else {
                  echo "you have error";
                }
              }
            }



            // crud operation delete
            elseif ($page == "delete") {
              if (isset($_GET['userid']) && is_numeric($_GET['userid'])) {
                $userid = intval($_GET['userid']);
              } else {
                $userid = '';
              }
              // check if the user exists in DB
              $statement = $connection->prepare("SELECT * FROM users WHERE id = ?");
              $statement->execute(array($userid));
              $rows_users = $statement->rowcount();
              // ok it's already is exits so you can delete

              if ($rows_users > 0) {
                $del_statement = $connection->prepare("DELETE FROM users WHERE id = ?");
                $del_statement->execute(array($userid));
                $del_Row = $statement->rowcount();
                if ($del_Row > 0) {
                  echo "<div class='alert alert-danger' role='alert'>user has been deleted</div>";
                  header('refresh:3;url=users.php');
                  exit();
                }
              }
            } elseif ($page == "adduser") {
?> <h2>Add New user</h2>
  <form method="post" action="?page=saveuser">
    <div class="form-group">
      <label for="exampleInputEmail1">username</label>
      <input name="username" placeholder="Enter Valid Username.." type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
    </div>
    <div class="form-group">
      <label for="exampleInputEmail1">Email </label>
      <input name="email" placeholder="Enter Valid Email.." type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
    </div>
    <div class="form-group">
      <label for="exampleInputPassword1">Password</label>
      <input name="password" type="password" placeholder="Enter Valid Password.." class="form-control" id="exampleInputPassword1">
    </div>

    <div class="form-group">
      <label for="exampleInputPassword1">Role</label>
      <select name="role" class="form-control">
        <option readonly>--chooose role </option>
        <option value="admin"> admin</option>
        <option value="user"> user</option>
      </select>
    </div>
    
    <button type="submit" name="save-user" class="btn btn-primary">Save</button>
  </form>
<?php
            } elseif ($page == "saveuser") {
              if ($_SERVER['REQUEST_METHOD'] == "POST") {
                $usernameErr = $emailErr = $passwordErr = $roleErr = "";
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                $hasedpassword = sha1($password);
                $role = $_POST['role'];
              }
              // validation data
              if (!empty($username)) {
                $username = filter_var($username, FILTER_SANITIZE_STRING);
              } else {
                $usernameErr = "username is required";
              }

              if (!empty($email)) {
                $email = filter_var($email, FILTER_SANITIZE_EMAIL);
              } else {
                $emailErr = "Email is required";
              }

              if (!empty($password)) {
                $email = filter_var($email, FILTER_SANITIZE_STRING);
              } else {
                $passwordErr = "Password is required";
              }


              if (!empty($role)) {
                $role = filter_var($role, FILTER_SANITIZE_STRING);
              } else {
                $roleErr = "role is required";
              }
              if (empty($usernameErr) && empty($emailErr) && empty($passwordErr) && empty($roleErr)) {
                $statement = $connection->prepare('INSERT INTO users ( `username`, `email`,
 `password`,`status`, `role`) VALUES (?,?,?,?,?)');
                $statement->execute(array(
                  $username,
                  $email,
                  $hasedpassword,
                  '0',
                  $role
                ));
                if ($statement->rowcount() > 0) {
                  echo '<div class="alert alert-success" role="alert">
user added successfully</div>';
                  header('refresh:3;url=users.php');
                  exit();
                }
              } else {
                echo 'there are errors';
              }
            }
?>


<?php
}

include("includes/templates/footer.php");

?>