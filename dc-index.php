<?php

include 'dc-storage/config.php';
if (isset($config)) {
    header('Location: pages/');
} else {
    header('Location: ./install/');
}