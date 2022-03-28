<?php

include "phplibs/UI/header.php"; 
require_once "phplibs/config.php";

// css code for table
$cssStr = <<<EOD
<style>

    table {
        min-width:300px;
        margin-left:auto; 
        margin-right:auto;
    }
    table, th, td {
      border: 1px solid gray;
      border-collapse: collapse;
    }

    table {
      width: 30%;
    }

    th, td {
      padding: 5px;
      text-align: center;
    }
    th {
      background-color: lightgreen;
      color: black;
    }

    td.ttl {
        text-align:left;
    }

    a:hover {
        font-weight: bold;
        color: blue;
    }
  </style>
EOD;
echo $cssStr;

if(isset($_SESSION["LoggedIn"]) && $_SESSION["LoggedIn"] === true){

    // printing the heading and structure of table
    echo '<table>';
    echo '<tr>';
    echo '<th style="width:100px;">User</th>';
    echo '<th style="width:50px;">Number of Paste</th>';
    echo '</tr>';

    $sql = "select u_id, username from users";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $rowcount = mysqli_num_rows($result);
    for($i = 0; $i < $rowcount; $i++) {
        $UID = $row[$i]["u_id"];
        $UNAME = $row[$i]["username"];
        // query to count the number of pastes craeted by users 
        $sql = "select count(*) from reg_pastes where pu_id = $UID";
        $result = mysqli_query($conn, $sql);
        $row1 = mysqli_fetch_array($result);
        echo '<tr><td style="text-align:left;"><a href="userpaste.php?uid=' . $UID . '">' . $UNAME . '</a></td><td>' . $row1[0] . '</td></tr>';
    }
    echo '</table>';
    mysqli_close($conn);
} else {
    echo "<ul><li style='color:red;'>You need to be Logged-In to access the user-list.</li></ul>";
}