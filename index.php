<?php
session_save_path("/tmp");
session_start();
$route = $_SERVER['PATH_INFO'] ?? '/';

use App\Users\UserController;
use App\Auth\Login;

require __DIR__ . '/vendor/autoload.php';

if (empty($_SESSION)) {
    if ($route == '/' || $route != '/login') {
        include "views/index.phtml";
    }

    if ($route == '/login') {
        $data = [
            "email" => filter_input(INPUT_POST, "email"),
            "password" => base64_encode(md5(filter_input(INPUT_POST, "password")))
        ];
        if (!Login::check($data)) {
            header("Location: /");
        }
    }
}
if (!empty($_SESSION)) {
    if ($route == '/logout') {
        include "src/Auth/Logout.php";
    }

    if ($route == '/' || $route == '/login' || $route == '/dashboard') {
        include "views/dashboard.phtml";
    }

    if ($route == '/users') {
        $users = (new UserController)->index();
        include "views/users.phtml";
    }
    if ($route == '/users/create') {
        $data = filter_input_array(INPUT_POST);
        $users = (new UserController)->store($data);
        include "views/usersAdd.phtml";
    }
    if ($route == '/users/edit') {
        $id = filter_input_array(INPUT_POST);
        $users = (new UserController)->show($id);
        include "views/usersEdit.phtml";
    }
}