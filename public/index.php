<?php
// public/index.php
session_start(); 


require_once "../config/constants.php";
require_once "../app/core/App.php";

(new App())->run();