<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 3/26/2015
 * Time: 11:25 AM
 */
$title='File Upload';
include_once 'header.php';
include_once 'menu.php';
require_once 'connect.php';
require_once 'functions.inc.php';

$showform = 0;
$fileerror = "";

if(isset($_POST['submit'])){

    //Variable used later
    $date = date_create();
    $targetDir = "/var/students/raroman/csci409sp15/uploads/";
    $image_info = 0;
    $imagesize = 0;

//CHECK FOR FILE UPLOAD ERRORS
    if ($_FILES['fileToUpload']['name'] == "") {
        $fileerror .= '<p>No File was found</p>';
    }
    else {
        //GET IMAGE DIMENSIONS
        $image_info = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        $image_width = $image_info[0];
        $image_height = $image_info[1];
        $imagesize = $image_width * $image_height;

    }

    if($imagesize > 10000){
        $fileerror .= '<p>Only a 100px by 100px image or smaller is allowed.</p>';
    }
    if ($_FILES['fileToUpload']['error'] != 0) {
        $fileerror .= '<p>File upload failed!</p>';
    }
    $filename = basename($_FILES['fileToUpload']['name']);
    $imageFileType = substr($filename, strrpos($filename, '.') + 1);

    // Allow img file formats only
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "bmp" ) {
        $fileerror .= '<p>Sorry, only JPG, JPEG, PNG ,BMP & GIF files are allowed.</p>';
    }

    if($fileerror != ""){
        echo $fileerror;

    }
    //if everythingis ok with no errors
    else{

        //echo $_FILES["fileToUpload"]["name"];
        //echo "<br />The temporary file is: ";
        //echo $_FILES["fileToUpload"]["tmp_name"];

        //change file name
        $newFileName = $_SESSION['userName'].date_timestamp_get($date);
        $targetFile = $targetDir . $newFileName;

        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile))
        {
            //First get old image and delete it
            try {
                $sql = 'SELECT profilePicture FROM registration WHERE ID = :ID';

                $s = $pdo->prepare($sql);
                $s->bindValue(':ID', $_SESSION['userid']);
                $s->execute();
            }
            catch(PDOException $e){
                echo 'Error inserting into database' . $e->getMessage();
                exit();
            }
            $row = $s->fetch();
            $oldPictureToDelete = $row['profilePicture'];

            //echo to check old picture was deleted
            //echo  "old pic". $oldPictureToDelete;

            if($oldPictureToDelete != ""){
                //delete old picture first then add image
                unlink("/var/students/raroman/csci409sp15/uploads/". $oldPictureToDelete);
                try {
                    //echo $newFileName;
                    //echo $_SESSION['userid'];

                    $sql = 'UPDATE registration SET
                    profilePicture = :profilePicture
                    WHERE ID = :ID';

                    $s = $pdo->prepare($sql);
                    $s->bindValue(':profilePicture', $newFileName);
                    $s->bindValue(':ID', $_SESSION['userid']);
                    $s->execute();
                }
                catch(PDOException $e){
                    echo 'Error inserting into database' . $e->getMessage();
                    exit();
                }
            }
            else{
                //Now Add new image
                try {
                    //echo $newFileName;
                    //echo $_SESSION['userid'];

                    $sql = 'UPDATE registration SET
                    profilePicture = :profilePicture
                    WHERE ID = :ID';

                    $s = $pdo->prepare($sql);
                    $s->bindValue(':profilePicture', $newFileName);
                    $s->bindValue(':ID', $_SESSION['userid']);
                    $s->execute();
                }
                catch(PDOException $e){
                    echo 'Error inserting into database' . $e->getMessage();
                    exit();
                }
            }
            echo '<div class="link"><p>Upload Successful!</p></div>';
            $showform = 1;
            echo '<div class="link"><p><a class="linkto" href="userlist.php">RETURN TO USER LIST</a></p></div>';
            header( "refresh:3;url=userlist.php" );
        }
        else
        {
            echo "<p>Could not move the file to the permanent location.<a href='upload.php'>Try again</a></p>";
        }

    }//end else
}

if($showform == 0)
{
    echo '<div class="link"><p><a class="linkto" href="userlist.php">RETURN TO USER LIST</a></p></div>';
?>
<div class ="container">
    <div class = "row">
        <div class = "col-sm-9">
            <div class ="panel panel-default">
                <div class ="panel-body">
                    <div class= "page-header">
                    <h3><b>Change Profile Picture</b></h3>
                    <form class="form-horizontal" role="form" name="upload" id="upload" action="upload.php" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="file" class="col-md-4 control-label">Select image to upload:</label>
                            <div class="col-lg-6">
                                <input type="file" class="form-control" name="fileToUpload" id="fileToUpload">
                            </div>
                        </div>
                        <div class = "modal-footer">
                            <button class = "btn btn-default" type="reset">Clear</button>
                            <button class = "btn btn-inverse" name="submit" id="submit" type="submit">Upload</button>
                        </div>
                    </form>
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