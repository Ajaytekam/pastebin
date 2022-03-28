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

if(isset($_SESSION["LoggedIn"]) && $_SESSION["LoggedIn"] === true){
    $pid =  preg_replace("/[^a-zA-Z0-9]/", "", filter_user_input($_GET["pid"]));
            // show all pastes related to user
    $sql = "select pu_id, deletion_time from reg_pastes where p_id = ?";
    if($stmt = mysqli_prepare($conn, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $pid);
        if(mysqli_stmt_execute($stmt)){
            // Store result
            mysqli_stmt_store_result($stmt);
            // check if paste is exists or not
            if(mysqli_stmt_num_rows($stmt) == 1){
                // Bind result variables
                mysqli_stmt_bind_result($stmt, $u_id, $deletion_time);
                if(mysqli_stmt_fetch($stmt)){
                // fetch the results
                    if($_SESSION['u_id'] == $u_id && $deletion_time == NULL) {
                        //delete the paste
                        $sql = "delete from reg_pastes where p_id = '$pid'";
                        if(mysqli_query($conn, $sql)) {
                            echo "Paste Deleted Successfully";
                            mysqli_close($conn);
                            mysqli_stmt_close($stmt);
                        header("Refresh:1; url=userpaste.php?uid=" . $u_id); 

                        }

                    } else {
                        echo "Paste_id:" . $pid . "is not created by user: " . $_SESSION['username'];
                        mysqli_stmt_close($stmt);
                        header("Refresh:3; url=index.php"); 
                    }
                }
            }
            // Close statement
            
        }
    }
} else {
    header("location: index.php");
}

?>