<?php 
session_start();
unset($_SESSION["log_id"]);
unset($_SESSION["fullname"]);
unset($_SESSION["level"]);
 
header("Location: login.php");