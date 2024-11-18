<?php

namespace Service\Authentication;


use Model\User;

class AuthenticationCookie implements AuthServiceInterface
{
    public function check()
    {
        if (isset($_COOKIE['user_id'])) {
            return true;
        } else {
        return false;
    }
    }

    public function authenticate(string $email,string $password)
    {
        $user = User::getByEmail($email);
        if ($user) {
            if (password_verify($password, $user->getPassword())) {
                setcookie('user_id', $user->getId(), time() + (86400 * 30), "/");
                header('Location: /main');
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function logout()
    {
        setcookie('user_id', '', time() - 3600);
        // TODO: Implement logout() method.
    }

    public function getUser()
    {
        if (!$this->check()) {
            return null;
        } else {
            $userId = $_COOKIE['user_id'];
            return User::getById($userId);
        }
    }


}