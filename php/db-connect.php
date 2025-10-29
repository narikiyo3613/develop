<?php
    const SERVER = 'mysql326.phy.lolipop.lan';
    const DBNAME = 'LAA1607944-development';
    const USER = 'LAA1607944';
    const PASS = 'mofumofu5';

    $connect = 'mysql:host='. SERVER . ';dbname='. DBNAME . ';charset=utf8';
    $pdo = new PDO($connect, USER, PASS);
?>