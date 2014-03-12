<?php

if (file_exists('inc/mysql.php')) {
    header('Location: pages/news.php');
} else {
    header('Location: ./installer/');
}