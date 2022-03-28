<?php

    include "phplibs/UI/header.php"; 
    require_once "phplibs/config.php";

// used to filter use input
function filter_user_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// calculate the deletion time
function CalculateDT($expTime) {
    if($expTime == "30 SECOND") {
        return date("Y-m-d H:i:s", time()+30);
    } else if($expTime == "1 MINUTE") {
        return date("Y-m-d H:i:s", time()+60);
    } else if($expTime == "10 MINUTE") {
        return date("Y-m-d H:i:s", time()+(60*10));
    } else if($expTime == "1 HOUR") {
        return date("Y-m-d H:i:s", time()+(60*60));
    } else if($expTime == "1 DAY") {
        return date("Y-m-d H:i:s", time()+(60*60*24));
    } else if($expTime == "1 WEEK") {
        return date("Y-m-d H:i:s", time()+(60*60*24*7));
    } else if($expTime == "2 WEEK") {
        return date("Y-m-d H:i:s", time()+(60*60*24*7*2));
    } else if($expTime == "1 MONTH") {
        return date("Y-m-d H:i:s", time()+(60*60*24*30));
    } else if($expTime == "6 MONTH") {
        return date("Y-m-d H:i:s", time()+(60*60*24*30*6));
    } else if($expTime == "1 YEAR") {
        return date("Y-m-d H:i:s", time()+(60*60*24*30*12));
    }
}

// define variables and initialize with empty values
$author = $title = $lang = $pasteData = $encrypt = $pasteExpiration = $pasteExposure = "";
$Errors = array();
//$authorErr = $titleErr = $langErr = $pasteDataErr = $encryptErr = $pasteExpirationErr = $pasteExposureErr = "";

// Check and process the submitted data
if($_SERVER["REQUEST_METHOD"] == "POST"){
    //validate author_name 
    if(empty(trim($_POST['author']))) {
        $Errors["AuthorName"] = "Please Enter a valid authorName";
    } else {
        $author = filter_user_input($_POST['author']); 
    }

    if(empty(trim($_POST['title']))) {
        $Errors["TitleName"] = "Please Enter a valid TitleName";
    } else {
        $title = filter_user_input($_POST['title']); 
    }

   if(isset($_POST['encryptData']) &&  $_POST['encryptData'] == 1) {
        $encrypt = 1;
    } else {
        $encrypt = 0;
    }

    if(empty($_POST['PasteData'])) {
        $Errors["Paste Data"] = "Please Enter the valid Data";
    } else {
        if($encrypt == 0) { 
            $pasteData = base64_encode($_POST['PasteData']);
        } elseif($encrypt == 1) {
            $pasteData = filter_user_input($_POST['PasteData']);
        }
    }

    if(empty(trim($_POST['SelectedLang']))) {
        $Errors["Language"] = "Please choose a valid Language";
    } else {
        $lang = filter_user_input($_POST['SelectedLang']); 
    }

    if(empty(trim($_POST['pastexp']))) {
        $Errors["PasteExpirationDate"] = "Please choose a valid Paste Expiration Date";
    } else {
        $pasteExpiration = filter_user_input($_POST['pastexp']); 
    }

    if(empty(trim($_POST['exposure']))) {
        $Errors["PasteExpsure"] = "Please choose a valid Paste Exposure";
    } else {
        $pasteExposure = filter_user_input($_POST['exposure']); 
    }

 echo "<ul>";
 
 // Check for errors, & if there is any errors occur then it will
    if(count($Errors) == 0) {

        if(isset($_SESSION["LoggedIn"]) && $_SESSION["LoggedIn"] == true){
            // put the data into table reg_pastes
            $sql = "INSERT INTO reg_pastes (p_id, pu_id, title, lang, encrypt_status, ps_data, pasteExposure, deletion_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            if($stmt = mysqli_prepare($conn, $sql)) {
                // bind variables to the prepared statements as parameters
                mysqli_stmt_bind_param($stmt, "sississs", $param_pid, $param_pu_id, $param_title, $param_lang, $param_enc_status, $param_ps_data, $param_pasteExposure, $param_deletion_time);
                
                // set parameters
                $param_pid = uniqid();
                $param_pu_id = $_SESSION["u_id"];
                $param_title = $title;
                $param_lang = $lang;
                $param_enc_status = $encrypt;
                $param_ps_data = $pasteData;
                $param_pasteExposure = $pasteExposure;
                if($pasteExpiration == "NEVER") {
                    $param_deletion_time =  NULL;
                } else {
                    $param_deletion_time = CalculateDT($pasteExpiration); 
                }
                
                // execute prepared query
                if(mysqli_stmt_execute($stmt)) {
                    // now try to write the sql event
                    echo "<li style='color:green;'>data written successfully.</li>";
                    if($pasteExpiration != "NEVER") {
                        // write paste deletion event
                        $eventId = uniqid("REGEvent");
                        $sql= "CREATE EVENT IF NOT EXISTS $eventId ON SCHEDULE AT CURRENT_TIMESTAMP + INTERVAL $pasteExpiration DO DELETE FROM reg_pastes WHERE p_id = '$param_pid'"; 
                        if (mysqli_query($conn, $sql)) {
                            echo "<li style='color:green;'>Event Added sucessfully</li>";
                        } else {
                            echo "<li style='color:red;'>Error: " . mysqli_error($conn) . "</li>";
                        }    
                    }
                    echo "<li>This page is redirected in 2 seconds..";
                    header("Refresh:2; url=index.php"); 
                } else {
                    echo "<li style='color:red;'>Error: " . mysqli_error($conn) . "</li>";
                    echo "<li>This page is redirected in 20 seconds..";
                    header("Refresh:20; url=index.php"); 
                } 
                // Close statement
                mysqli_stmt_close($stmt);
            }

        } else {
            // put the data into table unreg_pastes
            $sql = "INSERT INTO unreg_pastes (p_id, title, lang, encrypt_status, ps_data, deletion_time) VALUES (?, ?, ?, ?, ?, ?)";
            if($stmt = mysqli_prepare($conn, $sql)) {
                // bind variables to the prepared statements as parameters
                mysqli_stmt_bind_param($stmt, "sssiss", $param_pid, $param_title, $param_lang, $param_enc_status, $param_ps_data, $param_deletion_time);
                
                // set parameters
                $param_pid = uniqid("guest");
                $param_title = $title;
                $param_lang = $lang;
                $param_enc_status = $encrypt;
                $param_ps_data = $pasteData;
                if($pasteExpiration == "NEVER") {
                    $param_deletion_time =  NULL;
                } else {
                    $param_deletion_time = CalculateDT($pasteExpiration); 
                }
                
                // execute prepared query
                if(mysqli_stmt_execute($stmt)) {
                    // now try to write the sql event
                    echo "<li style='color:green'>data written successfully.</li>";
                    if($pasteExpiration != "NEVER") {
                        // write paste deletion event
                        $eventId = uniqid("UnREGEvent");
                        $sql= "CREATE EVENT IF NOT EXISTS $eventId ON SCHEDULE AT CURRENT_TIMESTAMP + INTERVAL $pasteExpiration DO DELETE FROM unreg_pastes WHERE p_id = '$param_pid'"; 
                        if (mysqli_query($conn, $sql)) {
                            echo "<li style='color:green'>Event Added sucessfully</li>";
                        } else {
                            echo "<li style='color:red'>Error: " . $mysqli_error($conn) . "</li>";
                        }    
                    
                    }
                    echo "<li>This page is redirected in 2 seconds..";
                    header("Refresh:2; url=index.php"); 
                } else {
                    echo "<li style='color:red'>There are some error of writing into database.</li>";
                    echo mysqli_error($conn);
                    echo "<li>This page is redirected in 20 seconds..";
                    header("Refresh:20; url=index.php"); 
                }
                // Close statement
                mysqli_stmt_close($stmt);
            }
        }

    } else {
        echo count($Errors);
        foreach($Errors as $x_key => $x_value) {
            echo "<li><span style='color: red;'><b>" . $x_key . "</b></span>: " . $x_value . "</li><br/>";
            echo "<li>This page is redirected in 20 seconds..";
            header("Refresh:20; url=index.php"); 
        }
    }
}

echo "</ul>";

?>