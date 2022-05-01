<?php
session_start();
include('init.php');
if (isset($_SESSION['admin'])) {
    // include('includes/templates/navbar.php');
    $page = "All";
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = "All";
    }
    if ($page == "All") {
        $statment = $connection->prepare("SELECT posts.* , 
  users.username AS Username ,
  users.id AS USERID ,
  categories.title AS CateTitle ,
  categories.id AS CATEID

  FROM posts
  INNER JOIN users
  ON posts.user_id=users.id
  INNER JOIN categories
  ON posts.category_id=categories.id
  ");
        $statment->execute();
        $postcount = $statment->rowCount();
        $posts = $statment->fetchAll();
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">

            <h3 class="text-center  mt-3">Posts</h3>
            <div class="card mt-2 mb-3">
                <div class="card-header">
                    Posts Managment
                    <span class="badge badge-success"><?php echo $postcount; ?></span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover text-center">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Publisher</th>
                                    <th scope="col">Created_At</th>
                                    <th scope="col">Operations</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                        if ($postcount > 0) {
                                            foreach ($posts as $post) {
                                        ?>

                                <tr>
                                    <th scope="row"><?php echo $post['id']; ?></th>
                                    <!-- <td></td> -->
                                    <td>
                                        <?php
                                                        echo "<a target='_blank' href='uploads/posts/" . $post['image'] . "'>";
                                                        echo "<img style='width:60px;height:60px;border-radius:10px' src='uploads/posts/" . $post['image'] . "'>";
                                                        echo "</a>";
                                                        ?>
                                    </td>
                                    <td><?php echo $post['title'] ?></td>
                                    <td><?php echo $post['description'] ?></td>
                                    <td>
                                        <?php
                                                        if ($post['status'] == 0) {
                                                            echo '<span class="badge bg-danger">Hidden</span>';
                                                        } else {
                                                            echo '<span class="badge bg-info">Visible</span>';
                                                        }
                                                        ?>
                                    </td>
                                    <td><?php echo "<a href='categories.php?page=Show&&userid=" . $post['CATEID'] . "'>";
                                                        echo $post['CateTitle'];
                                                        echo "</a>";
                                                        $post['category_id']
                                                        ?>
                                    </td>
                                    <td><?php echo "<a href='users.php?page=Show&userid=" . $post['USERID'] . "'>";
                                                        echo $post['Username'];
                                                        echo "</a>";
                                                        ?>
                                    </td>
                                    <td><?php echo $post['created_at'] ?></td>
                                    <td>
                                        <a href="posts.php?page=Show&userid=<?php echo $post['id']; ?>"
                                            class="btn btn-outline-primary mb-2">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="posts.php?page=delete&userid=<?php echo $post['id']; ?>"
                                            class="btn btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>


                                <?php
                                            }
                                        }
                                        ?>


                            </tbody>
                        </table>
                    </div>
                    <a href="?page=AddPost" class="btn btn-success ml-5 mb-3  add">Add New Post</a>
                </div>
            </div>


        </div>
    </div>
</div>
<?php
    } elseif ($page == "Show") {
        $userid = 1;
        if (isset($_GET['userid']) && is_numeric($_GET['userid'])) {
            $userid = $_GET['userid'];
        } else {
            $userid = '';
        }
        $statment = $connection->prepare("SELECT * FROM posts WHERE id = ?");
        $statment->execute(array($userid));
        $postcount = $statment->rowCount();
        if ($postcount > 0) {
            $postInfo = $statment->fetch();
        }
    ?>
<h1 class="text-center  mt-2"> Edit Post</h1>
<div class="container">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <form action="?page=Update" class="mt-3 mb-5 f_add" method="post">
                <label>Title</label>
                <input type="text" name="title" class="form-control" value="<?php echo $postInfo['title']; ?>" />
                <br>
                <label>Description</label>
                <textarea name="description" id="" cols="5" rows="3"
                    class="form-control"><?php echo $postInfo['description']; ?></textarea>
                <br>
                <!-- <label>Post Image </label>
        <input type="file" name="uImage" class="form-control" value="<?php echo $postInfo['image']; ?>" /> -->
                <br>
                <label class="mr-2">Status</label>
                <input <?php
                                if ($postInfo['status'] === '0') {
                                    echo 'checked';
                                } else {
                                    echo '';
                                }
                                ?> type="radio" name="status" value="0">Hidden
                <input <?php
                                if ($postInfo['status'] === '1') {
                                    echo 'checked';
                                } else {
                                    echo '';
                                }
                                ?> type="radio" name="status" value="1">Visible
                <br>

                <label>Category</label>
                <select name="category_id" class="form-control">
                    <option readonly>--Choose category--</option>
                    <?php

                            $selCates = $connection->prepare('SELECT * FROM categories');
                            $selCates->execute();
                            $allCategories = $selCates->fetchAll();
                            foreach ($allCategories as $category) {
                                echo '<option value="' . $category['id'] . '"' . '>' . $category['title'] . '</option>';
                            }



                            ?>
                </select>
                <br>
                <label>Publisher</label>
                <select name="user_id" class="form-control">
                    <option readonly>--Choose user--</option>
                    <?php
                            $selUser = $connection->prepare('SELECT * FROM users');
                            $selUser->execute();
                            $allUsers = $selUser->fetchAll();
                            foreach ($allUsers as $user) {
                                echo '<option value="' . $user['id'] . '">' . $user['username'] . '</option>';
                            }

                            ?>

                </select><br>

                <input type="hidden" name="postid" value="<?php echo $postInfo['id']; ?>" />

                <input type="submit" class="btn save mt-2" name="save-user" value="Save" />
            </form>
        </div>
    </div>
</div>
<?php
    } elseif ($page == "delete") {
        $userid = 1;
        if (isset($_GET['userid']) && is_numeric($_GET['userid'])) {
            $userid = intval($_GET['userid']);
        } else {
            $userid = '';
        }
        $check = $connection->prepare("SELECT * FROM posts WHERE id = ?");
        $check->execute(array($userid));
        $rows = $check->rowCount();
        if ($rows > 0) {
            $statment = $connection->prepare("DELETE FROM posts WHERE id = ?");
            $statment->execute(array($userid));
            $delRow = $statment->rowCount();
            if ($delRow > 0) {
                echo "<div class='alert alert-danger m-auto w-50'>Post has been deleted successfully</div>";
                header("refresh:3;url=posts.php");
                exit();
            }
        }
    } elseif ($page == 'AddPost') {
    ?>
<div class="container">
    <h1 class="text-center">Add New Post</h1>
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <form action="?page=SavePost" class="mt-3 mb-5 f_add " method="post" enctype="multipart/form-data">
                <label>Title</label>
                <input type="text" name="title" class="form-control" placeholder="Enter the title..  " />
                <br>
                <label>Description</label>
                <textarea name="description" id="" cols="5" rows="2" class="form-control"
                    placeholder="Enter post description..  "></textarea>
                <br>
                <label>Post Image </label>
                <input type="file" name="postImage" class="form-control" />
                <br>
                <label class="mr-2">Status</label>
                <input type="radio" name="status" value="0" class="radio">Hidden
                <input type="radio" name="status" value="1">Visible
                <br>
                <label>Category</label>
                <select name="category_id" class="form-control">
                    <option readonly>--Choose category--</option>
                    <?php
                            $selCates = $connection->prepare('SELECT * FROM categories');
                            $selCates->execute();
                            $allCategories = $selCates->fetchAll();
                            foreach ($allCategories as $category) {
                                echo '<option value="' . $category['id'] . '">' . $category['title'] . '</option>';
                            }

                            ?>

                </select><br>
                <label>Publisher</label>
                <select name="user_id" class="form-control">
                    <option readonly>--Choose user--</option>
                    <?php
                            $selUser = $connection->prepare('SELECT * FROM users');
                            $selUser->execute();
                            $allUsers = $selUser->fetchAll();
                            foreach ($allUsers as $user) {
                                echo '<option value="' . $user['id'] . '">' . $user['username'] . '</option>';
                            }

                            ?>

                </select><br>
                <input type="submit" class="btn save" name="save-user" value="Save" />
            </form>

        </div>
    </div>

</div>
<?php
    } elseif ($page == 'SavePost') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['save-user'])) {
                $postFromErrors = array();
                $title = $_POST['title'];
                $description = $_POST['description'];
                $status = $_POST['status'];
                $user_id = $_POST['user_id'];
                $category_id = $_POST['category_id'];
                //image
                $imageName = $_FILES['postImage']['name']; //name
                $imageSize = $_FILES['postImage']['size']; //size
                $imageTmp = $_FILES['postImage']['tmp_name']; //temporary name
                $imageType = $_FILES['postImage']['type']; //type

                $imageExtension1 = explode('.', $imageName); //sperate
                $imageExtension2 = strtolower(end($imageExtension1));

                $allowedExtensions = array("jpeg", "jpg", "png", "gif", "svg");


                //validation
                if (!empty($title)) {
                    $title = filter_var($title, FILTER_SANITIZE_STRING);
                } else {
                    $postFromErrors[] = "Title is required";
                }
                if (!empty($description)) {
                    $description = filter_var($description, FILTER_SANITIZE_EMAIL);
                } else {
                    $postFromErrors[] = "Description is required";
                }
                if (!empty($user_id)) {
                    $user_id = $user_id;
                } else {
                    $postFromErrors[] = "UseId is required";
                }
                if (!empty($category_id)) {
                    $category_id = $category_id;
                } else {
                    $postFromErrors[] = "CategoryId is required";
                }

                if (!in_array($imageExtension2, $allowedExtensions)) {
                    $postFromErrors[] = "this extension is not allowed to upload";
                }

                if ($imageSize > 1048576) {
                    $postFromErrors[] = "this image is greater than 1MG";
                }
                // check the errors
                if (empty($postFromErrors)) {
                    $finalImage = rand(0, 10000) . "_" . $imageName;
                    move_uploaded_file($imageTmp, "uploads/posts/" . $finalImage);
                    $stmt = $connection->prepare('INSERT INTO posts(`title` , `description` ,`image`, `status` , `user_id` , `category_id` , `created_at`)
          VALUES (:ztitle , :zdescription , :zimage , :zstatus , :zuser_id , :zcategory_id , now())
          ');
                    $stmt->execute(array(
                        'ztitle' => $title,
                        'zdescription' => $description,
                        'zimage' => $finalImage,
                        'zstatus' => $status,
                        'zuser_id' => $user_id,
                        'zcategory_id' => $category_id
                    ));

                    if ($stmt->rowCount() > 0) {
                        echo "<div class='alert alert-success m-auto w-50'>Post has been Created successfully</div>";
                        header("refresh:3;url=posts.php");
                        exit();
                    }
                } else {
                    echo 'There are errors';
                    print_r($postFromErrors);
                }
            }
        }
    } elseif ($page == 'Update') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $postid = $_POST['postid'];
            $status = $_POST['status'];

            $category_id = $_POST['category_id'];
            $user_id = $_POST['user_id'];


            $updateStmt = $connection->prepare('UPDATE posts SET `title`= ?,`description`= ?,`status`= ?,`category_id`=?,`user_id`=?,`updated_at`= now() WHERE id= ?');
            $updateStmt->execute(array($title, $description, $status, $category_id, $user_id,  $postid));
            $updateRow = $updateStmt->rowCount();
            if ($updateRow > 0) {
                echo "<div class='alert alert-success m-auto w-50'>Post has been Updated successfully</div>";
                header("refresh:3;url=posts.php");
                exit();
            }
        }
    }
    ?>

<?php
    include('includes/templates/footer.php');
} else {
    echo "<div class='alert alert-danger w-50  text-center m-auto mt-5' role='alert'>You are not Authenticated</div>";
    header('refresh:3;url=login.php');
}
?>
<br><br><br>