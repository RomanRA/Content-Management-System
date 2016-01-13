<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 3/9/2015
 * Time: 2:53 PM
 */
$title = 'User Details';
include_once 'header.php';
include_once 'menu.php';
require_once 'connect.php';


$showcontent = 0;  //HIDE CONTENT BY

//CHECK IF USER IS LOGGED IN
if(!isset($_SESSION['userid']))
{
    header("Location: login.php");
    exit();
}
else
{
    //CHECK USER TYPE 1 FOR ADMIN 2 FOR REGULAR USER
    if($_SESSION['usertype'] == 2)
    {
        if($_SESSION['userid'] == $_GET['x'])
        {
            $showcontent = 1;
        }
        else
        {
            echo 'You are not authorized to view this content.';
            $showcontent = 0;
        }
    }
    elseif($_SESSION['usertype'] == 1)
    {
        $showcontent = 1;
    }
    else
    {
        echo 'You are not authorized to view this content.  ADMINISTRATOR ACCESS ONLY!';
        $showcontent = 0;
    }
}//logged in

if($showcontent ==1)
{

    echo '<div class="link"><p><a class="linkto" href="userlist.php">RETURN TO USER LIST</a></p>';

    //CHECK TO SEE IF THIS USERNAME HAS ALREADY BEEN USED
    try
    {
        $sql = 'SELECT * FROM registration WHERE ID = :ID';
        $s = $pdo->prepare($sql);
        $s->bindValue(':ID', $_GET['x']);
        $s->execute();
    }
    catch (PDOException $e)
    {
        echo 'Error fetching users: ' . $e->getMessage();
        exit();
    }

    //get the results and store them in the variable
    $row = $s->fetch();
    $dir = "/raroman/csci409sp15/uploads";
    $file = $row['profilePicture'];

    if($file !=""){
        echo '<table>';
        echo '<tr><th>Profile Picture</th><td><img src="'.$dir ."/". $file .'" alt="Please Upload Profile Picture"/></td></tr>';
    }
    else{
        echo '<table>';
    }


    echo '<tr><th>Membership:</th><td>' . $row['membership'] . '</td></tr>';
    echo '<tr><th>User ID:</th><td>' . $row['ID'] . '</td></tr>';
    echo '<tr><th>Name:</th><td>' . $row['lastName'] . ', ' . $row['firstName']. ' '.$row['middleName']. '.</td></tr>';
    echo '<tr><th>Rank:</th><td>' . $row['rank'] . '</td></tr>';
    echo '<tr><th>Institution:</th><td>' . $row['institution'] . '</td></tr>';

    echo '<tr><th>Address:</th><td>' . $row['address1'] . '</td></tr>';
    echo '<tr><th></th><td>' . $row['address2'] . '</td></tr>';
    echo '<tr><th></th><td>' . $row['address3'] . '</td></tr>';

    echo '<tr><th>Email:</th><td>' . $row['email'] . '</td></tr>';
    echo '<tr><th>Contact Phone:</th><td>' . $row['telephone'] . '</td></tr>';
    echo '<tr><th>Username:</th><td>' . $row['userName'] . '</td></tr>';


    if($row['secQ'] == 'q1'){
        echo '<tr><th>Security Question:</th><td>' . 'What is the name of your first pet?' . '</td></tr>';
    }
    else if ($row['secQ'] == 'q2'){
        echo '<tr><th>Security Question:</th><td>' . 'What is your maiden name?' . '</td></tr>';
    }
    else{
        echo '<tr><th>Security Question:</th><td>' . 'What is your favourite food?' . '</td></tr>';
    }

    echo '<tr><th>Security Answer:</th><td>' . $row['secA'] . '</td></tr>';

    if($row['userType'] == 1){
        echo '<tr><th>User Type:</th><td>' ."Administrator".'</td></tr>';
    }
    else if($row['userType'] == 2){
        echo '<tr><th>User Type**:</th><td>' ."Regular User".'</td></tr>';
    }
    echo '</table></div>';

}//showcontent

include_once 'footer.php';
?>
