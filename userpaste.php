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

// css code for table
$cssStr = <<<EOD
<style>

    table {
        min-width:500px;
        margin-left:auto; 
        margin-right:auto;
    }
    table, th, td {
      border: 1px solid gray;
      border-collapse: collapse;
    }
    table {
      width: 95%;
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

if(($_SERVER['REQUEST_METHOD'] == 'GET') && isset($_GET["uid"]) && !empty($_GET["uid"])) {
    $uid =  preg_replace("/[^a-zA-Z0-9]/", "", filter_user_input($_GET["uid"]));

    // php code to check if logged in or not
    if(isset($_SESSION["LoggedIn"]) && $_SESSION["LoggedIn"] === true){

        echo '<table>';
        echo '<tr>';
        echo '<th style="width:30%;">Title</th>';
        echo '<th style="width:8%;">Language</th>';
        echo '<th style="width:8%;">Status</th>';
        echo '<th style="width:8%;">Access</th>';
        echo '<th style="width:15%;">Creation Time</th>';
        echo '<th style="width:15%;">Deletion Time</th>';


        // show all pastes related to user
        $sql = "select p_id, title, lang, encrypt_status, pasteExposure, creation_time, deletion_time from reg_pastes where pu_id = $uid";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $rowcount = mysqli_num_rows($result);
        if($rowcount == 0) {
            echo '</table>';
            echo '<br/>';
            echo "<span style='color:blue;text-align:center;font-weight:bold;font-size:20px;'><p>There is not any paste created by this user.</p></span>";
        } else {
            // print delete column if session_id == $uid
            if($_SESSION['u_id'] == $uid) {
                echo '<th style="width:10%;">Delete Paste</th>';
                echo '</tr>';
            } else {
                echo '</tr>';
            }

            for($i = 0; $i < $rowcount; $i++) {
                $EncStatus = "Nornml Text";
                if($row[$i]["encrypt_status"] == "1"){
                    $EncStatus = "Encrypted";
                }
                $deletionTime = "";
                if($row[$i]["deletion_time"] == NULL) {
                    $deletionTime = "NEVER";
                } else {
                    $deletionTime = $row[$i]["deletion_time"];
                }

                // now printing all the data 
                echo '<tr>';
                echo '<td class="ttl"><a href="p.php?u=' . $row[$i]["p_id"] . '">' . $row[$i]["title"] . '</a></td>';
                echo '<td>' . $row[$i]["lang"] . '</td>';
                echo '<td>' . $EncStatus . '</td>';
                echo '<td>' . $row[$i]["pasteExposure"] . '</td>';
                echo '<td>' . $row[$i]["creation_time"] . '</td>';
                echo '<td>' . $deletionTime . '</td>';
                if($_SESSION['u_id'] == $uid) {
                    if($deletionTime == "NEVER") {
                        echo '<td> <a href="deletepaste.php?pid=' . $row[$i]["p_id"] . '">Delete</a> </td>';
                    } else {
                        echo '<td> Auto-Delete </td>';
                    }
                }
                echo '</tr>';
            }
            echo '</table>';
            
        }
        mysqli_close($conn);
    } else {
        
        // printing the heading and structure of table
        echo '<table>';
        echo '<tr>';
        echo '<th style="width:40%;">Title</th>';
        echo '<th style="width:8%;">Language</th>';
        echo '<th style="width:8%;">Status</th>';
        echo '<th style="width:8%;">Access</th>';
        echo '<th style="width:15%;">Creation Time</th>';
        echo '<th style="width:15%;">Deletion Time</th>';
        echo '</tr>';

        // show only public accessible  pastes
        $sql = "select p_id, title, lang, encrypt_status, pasteExposure, creation_time, deletion_time from reg_pastes where pu_id = $uid and pasteExposure = 'public'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $rowcount = mysqli_num_rows($result);
        if($rowcount == 0) {
            echo '</table>';
            echo '<br/>';
            echo "<span style='color:blue;text-align:center;font-weight:bold;font-size:20px;'><p>There is not any paste craeted user.</p></span>";
        } else {
            for($i = 0; $i < $rowcount; $i++) {
                $EncStatus = "Nornml Text";
                if($row[$i]["encrypt_status"] == "1"){
                   $EncStatus = "Encrypted";
                }
                $deletionTime = "";
                if($row[$i]["deletion_time"] == NULL) {
                    $deletionTime = "NEVER";
                } else {
                    $deletionTime = $row[$i]["deletion_time"];
                }

                // now printing all the data 
                echo '<tr>';
                echo '<td class="ttl"><a href="p.php?u=' . $row[$i]["p_id"] . '">' . $row[$i]["title"] . '</a></td>';
                echo '<td>' . $row[$i]["lang"] . '</td>';
                echo '<td>' . $EncStatus . '</td>';
                echo '<td>' . $row[$i]["pasteExposure"] . '</td>';
                echo '<td>' . $row[$i]["creation_time"] . '</td>';
                echo '<td>' . $deletionTime . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        }
        mysqli_close($conn);

    }
         
} else {
    echo "<li style='color:red;'>There is no UID provided.</li>";
}

?> 




<!-- <table>
    <tr>
        <th style="width:40%;">Title</th>
        <th style="width:8%;">Language</th>
        <th style="width:8%;">Status</th>
        <th style="width:8%;">Access</th>
        <th style="width:15%;">Creation Time</th>
        <th style="width:15%;">Deletion Time</th>
    </tr>
    <tr>
        <td class="ttl"></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    </table> -->
