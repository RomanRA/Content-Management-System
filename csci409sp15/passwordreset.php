<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 2/11/2015
 * Time: 7:02 PM
 */
$title="Password Reset";
$showform = 0;
$title="Password Reset";
include_once 'header.php';
include_once 'menu.php';
require_once 'connect.php';
require_once 'functions.inc.php';

if(isset($_POST['submit'])) {

    $FORMFIELD['secQ'] = cleanse($_POST['secQ']);
    $FORMFIELD['secA'] = cleanse($_POST['secA']);
    $FORMFIELD['userName'] = strtolower(cleanse($_POST['userName']));

    //CHECK IF USER EXIST
    try {
        //echo 'In try SELECT';
        $sql = 'SELECT userName FROM registration WHERE  userName = :userName';
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':userName',  $FORMFIELD['userName']);
        $statement->execute();
        $count = $statement->rowCount();
    }
    catch(PDOException $e){
        echo 'Error getting information: ' . $e->getMessage();
        exit();
    }
    if($count !=1){
        echo 'That user does not exist';
    }

    //IF USER EXISTS GET SECURITY QUESTIONS AND SEND AN EMAIL
    else{
        try {
            //echo 'In try SELECT';
            $sql = 'SELECT * FROM registration WHERE secQ = :secQ AND secA = :secA AND userName = :userName ';
            $statement = $pdo->prepare($sql);
            $statement->bindValue(':secQ', $FORMFIELD['secQ']);
            $statement->bindValue(':secA', $FORMFIELD['secA']);
            $statement->bindValue(':userName',  $FORMFIELD['userName']);
            $statement->execute();
            $count = $statement->rowCount();
        }
        catch(PDOException $e){
            echo 'Error getting information: ' . $e->getMessage();
            exit();
        }
        $row = $statement->fetch();
        if($count != 1){
            echo "The question and answer do not match.";

        }
        else{

            $to = $row['email'];
            $salt = $row['salt'];

            //generate temp password
            $newPassword = rand(10530,15530);

            //change the password value by rehashing temp with salt
            $newSecurePassword = crypt($newPassword,$row['salt']);

            try{
                //echo "New secure password is: ".$newSecurePassword;
                //echo "The salt is: ".$row['salt'];
                //echo 'In try UPDATE';
                $sql2 = 'UPDATE registration SET password = :password WHERE secQ = :secQ AND secA = :secA';
                $statement2 = $pdo->prepare($sql2);
                $statement2->bindValue(':password', $newSecurePassword);
                $statement2->bindValue(':secQ', $FORMFIELD['secQ']);
                $statement2->bindValue(':secA', $FORMFIELD['secA']);
                $statement2->execute();
                $count = $statement2->rowCount();

                $sql3 = 'UPDATE registration SET tempPass = :tempPass WHERE secQ = :secQ AND secA = :secA';
                $statement3 = $pdo->prepare($sql3);
                $statement3->bindValue(':tempPass', 1);
                $statement3->bindValue(':secQ', $FORMFIELD['secQ']);
                $statement3->bindValue(':secA', $FORMFIELD['secA']);
                $statement3->execute();

            }
            catch(PDOException $e){
                echo 'Error inserting data into database' . $e->getMessage();exit();
            }


            $subject = "Temporary Password";
            $from = "raroman@g.coastal.edu";
            $message = "Your temporary password is '".$newPassword. "'. Once logged in please create a new password. ";
            //echo $message;
            if (mail($to, $subject, $message, "From: " . $from)) {
                echo 'An email  with your temporary password has been sent';
                $showform = 1;
                header( "refresh:5;url=login.php" );
            } else {
                echo 'Some went wrong.';
            }

        }//End else
    }

}//end if isset

if($showform == 0)
{

    ?>
    <div class ="container">
    <form name="secForm" id="secForm" method="post" action="passwordreset.php ">
        <table>
            <tr><th><label for="userName">Username:</label></th><td><input type="text" name="userName" autofocus="autofocus" id="userName" size="30" maxlength="30" value = "<?php if(isset($FORMFIELD['userName'])){echo $FORMFIELD['userName'];}?>"required>*</td></tr>
            <tr><th>Security Question</th><td><label for="secQ"></label>
                  <select name="secQ" id="secQ" required="">
                        <option value="">Choose a security question</option>
                        <option value="q1">What is the name of you first pet?</option>
                        <option value="q2">What is your maiden name?</option>
                        <option value="q3">What is your favourite food?</option
                    </select></td></tr>
            <tr><th>Answer to Security Question</th><td><label for="text"></label><input type="text" name="secA" id="secA" size="30" maxlength="250" value="<?php if(isset($FORMFIELD['secA'])){echo $FORMFIELD['secA'];}?>" required>*</td></tr>
            <tr><td><a style="color:#f5f5f5"href="javascript:history.back(-1)">Go Back</a></tr></td>
            <tr><td><input type="submit" name="submit" value="submit"></td></tr>
        </table>
    </form>
    <p>* Indicates a required field.</p>
    </div>
<?php
}
?>
<?php
include_once 'footer.php';
?>