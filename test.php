<?php
require 'class.clientlogin.php';
$login = new clientlogin('username@gmail.com', 'password', clientlogin::documents);
if($login->error) echo $login->error;