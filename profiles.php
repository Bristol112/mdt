<?php
    require "requires/config.php";
    if (!$_SESSION['loggedin']) {
        Header("Location: login");
    }
    $respone = false;
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if ($_POST['type'] == "search") {
            $result = $con->query("SELECT * FROM profiles WHERE concat(' ', fullname, ' ') LIKE '%".$con->real_escape_string($_POST['search'])."%' OR citizenid = '".$con->real_escape_string($_POST['search'])."' OR dnacode = '".$con->real_escape_string($_POST['search'])."' OR fingerprint = '".$con->real_escape_string($_POST['search'])."'");
            $search_array = [];
            while ($data = $result->fetch_assoc()) {
                $search_array[] = $data;
            }
        }elseif ($_POST['type'] == "show" || isset($_SESSION["personid"]) && $_SESSION["personid"] != NULL) {
            if (isset($_SESSION["personid"]) && $_SESSION["personid"] != NULL) {
                $personId = $_SESSION["personid"];
            } else {
                $personId = $_POST['personid'];
            }
            $query = $con->query("SELECT * FROM profiles WHERE id = ".$con->real_escape_string($personId));
            $selectedprofile = $query->fetch_assoc();
            $result = $con->query("SELECT * FROM reports WHERE profileid = ".$con->real_escape_string($personId)." ORDER BY created DESC LIMIT 5");
            $update = $con->query("UPDATE profiles SET lastsearch = ".time()." WHERE id = ".$personId);
            $reports_array = [];
            while ($data = $result->fetch_assoc()) {
                $reports_array[] = $data;
            }
            $citizenid = $selectedprofile["citizenid"];
            $vehicle_result = $con2->query("SELECT * FROM player_vehicles WHERE citizenid = '$citizenid'");
            while ($data = $vehicle_result->fetch_assoc()) {
                $vehicle_array[] = $data;
            }
            $_SESSION["personid"] = NULL;
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
        <link href="assets/css/profiles.css" rel="stylesheet">
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
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="warrants">Arrest warrants</a>
                    </li>
                    <?php if ($_SESSION["role"] == "admin") { ?>
                        <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Administration
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="laws">Fines</a>
                            <a class="dropdown-item" href="users">Users</a>
                        </div>
                    </li>
                    <?php } ?>
                    <li class="nav-item">
                        <a class="nav-link-report" href="createreport">Create report</a>
                    </li>
                </ul>
            </div>
        </nav>

        <main role="main" class="container">
            <div class="content-introduction">
                <h3>Citizens</h3>
                <p class="lead">Here you can search for people who can view information, create reports or create profiles. <br />  These people may not be around, please create a profile and enter the correct information.</p>
            </div>
            <div class="profile-container">
                <div class="profile-search">
                    <?php if ($_SERVER['REQUEST_METHOD'] != "POST" || $_SERVER['REQUEST_METHOD'] == "POST" && $_POST['type'] != "show") { ?>
                        <a href="createprofile" class="btn btn-pol btn-md my-0 ml-sm-2">Create a new profile</a>
                    <?php } else { ?>
                        <form method="post" action="createprofile" class="d-inline">
                            <input type="hidden" name="type" value="edit">
                            <input type="hidden" name="profileid" value="<?php echo $selectedprofile['id']; ?>">
                            <button type="submit" name="issabutn" class="btn btn-pol btn-md my-0 ml-sm-2">Private Profile</button>
                        </form>
                        <form method="post" action="createprofile" class="d-inline">
                            <input type="hidden" name="type" value="delete">
                            <input type="hidden" name="profileid" value="<?php echo $selectedprofile['id']; ?>">
                            <?php if ($_SESSION["role"] == "admin") { ?>
                            <button type="submit" name="issabutn" class="btn btn-danger btn-md my-0 ml-sm-2">Delete the profile</button>
                            <?php } ?>
                        </form>
                    <?php } ?>
                    <br /><br />
                    <form method="post" class="form-inline ml-auto">
                        <input type="hidden" name="type" value="search">
                        <div class="md-form my-0">
                            <input class="form-control" name="search" type="text" placeholder="looking for someone .." aria-label="Search">
                        </div>
                        <button type="submit" name="issabutn" class="btn btn-pol btn-md my-0 ml-sm-2">Search</button>
                    </form>
                </div>
                <?php if ($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['type'] == "search") { ?>
                    <div class="search-panel">
                        <h5 class="panel-container-title">People found ..</h5>
                        <div class="panel-list">
                            <?php if (empty($search_array)) { ?>
                                <p>No people were found. Create a profile.</p>
                            <?php } else { ?>
                                <?php foreach($search_array as $person) {?>
                                    <form method="post">
                                        <input type="hidden" name="type" value="show">
                                        <input type="hidden" name="personid" value="<?php echo $person['id']; ?>">
                                        <button type="submit" class="btn btn-panel panel-item">
                                            <h5 class="panel-title"><?php echo $person['fullname']; ?></h5>
                                            <p class="panel-author">BSN: <?php echo $person['citizenid']; ?></p>
                                        </button>
                                    </form>
                                <?php }?>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
                <?php if ($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['type'] == "show" && !empty($selectedprofile)) { ?>
                    <div class="profile-panel">
                        <div class="profile-avatar">
                            <img src="<?php echo $selectedprofile["avatar"]; ?>" alt="" width="150" height="150" />
                        </div>
                        <div class="profile-information">
                            <p><strong>The name :</strong><br /><?php echo $selectedprofile["fullname"]; ?></p>
                            <p><strong>BSN:</strong><br /><?php echo $selectedprofile["citizenid"]; ?></p>
                            <p><strong>Finger print:</strong><br /><?php echo $selectedprofile["fingerprint"]; ?></p>
                            <p><strong>DNA:</strong><br /><?php echo $selectedprofile["dnacode"]; ?></p>
                            <p><strong>Describe:</strong><br /><?php echo $selectedprofile["note"]; ?></p>
                        </div>
                    </div>
                    <div class="profile-reports-panel">
                        <div class="profile-lastincidents">
                            <form method="post" action="createreport" style="float:right; margin-left: 1vw;">
                                <input type="hidden" name="type" value="createnew">
                                <input type="hidden" name="profileid" value="<?php echo $selectedprofile['id']; ?>">
                                <button type="submit" name="issabutn" style="margin-left:0!important;" class="btn btn-success btn-md my-0 ml-sm-2">Create report</button>
                            </form>
                            <form method="post" action="createwarrant" style="float:right;">
                                <input type="hidden" name="type" value="create">
                                <input type="hidden" name="profileid" value="<?php echo $selectedprofile['id']; ?>">
                                <button type="submit" name="issabutn" style="margin-left:0!important;" class="btn btn-danger btn-md my-0 ml-sm-2">Create an arrest warrant</button>
                            </form>
                            <br />
                            <h5 class="panel-container-title">Latest people</h5>
                            <div class="panel-list">
                                <?php if (empty($reports_array)) { ?>
                                    <p> The search was not found for this person </p>
                                <?php } else { ?>
                                    <?php foreach($reports_array as $report) {?>
                                        <form method="post" action="reports">
                                            <input type="hidden" name="type" value="show">
                                            <input type="hidden" name="reportid" value="<?php echo $report['id']; ?>">
                                            <button type="submit" class="btn btn-panel panel-item">
                                                <h5 class="panel-title">#<?php echo $report['id']; ?> <?php echo $report['title']; ?></h5>
                                                <p class="panel-author">By: <?php echo $report['author']; ?></p>
                                            </button>
                                        </form>
                                    <?php }?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="profile-reports-panel mt-4 float-right">
                        <div class="profile-lastincidents">
                            <h5 class="panel-container-title">Registered vehicles</h5>
                            <div class="panel-list">
                                <?php if (empty($vehicle_array)) { ?>
                                    <p>There are no compounds for this person.</p>
                                <?php } else { ?>
                                    <?php foreach($vehicle_array as $vehicle) {?>
                                        <form method="post" action="vehicles">
                                            <input type="hidden" name="type" value="show">
                                            <input type="hidden" name="vehicleid" value="<?php echo $vehicle['id']; ?>">
                                            <button type="submit" class="btn btn-panel panel-item">
                                                <h5 class="panel-title"><?php echo $vehicle['vehicle']; ?></h5>
                                                <p class="panel-author">SPZ: <?php echo $vehicle['plate']; ?></p>
                                            </button>
                                        </form>
                                    <?php }?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <!---->
            </div>
        </main><!-- /.container -->

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="assets/js/main.js"></script>
        <script src="assets/js/car-replace-names.js"></script>
    </body>
</html>
