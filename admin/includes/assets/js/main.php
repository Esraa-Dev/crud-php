<?php
session_start();
include("init.php");
if (isset($_SESSION['admin'])) {
  if (isset($_GET['page'])) {
    $page = $_GET['page'];
  } else {
    $page = "All";
  }
  $statement = $connection->prepare("SELECT * FROM users WHERE id != ?");
  $statement->execute(array($_SESSION['admin_id']));
  $usercount = $statement->rowcount();
  $clients = $statement->fetchAll();
?>



<div class="card mt-5">
    <div class="card-header">
        <?php
      if ($page == "All") {
      ?>
        user Mangment<span class="badge badge-primary"><?php echo $usercount ?></span>
    </div>
    <div class="card-body">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
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
                if ($usercount > 0) {
                  foreach ($clients as $client) {
                ?>
                            <tr>
                                <th scope="row"><?php echo $client['id'] ?></th>
                                <td><?php echo $client['username'] ?></td>
                                <td><?php echo $client['email'] ?></td>
                                <td><?php if($client['status']==0){
                                  echo "<span class='badge bg-danger'>pending</span>";
                                }
                                else{
                                      echo "<span class='badge bg-info'>Approved</span>";

                                } ?></td>
                                <td><?php echo $client['role'] ?></td>
                                <td><a href="?page=showusers&userid=<?php echo $client['id']?>"
                                        class=" btn btn-primary">
                                        <i class="fas fa-eye"></i></a>
                                    <a href="?page=delete&userid=<?php echo $client['id']?>" class="btn btn-danger"> <i
                                            class="fas fa-trash"></i></a>
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
      }
      // elseif(){
        
      // }
?>


<?php
 include("includes/templates/footer.php");
?>