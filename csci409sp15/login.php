<?php

/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 2/9/2015
 * Time: 12:54 PM
 */
$title ="Login";
include_once 'header.php';
include_once 'menu.php';
require_once 'connect.php';
require_once 'functions.inc.php';


$showform = 0;
$errormessage = "";

//if isset($_GET[USERID])
if(isset($_GET["x"])){
    //UPDATE TABLE ATTEMPT = 0 WHERE ID = $_GET[USERID]
    try {

        $sql5 = 'UPDATE registration SET loginAttempt = :loginAttempt  WHERE ID = :ID';
        $s5 = $pdo->prepare($sql5);
        $s5->bindValue(':loginAttempt', 0);
        $s5->bindValue(':ID', $_GET["x"]);
        $s5->execute();
        $_SESSION["attempts"] = 0;


    } catch (PDOException $e) {
        echo 'Error resetting login attempts: ' . $e->getMessage();
        exit();
    }

}//END ISSET USERID



//USER PRESSES SUBMIT
if (isset ($_POST['submit'])) {
    $FORMFIELD['userName'] = strtolower(htmlchars(cleanse($_POST['userName'])));
    $FORMFIELD['password'] = cleanse($_POST['password']);


    //CHECK IF ACCOUNT IS LOCKED
    //IF IS NOT SET SET NUMBER OF LOGIN ATTEMPTS
    if (!isset($_SESSION["attempts"])) {
        $_SESSION["attempts"] = 0;
    }

    //IF USER IS ALLOWED TO LOGIN
    if ($_SESSION["attempts"] < 5) {

        //CLEANSE DATA THE SAME AS THE REGISTRATION PAGE
        $FORMFIELD['userName'] = strtolower(htmlchars(cleanse($_POST['userName'])));
        $FORMFIELD['password'] = cleanse($_POST['password']);


        //Need to get the username and salt from table in database
        try {

            $sql = 'SELECT userName, salt FROM registration WHERE userName = :userName';
            $statement = $pdo->prepare($sql);
            $statement->bindValue(':userName', $FORMFIELD['userName']);
            $statement->execute();
            $count = $statement->rowCount();

        } catch (PDOException $e) {
            echo 'Error getting user: ' . $e->getMessage();
            exit();
        }
        //CHECK IF USER EXISTS

        if ($count < 1) {
            echo "<p>That user does not exist.</p>";
        }
        //IF USER EXISTS
        else{
            $row = $statement->fetch();
            $confirmedusername = $row['userName'];
            $confirmedsalt = $row['salt'];
            $securepwd = crypt($FORMFIELD['password'], $confirmedsalt);//rehashing salt from db and password entered by user


            //GET USER AND CHECK NUMBER OF LOGIN ATTEMPTS
            try {
                $sql2 = 'SELECT * FROM registration WHERE userName = :userName AND password = :password AND loginAttempt < 5 ';
                $s2 = $pdo->prepare($sql2);
                $s2->bindValue(':userName', $confirmedusername);
                $s2->bindValue(':password', $securepwd);
                $s2->execute();
                $count2 = $s2->rowCount();

            }
            catch (PDOException $e2) {
                echo 'Error fetching user: ' . $e2->getMessage();

                exit();
            }//END ELSE

            $row2 = $s2->fetch();

            //IF LOGIN FAILS TO MATCH
            if ($count2 != 1) {
                try {

                    $sql3 = 'SELECT loginAttempt,email,salt FROM registration WHERE userName = :userName';
                    $s3 = $pdo->prepare($sql3);
                    $s3->bindValue(':userName', $confirmedusername);
                    $s3->execute();

                    $row = $s3->fetch();
                    $email = $row['email'];
                    $loginAttempt = $row['loginAttempt'];
                    $loginAttempt++;


                    $sql4 = 'UPDATE registration SET loginAttempt = :loginAttempt  WHERE userName = :userName';
                    $s4 = $pdo->prepare($sql4);
                    $s4->bindValue(':loginAttempt', $loginAttempt);
                    $s4->bindValue(':userName', $FORMFIELD['userName']);
                    $s4->execute();

                    echo "Login attempt: " . $loginAttempt . ".  ";
                    $_SESSION["attempts"] = $loginAttempt;

                    //IF USER AND PASSWORD DON'T MATCH
                    if ($loginAttempt < 5) {
                        echo "<b>" . "The username and password combination you entered is not correct." . "</b>";
                    }
                    else {
                        //IF LOGIN ATTEMPTS ARE GREATER THAN ALLOWED **EMAIL** A TEMPORARY PASSWORD TO USER
                        echo "<b>" . "The account associated with that user has been locked due to too many log in attempts." . "</b>";


                        $to = $email;
                        $salt = $row['salt'];

                        //GENERATE TEMPORARY PASSWORD
                        $newPassword = rand(10530, 15530);


                        //CHANGE THE PASSWORD VALUE AND REHASH TEMP WITH THE SALT
                        $newSecurePassword = crypt($newPassword, $row['salt']);


                        try {
                            //echo "New secure password is: ".$newSecurePassword;
                            //echo "The salt is: ".$row['salt'];
                            //echo 'In try UPDATE';
                            $sql2 = 'UPDATE registration SET password = :password WHERE userName = :userName';
                            $statement2 = $pdo->prepare($sql2);
                            $statement2->bindValue(':password', $newSecurePassword);
                            $statement2->bindValue(':userName', $FORMFIELD['userName']);
                            $statement2->execute();

                            //set new temp pass
                            $sql3 = 'UPDATE registration SET tempPass = :tempPass WHERE userName =:userName';
                            $statement3 = $pdo->prepare($sql3);
                            $statement3->bindValue(':tempPass', 1);
                            $statement3->bindValue(':userName', $FORMFIELD['userName']);
                            $statement3->execute();

//
//                            //RESET LOGIN ATTEMPTS
//                            $sql5 = 'UPDATE registration SET loginAttempt = :loginAttempt  WHERE userName = :userName';
//                            $s5 = $pdo->prepare($sql5);
//                            $s5->bindValue(':loginAttempt', 0);
//                            $s5->bindValue(':userName', $FORMFIELD['userName']);
//                            $s5->execute();


                        } catch (PDOException $e) {
                            echo 'Error updating database' . $e->getMessage();
                            exit();
                        }

                        //GET USERID TO APPEND TO EMAIL WITH TEMPORARY PASSWORD
                        $sql5 = 'SELECT ID FROM registration  WHERE userName = :userName';
                        $s5 = $pdo->prepare($sql5);
                        $s5->bindValue(':userName', $FORMFIELD['userName']);
                        $s5->execute();
                        $row=$s5->fetch();

                        //SET USERID SESSION VARIABLE
                        $_SESSION['userid'] = $row['ID'];

                        //CREATE A LINK TO SEND TO USER
                        $link = 'http://ccuresearch.coastal.edu/raroman/csci409sp15/seasecs/login.php?x='.$_SESSION['userid'];

                        $subject = "Temporary Password";
                        $from = "raroman@g.coastal.edu";
                        $message = 'Your new password is: "'.$newPassword.'". Please click link below and login with your temporary password.'.$link  ;

                        //SEND EMAIL
                        if (mail($to, $subject, $message, "From: " . $from)) {

                            echo "<br><b>" . 'An email with your temporary password has been sent to the registered email' . "<b>";
                            //REFRESH PAGE TO LOGIN USER SHOULD BE LOCKED OUT
                            header("refresh:5;url=login.php");
                            $showform = 1;

                        } else {
                            echo 'Some went wrong.';
                        }
                    }
                }//end ry
                catch (PDOException $e2) {
                    echo 'Error fetching user: ' . $e2->getMessage();

                    exit();
                }//End else
            }

            else {

                $sql5 = 'UPDATE registration SET loginAttempt = :loginAttempt  WHERE userName = :userName';
                $s5 = $pdo->prepare($sql5);
                $s5->bindValue(':loginAttempt', 0);
                $s5->bindValue(':userName', $FORMFIELD['userName']);
                $s5->execute();

                $_SESSION['userid'] = $row2['ID'];
                //echo "Session id: ".$_SESSION['userid'];
                $_SESSION['userName'] = $confirmedusername;
                $_SESSION['firstName'] = $row2['firstName'];
                $_SESSION['usertype'] = $row2['userType'];
                $_SESSION["attempts"] = 1;

                $showform = 1;

                //CHECK TEMP FLAG FOR TEMP PASSWORD
                $_SESSION['tempPassUser'] = $row2['tempPass'];

                //if user is using temp redirect  to change password
                if($_SESSION['tempPassUser']){
                    echo 'Redirecting...User must change password';
                    header( "refresh:3;url=updatepassword.php" );
                }
                else{
                    header("Location: index.php");
                }

            }
        }
    }
    //USER IS NOT ALLOWED TO LOGIN
    else{
        echo "That account has been locked.";
    }
}//END IF ISSET


if($showform == 0)
{
?>
    <div class ="container">
        <div class = "row">
            <div class = "col-lg-9">
                <div class ="panel panel-default">
                    <div class ="panel-body">
                        <div class= "page-header">

                        <h3><b>Log In</b></h3>
                        <div class = "modal-footer"><a href="emailusername.php">Forgot Username?</a>
                        <a href="passwordreset.php">Forgot Password?</a></div>

            <p></p>

            <form class ="form-horizontal" role="form" autocomplete="on" name="loginForm" id="loginForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

                <div class="form-group">
                    <label for="userName" class="col-lg-2 control-label">* User Name</label>
                    <div class="col-sm-4">
                        <input type="text" class ="form-control" name="userName" id="userName" size="30" maxlength="30" placeholder="Username goes here" autofocus required  >
                    </div>
                </div>

                <div class="form-group">
                    <label for="pwd" class="col-lg-2 control-label">* Password</label>
                    <div class="col-sm-4">
                        <input type="password" class ="form-control" name="password" id="password" size="30" maxlength="10" placeholder="Password goes here" required >
                    </div>
                </div>

                <p>(*) Indicates required field.</p>

                <div class = "modal-footer">
                    <button class = "btn btn-default" type="reset">Clear</button>
                    <button class ="btn btn-inverse" name="submit" id="submit" type="submit"> Submit and login</button>
                </div>
                <div><h4 id="heading"><a style="" href="registration.php">Not a registered member?</a></h4></div>
<!--                <div><h4 id="heading"><a style="" href="conferencereg.php">Registering for conference a guest?</a></h4></div>-->

                <!--<input type="submit" name="submit" value="Login"><input type="reset" value="Reset">-->
            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!--        <table>-->
<!--            <tr>-->
<!--                <td><label for="userName">Username:</label></td>-->
<!--                <td><input type="text" name="userName" autofocus="autofocus" id="userName" size="30" maxlength="30" required>*-->
<!--                </td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td><label for="pwd">Password:</label></td>-->
<!--                <td><input type="password" name="password" id="password" size="30" maxlength="10" required>*<br></td>-->
<!--            </tr>-->
<!--            <td><input type="submit" name="submit" value="Login"><input type="reset" value="Reset"></td>-->
<!--            </tr>-->
<!--           <tr><td><a href="emailusername.php">Forgot User Name</a><td><a href="passwordreset.php">Password Reset</a></td></tr>-->
<!--          <tr><td><a href="registration.php">New Registration Signup</a></td></tr>-->
<!--        </table>-->




<?php
}
?>
<?php
include_once 'footer.php';
?>