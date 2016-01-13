<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 3/4/2015
 * Time: 4:11 PM
 */
$title='Update Password';
include_once 'header.php';
include 'menu.php';
require_once 'connect.php';
require_once 'functions.inc.php';


$showform = 0;
$errormessage = "";

if(isset($_POST['submit'])) {

    $FORMFIELD['password'] = cleanse($_POST['password']);
    $FORMFIELD['password2'] = cleanse($_POST['password2']);

    //Check if password are the same
    if ($FORMFIELD['password'] != $FORMFIELD['password2']) {
        $errormessage .= '<p> The passwords you entered do not match.</p>';
    }
    if(!preg_match('/^(?=.*\d)(?=.*[A-Z]).{8,}$/', $FORMFIELD['password'])) {
        $errormessage .= '<p class="passerror">The password does not meet the requirements! A password requires at LEAST 1 NUMBER and 1 LETTER and MINIMUM 8 character MAX of 10.</p>';
    }

    if($errormessage !=""){
        echo $errormessage;
    }
    else{

        $costParam = rand(10,15);
        $char22 = "";

        for($i=0; $i<22; $i++)
        {
            $char22 .= substr('./ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', mt_rand(0,63), 1);
        }

        $salt = '$2y$' . $costParam . '$' . $char22;
        $securePassword = crypt($FORMFIELD['password'],$salt);

        //echo $securePassword;
        echo 'Password has been updated';

        try{
            $sql3 = 'UPDATE registration SET password = :password WHERE userName = :userName';
            $statement3 = $pdo->prepare($sql3);
            $statement3->bindValue(':password', $securePassword);
            $statement3->bindValue(':userName',$_SESSION['userName']);
            $statement3->execute();

            $sql4 = 'UPDATE registration SET salt = :salt WHERE userName = :userName';
            $statement4 = $pdo->prepare($sql4);
            $statement4->bindValue(':salt', $salt);
            $statement4->bindValue(':userName',$_SESSION['userName']);
            $statement4->execute();

        }
        catch(PDOException $e){
            echo 'Error updating password: ' . $e->getMessage();exit();
        }

        //  RESET TEMP PASS FLAG
        try{
            $sql2 = 'UPDATE registration SET tempPass = :tempPass WHERE userName = :userName';
            $statement = $pdo->prepare($sql2);
            $statement->bindValue(':tempPass', 0);
            $statement->bindValue(':userName',$_SESSION['userName']);
            $statement->execute();

        }
        catch(PDOException $e){
            echo 'Error fetching tempPass: ' . $e->getMessage();exit();
        }

        $showform=1;
        header("location:logout.php");


    }//ENDELSE

}
if($showform == 0)
{
?>
    <div class ="container">
        <div class = "row">
            <div class = "col-lg-9">
                <div class ="panel panel-default">
                    <div class ="panel-body">
                        <div class= "page-header">

                        <h3><b>Update Password</b></h3>
                        <form class="form-horizontal" role="form" name="updatepassword" id="updatePassword" method="post" action="updatepassword.php ">

                        <div class="form-group">
                            <label for="password" class="col-lg-2 control-label">* New Password</label>
                            <div class="col-sm-4">
                                <input type="password" class="form-control" name="password" id="password" size="30" maxlength="10" value="" autofocus="autofocus" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password2" class="col-lg-2 control-label">* Confirm Password</label>
                            <div class="col-sm-4">
                                <input type="password" class="form-control" name="password2" id="password2" size="30" maxlength="10" value="" required>
                            </div>
                        </div>
                        <p>(*) Indicates a required field.</p>
                        <div class = "modal-footer">
                            <button class = "btn btn-default" type="reset">Clear</button>
                            <button class ="btn btn-inverse" name="submit" id="submit" type="submit">Submit</button>
                        </div>


                        <!--    <table>-->
                    <!--        <tr><th>New Password</th><td><label for="password"></label><input type="password" name="password" id="password" size="30" maxlength="10" value="" autofocus="autofocus" required>*</td></tr>-->
                    <!--        <tr><th>Confirm Password</th><td><label for="password2"></label><input type="password" name="password2" id="password2" size="30" maxlength="10" value="" required>*</td></tr>-->
                    <!--        <tr><td><input type="submit" name="submit" value="submit"></td></tr>-->
                    <!--    </table>-->
                    </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
}
?>
<?php
include_once 'footer.php';
?>