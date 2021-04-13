<?php

ini_set('max_execution_time', 300); //300 seconds 

if (isset($_POST)) {
    $host = $_POST["host"];
    $dbuser = $_POST["dbuser"];
    $dbpassword = $_POST["dbpassword"];
    $dbname = $_POST["dbname"];

    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
    $login_password = $_POST["password"] ? $_POST["password"] : "";

    //check required fields
    if (!($host && $dbuser && $dbname && $first_name && $last_name && $email && $login_password)) {
        echo json_encode(array("success" => false, "message" => "Please input all fields."));
        exit();
    }


    //check for valid email
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        echo json_encode(array("success" => false, "message" => "Please input a valid email."));
        exit();
    }

    //check for valid database connection
    $mysqli = @new mysqli($host, $dbuser, $dbpassword, $dbname);

    if (mysqli_connect_errno()) {
        echo json_encode(array("success" => false, "message" => $mysqli->connect_error));
        exit();
    }


    //all input seems to be ok. check required fiels
    if (!is_file('database.sql')) {
        echo json_encode(array("success" => false, "message" => "The database.sql file could not found in install folder!"));
        exit();
    }

    //check if the env production is installed.
    if (is_file('.env.production')) {
        echo json_encode(array("success" => false, "message" => "Found environment configuration, app seems installed!"));
        exit();
    }

    //start installation

    $sql = file_get_contents("database.sql");

    //set admin information to database
    $now = date("Y-m-d H:i:s");

    $sql = str_replace('admin_first_name', $first_name, $sql);
    $sql = str_replace('admin_last_name', $last_name, $sql);
    $sql = str_replace('admin_email', $email, $sql);
    $sql = str_replace('admin_password', password_hash($login_password, PASSWORD_DEFAULT), $sql);
    $sql = str_replace('admin_created_at', $now, $sql);

    //create tables in datbase 

    $mysqli->multi_query($sql);
    do {
        
    } while (mysqli_more_results($mysqli) && mysqli_next_result($mysqli));


    $mysqli->close();
    // database created

    // set the database config file
    $db_file_path = "../.env.backup.php";
    $prod_file_path = "../.env.production.php";
    $db_file = file_get_contents($db_file_path);

    $db_file = str_replace('enter_hostname', $host, $db_file);
    $db_file = str_replace('enter_db_username', $dbuser, $db_file);
    $db_file = str_replace('enter_db_password', $dbpassword, $db_file);
    $db_file = str_replace('enter_database_name', $dbname, $db_file);

    $encryption_key = substr(md5(rand()), 0, 15);
    $db_file = str_replace('enter_encryption_key', $encryption_key, $db_file);
    $db_file = str_replace('deployment', 'production', $db_file);

    file_put_contents($prod_file_path, $db_file);
    unlink("../.env.backup.php");
    unlink("database.sql");    

    echo json_encode(array("success" => true, "message" => "Installation successfull."));
    exit();
}
