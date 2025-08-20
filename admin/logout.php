<?php

session_start();
session_unset();
session_destroy();

//redireact

header("location: login.php");
    exit();



?>