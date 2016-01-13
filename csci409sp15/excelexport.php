<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 4/22/2015
 * Time: 5:57 PM
 */
session_start();

header("Content-Transfer-Encoding: ascii");
header("Content-Disposition: attachment; filename=report.csv");
header("Content-Type: text/comma-separated-values");

?>
 Member ID, First, Last, Middle, UserName, Institution, Email, Telephone
<?php
    require "connect.php";
    require "functions.inc.php";

    if(!isset($_SESSION['userid']))
    {
        header("refresh:2;url=login.php");
        exit();
    }

    if(isset($_SESSION['userid']) && $_SESSION['usertype'] == 1) {
        try {
            $sql = 'SELECT ID, firstName, middleName, lastName, userName, institution, email,telephone FROM registration';
            $s = $pdo->prepare($sql);
            $s->execute();
        } catch (PDOException $e) {
            echo 'Error fetching users: ' . $e->getMessage();
            exit();
        }
        //IF ERROR
        if (!($s))
        {
            echo "ERROR!";
            exit();
        }

        while($row = $s->fetch()) {
            $a = str_replace(",", ";", $row['firstName']);
            $a = str_replace("\n", " ", $a);
            $a = str_replace("\r", " ", $a);

            $b = str_replace(",", ";", $row['middleName']);
            $b = str_replace("\n", " ", $b);
            $b = str_replace("\r", " ", $b);

            $c = str_replace(",", ";", $row['lastName']);
            $c = str_replace("\n", " ", $c);
            $c = str_replace("\r", " ", $c);

            $d = str_replace(",", ";", $row['userName']);
            $d = str_replace("\n", " ", $d);
            $d = str_replace("\r", " ", $d);

            $f = str_replace(",", ";", $row['institution']);
            $f = str_replace("\n", " ", $f);
            $f = str_replace("\r", " ", $f);

            $g = str_replace(",", ";", $row['telephone']);
            $g = str_replace("\n", " ", $g);
            $g = str_replace("\r", " ", $g);


            echo "". $row['ID'] . "," . $a . "," . $b . "," . $c . "," . $d . "," . $f . "," . $row['email'].",". $g .","."\r\n";

        }
    }

?>