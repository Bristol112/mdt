<?php
    require "requires/config.php";
    if (!$_SESSION['loggedin']) {
        Header("Location: login");
    }
    $respone = false;
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if ($_POST['type'] == "search") {
            $result = $con2->query("SELECT * FROM player_vehicles WHERE concat(' ', citizenid, ' ') LIKE '%".$con->real_escape_string($_POST['search'])."%'");
            $search_array = [];
            while ($data = $result->fetch_assoc()) {
                $search_array[] = $data;
            }
        } elseif ($_POST['type'] == "show" || isset($_SESSION["vehicleid"]) && $_SESSION["vehicleid"] != NULL) {
            if (isset($_SESSION["vehicleid"]) && $_SESSION["vehicleid"] != NULL) {
                $vehicleid = $_SESSION["vehicleid"];
            } else {
                $vehicleid = $_POST['vehicleid'];
            }
            $query = $con2->query("SELECT * FROM player_vehicles WHERE id = ".$con->real_escape_string($vehicleid));
            $selectedvehicle = $query->fetch_assoc();

            $house_name = $selectedvehicle['house'];
            $query = $con2->query("SELECT * FROM houselocations WHERE name = '$house_name'");
            $selectedvehicleInformation = $query->fetch_assoc();


            $get_houses_info = $con2->query("SELECT * FROM houselocations WHERE name = '$house_name'");
            while ($houses_info = $get_houses_info->fetch_object()){
              $coords = json_decode($houses_info->coords, true);
              $enter_coords = isset($coords['enter']) ? $coords['enter'] : ' ';
            }

            $_SESSION["vehicleid"] = NULL;
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

        <title>MDT | Rescue services </title>

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
                            <button class="btn btn-outline-light btn-logout my-2 my-sm-0" type="button">Logout</button>
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
                            <a class="dropdown-item" href="houses">Houses</a>
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
                <h3>Vehicles</h3>
                <p class="lead">You can look for a vehicle and its owner here. <br/> You can search for vehicles if ownership is on record.</p>
            </div>
            <div class="profile-container">
                <div class="profile-search">
                <?php if ($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['type'] == "show") { ?>
                    <form method="post" action="createvehicle" class="mb-4">
                        <input type="hidden" name="type" value="edit">
                        <input type="hidden" name="vehicleid" value="<?php echo $selectedvehicle['id']; ?>">
                        <button type="submit" name="issabutn" class="btn btn-pol btn-md my-0 ml-sm-2">Adjustment vehicles</button>
                    </form>
                <?php } ?>
                    <form method="post" class="form-inline ml-auto">
                        <input type="hidden" name="type" value="search">
                        <div class="md-form my-0">
                            <input class="form-control" name="search" type="text" placeholder="search for vehicle" aria-label="Search">
                        </div>
                        <button type="submit" name="issabutn" class="btn btn-pol btn-md my-0 ml-sm-2">Search</button>
                    </form>
                </div>
                <?php if ($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['type'] == "search") { ?>
                    <div class="search-panel">
                        <h5 class="panel-container-title">Found vehicles..</h5>
                        <div class="panel-list">
                            <?php if (empty($search_array)) { ?>
                                <p>No vehicle found.</p>
                            <?php } else { ?>
                                <?php foreach($search_array as $vehicle) {?>
                                    <form method="post">
                                        <input type="hidden" name="type" value="show">
                                        <input type="hidden" name="vehicleid" value="<?php echo $vehicle['id']; ?>">
                                        <button type="submit" class="btn btn-panel panel-item">
                                            <h5 class="panel-title"><?php echo $vehicle['vehicle'] . " - " . $vehicle['plate']; ?></h5>
                                            <?php
                                                $citizenid = $vehicle["citizenid"];
                                                $profile_data = $con2->query("SELECT charinfo FROM players WHERE citizenid = '$citizenid'");
                                                while ($players = $profile_data->fetch_object()){
                                                  $name = json_decode($players->charinfo, true);
                                                  $firstname_owner = isset($name['firstname']) ? $name['firstname'] : ' ';
                                                  $lastname_owner = isset($name['lastname']) ? $name['lastname'] : ' ';
                                                }
                                            ?>
                                            <p class="panel-author">Owner: <?php echo $firstname_owner . " " .$lastname_owner; ?></p>
                                        </button>
                                    </form>
                                <?php }?>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
                <?php if ($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['type'] == "show" && !empty($selectedvehicle)) { ?>
                    <div class="profile-panel">
                        <div class="profile-information">
                            <p><strong>Vehicle:</strong><br /><?php echo $selectedvehicle["vehicle"]; ?></p>
                            <p><strong>License Plate:</strong><br /><?php echo $selectedvehicle["plate"]; ?></p>
                            <p><strong>Owner:</strong><br /><?php echo $selectedvehicle . " " . $lastname_owner; ?></p>
                            <p><strong>BSN:</strong><br /><?php echo $selectedvehicle["citizenid"]; ?></p>
                            <p><strong>Note:</strong><br /><?php echo $note; ?></p>
                        </div>
                    </div>
                    <div class="profile-reports-panel">
                        <div class="profile-lastincidents">
                            <form style="float:right; margin-left: 1vw;">
                                <a style="margin-left:0!important;" class="btn <?php if(trim($apk) == '1') echo 'btn-success' ?><?php if(trim($apk) == '0') echo 'btn-danger' ?> btn-md my-0 ml-sm-2 text-white">
                                    APK: <?php if(trim($apk) == '1') echo 'ANO' ?><?php if(trim($apk) == '0') echo 'NE' ?>
                                </a>
                                <a style="margin-left:0!important;" class="btn <?php if(trim($warrant) == '1') echo 'btn-success' ?><?php if(trim($warrant) == '0') echo 'btn-danger' ?> btn-md my-0 ml-sm-2 text-white">
                                    Searched: <?php if(trim($warrant) == '1') echo 'ANO' ?><?php if(trim($warrant) == '0') echo 'NE' ?>
                                </a>
                            </form>
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
