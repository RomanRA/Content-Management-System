<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 2/9/2015
 * Time: 12:49 PM
 */
if(!isset($_SESSION))
{
    session_start();


}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link type="text/css" rel="stylesheet" href="styles.css" />
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.5/css/jquery.dataTables.css">

      <!-- jQuery -->
    <script type="text/javascript" charset="utf8" src="//code.jquery.com/jquery-1.10.2.min.js"></script>

    <!-- DataTables -->
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.5/js/jquery.dataTables.js"></script>

    <script type="text/javascript" src="table.js"></script>

    <!-- Bootstrap -->

    <link href = "css/bootstrap.min.css" rel="stylesheet">
    <link href = "css/styles.css" rel ="stylesheet">
<!--    <script src = "http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>-->
    <script src = "js/bootstrap.js"></script>
    <script src = imgerror.js></script>


</head>
<body>
<div class="currentPage">
<!--    <h1>--><?php //echo $title;?><!--</h1>-->
</div>

