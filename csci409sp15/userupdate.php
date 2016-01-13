<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 3/17/2015
 * Time: 1:18 PM
 */

$title='User Update';
include_once 'header.php';
include_once 'menu.php';
require_once 'connect.php';
require_once 'functions.inc.php';

//CHECK IF USER IS LOGGED IN AND IF THE USER IS AN ADMIN
$showcontent =0;  //do not show content by default


if(!isset($_SESSION['userid']))
{
    header("Location: login.php");
    exit();
}
else
{

if (isset($_POST['submit'])) {
    $_GET['x'] = $_POST['x'];
}

    if($_SESSION['usertype'] == 2){

//        echo'userid:' .$_SESSION['userid'];
//        echo'get: '.$_GET['x'];

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
        echo 'Only administrators can view this content.  ';
        $showcontent = 0;
    }
}
//END LOGIN

if($showcontent == 1) {

    echo '<div class="link"><p><a class ="linkto" href="userlist.php">RETURN TO USER LIST</a></p></div>';
    echo '<div class="link"><p><a class = "linkto" href="upload.php">CLICK HERE TO UPDATE PROFILE PICTURE</a></p></div>';
//SET VARIABLES WE WILL NEED LATER
    $showform = 0;
    $errormessage = "";


    if (isset($_POST['submit']))
    {

        $FORMFIELD['firstName'] = htmlchars(cleanse($_POST['firstName']));
        $FORMFIELD['middleName'] = htmlchars(cleanse($_POST['middleName']));
        $FORMFIELD['lastName'] = htmlchars(cleanse($_POST['lastName']));
        $FORMFIELD['userName'] = strtolower(cleanse($_POST['userName']));

        $FORMFIELD['rank'] = htmlchars(cleanse($_POST['rank']));
        $FORMFIELD['institution'] = htmlchars(cleanse($_POST['institution']));
        $FORMFIELD['address1'] = htmlchars(cleanse($_POST['address1']));
        $FORMFIELD['address2'] = htmlchars(cleanse($_POST['address2']));
        $FORMFIELD['address3'] = htmlchars(cleanse($_POST['address3']));
        $FORMFIELD['city'] = htmlchars(cleanse($_POST['city']));
        $FORMFIELD['state'] = htmlchars(cleanse($_POST['state']));
        $FORMFIELD['zip'] = htmlchars(cleanse($_POST['zip']));
        $FORMFIELD['email'] = strtolower(cleanse($_POST['email']));
        $FORMFIELD['telephone'] = htmlchars(cleanse($_POST['telephone']));
        $FORMFIELD['secQ'] = htmlchars(cleanse($_POST['secQ']));
        $FORMFIELD['secA'] = htmlchars(cleanse($_POST['secA']));
        $FORMFIELD['membership'] = htmlchars(cleanse($_POST['membership']));


    //Check telephone
    if (!preg_match('/^[0-9]{10}$/', $FORMFIELD['telephone'])) {
        $errormessage .= '<p>The telephone does not have the required format</p>';
    }

    //See if username  has already been used
    try {
        $sql = ('SELECT * FROM registration WHERE userName = :userName AND ID != :ID');
        $q = $pdo->prepare($sql);
        $q->bindValue(':userName', $FORMFIELD['userName']);
        $q->bindValue(':ID', $_POST['x']);
        $q->execute();
        $count = $q->rowCount();
    } catch (PDOException $e) {
        echo 'Error fetching users: ' . $e->getMessage();
        exit();
    }
    if ($count > 0) {
        $errormessage .= '<p>The user name you entered has already been taken.</p>';
    }

    //See if email has already been used
    try {
        $sql = ('SELECT * FROM registration WHERE email = :email AND ID != :ID');
        $q = $pdo->prepare($sql);
        $q->bindValue(':email', $FORMFIELD['email']);
        $q->bindValue(':ID', $_POST['x']);
        $q->execute();
        $count = $q->rowCount();
    }
    catch (PDOException $e) {
        echo 'Error fetching users: ' . $e->getMessage();
        exit();
    }
    if($count > 0){
        $errormessage .= '<p>The email entered has already been taken.</p>';
    }


    if ($errormessage != "") {
        echo $errormessage;

    }
    else{
        try {
            $sql = 'UPDATE registration SET
                    firstName = :firstName,
                    middleName = :middleName,
                    lastName = :lastName,
                    userName = :userName,
                    rank = :rank,
                    institution = :institution,
                    address1 = :address1,
                    address2 = :address2,
                    address3 = :address3,
                    city = :city,
                    state = :state,
                    zip = :zip,
                    email = :email,
                    telephone = :telephone,
                    secQ = :secQ,
                    secA = :secA,
                    membership = :membership
                    WHERE ID = :ID';


            $s = $pdo->prepare($sql);
            $s->bindValue(':firstName', $FORMFIELD['firstName']);
            $s->bindValue(':middleName', $FORMFIELD['middleName']);
            $s->bindValue(':lastName', $FORMFIELD['lastName']);
            $s->bindValue(':userName', $FORMFIELD['userName']);
            $s->bindValue(':rank', $FORMFIELD['rank']);
            $s->bindValue(':institution', $FORMFIELD['institution']);
            $s->bindValue(':address1', $FORMFIELD['address1']);
            $s->bindValue(':address2', $FORMFIELD['address2']);
            $s->bindValue(':address3', $FORMFIELD['address3']);
            $s->bindValue(':city', $FORMFIELD['city']);
            $s->bindValue(':state', $FORMFIELD['state']);
            $s->bindValue(':zip', $FORMFIELD['zip']);
            $s->bindValue(':email', $FORMFIELD['email']);
            $s->bindValue(':telephone', $FORMFIELD['telephone']);
            $s->bindValue(':secQ', $FORMFIELD['secQ']);
            $s->bindValue(':secA', $FORMFIELD['secA']);
            $s->bindValue(':membership', $FORMFIELD['membership']);
            $s->bindValue(':ID', $_POST['x']);
            $s->execute();
        }
        catch(PDOException $e){


            echo 'Error inserting into database' . $e->getMessage();
            exit();
        }
        //If there are no errors and the query runs
        echo '<p>Your information has been updated...Returning to user list...</p>';
        header( "refresh:3;url=userlist.php" );
        $showform = 1;
    }
}
if($showform == 0) {

    try {
        $sql = 'SELECT * FROM registration WHERE ID = :ID';
        $s = $pdo->prepare($sql);
        $s->bindValue(':ID', $_GET['x']);
        $s->execute();

    }
    catch (PDOException $e) {
        echo 'Error fetching users: ' . $e->getMessage();
        exit();
    }
    $row = $s->fetch();
    ?>
<div class="link">
    <form name="userupdate" id="userupdate" method="post" action="userupdate.php">

        <table>
            <tr><th>Membership</th><td><label for="membership"></label>
                    <select name="membership" id="membership" required="">
                        <option value="" <?php if(isset($row['membership'])&& $row['membership'] == ''){echo ' selected';}?>>Choose Membership</option>
                        <option value="regular" <?php if(isset($row['membership']) && $row['membership'] == 'regular'){echo ' selected';}?>>regular</option>
                        <option value="undergraduate" <?php if(isset($row['membership']) && $row['membership'] == 'undergraduate'){echo ' selected';}?>>undergraduate</option>
                        <option value="graduate" <?php if(isset($row['membership']) && $row['membership'] == 'graduate'){echo ' selected';}?>>graduate</option
                    </select></td></tr>

            <tr><th>First Name:</th><td><label for="firstName"></label><input type="text" name="firstName" autofocus="autofocus" id="firstName" size="30" maxlength="30" value="<?php if(isset($row['firstName'])){echo $row['firstName'];}?>" required>*</td></tr>
            <tr><th>Middle Initial:</th><td><label for="middleName"></label><input type="text" name="middleName" id="middleName" size="30" maxlength="1" value="<?php if(isset($row['middleName'])){echo $row['middleName'];}?>">*</td></tr>
            <tr><th>Last Name:</th><td><label for="lastName"></label><input type="text" name="lastName" id="lastName" size="30" maxlength="30" value="<?php if(isset($row['lastName'])){echo $row['lastName'];}?>" required>* </td></tr>
            <tr><th>User Name:</th><td><label for="userName"></label><input type="text" name="userName" id="userName" size="30" maxlength="30" value="<?php if(isset($row['userName'])){echo $row['userName'];}?>" required>*</td></tr>

            <tr><th>Rank</th><td><label for="rank"></label><input type="text" name="rank" id="rank" size="30" maxlength="30" value="<?php if(isset($row['rank'])){echo $row['rank'];}?>" required </td>*</tr>
            <tr><th>Institution</th><td><label for="institution"></label><input type="text" name="institution" id="institution" size="30" maxlength="30" value="<?php if(isset($row['institution'])){echo $row['institution'];}?>" required>*</td></tr>
            <tr><th>Address 1</th><td><label for="address1"></label><input type="text" name="address1" id="address1" size="30" maxlength="100" value="<?php if(isset($row['address1'])){echo $row['address1'];}?>" required>*</td></tr>
            <tr><th>Address 2</th><td><label for="address2"></label><input type="text" name="address2" id="address2" size="30" maxlength="100" value="<?php if(isset($row['address2'])){echo $row['address2'];}?>" ></td></tr>
            <tr><th>Address 3</th><td><label for="address3"></label><input type="text" name="address3" id="address3" size="30" maxlength="100" value="<?php if(isset($row['address3'])){echo $row['address3'];}?>" ></td></tr>
            <tr><th>City</th><td><label for="city"></label><input type="text" name="city" id="city" size="30" maxlength="30" value="<?php if(isset($row['city'])){echo $row['city'];}?>" required>*</td></tr>
            <tr><th>State</th><td><label for="state"></label><input type="text" name="state" id="state" size="30" maxlength="2" value="<?php if(isset($row['state'])){echo $row['state'];}?>" placeholder = "XX" required>*</td></tr>
            <tr><th>Zip</th><td><label for="zip"></label><input type="text" name="zip" id="zip" size="30" maxlength="10" value="<?php if(isset($row['zip'])){echo $row['zip'];}?>" required>*</td></tr>
            <tr><th>Email</th><td><label for="email"></label><input type="email" name="email" id="email" size="30" maxlength="30" value="<?php if(isset($row['email'])){echo $row['email'];}?>" placeholder="email@email.com" required>*</td></tr>
            <tr><th>Phone</th><td><label for="telephone"></label><input type="text" name="telephone" id="telephone" size="30" maxlength="10" value="<?php if(isset($row['telephone'])){echo $row['telephone'];}?>" placeholder="XXXXXXXXXX" required>*</td></tr>


            <tr><th>Security Question</th><td><label for="secQ"></label>
                    <select name="secQ" id="secQ" required="">
                        <option value="" <?php if(isset($row['secQ']) && $row['secQ'] == ''){echo ' selected';}?>>Choose a security question</option>
                        <option value="q1" <?php if(isset($row['secQ']) && $row['secQ'] == 'q1'){echo ' selected';}?>>What is the name of you first pet?</option>
                        <option value="q2" <?php if(isset($row['secQ']) && $row['secQ'] == 'q2'){echo ' selected';}?>>What is your maiden name?</option>
                        <option value="q3" <?php if(isset($row['secQ']) && $row['secQ'] == 'q3'){echo ' selected';}?>>What is your favourite food?</option
                    </select></td></tr>

            <tr><th>Answer to Security Question</th><td><label for="secA"></label><input type="text" name="secA" id="secA" size="30" maxlength="250" value="<?php if(isset($row['secA'])){echo $row['secA'];}?>" required>*</td></tr>
            <p>* Indicates a required field.</p>
            <tr><td><input type="hidden" name="x" id="x" value="<?php echo $row['ID'];?>"></td></tr>
            <tr><th>SUBMIT</th><td><input type="submit" name="submit" value="submit"></td></tr>
        </table>
    </form>
</div>
<?php
}//END SHOWFORM
}//END SHOW CONTENT
include_once 'footer.php';
?>

