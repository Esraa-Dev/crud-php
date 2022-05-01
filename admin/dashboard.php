<?php
session_start();
include("init.php");
if(isset($_SESSION['admin'])){ 
$q1=$connection->prepare("SELECT * FROM users");
$q1->execute();
$usercount=$q1 -> rowcount();

$q2=$connection ->prepare("SELECT * FROM categories");
$q2 -> execute();
$catecount=$q2 -> rowcount();

$q3=$connection ->prepare("SELECT * FROM comments");
$q3 -> execute();
$comcount=$q3 -> rowcount();

$q4=$connection ->prepare("SELECT * FROM posts");
$q4 -> execute();
$postcount=$q4 -> rowcount();
?>

<div class="static mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="box">
                    <i class="fas fa-users"></i>
                    <h4>users</h4>
                    <span> <?php echo $usercount ?></span>
                    <br> <a href="users.php" class="btn btn-primary">show</a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="box">
                    <i class="fas fa-shapes"></i>
                    <h4>categories</h4>
                    <span><?php echo $catecount ?></span>
                    <br> <a href="cate.php" class="btn btn-primary">show</a>

                </div>
            </div>
            <div class="col-md-3">
                <div class="box">
                    <i class="fas fa-comments"></i>
                    <h4>comments</h4>
                    <span> <?php echo $comcount ?></span>
                    <br> <a href="comments.php" class="btn btn-primary">show</a>

                </div>
            </div>
            <div class="col-md-3">
                <div class="box">
                    <i class="fas fa-address-card"></i>
                    <h4>posts</h4>
                    <span> <?php echo $postcount ?></span>
                    <br> <a href="posts.php" class="btn btn-primary">show</a>

                </div>
            </div>
        </div>
    </div>
</div>

<?php
include("includes/templates/footer.php");
}
else{
    echo "<div class='alert alert-warning' role='alert'>you are not authunticated</div>";
    header('refresh:1;url=login.php');
exit();
}
?>