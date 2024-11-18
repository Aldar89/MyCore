<?php

namespace Service\Authentication;

use Model\User;

class AuthenticationSession implements AuthServiceInterface
{
    public function check():bool
    {
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (isset($_SESSION['user_id'])) {
            return true;
        } else {
            return false;
        }
    }

    public function logout()
    {
        session_start();
        $_SESSION['user_id'] = null;
        session_destroy();

        require_once './../View/get_login.php';

    }

    public function getUser():?User
    {
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }
        if ($this->check()){
            $userId = $_SESSION['user_id'];
            return User::getById($userId);
        } else {
            return null;

        }
    }

    public function authenticate(string $email,string $password):?User
    {
        $user =User::getByEmail($email);
        if ($user === false) {
            return null;
        } else { if (password_verify($password, $user->getPassword())) {
            return $user;
        } else { return null;}

        }

    }

}