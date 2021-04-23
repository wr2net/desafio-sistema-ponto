<?php
session_save_path("/tmp");
session_start();
$route = $_SERVER['PATH_INFO'] ?? '/';

use App\Users\UserController;
use App\Employees\EmployeeController;
use App\Overtimes\OvertimeController;
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
    $id = filter_input(INPUT_POST, 'id');

    switch ($route) {
        case('/logout'):
            include "src/Auth/Logout.php";
            break;
        case('/users'):
            if (isset($id)) {
                $user = (new UserController)->destroy($id);
            }
            $users = (new UserController)->index();
            include "views/users.phtml";
            break;
        case('/users/register'):
            if (isset($id)) {
                $user = (new UserController)->show($id);
            }
            include "views/userRegister.phtml";
            break;
        case('/users/save'):
            $data = filter_input_array(INPUT_POST);
            if (empty($data['id'])) {
                (new UserController)->store($data);
            } else {
                (new UserController)->update($data, $data['id']);
            }
            $users = (new UserController)->index();
            include "views/users.phtml";
            break;
        case('/users/delete'):
            (new UserController)->destroy($id);
            $users = (new UserController)->index();
            include "views/users.phtml";
            break;

        case('/employees'):
            if (isset($id)) {
                $employee = (new EmployeeController)->destroy($id);
            }
            $employees = (new EmployeeController)->index();
            include "views/employees.phtml";
            break;
        case('/employees/register'):
            if (isset($id)) {
                $employee = (new EmployeeController)->show($id);
            }
            include "views/employeesRegister.phtml";
            break;
        case('/employees/save'):
            $data = filter_input_array(INPUT_POST);
            if (empty($data['id'])) {
                (new EmployeeController)->store($data);
            } else {
                (new EmployeeController)->update($data, $data['id']);
            }
            $employees = (new EmployeeController)->index();
            include "views/employees.phtml";
            break;
        case('/employees/delete'):
            (new EmployeeController)->destroy($id);
            $employees = (new EmployeeController)->index();
            include "views/employees.phtml";
            break;

        case('/overtimes'):
            if (filter_input(INPUT_POST, 'employee_id')) {
                $_SESSION['employee_id'] = filter_input(INPUT_POST, 'employee_id');
            }
            $employee_id = $_SESSION['employee_id'];
            $employee = (new EmployeeController)->show($employee_id);
            $employee = $employee['name'];
            if (isset($id)) {
                $overtime = (new OvertimeController)->destroy($id);
            }
            $overtimes = (new OvertimeController)->findByUser($employee_id);
            include "views/overtimes.phtml";
            break;
        case('/overtimes/register'):
            $employee_id = $_SESSION['employee_id'];
            if (isset($id)) {
                $overtime = (new OvertimeController)->show($id);
            }
            include "views/overtimesRegister.phtml";
            break;
        case('/overtimes/save'):
            $employee_id = $_SESSION['employee_id'];
            $data = filter_input_array(INPUT_POST);
            if (empty($data['id'])) {
                if (empty($data['created_at'])) {
                    $data['created_at'] = date("Y-m-d 00:00:00");
                }
                $data['updated_at'] = date("Y-m-d 00:00:00");
                (new OvertimeController)->store($data);
            } else {
                $data['updated_at'] = date("Y-m-d H:i:s");
                (new OvertimeController)->update($data, $data['id']);
            }
            $employee = (new EmployeeController)->show($employee_id);
            $employee = $employee['name'];
            $overtimes = (new OvertimeController)->findByUser($employee_id);
            include "views/overtimes.phtml";
            break;
        case('/overtimes/delete'):
            $employee_id = $_SESSION['employee_id'];
            $employee = (new EmployeeController)->show($employee_id);
            $employee = $employee['name'];
            (new OvertimeController)->destroy($id);
            $overtimes = (new OvertimeController)->findByUser($employee_id);
            include "views/overtimes.phtml";
            break;

        case('/all-overtimes'):
            $overtimes = (new OvertimeController)->findJoin('employees');
            include "views/allOvertimes.phtml";
            break;

        default :
            include "views/dashboard.phtml";
            break;
    }
}