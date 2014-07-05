<?php
include 'dyn-content/config.php';
if (isset($config)) {
    header('Location: pages/news.php');
} else {
    header('Location: ./install/');
}