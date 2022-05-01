<nav class="navbar navbar-expand-lg navbar-light bg-dark text-white">
    <div class="container"><a class="navbar-brand" href="dashboard.php">Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link " href="users.php">users <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cate.php">categories</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="posts.php">posts</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="comments.php">comments</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        register
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="login.php">login</a>
                        <a class="dropdown-item" href="logout.php">logout</a>
                    </div>
                </li>

            </ul>
        </div>
    </div>
</nav>