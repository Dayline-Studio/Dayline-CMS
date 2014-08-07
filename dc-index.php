<?php
session_start();
$_SESSION['go_home'] = true;

include 'dc-storage/config.php';
if (isset($config)) {
    header('Location: news');
} else {
    header('Location: ./install/');
}