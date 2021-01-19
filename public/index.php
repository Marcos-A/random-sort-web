<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>R A N D O M   S O R T   W E B</title>
    <style>
        <?php include 'css/style.css'; ?>
    </style>
	</head>

<body>
  <div class="centered">
    <h3>Comptar el total d'UF avaluades i aprovades</h3>
    <form method="post" enctype="multipart/form-data">
        <font size="-1">Afegiu el fitxer CSV:</font><br>
        <input class="left" type="file" name="fileToUpload" id="fileToUpload"><br><br>
        <input type="submit" value="Reordenar" name="submitFile">
    </form>
   <div class="result">
<?php 

    define("PYTHON_SCRIPT", "StudentsRandomizer.py");
    define("PYTHON_SCRIPT_DIR", "../script/");
    define("TARGET_CSV_DIR", "../uploads/");
    define("RESULT_TXT_FILE", "randomly_sorted_students.txt");
    define("RESULT_TXT_FILE_DIR", "../script/tmp/");

    $fileType = "pdf";
    $target_file = TARGET_CSV_DIR.time().".".$fileType;
    $uploadOk = true;

    if (isset($_FILES["fileToUpload"])) {
        $uploaded_file = $_FILES["fileToUpload"];
    }
    if (isset($_FILES["fileToUpload"]["size"])) {
        $uploaded_size = $_FILES["fileToUpload"]["size"];
    }

    // Check PDF file type
    if(isset($_POST["submit"])) {
        $submit = $_POST["submitFile"];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        
        $uploaded_file_tmp_name = $_FILES["fileToUpload"]["tmp_name"];
        
        if(finfo_file($finfo, $uploaded_file_tmp_name) === 'text/csv') {
            $uploadOk = true;
        } else {
            $uploadOk = false;
        }
        finfo_close($finfo);
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "<br>El fitxer ja existeix. ";
        $uploadOk = false;
    }

    // Check file size
    if ($uploaded_size > 25000000) {
        echo "<br>El fitxer pujat és massa gran. ";
        $uploadOk = false;
    }

    // Check file is not empty
    if (isset($_POST["submitActes"]) && $uploaded_size == 0) {
        echo "<br>El fitxer pujat està buit. ";
        $uploadOk = false;
    }

    // Allow certain file formats
    if($fileType != "pdf" ) {
        echo "<br>Només es poden pujar fitxers PDF. ";
        $uploadOk = false;
    }

    // Check if $uploadOk is set to 0 by an error
    if (!$uploadOk) {
        echo "<br>No s'ha pogut pujar el seu fitxer. ";

    // if everything is ok, try to upload file
    } else {

   if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        #echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.\n";

        // Call the script
        $command = "python3 '".PYTHON_SCRIPT_DIR.PYTHON_SCRIPT."' '".$target_file."'";
        $output = shell_exec($command);

        echo $output;
        echo "test";       
      
        // Download zip file
        $file = RESULT_TXT_FILE_DIR.RESULT_TXT_FILE;

        if (headers_sent()) {
            echo 'HTTP header already sent';
        } else {
            if (!is_file($file)) {
                header($_SERVER['SERVER_PROTOCOL']." 404 Not Found");
                echo "<br>No s'ha trobat el fitxer. ";
            } else if (!is_readable($file)) {
                header($_SERVER['SERVER_PROTOCOL']." 403 Forbidden");
                echo "<br>El fitxer no es pot llegir. ";
            } else {
                header($_SERVER['SERVER_PROTOCOL']." 200 OK");
                header("Content-type: application/zip"); 
                header("Content-Transfer-Encoding: Binary");
                header("Content-Length: ".filesize($file));
                header("Content-Disposition: attachment; filename=\"".basename($file)."\"");

                // Disable cache
                header("Expires: " .gmdate('D, d M Y H:i:s') ." GMT");
                header("Cache-control: private");
                header("Pragma: private");

                ob_end_clean();
                readfile($file);

                // Delete file
                unlink($file);

                // Clear temp zip file
                system("rm -rf ".RESULT_TXT_FILE_DIR.RESULT_TXT_FILE, $retval);

                // Clear uploaded file
                system("rm -rf ".TARGET_CSV_DIR."*.pdf", $retval);

                exit();
            }
        }

    } else if(isset($_POST["submitButlletins"])) {
        echo "<br>S'ha produït un error en pujar el seu fitxer. ";
    }
}
?>
  </div>
</body>
</html>

