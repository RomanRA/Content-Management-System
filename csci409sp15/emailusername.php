<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 2/11/2015
 * Time: 7:02 PM
 */
$title='Email User Name';
$showform = 0;
include_once 'header.php';
include_once 'menu.php';
require_once 'connect.php';
require_once 'functions.inc.php';

    $sendUser="";
    $to = $_POST['email'];
    $subject = "Username";
    $from = "raroman@g.coastal.edu";
    $message = "Your username is: ";

if(isset($_POST['submit'])) {

    //CHECK IF EMAIL EXISTS
    $FORMFIELD['email'] = strtolower(cleanse($_POST['email']));//CHECK IF USER EXIST
    try {
        //echo 'In try SELECT';
        $sql = 'SELECT email FROM registration WHERE  email = :email';
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':email',  $FORMFIELD['email']);
        $statement->execute();
        $count = $statement->rowCount();
    }
    catch(PDOException $e){
        echo 'Error getting information: ' . $e->getMessage();
        exit();
    }
    if($count !=1){
        echo 'That email <em>does not exist</em>';
    }

    //IF EMAIL DOES EXIST
    else{
        try{
            $sql2 = 'SELECT userName FROM registration WHERE email = :email';
            $s = $pdo->prepare($sql2);
            $s->bindValue(':email',  $FORMFIELD['email']);
            $s->execute();
            $user = $s->fetch();
            $sendUser = $user['userName'];

        }
        catch (PDOException $e2) {
            echo 'Error fetching user: ' . $e2->getMessage();
            exit();
        }//End else

        if (mail($to, $subject, $message.$sendUser, "From: " . $from)) {

//        $message = "This user name is..". $sendUser;
//        echo $message;
            echo 'The username was <em>sent</em> to the email provided.';
            $showform = 1;
            header( "refresh:4;url=index.php" );

        } else {
            echo 'Some went wrong.';
        }
    }
}//END IFISSET

if($showform == 0)
{
?>
    <div class ="container">
    <div class = "row">
    <div class = "col-lg-9">
    <div class ="panel panel-default">
    <div class ="panel-body">
    <div class= "page-header">

        <h3><b>Retrieve Username</b></h3>
        <hr>
            <form class="form-horizontal" role="form" name="emailUserName" id="emailUserName" method="post" action="emailusername.php ">

                <div class="form-group">
                    <label for="email" class="col-lg-2 control-label">* Email</label>
                    <div class="col-sm-4">
                        <input type="email" class="form-control" name="email" autofocus="autofocus" id="email" size="30" maxlength="30" required>
                    </div>
                </div>
                <p>(*) Indicates required field.</p>
                <div class = "modal-footer">
                    <button class = "btn btn-default" type="reset">Clear</button>
                    <button class ="btn btn-inverse" name="submit" id="submit" type="submit"> Submit</button>
                </div>




                <!--                <table>-->
<!--                    <tr>-->
<!--                        <td><label for="email">Email:</label></td>-->
<!--                        <td><input type="email" name="email" autofocus="autofocus" id="email" size="30" maxlength="30" required>*</td>-->
<!--                    </tr>-->
<!--                    </tr>-->
<!--                    <td><a href="javascript:history.back(-1)">Go Back</a></td>-->
<!--                    </tr>-->
<!--                    <tr><td><input type="submit" name="submit" value="submit"></td></tr>-->
<!--                </table>-->
            </form>
    <p>* Indicates a required field.</p>
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