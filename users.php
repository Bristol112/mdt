    <?php
    require "requires/config.php";
    if (!$_SESSION['loggedin']) {
        Header("Location: login");
    }
    if ($_SESSION["role"] != "admin") {
        Header("Location: dashboard");
    }
    $respone = false;
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (trim($_POST['type']) == NULL) {
            Header("Location:dashboard");
        }
        if ($_POST['type'] == "create") {
            $insert = $con->query("INSERT INTO users (username,password,name,role,rank,last_login) VALUES('".$con->real_escape_string($_POST['username'])."','".$con->real_escape_string($_POST['password'])."','".$con->real_escape_string($_POST['fullname'])."','user','".$con->real_escape_string($_POST['rank'])."','".date('Y-m-d')."')");
            if ($insert) {
                $respone = true;
            }
        } elseif ($_POST['type'] == "delete") {
            $sql = "DELETE FROM users WHERE id = ".$con->real_escape_string($_POST['deleteuser']);
            if ($con->query($sql)) {
                $respone = true;
            } else {
                echo "Error deleting record: " . mysqli_error($conn);
                exit();
            }
        } elseif ($_POST['type'] == "edit") {
            $query = $con->query("SELECT * FROM users WHERE id = ".$con->real_escape_string($_POST['edituser']));
            $selecteduser = $query->fetch_assoc();
        } elseif ($_POST['type'] == "realedit") {
            $update = $con->query("UPDATE users SET username = '".$con->real_escape_string($_POST['username'])."', name = '".$con->real_escape_string($_POST['fullname'])."', rank = '".$con->real_escape_string($_POST['rank'])."' WHERE id = ".$_POST['userid']);
            if ($update) {
                $respone = true;
            } else {
                $response = false;
            }
        }
    }
    $name = explode(" ", $_SESSION["name"]);
    $firstname = $name[0];
    $last_word_start = strrpos($_SESSION["name"], ' ') + 1;
    $lastname = substr($_SESSION["name"], $last_word_start);

    $result = $con->query("SELECT * FROM users WHERE role = 'user'");
    $user_array = [];
    while ($data = $result->fetch_assoc()) { 
        $user_array[] = $data;
    }
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="shortcut icon" href="https://www.politie.nl/politie2018/assets/images/icons/favicon.ico" type="image/x-icon" />
        <link rel="icon" type="image/png" sizes="16x16" href="https://www.politie.nl/politie2018/assets/images/icons/favicon-16.png">
        <link rel="icon" type="image/png" sizes="32x32" href="https://www.politie.nl/politie2018/assets/images/icons/favicon-32.png">
        <link rel="icon" type="image/png" sizes="64x64" href="https://www.politie.nl/politie2018/assets/images/icons/favicon-64.png">

        <title>MDT | Rescue services</title>

        <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/starter-template/">

        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

        <!-- Custom styles for this template -->
        <link href="assets/css/main.css" rel="stylesheet">
    </head>
    <body>
        <nav class="navbar fixed-top navbar-expand-lg navbar-custom bg-custom">
            <div class="collapse navbar-collapse" id="navbarsExampleDefault">

                <!-- Left menu -->
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-label" href="#">
                            <img src="assets/images/icon.png" width="22" height="22" alt="">
                            <span class="title">
                                Welcome <?php echo $_SESSION["rank"] . " " . $firstname . " " . substr($lastname, 0, 1); ?>.
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-button" href="logout">
                            <button class="btn btn-outline-light btn-logout my-2 my-sm-0" type="button">Logging out</button>
                        </a>
                    </li>
                </ul>

                <!-- Right menu -->
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="dashboard">Dashboard</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Search
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="profiles">Profiles</a>
                            <a class="dropdown-item" href="reports">Reports</a>
                            <a class="dropdown-item" href="vehicles">Vehicles</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="warrants">Warrants</a>
                    </li>
                    <?php if ($_SESSION["role"] == "admin") { ?>
                        <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Administration
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="laws">Laws</a>
                            <a class="dropdown-item" href="users">Users</a>
                        </div>
                    </li>
                    <?php } ?>
                    <li class="nav-item">
                        <a class="nav-link-report" href="createreport">Create a report</a>
                    </li>
                </ul>
            </div>
        </nav>

        <main role="main" class="container">
            <div class="content-introduction">
                <h3>User settings</h3>
                <p class="lead">You can create, edit and delete users here. careful, deleting a user is permanent!</strong></p>
            </div>
            <div class="users-container">
                <?php if ($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['type'] == "edit") { ?>
                    <div class="left-panel-container">
                    <h5 class="panel-container-title">Edit user</h5>
                    <form method="post">
                        <input type="hidden" name="type" value="realedit">
                        <input type="hidden" name="userid" value="<?php echo $selecteduser['id']; ?>">
                        <div class="input-group mb-3">
                            <input type="text" name="username" class="form-control login-user" value="<?php echo $selecteduser['username']; ?>" placeholder="username">
                        </div>
                        <div class="input-group mb-3">
                            <input type="text" name="fullname" class="form-control login-user" value="<?php echo $selecteduser['name']; ?>" placeholder="full name">
                        </div>
                        <div class="input-group mb-3">
                            <input type="text" name="rank" class="form-control login-user" value="<?php echo $selecteduser['rank']; ?>" placeholder="rank">
                        </div>
                        <div class="form-group">
                            <button type="submit" name="create" class="btn btn-primary btn-police">create</button>
                        </div>
                    </form>
                </div> 
                <?php } else { ?>
                <!-- Left Container -->
                <div class="left-panel-container">
                    <h5 class="panel-container-title">Add user</h5>
                    <?php if ($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['type'] == "realedit" && $respone) {?>
                        <?php echo "<p style='color: #13ba2c;'>username upraven!</p>"; ?>
                    <?php } ?>
                    <?php if ($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['type'] == "realedit" && !$respone) {?>
                        <?php echo "<p style='color:#9f1010;'>username nebyl upraven!</p>"; ?>
                    <?php } ?>
                    <form method="post">
                        <input type="hidden" name="type" value="edit">
                        <div class="form-group">
                            <label for="userselect">Username</label>
                            <select class="form-control" name="edituser">
                            <?php foreach($user_array as $user){?>
                                <option value="<?php echo $user["id"] ?>"><?php echo $user['name']; ?></option>
                            <?php }?>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" name="edit" class="btn btn-primary btn-police">Create</button>
                        </div>
                    </form>
                </div>  
                <!-- Right Container -->
                <div class="right-panel-container">
                    <h5 class="panel-container-title">Delete user</h5>
                    <?php if ($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['type'] == "delete" && $respone) {?>
                        <?php echo "<p style='color: #13ba2c;'>username odstraněn!</p>"; ?>
                    <?php } ?>
                    <form method="post">
                        <input type="hidden" name="type" value="delete">
                        <div class="form-group">
                            <label for="userselect">Username</label>
                            <select class="form-control" name="deleteuser">
                            <?php foreach($user_array as $user){?>
                                <option value="<?php echo $user["id"] ?>"><?php echo $user['name']; ?></option>
                            <?php }?>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" name="delete" class="btn btn-primary btn-police">Delete</button>
                        </div>
                    </form>
                </div> 
                <div class="left-panel-container">
                    <h5 class="panel-container-title">Add username</h5>
                    <?php if ($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['type'] == "create" && $respone) {?>
                        <?php echo "<p style='color: #13ba2c;'>username přidán!</p>"; ?>
                    <?php } ?>
                    <form method="post">
                        <input type="hidden" name="type" value="create">
                        <div class="input-group mb-3">
                            <input type="text" name="username" class="form-control login-user" value="" placeholder="username" required>
                        </div>
                        <div class="input-group mb-2">
                            <input type="password" name="password" class="form-control login-pass" value="" placeholder="password" required>
                        </div>
                        <div class="input-group mb-3">
                            <input type="text" name="fullname" class="form-control login-user" value="" placeholder="full name" required>
                        </div>
                        <select class="form-control" style="margin-bottom:2vh;" name="rank" required>
                            <option value="Aspirant">Cadet</option>
                            <option value="Surveillant">Officer</option>
                            <option value="Agent">Senior Officer</option>
                            <option value="Hoofdagent">Deputy</option>
                            <option value="Brigadier">Sergeant</option>
                            <option value="Inspecteur">Assistant Chief</option>
                            <option value="Hoofdinspecteur">Chief</option>
                        </select>
                        <div class="form-group">
                            <button type="submit" name="create" class="btn btn-primary btn-police">add</button>
                        </div>
                    </form>
                </div> 
                <?php } ?>
            </div>
        </main><!-- /.container -->

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="assets/js/main.js"></script>
    </body>
</html>
