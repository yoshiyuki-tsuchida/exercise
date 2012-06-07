<?php

class PasswordUtil
{
    public static function generateSalt()
    {
        return base64_encode(hash('sha256', strval(mt_rand()) . strval(time()) . strval(mt_rand()), true));
    }

    public static function hashPassword($password, $salt)
    {
        $x = '';
        for ($i = 0; $i < 1000; $i++) {
            $x = hash('sha256', $x . $password . $salt, true);
        }
        return base64_encode($x);
    }
}

