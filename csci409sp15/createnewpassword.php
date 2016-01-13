<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 2/13/2015
 * Time: 12:50 PM
 */
$title="Create New Password";

if(isset($_SESSION['userid'])) {
    echo '<p>Hello, ' . $_SESSION['firstName']."<b>Please change your temporary password to a new one.</b> ";

    try{


    }
    catch(PDOException $e){
        echo 'Error inserting data into database' . $e->getMessage();exit();
    }

}
else{

}

header( "refresh:3;url=login.php" );
if($showform == 0)
{
    ?>

    <form name="newPassword" id="newPassword" method="post" action="createnewpassword.php ">
        <table>
            <tr>
                <b>Please change your temporary password.</b>
                <td><label for="tempPass">Current password:</label></td>
                <td><input type="text" name="tempPass" id="tempPass" size="30" maxlength="30" required>*</td>
            </tr>
            <tr>
                <td><label for="tempPass">New password:</label></td>
                <td><input type="text" name="tempPass" id="tempPass" size="30" maxlength="30" required>*</td>
            </tr>
            <tr>
                <td><label for="tempPass">Confirm new password</label></td>
                <td><input type="text" name="tempPass" id="tempPass" size="30" maxlength="30" required>*</td>
            </tr>
            </tr>
                <td><input type="submit" name="submit" value="Change Password"><input type="reset" value="Reset"></td>
            </tr>
        </table>
    </form>
    <p>* Indicates a required field.</p>
<?php
}
?>
<?php
include_once 'footer.php';
?>