<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 2/10/2015
 * Time: 9:48 AM
 */
$title='Home Page';
include_once 'header.php';
include 'menu.php';
require_once 'connect.php';
require_once 'functions.inc.php';
$message = "";

?>

    <div class="container" id="indexcontainer" ; xmlns="http://www.w3.org/1999/html">
        <div class = "jumbotron text-center">
            <h2><a class="linktitle" href="registration.php">REGISTRATION for Membership is now available</a></h2>
            <p>Culture, history, literature, philosophy, politics, music, economics, architecture, art, medicine, and science of the eighteenth-century world.</p>
        </div>

        <div class "container"><!--total no more than 12  -->
        <div class = "row">
            <div class = "col-md-4">
                <h2 class="heading1">SEASECS Annual Conference Registration!</h2>
                <p class="paragraph2">Annual conference date set! SEASECS will be heading south to the balmy breezes of north central Florida, so register before its to late. Must have a registered account to signup. One guest allowed.</p>
                <a href = "conferencereg.php" class = "btn btn-default " id="button1" ><span class="glyphicon glyphicon-pencil"></span>
                   <?php

                    //CHANGE BUTTON TEXT TO LET USER KNOW IF USER IS ALREADY REGISTERED
                    if(isset($_SESSION['userid'])){

                        //CHECK IF USER CAN REGISTER FOR CONFERENCE/Previous Registration
                        try {
                            $sql = 'SELECT * FROM conference WHERE reg_ID = :ID';
                            $s = $pdo->prepare($sql);
                            $s->bindValue(':ID', $_SESSION['userid']);
                            $s->execute();
                            $count = $s->rowCount();

                        }
                        catch (PDOException $e) {
                            echo 'Error fetching users: ' . $e->getMessage();
                        }
                        //IF USER IS REGISTERED THEY CANNOT RE-REGISTER
                        if($count == 1 ){
                           $message.= 'You are Registered';
                        }
                        //IF THERE ARE REGISTERED FOR 1 OR MORE YEARS
                        else if($count >  1){
                            $message.= 'You are Registered for Multiple Conference Years';
                        }
                        //iF NOT REGISTERED
                        else{
                            $message .= 'Register Now';
                        }
                    }
                    else{
                        $message .= 'Register Now';
                    }
                    if($message != ""){echo $message;}

                    ?>  </a><span class="label label-default">New</span>
            </div>

            <div class = "col-md-4">
                <h2 class="heading1">The Society</h2>
                <p class="paragraph2">The Southeastern American Society for Eighteenth-Century Studies is an interdisciplinary society.</p>
                <a href = "http://www.seasecs.net" class = "btn btn-default" id="button2" >Read More</a>
            </div>

            <div class = "col-md-4">
                <h2 class="heading1">The SEASECS Gazette </h2>
                <p class="paragraph2">The Southeastern American Society for Eighteenth-Century Studies is an interdisciplinary society.</p>
                <a href = "http://www.seasecs.net/pdf/gazette.pdf" class = "btn btn-default" id="button3">Read More</a><span class="label label-default">New Edition</span>
            </div>


        </div>
    </div>
<?php
include_once 'footer.php';
?>