<?php
require 'User.php';
$userDao = new Db_Dao_User;
$user = $userDao->findByUserId(1);
echo $user["name"];
