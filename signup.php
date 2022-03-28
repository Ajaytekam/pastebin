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

// Define variables and initialize with empty values
$username = $email = $password = $confirm_password = "";
$username_err = $email_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    echo "<ul>";
    // Validate username
    if(empty(trim($_POST["name"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT u_id FROM users WHERE username = ?";
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = filter_user_input($_POST["name"]);
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else {
                    $username = filter_user_input($_POST["name"]);
                }
            } else{
                echo "<li style=' color: red;font-weight:bold;'>Oops! Something went wrong. Please try again later.</li>";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // validate email 
    if (empty(trim($_POST["email"]))) {
        $email_err = "Email is required";
      } else {
        $email_temp = filter_user_input($_POST["email"]);
        // check if e-mail address is well-formed
        if (!filter_var($email_temp, FILTER_VALIDATE_EMAIL)) {
            $email_err = "Invalid email format";
        } else {
            $email = $email_temp;
        }
      }


    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = filter_user_input($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    // Check input errors before inserting in database
    if(empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_email, $param_password);
            
            // Set parameters
            $param_username = $username;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                echo "<li style=' color: green;font-weight:bold;'>Successfully Signed-Up</li></ul>";
                echo "<br/><br/>This page is redirected to Home page in 3 seconds..";
                header("Refresh:3; url=index.php");
            } else{
                echo "<li style=' color: red;font-weight:bold;'>Something went wrong. Please try again later.</li></ul>";
                echo "<br/><br/>This page is reload in 4 seconds..";
                header("Refresh:4; url=signup.php");
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }

    } else {
        // display errors
        $errs = array($username_err, $email_err, $password_err, $confirm_password_err);
        foreach($errs as $str) {
            if(!empty($str)) {
                echo "<li style='color: red;font-weight:bold;'>" . $str . "</li>";   
            }
        }        
        echo "</ul>";
        echo "<br/><br/>This page is reload in 4 seconds..";
        header("Refresh:4; url=signup.php");
    }
    // Close connection
    mysqli_close($conn);
} else {
    include "phplibs/UI/signupform.php";
}

include "phplibs/UI/footer.php"; 

?>