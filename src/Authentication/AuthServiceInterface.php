<?php

namespace Service\Authentication;

interface AuthServiceInterface
{
    public function check();
    public function authenticate(string $email,string $password);
    public function logout();
    public function getUser();
}