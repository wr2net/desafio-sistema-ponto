<?php

namespace App\Auth;

use App\Model\Query;

/**
 * Class Login
 * @package App\Auth
 */
class Login
{
    /**
     * @param $data
     * @return bool|string[]
     */
    public function check($data)
    {
        try {
            $user = $data['email'];
            $passwd = $data['password'];

            $query = new Query();
            $user = $query->twofields('users', 'email', 'password', $user, $passwd);

            if (!$user) {
                return false;
            }
            $_SESSION['name'] = $user[0]['name'];
            $_SESSION['email'] = $user[0]['email'];
            header("Location: /dashboard");
            return true;
        } catch (\PDOException $ex) {
            return false;
        }
    }
}