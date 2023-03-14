<?php 
    session_start();
    include_once __DIR__ . '/../helpers/helper.php';
    $helper = new Helper();


    session_destroy();

    $pageUrl = $helper->pageUrl('home.php');
    header("Location: $pageUrl");
    exit;
?>
