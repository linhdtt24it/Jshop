<?php
// public/logout.php
require_once "../config/constants.php";
session_destroy();
header("Location: home.php");
exit;