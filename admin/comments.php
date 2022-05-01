<?php
session_start();
include("init.php");
if (isset($_SESSION['admin'])) {

    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = "All";
    }
    //hold all data of table users 
    $statement = $connection->prepare("SELECT * FROM comments");


    $statement->execute();
    $comment_count = $statement->rowcount();
    $clients = $statement->fetchAll();

?>



<div class="card mt-5">
    <div class="card-header">
        user Mangment<span class="badge badge-primary"><?php echo $comment_count ?></span>
    </div>
    <div class="container">
        <div class="row">
            <div class="card-body">
                <a href="?page=add_comment" class="btn btn-danger mb-3">Add new comment</a>

                <?php
                    if ($page == "All") {
                    ?>
                <div class="col-md-12">
                    <table class="table table-dark table-hover table-striped table-bordered text-center">
                        <thead>


                            <tr>
                                <th scope="col">id</th>
                                <th scope="col">comment</th>
                                <th scope="col">status</th>
                                <th scope="col">user_id</th>
                                <th scope="col">post_id</th>
                                <th scope="col">operation</th>
                            </tr>

                        </thead>
                        <tbody>
                            <?php
                                    if ($comment_count > 0) {
                                        foreach ($clients as $client) {
                                    ?>
                            <tr>
                                <th scope="row"><?php echo $client['id'] ?></th>
                                <td><?php echo $client['comment'] ?></td>
                                <td><?php if ($client['status'] == 0) {
                                                        echo "<span class='badge bg-danger'>pending</span>";
                                                    } else {
                                                        echo "<span class='badge bg-info'>Approved</span>";
                                                    } ?></td>
                                <td><?php echo $client['user_id'] ?></td>
                                <td><?php echo $client['post_id'] ?></td>
                                <td><a href="?page=show_comment&userid=<?php echo $client['id'] ?>"
                                        class=" btn btn-primary">
                                        <i class="fas fa-eye"></i></a>
                                    <a href="?page=del_comment&userid=<?php echo $client['id'] ?>"
                                        class="btn btn-danger">
                                        <i class="fas fa-trash"></i></a>
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
                    // crud operation delete
                    elseif ($page == "del_comment") {
                        if (isset($_GET['userid']) && is_numeric($_GET['userid'])) {
                            $userid = intval($_GET['userid']);
                        } else {
                            $userid = '';
                        }
                        // check if the user exists in DB
                        $statement = $connection->prepare("SELECT * FROM users WHERE id = ?");
                        $statement->execute(array($userid));
                        $rows = $statement->rowcount();
                        // ok it's already is exits so you can delete

                        if ($rows > 0) {
                            $del_statement = $connection->prepare("DELETE FROM users WHERE id = ?");
                            $del_statement->execute(array($userid));
                            $del_Row = $statement->rowcount();
                            if ($del_Row > 0) {
                                echo "<div class='alert alert-danger' role='alert'>comment has been deleted successfully</div>";
                                header('refresh:3;url=comments.php');
                                exit();
                            }
                        }
                    }

?>
<?php
}
include("includes/templates/footer.php");
?>