<?php
$title='New Password';
include_once 'header.php';
include_once 'menu.php';
?>

    <body id="colorbody">
	<div id="main">
        <div class ="container">
            <div class = "row">
                <div class = "col-lg-9">
                    <div class ="panel panel-default">
                        <div class ="panel-body">
                            <div class= "page-header">
                                <h3><b>Password Reset</b></h3>

                        <form class="form-horizontal" role="form" autocomplete="on" name="newpass" id="newpass" method="post" action="/service/npreq">

                            <div class="form-group">
                                <label for="username" class ="col-lg-2 control-label">* User Name</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="username" id="username" size="30" maxlength="10" value="" autofocus="autofocus" required="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="group" class="col-lg-2 control-label">* Group</label>
                                <div class="col-sm-4">
                                    <select  class="form-control" name="group" id="group" required="">
                                        <option value=""<?php if(isset($FORMFIELD['group'])){echo $FORMFIELD['group'];}?>>Select group</option>
                                        <option value="scholars">Scholars Academy Student</option>
                                        <option value="ugrads">Undergraduate</option>
                                        <option value="grads">Graduate</option>
                                        <option value="staff">University Staff</option>
                                        <option value="faculty">University Faculty</option>
                                        <option value="external">Guest User&nbsp(External)</option>
                                    </select>
                                </div>
                            </div>


                            <div class="form-group">
                                <label for="password" class="col-lg-2 control-label">* Password</label>
                                <div class="col-sm-4">
                                    <input type="password" class="form-control" name="password" id="passwordcheck" size="30" maxlength="15" value="<?php if(isset($FORMFIELD['password'])){echo $FORMFIELD['password'];}?>" required >
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password2" class="col-lg-2 control-label">* Confirm Password</label>
                                <div class="col-sm-4">
                                    <input type="password" class="form-control" name="password2" id="password2" size="30" maxlength="10" value="<?php if(isset($FORMFIELD['password2'])){echo $FORMFIELD['password2'];}?>" required>
                                </div>
                            </div>


                            <div class = "align">

                                <?php
                            if($_GET['opencaptcha']=='failed') { echo "<script>alert('You Did Not Fill In The Security Code Correctly');</script>";}
                            $date = date("Ymd");
                            $rand = rand(0,9999999999999);
                            $height = "80";
                            $width  = "240";
                            $img    = "$date$rand-$height-$width.jpgx";
                            echo "<input type='hidden' name='img' value='$img'>";
                            echo "<a href='http://www.opencaptcha.com'><img src='http://www.opencaptcha.com/img/$img' height='$height' alt='captcha' width='$width' border='0' /></a><br />";
                            echo "<input type=text name=code value='Enter The Code' size='35' />";


                           /*
                            if(file_get_contents("http://www.opencaptcha.com/validate.php?ans=".$_POST['code']."&img=".$_POST['img'])=='pass') {
                                // CONTINUE LOGIN
                            } else {
                                header("LOCATION: ".$_SERVER['HTTP_REFERER']."?opencaptcha=failed");
                            }

                            //Back end code for processing page
                            //Returns pass or fail
                            */

                            ?>

                                </div>
                                <br />



                            <input type="hidden" name="token" id="token" value="<?php echo $_GET['token'];?>">
<!--                            <tr><td>--><?php //require_once'recaptchalib.php'; $publickey ="6LfpbvISAAAAABcxhT6qq_KteKfDOADqnELGjZCH"; echo recaptcha_get_html($publickey);?><!--</td></tr>-->


                            <div class = "modal-footer">
                                <button class = "btn btn-default" type="reset">Clear</button>
                                <button class ="btn btn-inverse" name="submit" id="submit" type="submit"> Submit and login</button>
                            </div>


                            <tr><td><strong>Instructions:</strong>&nbsp Please confirm your user information, and enter the Validation Code that was
                            emailed to you. If you do not have a validation Code, please fill out the <a href="passwordreset.html">password reset request</a> form first.</td></tr>

                        </form>

	<p>* Indicates a required field.</p>
	</div>
</body>
<?php

include_once 'footer.php';

?>