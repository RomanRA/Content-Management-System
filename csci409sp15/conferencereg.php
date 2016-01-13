<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 4/2/2015
 * Time: 11:10 AM
 */

$title="Conference SignUp";
include_once 'header.php';
include_once 'menu.php';
require_once 'connect.php';
require_once 'functions.inc.php';

$showform = 0;
$errormessage = "";

$total = 0;
$membership = 0;
$memberPrice = 0;
$regularRegPrice = 80;
$undergraduatePrice = 20;
$graduatePrice = 50;
$guestPrice = 0;

$lunchPrice = 0;
$regularLunch = 25;
$guestLunch = 0;
$graduateLunch = 15;
$undergraduateLunch = 15;
$year="";

//CHECK LOGIN
if(!isset($_SESSION['userid'])) {
    header("location: login.php");
    exit();
}
else {
    //USED LATER TO QUERY MEMBERSHIP TYPE OF USER THIS AUTO FILL REGISTRATION
    // AND ONLY ALLOWS THE USER TO SELECT THAT MEMBERSHIP
    $id = $_SESSION['userid'];
    //echo 'This is userid: '.


    //CHECK IF USER CAN REGISTER FOR CONFERENCE/Previous Registration
    try {
        $sql = 'SELECT * FROM conference WHERE reg_ID = :ID';
        $s = $pdo->prepare($sql);
        $s->bindValue(':ID', $id);
        $s->execute();
        $count = $s->rowCount();
    }
    catch (PDOException $e) {
        echo 'Error fetching users: ' . $e->getMessage();
        header( "refresh:3;url=index.php" );
    }

    $row = $s->fetch();
    $year = $row['theYear'];


//IF USER IS REGISTERED THEY CANNOT RE-REGISTER
    if($count > 1 && $_SESSION['both'] = 2){
        echo '<h4 class="heading1">You are already signed up for both the 2015 and 2016 conference. You cannot sign up again.</h>';
        $showform = 1;
        header( "refresh:3;url=index.php" );
    }

}
//WHEN SUBMIT ADJUST THE PRICE
if (isset ($_POST['submit'])) {

        //VARIABLES USED LATER
        $_SESSION['both'] = 0;

        //USER CAN REGISTER
        $FORMFIELD['year'] = htmlchars(cleanse($_POST['year']));
        $FORMFIELD['guestName'] = htmlchars(cleanse($_POST['guestName']));
        $_SESSION['year'] = $FORMFIELD['year'];

        //REGULAR MEMBER CACLULATION
        if ($_SESSION['membership'] == 'regular') {
            $membership = 30;
            if($_POST['memberradio'] == 'member1'){
                if ($_POST['radio1'] == 'radio1'){
                    if(isset($_POST['radio2'])){
                        $value = $_POST['radio2'];
                        //echo "The value is: ".$value;
                        if ($value == 'radio4'){
                            $total = $regularRegPrice + $regularLunch + $membership;
                            $memberPrice = $regularRegPrice;
                            $lunchPrice = $regularLunch;
                        }
                        else if($value == 'radio5'|| $value =='radio6'){
                            $errormessage.='<p>YOU MUST SELECT REGULAR LUNCHEON IF YOU WISH TO ATTEND LUNCHEON </p>';
                        }
                    }
                    else{
                        $total = $regularRegPrice + $membership;
                        $memberPrice = $regularRegPrice;
                        //$lunchPrice;
                    }
                }
                else{
                    $errormessage.='<p>YOU MUST SELECT REGULAR CONFERENCE</p>';
                }
            }
            else{
                $errormessage.="<p>YOU MUST SELECT REGULAR MEMBER</p>";
            }
        }

        //UNDERGRADUATE MEMBER
        if ($_SESSION['membership'] == 'undergraduate'){

            if($_POST['radio1'] == 'radio2'){
                if(isset($_POST['radio2'])){
                    $value = $_POST['radio2'];
                    //echo 'The value is: ' . $value;
                    if ($value == 'radio5') {
                        $total = $undergraduatePrice + $undergraduateLunch;
                        //NO MEMBER PRICE FOR UNDERGRAD
                        $memberPrice = $undergraduatePrice;
                        $lunchPrice = $undergraduateLunch;
                    }
                    else if($value == 'radio4' || $value == 'radio6') {
                        $errormessage .= '<p>YOU MUST SELECT UNDERGRADUATE LUNCHEON IF YOU WISH TO ATTEND LUNCHEON </p>';
                    }
                }
                else{
                    $total = $undergraduatePrice;
                    $memberPrice = $undergraduatePrice;
                    //$lunchPrice;
                }
            }
            else{
                $errormessage.='<p>YOU MUST SELECT UNDERGRADUATE CONFERENCE</p>';
            }
        }


        //GRADUATE MEMBER
        if ($_SESSION['membership'] == 'graduate'){
            $membership = 10;
            if($_POST['memberradio'] == 'member2'){
                if($_POST['radio1'] == 'radio3'){
                    if(isset($_POST['radio2'])) {
                        $value = $_POST['radio2'];
                        //echo 'The value is: ' . $value;
                        if ($value == 'radio6') {
                            $total = $graduatePrice + $graduateLunch + $membership;
                            $memberPrice = $graduatePrice;
                            $lunchPrice = $graduateLunch;
                            //echo "THIS IS PRICE G:" . $total;
                        }
                        else if ($value == 'radio4' || $value == 'radio5') {
                            $errormessage .= '<p>YOU MUST SELECT GRADUATE LUNCHEON IF YOU WISH TO ATTEND LUNCHEON</p>';
                        }
                    }
                    else {
                        $total = $graduatePrice + $membership;
                        $memberPrice = $graduatePrice;
                        //$lunchPrice
                    }
                }
                else{
                    $errormessage.='<p>YOU MUST SELECT GRADUATE CONFERENCE</p>';
                }
            }
            else{
                $errormessage .= '<p>YOU MUST SELECT GRADUATE MEMBERSHIP</p>';
            }
        }


        //TAKE CARE OF GUEST INFORMATION HERE SET PRICES WHEN CHECKED BY USER
        if(isset($_POST['checkbox1'])){
            //echo 'Guest name is:'.$_POST['guestName'];
            if(isset($_POST['guestName'])  && $_POST['guestName'] != ""){
                if(isset($_POST['checkbox2'])){
                    $guestPrice = 80;
                    $total = $total + $guestPrice + $guestLunch;
                }
                else{
                    $guestPrice = 80;
                    $total = $total + $guestPrice;
                }
            }
            else{
                //IF GUEST NAME NOT SET
                $errormessage .= '<p>YOU MUST ENTER GUEST NAME IF GUEST REGISTRATION SELECTED</p>';
            }

        }


        if(isset($_POST['checkbox2'])){
            $guestLunch = 25;
            $total = $total + $guestLunch;
        }

        //CHECK YEAR TO SEE IF THEY HAVE ALREADY REGISTEREDfor that year
        if($year == $FORMFIELD['year']){
            //echo $year; check year
            $errormessage .= '<p>YOU ARE ALREADY SIGNED UP FOR THAT YEAR.</p>';
        }

        //DISPLAY ERROR MESSAGES IF NECESSARY
        if($errormessage != ""){
            echo $errormessage;
        }
        //ADD CONFERENCE REGISTRATION TO DATABASE
        else{

            try{
                $sql = 'INSERT INTO conference SET
               theYear = :theYear,
               reg_ID = :reg_ID,
               membership = :membership,
               memberPrice = :memberPrice,
               luncheonPrice = :luncheonPrice,
               guestPrice = :guestPrice,
               luncheonGuestPrice = :luncheonGuestPrice,
               totalPrice = :totalPrice,
               guestName = :guestName';

                $s = $pdo->prepare($sql);
                $s->bindValue(':theYear', $FORMFIELD['year']);
                $s->bindValue(':reg_ID',$id );
                $s->bindValue(':membership',$membership);
                $s->bindValue(':memberPrice', $memberPrice);
                $s->bindValue(':luncheonPrice', $lunchPrice);
                $s->bindValue(':guestPrice',$guestPrice);
                $s->bindValue(':luncheonGuestPrice',$guestLunch);
                $s->bindValue(':totalPrice',$total );
                $s->bindValue(':guestName', $FORMFIELD['guestName']);

                $s->execute();
            }

            catch(PDOException $e){echo 'Error inserting data into database' . $e->getMessage();exit();}
            echo '<div class="link"><h4 class="linkto">Successfully registered for the '.$_SESSION['year'].' Conference. Your total is: $'. ($total) .'</h4></div>';
            $_SESSION['both'] += 1;
            echo '
            <form  action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----MIIHfwYJKoZIhvcNAQcEoIIHcDCCB2wCAQExggEwMIIBLAIBADCBlDCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20CAQAwDQYJKoZIhvcNAQEBBQAEgYAhCDyTpQ9yCdhSZIOpJDZHQ1izjz3EzLRg9mqBpYubdaaECp8ilraOZlmJygzCl/aVi7yo9KJm4q9mtPJIDDUgYdqXx4gwL98svfetdg2fQqW1J1w+4KyHjQrwNTFNrraoS/goX+FZ+5yoaFbMBfqoO88pkX9cCvpmDMswbCvr7zELMAkGBSsOAwIaBQAwgfwGCSqGSIb3DQEHATAUBggqhkiG9w0DBwQIxNP9JEVZHF+Agdg9yiJPuVyGZaHtlBt71kVodw/aBhoTf52Lop9KxZ7A0DuGYJ5mQHysyWRjBAAYX+2Vk6a6qUQyDVDH9IHIJfQA7nzIVqpUsG3qOgIu/7M570MVR+DTM1Nn60SYmaF6BkG7rhQAR6L6jxo9qoYCznujhyvh6gCzezFuStcMmProlLn1z52g8otpQkeqZvzfx/R2S4vBlPghw+e/1gvg5Z5LeoNjaTAL+zlYVk2FvfYtmCD+M35V4Ajs+1KZeqkQT/ZTwEYOm5ldiG9sXj38Dt8uwzTXRInTD0agggOHMIIDgzCCAuygAwIBAgIBADANBgkqhkiG9w0BAQUFADCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20wHhcNMDQwMjEzMTAxMzE1WhcNMzUwMjEzMTAxMzE1WjCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20wgZ8wDQYJKoZIhvcNAQEBBQADgY0AMIGJAoGBAMFHTt38RMxLXJyO2SmS+Ndl72T7oKJ4u4uw+6awntALWh03PewmIJuzbALScsTS4sZoS1fKciBGoh11gIfHzylvkdNe/hJl66/RGqrj5rFb08sAABNTzDTiqqNpJeBsYs/c2aiGozptX2RlnBktH+SUNpAajW724Nv2Wvhif6sFAgMBAAGjge4wgeswHQYDVR0OBBYEFJaffLvGbxe9WT9S1wob7BDWZJRrMIG7BgNVHSMEgbMwgbCAFJaffLvGbxe9WT9S1wob7BDWZJRroYGUpIGRMIGOMQswCQYDVQQGEwJVUzELMAkGA1UECBMCQ0ExFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxFDASBgNVBAoTC1BheVBhbCBJbmMuMRMwEQYDVQQLFApsaXZlX2NlcnRzMREwDwYDVQQDFAhsaXZlX2FwaTEcMBoGCSqGSIb3DQEJARYNcmVAcGF5cGFsLmNvbYIBADAMBgNVHRMEBTADAQH/MA0GCSqGSIb3DQEBBQUAA4GBAIFfOlaagFrl71+jq6OKidbWFSE+Q4FqROvdgIONth+8kSK//Y/4ihuE4Ymvzn5ceE3S/iBSQQMjyvb+s2TWbQYDwcp129OPIbD9epdr4tJOUNiSojw7BHwYRiPh58S1xGlFgHFXwrEBb3dgNbMUa+u4qectsMAXpVHnD9wIyfmHMYIBmjCCAZYCAQEwgZQwgY4xCzAJBgNVBAYTAlVTMQswCQYDVQQIEwJDQTEWMBQGA1UEBxMNTW91bnRhaW4gVmlldzEUMBIGA1UEChMLUGF5UGFsIEluYy4xEzARBgNVBAsUCmxpdmVfY2VydHMxETAPBgNVBAMUCGxpdmVfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tAgEAMAkGBSsOAwIaBQCgXTAYBgkqhkiG9w0BCQMxCwYJKoZIhvcNAQcBMBwGCSqGSIb3DQEJBTEPFw0xNTA0MTYxMzQ0MzhaMCMGCSqGSIb3DQEJBDEWBBRHOgqZmdivdlg5IbuYG/AECdLG6zANBgkqhkiG9w0BAQEFAASBgJ31W7+Vb6Inm/Kzm1PTIBOvnQxpsHYXG2E5wyqdHSO9OOr1E7VBLVNIsqjqBs2E6wPadkvQ7Q12jM56ZDgX8LAixGSrrxJA/sbx1IcUBhqjdDX/W2LS05gJ5evmE55N45x9Z9WqEsbh0umkNrhTn51qf61gIxWe/XkFJrwLoqp1-----END PKCS7-----
                ">
                <input type="image" class="paypal" src="https://www.paypalobjects.com/en_US/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
            </form>';
            //header( "refresh:5;url=index.php" );
            $showform = 1;
        }



}//END ISSET SUBMIT


if($showform == 0) {

    try {
        $sql = 'SELECT * FROM registration WHERE ID = :ID';
        $s = $pdo->prepare($sql);
        $s->bindValue(':ID', $id);
        $s->execute();

    }
    catch (PDOException $e) {
        echo 'Error fetching users: ' . $e->getMessage();
        exit();
    }
    $row = $s->fetch();
    $_SESSION['membership'] = $row['membership'];

    //echo $_SESSION['membership'];

?>

<div class ="container">
    <div class = "row">
        <div class = "col-lg-9">
            <div class ="panel panel-default">
                <div class ="panel-body">
                    <div class= "page-header">
                        <h3><b>Conference Signup</b></h3>
<!--                        <div><h4 id="heading"><a style="" href="login.php">Already a member? Login now</a></h4></div>-->

                        <hr>

                        <form class="form-horizontal" role="form" name="conferencereg" id="conferencereg" method="post" action="conferencereg.php">


                            <div class="form-group">
                                <div class="col-sm-4">
                                    <select  class="form-control" name="year" id="year" required="">
                                        <option value="2015">2015</option>
                                        <option value="2016">2016</option>

                                    </select>
                                </div>
                            </div>
                            <!--member part-->
                            <div class="form-group">
                                <label for="membertype" class="col-lg-2 control-label">Membership</label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="memberradio" id="memberradio" value="member1"<?php if($_SESSION['membership'] == 'regular') {echo 'checked= "checked"'; } else if($_SESSION['membership']== 'undergraduate') {echo 'disabled= "disabled"'; } ?> />$30 Regular Membership
                                </label>
                            </div>

                            <div class="radio">
                                <label>
                                    <input type="radio" name="memberradio" id="memberradio" value="member2" <?php if($_SESSION['membership'] == 'graduate') {echo 'checked= "checked"';} else if($_SESSION['membership']== 'undergraduate') {echo 'disabled= "disabled"'; } ?> /> $40 Graduate Membership
                                </label>
                            </div>
                            <hr>



                            <div class="form-group">
                                <label for="registration" class="col-lg-2 control-label">Registration</label>
                            </div>

                            <div class="radio">
                                <label>
                                    <input type="radio" name="radio1" id="radio" value="radio1" <?php if($_SESSION['membership'] == 'regular') {echo 'checked= "checked"';}?> /> $80 Regular Conference
                                </label>
                            </div>

                            <div class="radio ">
                                <label>
                                    <input type="radio" name="radio1" id="radio" value="radio2" <?php if($_SESSION['membership'] == 'undergraduate') {echo 'checked= "checked"';}else{}?> />$20 Undergraduate Student Conference
                                </label>
                            </div>

                            <div class="radio">
                                <label>
                                    <input type="radio" name="radio1" id="radio" value="radio3" <?php if($_SESSION['membership'] == 'graduate') {echo 'checked= "checked"';}else{}?> />$50 Graduate Conference Registration
                                </label>
                            </div>

                        <!--Guest form-->
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="checkbox1" id="checkbox1" value="checkbox1"<?php if(isset($_POST['checkbox1'])){echo 'checked= "checked"';}?>/>$80 Guest Registration
                                </label>
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="guestName" class="col-lg-2 control-label">Guest Name *</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="guestName" id="guestName" size="30" maxlength="30" value="<?php if(isset($FORMFIELD['guestName'])){echo $FORMFIELD['guestName'];}?>" placeholder="enter guest name here">
                                </div>
                            </div>
                            <p>(*) Required field for guest registration.</p>
                        <hr>

                        <!--Luncheon From-->

                            <div class="form-group">
                                <label for="luncheon" class="col-lg-2 control-label">Luncheon</label>
                            </div>

                            <div class="radio">
                                <label>
                                    <input type="radio" name="radio2" id="radio2" value="radio4" /> $25 Regular SEASECS Luncheon
                                </label>
                            </div>

                            <div class="radio">
                                <label>
                                    <input type="radio" name="radio2" id="radio2" value="radio5" />$15 Undergraduate SEASECS Luncheon
                                </label>
                            </div>

                            <div class="radio">
                                <label>
                                    <input type="radio" name="radio2" id="radio2" value="radio6"  />$15 Graduate SEASECS Luncheon
                                </label>
                            </div>


                         <!--Luncheon guest-->
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="checkbox2" id="checkbox2" value="checkbox2"/>$25 Guest SEASECS Luncheon
                                </label>
                            </div>


                            <div class = "modal-footer">
                                <button class = "btn btn-default" type="reset">Reset</button>
                                <button class ="btn btn-inverse" name="submit" id="submit" type="submit"> Sign Up</button>
                            </div>

                            </form>
                        <br>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
}//END SHOWFORM

include_once 'footer.php';
?>
