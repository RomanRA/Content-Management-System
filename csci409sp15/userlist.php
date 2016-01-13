<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 3/9/2015
 * Time: 1:02 PM
 */

$title='User List';
include_once 'header.php';
include_once 'menu.php';
require_once 'connect.php';


if(!isset($_SESSION['userid']))
{
    header("Location: login.php");
    exit();
}

    try
    {
        //echo $_SESSION['userid']." And ". $_SESSION['usertype'];
        //IF LOGGED IN USER IS ADMIN, GET ALL DATA FROM THE DATABASE
        if(isset($_SESSION['userid']) && $_SESSION['usertype'] == 1)
        {
            $sql = 'SELECT * FROM registration';
            $result = $pdo->query($sql);
            $result->execute();

        }
        //IF THE LOGGED IN USER IS NOT THE ADMIN, PULL ONLY THAT USER'S INFO
        if(isset($_SESSION['userid']) && $_SESSION['usertype'] == 2)
        {
            $sql2 = 'SELECT * FROM registration WHERE ID = :ID';
            $result = $pdo->prepare($sql2);
            $result->bindValue(':ID', $_SESSION['userid']);
            $result->execute();

        }
    }
    catch (PDOException $e)
    {
        echo 'Error fetching results: ' . $e->getMessage();
        exit();
    }

    while($row = $result->fetch()){
        $registration[] = array(
            'ID' => $row['ID'],
            'lastName' => $row['lastName'],
            'middleName' => $row['middleName'],
            'firstName' => $row['firstName'],
            'institution' => $row['institution'],
            'email' => $row['email'],
            'userName' => $row['userName']);

        }
    echo '<div class="container">';
    if(isset($_SESSION['userid']) && $_SESSION['usertype'] == 1) {
        echo '<div><p><a class="linkto" href="excelexport.php">EXPORT LIST TO EXCEL</a></p></div>';
    }
    echo '<table id = "information" class="display" cellspacing="0" width="100%">';
    echo '<thead>';
    echo '<tr><th>Options</th><th>ID</th><th>Name</th><th>Institution</th><th>Username</th><th>Email</th></tr>';
    echo '</thead>';
    echo '</div>';
    foreach ($registration as $user) {
        echo '<tr><td><a href="userdetails.php?x=' .$user['ID'] .'">VIEW </a>&nbsp|&nbsp<a href="userupdate.php?x=' .$user['ID'].'">UPDATE USER</a>&nbsp|&nbsp<a href="upload.php?x=' .$user['ID'].'">UPLOAD PICTURE</a>';
        if(isset($_SESSION['userid']) && $_SESSION['usertype'] == 1)
    {
        echo ' | <a href="userdelete.php?x=' .$user['ID'] .'">DELETE USER</a>';
    }

    echo '<td>'.$user['ID'].'</td><td>' .$user['lastName'] . ', ' . $user['firstName'] . ' ' . $user['middleName'] . '.</td><td>' . $user['institution'] . '</td><td>' . $user['userName']. '</td><td>'. $user['email'] . '</td></tr>';
}

echo '</table>';
include_once 'footer.php';
?>


