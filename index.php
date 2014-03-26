<?php

if (file_exists('inc/config.php')) {
    header('Location: pages/news.php');
} else {
    header('Location: ./installer/');
}