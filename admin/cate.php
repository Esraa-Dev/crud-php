<?php
session_start();
include("init.php");
if (isset($_SESSION['admin'])) {

    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = "All";
    }
    $statement = $connection->prepare("SELECT * FROM categories");
    $statement->execute();
    $catcount = $statement->rowcount();
    $clients = $statement->fetchAll();
?>



<div class="card mt-5">

    <div class="card-header text-center text-light">
        catgory Mangment<span class="badge badge-primary"><?php echo $catcount ?></span>
    </div>
    <div class="container">
        <div class="row">
            <div class="card-body">
                <a href="?page=addcate" class="btn btn-danger mb-3">add new category</a>
                <?php
                    if ($page == "All") {
                    ?>

                <div class="col-md-12">
                    <table class="table table-dark table-hover table-striped table-bordered text-center">
                        <thead>


                            <tr>
                                <th scope="col">id</th>
                                <th scope="col">title</th>
                                <th scope="col">description</th>
                                <th scope="col">status</th>
                                <th scope="col">operation</th>
                            </tr>

                        </thead>
                        <tbody>
                            <?php
                                    if ($catcount > 0) {
                                        foreach ($clients as $client) {
                                    ?>
                            <tr>
                                <th scope="row"><?php echo $client['id'] ?></th>
                                <td><?php echo $client['title'] ?></td>
                                <td><?php echo $client['description'] ?></td>
                                <td><?php if ($client['status'] == 0) {
                                                        echo "<span class='badge bg-danger'>pending</span>";
                                                    } else {
                                                        echo "<span class='badge bg-info'>Approved</span>";
                                                    } ?></td>

                                <td><a href="?page=showcat&userid=<?php echo $client['id'] ?>" class=" btn btn-primary">
                                        <i class="fas fa-eye"></i></a>
                                    <a href="?page=delcat&userid=<?php echo $client['id'] ?>" class="btn btn-danger">
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
                    elseif ($page == "delcat") {
                        if (isset($_GET['userid']) && is_numeric($_GET['userid'])) {
                            $userid = intval($_GET['userid']);
                        } else {
                            $userid = '';
                        }
                        // check if the user exists in DB
                        $statement = $connection->prepare("SELECT * FROM categories WHERE id = ?");
                        $statement->execute(array($userid));
                        $rows_cate = $statement->rowcount();
                        // ok it's already is exits so you can delete
                        echo $rows_cate;
                        if ($rows_cate > 0) {
                            $del_statement = $connection->prepare("DELETE FROM categories WHERE id = ?");
                            $del_statement->execute(array($userid));
                            $del_Row = $statement->rowcount();
                            if ($del_Row > 0) {
                                echo "<div class='alert alert-danger' role='alert'>user has been deleted</div>";
                                header('refresh:3;url=cate.php');
                                exit();
                            }
                        }
                    } elseif ($page == "addcate") { ?>
<h2>Add New category</h2>
<form method="post" action="?page=savecate">
    <div class="form-group">
        <label for="exampleInputEmail1">title</label>
        <input name="title" placeholder="Enter Valid Title.." type=" text" class="form-control" id="exampleInputEmail1"
            aria-describedby="emailHelp">
    </div>
    <div class="form-group">
        <label for="exampleFormControlTextarea1">description</label>
        <textarea name="desc" class="form-control" placeholder="Enter Valid description.."
            id="exampleFormControlTextarea1" rows="3"></textarea>
    </div>


    <div class="form-group">
        <label for="exampleInputPassword1">status</label>
        <input type="radio" name="status" value="admin">admin
        <input type="radio" name="status" value="user">user
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
</form>
<?php
                    } elseif ($page == "savecate") {
                        if ($_SERVER['REQUEST_METHOD'] == "POST") {
                            $titleErr = $descErr = $statusErr = "";
                            $title = $_POST['title'];
                            $desc = $_POST['desc'];
                            $status = $_POST['status'];
                        }
                        // validation data
                        if (!empty($title)) {
                            $title = filter_var($title, FILTER_SANITIZE_STRING);
                        } else {
                            $titleErr = "title is required";
                        }

                        if (!empty($desc)) {
                            $desc = filter_var($desc, FILTER_SANITIZE_STRING);
                        } else {
                            $descErr = "description is required";
                        }

                        if (!empty($status)) {
                            $status = filter_var($status, FILTER_SANITIZE_STRING);
                        } else {
                            $statusErr = "role is required";
                        }

                        if (empty($titleErr) && empty($descErr) && empty($statusErr)) {
                            $statement = $connection->prepare('INSERT INTO categories ( `title`, `description`,`status`) VALUES (?, ?,?)');
                            $statement->execute(array(
                                $title,
                                $desc,
                                $status
                            ));
                            if ($statement->rowcount() > 0) {
                                echo '<div class="alert alert-success" role="alert">
  category updated successfully!
</div>';
                                header('refresh:3;url=cate.php');
                                exit();
                            }
                        } else {
                            echo 'there are errors';
                        }
                    }
                    // END
                    elseif ($page == "showcat") {
                        if (isset($_GET['userid']) && is_numeric($_GET['userid'])) {
                            $userid = intval($_GET['userid']);
                        } else {
                            $userid = '';
                        }
                        // check if the user exist in DB
                        $check = $connection->prepare("SELECT * FROM categories WHERE id = ?");
                        $check->execute(array($userid));
                        $rows = $check->rowcount();
                        if ($rows > 0) {
                            $catinfo = $check->fetch();
                        }

?>
<h2> Edit user</h2>

<form method="post" action="?page=updateduser">
    <div class="form-group">
        <label for="exampleInputEmail1">title</label>
        <input name="title" value="<?php echo $catinfo['title'] ?>" type="text" class="form-control"
            id="exampleInputEmail1" aria-describedby="emailHelp">
    </div>
    <div class="form-group">
        <label for="exampleInputEmail1">description</label>
        <input name="desc" type="text" class="form-control" value="<?php echo $catinfo['description'] ?>" id="
            exampleInputEmail1" aria-describedby="emailHelp">
    </div>
    <input type="hidden" name="userid" value="<?php echo $catinfo['id'] ?>">

    <div class="form-group">
        <label for="exampleInputPassword1">Status</label>
        <input <?php if ($catinfo['status'] === '0') {
                            echo "checked";
                        } else {
                            echo "";
                        } ?> type="radio" name="status" value="0">pending

        <input <?php if ($catinfo['status'] === '1') {
                            echo "checked";
                        } else {
                            echo "";
                        } ?> type="radio" name="status" value="1">Approved
        <button type="submit" class="btn btn-primary">Save</button>
</form>
<?php
                    } elseif ($page == 'updateduser') {
                        if ($_SERVER['REQUEST_METHOD'] == "POST") {
                            $titleErr = $descErr = $statusErr = "";
                            $title = $_POST['title'];
                            $description = $_POST['desc'];
                            $status = $_POST['status'];
                        $Userid = $_POST['userid'];




                            $update_statment = $connection->prepare('UPDATE categories SET `title`= ?, `description`= ?,`status`=?
     WHERE id =?');
                            $update_statment->execute(array(
                                $title, $description, $status, $_POST['userid']

                            ));

                            if ($update_statment->rowcount() > 0) {
                                echo '<div class="alert alert-success" role="alert">
  category has been updated 
</div>';
                                header('refresh:3;url=cate.php');
                                exit();
                            } else {
                                echo "you have error";
                            }
                        }
                    }


?>
<?php
}
include("includes/templates/footer.php");
?>
<?php
//                    