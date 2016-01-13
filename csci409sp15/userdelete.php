<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 3/9/2015
 * Time: 4:05 PM
 */

$title='User Delete';
include_once 'header.php';
include_once 'menu.php';
require_once 'connect.php';

//CHECK IF USER IS LOGGED IN
if(!isset($_SESSION['userid']))
{
    header("Location: login.php");
    exit();
}


else if(isset($_SESSION['userid']) && $_SESSION['usertype'] == 1)
{
    echo '<p><a href="userlist.php">Return to user list</a></p>';
    $showform = 0;

    if(isset ($_POST['delete']) && $_POST['delete'] == "YES")
    {
        try
        {
            //echo 'This is POST value'.$_POST['id'];

            $sql = 'DELETE FROM registration WHERE ID = :ID';
            $s = $pdo->prepare($sql);
            $s->bindValue(':ID',$_POST['id']); //USING DATA FROM FORM
            $s->execute();
        }
        catch(PDOException $e)
        {
            echo 'Error deleting from database' . $e->getMessage();
            exit();
        }
        //confirmation
        echo '<p>The user has been deleted.</p>';
        $showform = 1;
        header( "refresh:3;url=userlist.php" );
    }

    if($showform == 0)
    {
        echo 'Are you sure you want to delete user No. ' . $_GET['x'];

        try
        {
            $sql = 'SELECT * FROM registration WHERE ID = :ID';
            $s = $pdo->prepare($sql);
            $s->bindValue(':ID', $_GET['x']);
            $s->execute();
        }
        catch (PDOException $e)
        {
            echo 'Error fetching users: ' . $e->getMessage();
            exit();
        }

        $row = $s->fetch();
        echo ' (' . $row['firstName'] . ' ' . $row['lastName'] . ') ? ';

        ?>

        <form name="userdelete" id="userdelete" method="post" action="userdelete.php">
            <input type="hidden" name="id" value="<?php echo $_GET['x'];?>">
            <input type="submit" name="delete" value="YES">
            <input type="button" name="nodelete" value="NO" onClick="window.location = 'userlist.php'" />
        </form>

    <?php
    }// end showform

}//end elseif login
else
{
    echo '<p>This is an administrative page only.</p>';

}
include_once 'footer.php';
?>
