<?php
    require "requires/config.php";
    if (!$_SESSION['loggedin']) {
        Header("Location: login");
    }
    if ($_SESSION["role"] != "admin") {
        Header("Location: dashboard");
    }
    $respone = false;
    if ($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['type'] == "create") {
        $insert = $con->query("INSERT INTO laws (name,description,fine,months) VALUES('".$con->real_escape_string($_POST['name'])."','".$con->real_escape_string($_POST['description'])."',".$con->real_escape_string($_POST['fine']).",".$con->real_escape_string($_POST['months']).")");
        if ($insert) {
            $respone = true;
            $result = $con->query("SELECT * FROM laws ORDER BY months ASC");
            $laws_array = [];
            while ($data = $result->fetch_assoc()) {
                $laws_array[] = $data;
            }
        }
    } elseif ($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['type'] == "edit") {
        $update = $con->query("UPDATE laws SET name = '".$con->real_escape_string($_POST['name'])."', description = '".$con->real_escape_string($_POST['description'])."', fine = ".$con->real_escape_string($_POST['fine']).", months = ".$con->real_escape_string($_POST['months'])." WHERE id = ".$_POST['lawid']);
        if ($update) {
            $respone = true;
        } else {
            $response = false;
        }
    } elseif ($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['type'] == "delete") {
        $sql = "DELETE FROM laws WHERE id = ".$con->real_escape_string($_POST['lawid']);
        if ($con->query($sql)) {
            $respone = true;
        } else {
            echo "Error deleting record: " . mysqli_error($conn);
            exit();
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['type'] == "editlaw") {
        $query = $con->query("SELECT * FROM laws WHERE id = ".$con->real_escape_string($_POST['lawid']));
        $selectedlaw = $query->fetch_assoc();
    } elseif  ($_SERVER['REQUEST_METHOD'] != "POST" || $_SERVER['REQUEST_METHOD'] == "POST" && $_POST['type'] != "addlaw" && $_POST['type'] != "editlaw"){
        $result = $con->query("SELECT * FROM laws ORDER BY months ASC");
        $laws_array = [];
        while ($data = $result->fetch_assoc()) {
            $laws_array[] = $data;
        }
    }
    $name = explode(" ", $_SESSION["name"]);
    $firstname = $name[0];
    $last_word_start = strrpos($_SESSION["name"], ' ') + 1;
    $lastname = substr($_SESSION["name"], $last_word_start);
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
        <link href="assets/css/laws.css" rel="stylesheet">
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
                            <button class="btn btn-outline-light btn-logout my-2 my-sm-0" type="button">Log out</button>
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
                            <a class="dropdown-item" href="profiles">Citizens</a>
                            <a class="dropdown-item" href="reports">Reports</a>
                            <a class="dropdown-item" href="vehicles">Vehicles</a>
                            <a class="dropdown-item" href="houses">Houses</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="warrants">Arrest warrants </a>
                    </li>
                    <?php if ($_SESSION["role"] == "admin") { ?>
                        <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Password
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="laws"> laws </a>
                            <a class="dropdown-item" href="users"> users </a>
                        </div>
                    </li>
                    <?php } ?>
                    <li class="nav-item">
                        <a class="nav-link-report" href="createreport">Create a report </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main role="main" class="container">
            <div class="content-introduction">
                <h3>Fines</h3>
                <p class="lead"> You can add, remove and modify fines as an administrator. Make sure you include the correct time (in minutes) and the penalty (price without comma).
                Also, make sure there is a clear description so that other colleagues can read it. </p>
            </div>
            <div class="law-container">
                <?php
                    if ($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['type'] == "editlaw") {
                ?>
                    <div class="law-add">
                        <h5 class="panel-container-title">Change the penalty </h5>
                        <form method="post">
                            <input type="hidden" name="type" value="edit">
                            <input type="hidden" name="lawid" value="<?php echo $selectedlaw['id']; ?>">
                            <div class="input-group mb-3">
                                <input type="text" name="name" class="form-control login-user" value="<?php echo $selectedlaw['name']; ?>" placeholder="naam" required>
                            </div>
                            <?php $description = str_replace( "<br />", '', $selectedlaw['description']); ?>
                            <div class="input-group mb-2">
                                <textarea name="description" class="form-control" placeholder="omschrijving" required><?php echo $description ?></textarea>
                            </div>
                            <div class="input-group mb-3">
                                <input type="number" min="0" name="fine" class="form-control login-user" value="<?php echo $selectedlaw['fine']; ?>" placeholder="boete" required>
                            </div>
                            <div class="input-group mb-3">
                                <input type="number" min="0" name="months" class="form-control login-user" value="<?php echo $selectedlaw['months']; ?>" placeholder="months" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="create" class="btn btn-primary btn-police">create</button>
                            </div>
                        </form>
                        <form method="post">
                            <input type="hidden" name="type" value="delete">
                            <input type="hidden" name="lawid" value="<?php echo $selectedlaw['id']; ?>">
                            <div class="form-group">
                                <button type="submit" name="create" style="width:100%!important" class="btn btn-danger">Remove the penalty</button>
                            </div>
                        </form>
                    </div>
                <?php
                    } elseif ($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['type'] == "addlaw") {
                ?>
                    <div class="law-add">
                        <h5 class="panel-container-title">Add the penalty</h5>
                        <form method="post">
                            <input type="hidden" name="type" value="create">
                            <div class="input-group mb-3">
                                <input type="text" name="name" class="form-control login-user" value="" placeholder="name" required>
                            </div>
                            <div class="input-group mb-2">
                                <textarea name="description" class="form-control" value="" placeholder="description" required></textarea>
                            </div>
                            <div class="input-group mb-3">
                                <input type="number" min="0" name="fine" class="form-control login-user" value="" placeholder="fine" required>
                            </div>
                            <div class="input-group mb-3">
                                <input type="number" min="0" name="months" class="form-control login-user" value="" placeholder="months" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="create" class="btn btn-primary btn-police">Create</button>
                            </div>
                        </form>
                    </div>
                <?php
                    } else {
                ?>
                    <div class="law-list">
                        <?php if ($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['type'] == "create" && $respone) {?>
                            <?php echo "<p style='color: #13ba2c;'>Pokuta přidána!</p>"; ?>
                        <?php } ?>
                        <?php if ($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['type'] == "edit" && $respone) {?>
                            <?php echo "<p style='color: #13ba2c;'>Pokuta upravena!</p>"; ?>
                        <?php } ?>
                        <?php if ($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['type'] == "delete" && $respone) {?>
                            <?php echo "<p style='color: #13ba2c;'>Pokuta odebrána!</p>"; ?>
                        <?php } ?>
                        <div class="lawlist-search">
                            <form method="post">
                                <input type="hidden" name="type" value="addlaw">
                                <div class="form-group">
                                    <button type="submit" name="addlaw" class="btn btn-pol btn-md my-0 ml-sm-2">Add a penal</button>
                                </div>
                            </form>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Search</span>
                                </div>
                                <input type="text" class="lawsearch form-control" aria-label="Zoeken" aria-describedby="inputGroup-sizing-sm">
                            </div>
                        </div>
                        <?php foreach($laws_array as $law){?>
                            <form method="post">
                                <input type="hidden" name="type" value="editlaw">
                                <input type="hidden" name="lawid" value="<?php echo $law['id']; ?>">
                                <button type="submit" class="law-item" data-toggle="tooltip" data-html="true" title="<?php echo $law['description']; ?>">
                                    <h5 class="lawlist-title"><?php echo $law['name']; ?></h5>
                                    <p class="lawlist-fine">Fine: €<?php echo $law['fine']; ?></p>
                                    <p class="lawlist-months">Months: <?php echo $law['months']; ?> Months</p>
                                </button>
                            </form>
                        <?php }?>
                    </div>
                <?php
                    }
                ?>
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