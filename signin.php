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

function ValidateURLRedirect($data) {
    // contaions valid page names to redirect
    $validPages = array("index.php", "signup.php", "p.php", "listpastes.php", "createpaste.php", "userpaste.php", "searchpaste.php");
    if(in_array($data, $validPages)) {
        return $data;
    } else {
        return "index.php";
    }

} 

// setup page redirect
$RedirectURL = ValidateURLRedirect(basename(filter_user_input($_POST["redirectURL"])));

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["LoggedIn"]) && $_SESSION["LoggedIn"] === true){
    echo "<script>window.location = '" . $RedirectURL . "';</script>";
    exit;
}

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = filter_user_input($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = filter_user_input($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT u_id, username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            
                            // Store data in session variables
                            $_SESSION["LoggedIn"] = true;
                            $_SESSION["u_id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Redirect user to welcome page
                            echo "<script>window.location = '" . $RedirectURL . "';</script>";
                        } else{
                            // Display an error message if password is not valid
                            echo "<li style='color: red;font-weight:bold;'>The assword you entered was not valid.</li>";
                            echo "<li style='color: red;font-weight:bold;'>Try Again..!!</li>";  
                            echo "<li>This page is redirected in 3 seconds..";
                            header("Refresh:3; url=index.php"); 
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    echo "<li style='color: red;font-weight:bold;'>No account found with that username.</li>";
                    echo "<li style='color: red;font-weight:bold;'>Try Again..!!</li>";  
                    echo "<li>This page is redirected in 3 seconds..";
                    header("Refresh:3; url=index.php"); 
                }
            } else{
                echo "<li style='color: red;font-weight:bold;'>Oops! Something went wrong. Please try again later.</li>";
                echo "<li style='color: red;font-weight:bold;'>Try Again..!!</li>"; 
                echo "<li>This page is redirected in 3 seconds..";
                header("Refresh:3; url=index.php"); 
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    } else {
        echo "<li style='color: red;font-weight:bold;'>" . $username_err . "</li>";  
        echo "<li style='color: red;font-weight:bold;'>" . $password_err . "</li>";  
        echo "<li style='color: red;font-weight:bold;'>Try Again..!!</li>";  
    }
    
    echo '</ul>';
    // Close connection
    mysqli_close($conn);
}


include "phplibs/UI/footer.php"; 

?>