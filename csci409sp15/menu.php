<div class="navbar navbar-default navbar-static-top">
    <div class = "container"></div>
        <a href="#" class = "navbar-brand">Seasecs</a>
        <button class ="navbar-toggle" data-toggle = "collapse" data-target= ".navHeaderCollapse">
            <span class = "icon-bar"></span>
            <span class = "icon-bar"></span>
            <span class = "icon-bar"></span>
        </button>
            <div class="collapse navbar-collapse navHeaderCollapse">
                <ul class ="nav navbar-nav navbar-center">
                    <li class = "active"><a href="index.php"><span class="glyphicon glyphicon-home">&nbspHome</span></a></li>
                    <li><a href="registration.php" class>Register</a></li>
                    <li><a href="userlist.php">Manage Registration</a></li>
<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 2/13/2015
 * Time: 1:13 PM
 */
$title='Hello Menu';
require_once 'connect.php';

if(isset($_SESSION['userid']))
{

    try
    {
        $sql = 'SELECT profilePicture FROM registration WHERE ID = :ID';
        $s = $pdo->prepare($sql);
        $s->bindValue(':ID', $_SESSION['userid']);
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

//    echo '<li><a href="updatepassword.php">Change Password</a></li>'.'<li>Hello, ' . $_SESSION['firstName'] . ' ' . ' <a href="logout.php">Log Out</a></li>';
    echo '<li><a href="updatepassword.php">Change Password</a></li>'.'<li><img src="'.$dir ."/". $file .'" onerror="imgError(this);" alt="" class="profilepicture" width="35" height="35" role="presentation"></li> '.'<li><a href="#" >Hello, ' . $_SESSION['firstName'] . '</a></li>' . '<li><a href="logout.php">Log Out</a></li>'  ;
}


else {
    echo "<li><a href='login.php'>Log In </a></li>";
}
?>
                <li><a href="#contact" data-toggle="modal">About</a></li>
            </ul>
        </div>
    </div>
</div>
<div class="modal fade" id="contact" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <p>About</p>
            </div>
            <div class="modal-body">
                <p>Richard A. Roman
                <p>Senior</p></p>
                <p>CSCI 409</p>
                <p>Advanced Web Development</p>
            </div>
            <div class="modal-footer">
                <a class="btn btn-default" data-dismiss="modal">Close</a>
            </div>
        </div>
    </div>
</div>


