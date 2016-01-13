<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 2/13/2015
 * Time: 2:57 PM
 */

    session_start();
    session_unset();
    session_destroy();
    header("Location: login.php");

?>
<?php
include_once 'footer.php';
?>