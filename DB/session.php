<?php
session_start();
if(!isset($_SESSION['userinfo']))
    $_SESSION['userinfo'] = false;