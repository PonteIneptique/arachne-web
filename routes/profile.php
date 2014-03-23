<?php

if (isset($_SESSION["user"])) {
    $app->get('/account/profile', function () {
        display("./pages/profil.php", array(), array(), $title = "Profil");
    });
    $app->post('/account/update', function () use ($app) {
        $input = $app->request->post();
        $data = array();

        //First we checkup our data
        $requiredFields = array("mail" => "Email field is missing", "name" => "Name field is missing");
        $data["error"] = array("update" => array());
        foreach ($requiredFields as $field => $message) {
            if (!isset($input[$field]) || empty($input[$field]) || strlen($input[$field]) <= 3) {
                $data["error"]["update"][$field] = $message;
            }
        }
        if(isset($input['password']) && !empty($input[$field]) && strlen($input[$field]) <= 3){
            $data["error"]["update"]["password"] = "Your password must be, at least, 4 characters";
        }elseif(isset($input['password']) && !empty($input[$field]) && strlen($input[$field]) > 3 && $input['password'] != $input['confirm']){
            $data["error"]["update"]["confirm"] = "Your password confirmation must match with the original";
        }
        
        if (count($data["error"]["update"]) >= 1) {
            display("./pages/profil.php", $data, array(), $title = "Profil");
        } else {
            $data = User::update($input, $_SESSION['user']['id']);

            if (isset($data["status"]) && $data["status"] == "success") {
                $_SESSION['user']['name'] = $input["name"];
                $_SESSION['user']['mail'] = $input["mail"];
                display("./pages/profil_update.php", $data, array(), $title = "Update Done");
            } else {
                var_dump($data);
                display("./pages/profil.php", $data, array(), "Profil");
            }
        }
    });

    $app->get('/account/history', function () {
        display("./pages/history.php", array("history" => Logs::History()), array(), $title = "History");
    });

    $app->get('/account/signout', function () use ($app) {
        unset($_SESSION["user"]);
        session_destroy();
        display("pages/home.php", array(), array(), "Home");
    });
}

$app->get('/account/login', function () {
    display("./pages/login.php", array(), array(), $title = "Login");
});

$app->post('/account/signup', function () use ($app) {
    $input = $app->request->post();
    $data = array();

    //First we checkup our data
    $requiredFields = array("mail" => "Email field is missing", "password" => "Password field is missing", "name" => "Name field is missing", "confirm" => "Confirm password field is missing");
    $data["error"] = array("signup" => array());
    foreach ($requiredFields as $field => $message) {
        if (!isset($input[$field]) || empty($input[$field]) || strlen($input[$field]) <= 3) {
            $data["error"]["signup"][$field] = $message;
        }
    }

    if (count($data["error"]["signup"]) >= 1) {

        display("pages/login.php", $data, array(), "Login");
    } elseif ($input["confirm"] != $input["password"]) {

        $data["errors"]["signup"]["confirm"] = "Passwords are not equal";
        display("pages/login.php", $data, array(), "Login");
    } else {

        $data = User::signup($input);

        if (isset($data["status"]) && $data["status"] == "success") {
            display("pages/home.php", $data, array(), "Home");
        } else {
            display("pages/login.php", $data, array(), "Login");
        }
    }
});

$app->post('/account/signin', function () use ($app) {
    // Don't forget to set the correct attributes in your form (name="user" + name="password")
    $input = $app->request->post();

    if (isset($input["mail"]) && isset($input["password"])) {
        $data = User::login($input);

        if ($data["signin"] == true) {
            $d = $data["data"];
            $_SESSION["user"] = array("id" => $d["UID"], "name" => $d["Name"], "mail" => $d["Mail"], "game" => User::rank($d["UID"]));

            display("pages/home.php", $data, array(), "Home");
        } else {
            display("pages/login.php", $data, array(), "login");
        }
    } else {
        display("pages/login.php", array("status" => "error", "error" => array("signup" => array("message" => "Missing fields."))), array(), "Login");
    }
});
?>