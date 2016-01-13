<?php
/* CREATE A CONNECTION TO THE SERVER */
try{
    //$pdo = new PDO('mysql:host=localhost;dbname=cs409raroman', 'cs409raroman', '0914490');
    $pdo = new PDO('mysql:host=localhost;dbname=cs409raroman','root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec('SET NAMES "utf8"');
}
catch (PDOException $e)
{
    echo 'Unable to connect to the database server.' . $e->getMessage();
    exit();
}
?>
