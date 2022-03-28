<?php

include "phplibs/UI/header.php"; 
require_once "phplibs/config.php";

// css code for table
$cssStr = <<<EOD
<style>

    table {
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

function filter_user_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if(($_SERVER['REQUEST_METHOD'] == 'GET') && isset($_GET["q"]) && !empty($_GET["q"])) {
  $QUERY = preg_replace("/[^a-zA-Z0-9]/", "", filter_user_input($_GET["q"]));
  echo '<ul><li><b>Search result for query <span style="color:blue;">' . $QUERY . '</span></li></ul>';
// show search result 
// ======================Start 
// printing the heading and structure of table
echo '<table>';
echo '<tr>';
echo '<th style="width:40%;">Title</th>';
echo '<th style="width:10%;">Posted-By</th>';
echo '<th style="width:10%;">Language</th>';
echo '<th style="width:10%;">Status</th>';
echo '<th style="width:15%;">Creation Time</th>';
echo '<th style="width:15%;">Deletion Time</th>';
echo '</tr>';

if(isset($_SESSION["LoggedIn"]) && $_SESSION["LoggedIn"] === true){
    $sql = "select p_id, pu_id, title, lang, encrypt_status, creation_time, deletion_time from reg_pastes where title like '%" . $QUERY . "%' union select p_id, null, title, lang,encrypt_status, creation_time, deletion_time from unreg_pastes where title like '%" . $QUERY . "%' order by creation_time desc";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $rowcount = mysqli_num_rows($result);
    for($i = 0; $i < $rowcount; $i++) {
        $PID = $row[$i]["p_id"];
        if($row[$i]["pu_id"] == NULL) {
            $UID = NULL;
        } else {
            $UID = $row[$i]["pu_id"];
            $sql = "select username from users where u_id = $UID";
            $result = mysqli_query($conn, $sql);
            $row1 = mysqli_fetch_array($result); 
            $UNAME = $row1[0];
        }
        $TITLE = $row[$i]["title"];
        $LANG = $row[$i]["lang"];
        $EncStatus = "Nornml Text";
        if($row[$i]["encrypt_status"] == "1"){
            $EncStatus = "Encrypted";
        }
        $CREATIONTIME = $row[$i]["creation_time"];
        if($row[$i]["deletion_time"] == NULL) {
            $deletionTime = "NEVER";
        } else {
            $deletionTime = $row[$i]["deletion_time"];
        }
   
        // printing the data        
        echo "<tr>";
        echo "<td style='text-align:left;'><a href='p.php?u=" . $PID .  "'>" . $TITLE . "</a></td>";
        if($UID == NULL) {
            echo "<td>GUEST</td>";
        } else {
            echo "<td><a href='userpaste.php?uid=" . $UID . "'>" .$UNAME . "</a></td>";
        }
        echo "<td>" . $LANG . "</td>";
        echo "<td>" . $EncStatus . "</td>";
        echo "<td>" . $CREATIONTIME. "</td>";
        echo "<td>" . $deletionTime . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    mysqli_close($conn);
} else {
    $sql = "select p_id, pu_id, title, lang,encrypt_status, creation_time, deletion_time from reg_pastes where pasteExposure = 'public' and title like '%" . $QUERY . "%' union select p_id, null, title, lang,encrypt_status, creation_time, deletion_time from unreg_pastes where title like '%" . $QUERY . "%' order by creation_time desc";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $rowcount = mysqli_num_rows($result);
    for($i = 0; $i < $rowcount; $i++) {
        $PID = $row[$i]["p_id"];
        if($row[$i]["pu_id"] == NULL) {
            $UID = NULL;
        } else {
            $UID = $row[$i]["pu_id"];
            $sql = "select username from users where u_id = $UID";
            $result = mysqli_query($conn, $sql);
            $row1 = mysqli_fetch_array($result); 
            $UNAME = $row1[0];
        }
        $TITLE = $row[$i]["title"];
        $LANG = $row[$i]["lang"];
        $EncStatus = "Nornml Text";
        if($row[$i]["encrypt_status"] == "1"){
            $EncStatus = "Encrypted";
        }
        $CREATIONTIME = $row[$i]["creation_time"];
        if($row[$i]["deletion_time"] == NULL) {
            $deletionTime = "NEVER";
        } else {
            $deletionTime = $row[$i]["deletion_time"];
        }
   
        // printing the data        
        echo "<tr>";
        echo "<td style='text-align:left;'><a href='p.php?u=" . $PID .  "'>" . $TITLE . "</a></td>";
        if($UID == NULL) {
            echo "<td>GUEST</td>";
        } else {
            echo "<td><a href='userpaste.php?uid=" . $UID . "'>" .$UNAME . "</a></td>";
        }
        echo "<td>" . $LANG . "</td>";
        echo "<td>" . $EncStatus . "</td>";
        echo "<td>" . $CREATIONTIME. "</td>";
        echo "<td>" . $deletionTime . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    mysqli_close($conn);
}

// ===========end

} else {

    echo '<span style="color: red;"><ul><li><b>Empty Search Query !!</b></li><li>Try again with some query words</li></ul></span>';
}

?>