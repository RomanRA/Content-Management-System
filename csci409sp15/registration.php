<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 2/5/2015
 * Time: 11:38 AM
 */
$title="Registration";

include_once 'header.php';
include_once 'menu.php';
require_once 'connect.php';
require_once 'functions.inc.php';

$showform = 0;
$errormessage ="";

//IF USER IS LOGGED IN THEY CANNOT REGISTER
if(isset($_SESSION['userid']))
{
    echo '<h4 class="linkto"> You can not create a user while logged in. Please logout.</h4>';
    header("refresh:2;url=index.php");
    exit();
}



if(isset($_POST['submit'])) {


    $FORMFIELD['firstName'] = htmlchars(cleanse($_POST['firstName']));
    $FORMFIELD['middleName'] = htmlchars(cleanse($_POST['middleName']));
    $FORMFIELD['lastName'] = htmlchars(cleanse($_POST['lastName']));
    $FORMFIELD['userName'] = strtolower(cleanse($_POST['userName']));

    $FORMFIELD['password'] = cleanse($_POST['password']);
    $FORMFIELD['password2'] = cleanse($_POST['password2']);

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
    $FORMFIELD['secA'] = htmlchars(cleanse($_POST['secA']));
    $FORMFIELD['membership'] = htmlchars(cleanse($_POST['membership']));


    //Check if password are the same
    if ($FORMFIELD['password'] != $FORMFIELD['password']) {
        $errormessage .= "<p> The passwords you entered do not match.</p>";
    }
    if(!preg_match('/^(?=.*\d)(?=.*[A-Z]).{8,}$/', $FORMFIELD['password'])) {
      $errormessage .= '<p class="passerror">The password does not meet the requirements! A password requires at LEAST 1 NUMBER and 1 LETTER and MINIMUM 8 character MAX of 10.</p>';
    }
    //Check telephone
    if(!preg_match('/^[0-9]{10}$/',$FORMFIELD['telephone'])){
        $errormessage .= '<p>The telephone does not have the required format</p>';
    }

    //See if username  has already been used
    try {
        $sql = ('SELECT * FROM registration WHERE userName = :userName ');
        $q = $pdo->prepare($sql);
        $q->bindValue(':userName', $FORMFIELD['userName']);
        $q->execute();
        $count = $q->rowCount();
    }
    catch(PDOException $e){
        echo 'Error fetching users: ' . $e->getMessage();exit();
    }
    if($count > 0){
    $errormessage .= '<p>The user name you entered has already been taken.</p>';
    }

    //See if email has already been used
    try {
        $sql = ('SELECT * FROM registration WHERE email = :email');
        $q = $pdo->prepare($sql);
        $q->bindValue(':email', $FORMFIELD['email']);
        $q->execute();
        $count = $q->rowCount();
    }
    catch(PDOException $e){
        echo 'Error fetching users: ' . $e->getMessage();exit();
    }
    if($count > 0){
        $errormessage .= '<p>The email entered has already been taken.</p>';
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
        try{
            //Code to enter database
            $sql = 'INSERT INTO registration SET
                    firstName = :firstName,
                    middleName = :middleName,
                    lastName = :lastName,
                    userName = :userName,
                    password  = :password,
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
                    salt = :salt,
                    secQ = :secQ,
                    secA = :secA,
                    tempPass = :tempPass,
                    userType = :userType,
                    loginAttempt = :loginAttempt,
                    membership = :membership,
                    inputDate = CURDATE()';

            $s = $pdo->prepare($sql);
            $s->bindValue(':firstName', $FORMFIELD['firstName']);
            $s->bindValue(':middleName', $FORMFIELD['middleName']);
            $s->bindValue(':lastName', $FORMFIELD['lastName']);
            $s->bindValue(':password', $securePassword);
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
            $s->bindValue(':salt', $salt);
            $s->bindValue(':secQ', $FORMFIELD['secQ']);
            $s->bindValue(':secA', $FORMFIELD['secA']);
            $s->bindValue(':membership', $FORMFIELD['membership']);
            $s->bindValue(':tempPass', 0);//THIS DOESNT PUT 0 INTO DATABASE
            $s->bindvalue(':userType', 2);
            $s->bindvalue(':loginAttempt', 0);//THIS DOESNT PUT 0 INTO DATABASE
            $s->execute();
        }//End try
        catch(PDOException $e){echo 'Error inserting data into database' . $e->getMessage();exit();}
        echo 'Successfully entered into database.';

        //$_SESSION['userName'] = $FORMFIELD['userName'];
        $showform = 1;
        header( "refresh:3;url=login.php" );
    }//end else
}//end if


if($showform == 0)
{
?>

<div class ="container">
    <div class = "row">
        <div class = "col-lg-9">
            <div class ="panel panel-default">
                <div class ="panel-body">
                    <div class= "page-header">
                        <h3><b>Registration</b></h3>

                        <hr>

                            <form class="form-horizontal" role="form" name="registration" id="registration" method="post" action="registration.php">
                                <div class="form-group">
                                    <label for="membership" class="col-lg-2 control-label">* Membership</label>
                                    <div class="col-sm-4">
                                        <select  class="form-control" name="membership" id="membership" required="">
                                            <option value=""<?php if(isset($FORMFIELD['membership'])){echo $FORMFIELD['membership'];}?>>Select membership</option>
<!--                                            <option value="guest">Guest</option>-->
                                            <option value="regular">Regular</option>
                                            <option value="undergraduate">Undergraduate</option>
                                            <option value="graduate">Graduate</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label for="firstName" class="col-lg-2 control-label">* First Name</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="firstName" autofocus="autofocus" id="firstName" size="30" maxlength="30" value="<?php if(isset($FORMFIELD['firstName'])){echo $FORMFIELD['firstName'];}?>" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="middleName" class="col-lg-2 control-label">Middle Initial</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="middleName" id="middleName" size="30" maxlength="1" value="<?php if(isset($FORMFIELD['middleName'])){echo $FORMFIELD['middleName'];}?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="lastName" class="col-lg-2 control-label">* Last Name</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="lastName" id="lastName" size="30" maxlength="30" value="<?php if(isset($FORMFIELD['lastName'])){echo $FORMFIELD['lastName'];}?>" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="userName" class="col-lg-2 control-label">* Username</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="userName" id="userName" size="30" maxlength="30" value="<?php if(isset($FORMFIELD['userName'])){echo $FORMFIELD['userName'];}?>" required>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="password" class="col-lg-2 control-label">* Password</label>
                                    <div class="col-sm-4">
                                        <input type="password" class="form-control" name="password" id="password" size="30" maxlength="10" value="<?php if(isset($FORMFIELD['password'])){echo $FORMFIELD['password'];}?>" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="password2" class="col-lg-2 control-label">* Confirm Password</label>
                                    <div class="col-sm-4">
                                        <input type="password" class="form-control" name="password2" id="password2" size="30" maxlength="10" value="<?php if(isset($FORMFIELD['password2'])){echo $FORMFIELD['password2'];}?>" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="rank" class="col-lg-2 control-label">* Rank</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="rank" id="rank" size="30" maxlength="30" value="<?php if(isset($FORMFIELD['rank'])){echo $FORMFIELD['rank'];}?>" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="institution" class="col-lg-2 control-label">* Institution</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="institution" id="institution" size="30" maxlength="30" value="<?php if(isset($FORMFIELD['institution'])){echo $FORMFIELD['institution'];}?>" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="address1" class="col-lg-2 control-label">* Address 1</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="address1" id="address1" size="30" maxlength="30" value="<?php if(isset($FORMFIELD['address1'])){echo $FORMFIELD['address1'];}?>" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="address2" class="col-lg-2 control-label">Address 2</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="address2" id="address2" size="30" maxlength="30" value="<?php if(isset($FORMFIELD['address2'])){echo $FORMFIELD['address2'];}?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="address3" class="col-lg-2 control-label"> Address 3</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="address3" id="address3" size="30" maxlength="30" value="<?php if(isset($FORMFIELD['address3'])){echo $FORMFIELD['address3'];}?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="city" class="col-lg-2 control-label">* City</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="city" id="city" size="30" maxlength="30" value="<?php if(isset($FORMFIELD['city'])){echo $FORMFIELD['city'];}?>"required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="state" class="col-lg-2 control-label">* State</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="state" id="state" size="30" maxlength="2" value="<?php if(isset($FORMFIELD['state'])){echo $FORMFIELD['state'];}?>"  placeholder = "XX" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="zip" class="col-lg-2 control-label">* Zip</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="zip" id="zip" size="30" maxlength="9" value="<?php if(isset($FORMFIELD['zip'])){echo $FORMFIELD['zip'];}?>" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="email" class="col-lg-2 control-label">* Email</label>
                                    <div class="col-sm-4">
                                        <input type="email" class="form-control" name="email" id="email" size="30" maxlength="30" value="<?php if(isset($FORMFIELD['email'])){echo $FORMFIELD['email'];}?>" placeholder="email@email.com" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="telephone" class="col-lg-2 control-label">* Telephone</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="telephone" id="telephone" size="30" maxlength="10" value="<?php if(isset($FORMFIELD['telephone'])){echo $FORMFIELD['telephone'];}?>" placeholder="XXXXXXXXXX" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="secQ" class="col-lg-2 control-label">* Security Question</label>
                                    <div class="col-sm-4">
                                        <select  class="form-control" name="secQ" id="secQ" required="">
                                            <option value=""<?php if(isset($FORMFIELD['secQ'])){echo $FORMFIELD['secQ'];}?>>Choose a security question</option>
                                            <option value="q1">What is the name of you first pet?</option>
                                            <option value="q2">What is your maiden name?</option>
                                            <option value="q3">What is your favourite food?</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="secA" class="col-lg-2 control-label">* Answer to Security Question</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="secA" id="secA" size="30" maxlength="250" value="<?php if(isset($FORMFIELD['secA'])){echo $FORMFIELD['secA'];}?>" required>
                                    </div>
                                </div>
                                <p>(*) Indicates required field.</p>

                                <div class = "modal-footer">
                                    <button class = "btn btn-default" type="reset">Clear</button>
                                    <button class ="btn btn-inverse" name="submit" id="submit" type="submit"> Submit and login</button>
                                </div>

                        <!--        <tr><a href="javascript:history.back(-1)">Go Back</a></tr>-->
                            </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


    <?php
}//End show form

include_once 'footer.php';

?>
