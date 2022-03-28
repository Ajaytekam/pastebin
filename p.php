<?php

include "phplibs/UI/header.php";
require_once "phplibs/config.php"; 

?>

<!-- SyntaxHighlighter core library -->
<script type="text/javascript" src="/webapp/frontend/syntaxHL/XRegExp.js"></script>
<script type="text/javascript" src="/webapp/frontend/syntaxHL/shCore.js"></script>
<link type="text/css" rel="stylesheet" href="/webapp/frontend/syntaxHL/shCore.css"/> 
<link type="text/css" rel="stylesheet" href="/webapp/frontend/syntaxHL/shThemeDefault.css"/>

<!-- includes sjcl library-->
<script src="libs/sjcl.js"></script> 

<!-- Script tag to encrypt/decrypt-->
<script>

$( document ).ready(function() {
    $('#decrypt').click(function(){
        var pass = $('#password').val();
        if(pass.length === 0) {
            $('#msg').html("Password field must not be empty");
        } else {
            var EncodedData =  $('#encryptedData').val();
            var encryptedData = atob(EncodedData);  // base64 decoding 
            try {
                var DecryptedCode = sjcl.decrypt(pass, encryptedData); // sjcl decoding
                pass="";  // setting the password to null
                // 
                // sending A post request to this page so we can display the code 
                // with syntax highlighiting
                $('#CodeData').val(DecryptedCode);
                $("#myForm").submit();
            } catch(err) {
                $('#msg').html("<b>Wrong Password!!</b>.  Please try again..");
            }
        }
    });

    $('#password').focus(function(){
        $('#msg').html("");
    });    

    $('#copyCode').click(function(){
        // copy the code into system clipboard
        var ENData = $('#MainCode').val();
        $('#MainCode').val(atob(ENData));
        $('#MainCode').css("display", "inline");
        $('#MainCode').select()
        try {
            document.execCommand("Copy", false, null);
            $("#fademsg").css("color", "green");
            $("#fademsg").text("Code copied into system's clipboard.");
            $("#fademsg").css("font-weight", "bold");
            $("#fademsg").css("margin-left", "10px");
            $("#fademsg").css("display", "inline");
            $("#fademsg").fadeOut(1000);
        } catch(err) {
            $("#fademsg").css("color", "red");
            $("#fademsg").text("Oops.!! There are some problem!!");
            $("#fademsg").css("font-weight", "bold");
            $("#fademsg").css("margin-left", "10px");
            $("#fademsg").css("display", "inline");
            $("#fademsg").fadeOut(1000);
        }
        $('#MainCode').css("display", "none");
        $('#MainCode').val(ENData);
    });
});

</script>


<?php

$cssSTR = <<<EOD
<style>
a:hover {
    font-weight:bold;
    color:blue;
}
</style>
EOD;

echo $cssSTR;

$SLBrush = "";

// used to filter use input
function filter_user_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function DisplayPaste($data, $type, $conn=null) {

        // code to include appropriate Brush
        $Plang = "plain";
        $SLBrush = "shBrushPlain.js";
        if($data["lang"] == "Plain") {
            $Plang = "plain";
            $SLBrush = "shBrushPlain.js";
        } else if($data["lang"] == "C") {
            $Plang = "c";
            $SLBrush = "shBrushCpp.js";
        } else if($data["lang"] == "C++") {
            $Plang = "cpp";
            $SLBrush = "shBrushCpp.js";
        } else if($data["lang"] == "CSharp") {
            $Plang = "csharp";
            $SLBrush = "shBrushCSharp.js";
        } else if($data["lang"] == "Java") {
            $Plang = "java";
            $SLBrush = "shBrushJava.js";
        } else if($data["lang"] == "JavaScript") {
            $Plang = "js";
            $SLBrush = "shBrushJScript.js";
        } else if($data["lang"] == "Php") {
            $Plang = "php";
            $SLBrush = "shBrushPhp.js";
        } else if($data["lang"] == "Python") {
            $Plang = "py";
            $SLBrush = "shBrushPython.js";
        } else if($data["lang"] == "CSS") {
            $Plang = "css";
            $SLBrush = "shBrushCss.js";
        } else if($data["lang"] == "Bash") {
            $Plang = "bash";
            $SLBrush = "shBrushBash.js";
        } else if($data["lang"] == "HTML") {
            $Plang = "html";
            $SLBrush = "shBrushXml.js";
        } else if($data["lang"] == "XML") {
            $Plang = "xml";
            $SLBrush = "shBrushXml.js";
        } else if($data["lang"] == "Perl") {
            $Plang = "pl";
            $SLBrush = "shBrushPerl.js";
        } else if($data["lang"] == "PowerShell") {
            $Plang = "ps";
            $SLBrush = "shBrushPowerShell.js";
        } else if($data["lang"] == "Ruby") {
            $Plang = "rb";
            $SLBrush = "shBrushRuby.js";
        } else if($data["lang"] == "Sql") {
            $Plang = "sql";
            $SLBrush = "shBrushSql.js";
        } else if($data["lang"] == "VB") {
            $Plang = "vb";
            $SLBrush = "shBrushVb.js";
        }
        
        // setup deletion time to Never
        $deletionTime = "";//strval($data["deletion_time"]);
        if($data["deletion_time"] == NULL) {
            $deletionTime = "NEVER";
        } else {
            $deletionTime = $data["deletion_time"];
        }

        // include the syntaxHighlighter brush 
        //Library highlights the javascript code 
        echo '<script type="text/javascript" src="/webapp/frontend/syntaxHL/brushes/' . $SLBrush . '"></script>';
        echo '<script type="text/javascript" src="/webapp/frontend/syntaxHL/brushes/shBrushPlain.js"></script>';


    if($type == "guest") {

        echo '<div class="panel panel-default" style="background-color: #EEEBEA;margin-right:5%;">';
        echo '<div style="font-weight:bold;font-size: 18px;padding-top: 3px;padding-left:1%;padding-bottom:3px;">' . $data["title"] . '</div>';
        echo '<table style="margin-left:1%;font-size: 12px;"><tr>';
        echo '<td style="padding: 2px;">Language: ' . $data["lang"] . ' </td>';
        echo '<td style="padding: 2px;" data-toggle="tooltip" data-placement="bottom" title="Paste Creation time"><img src="/webapp/frontend/images/cal.png" height="15" width="15"/> ' . $data["creation_time"] . ' </td>';
        echo '<td style="padding: 2px;" data-toggle="tooltip" data-placement="bottom" title="Paste Expiration time"><img src="/webapp/frontend/images/clock.png" height="15" width="15"/> ' . $deletionTime . ' </td>';
        echo '</tr></table>';
        echo '</div>';

        //checking the code if encrypted or not 
        if($data["encrypt_status"] == "0") {
            $CodeData =  base64_decode($data["ps_data"]);
            echo '<pre class="brush: ' . $Plang . ';">';
            echo htmlspecialchars($CodeData);
            echo '</pre>';
            echo '<textarea rows="1" cols="1" id="MainCode" style="display: none;">' . $data["ps_data"] . '</textarea>';
            echo '<button id="copyCode" class="btn btn-primary" style="height:30px;">Copy Code</button>';
            echo '<span id="fademsg"></span>';

        } else {
            // code is encrypted 

            // setting up the hidden field to send data in post
            
            echo '<form method="POST" action="p.php" id="myForm">';
                echo '<input type="hidden" id="brushSC" name="brushSC" value="' . $SLBrush . '">';
                echo '<input type="hidden" id="ptitle" name="ptitle" value="' . $data["title"] . '">';
                echo '<input type="hidden" id="plang" name="plang" value="' . $Plang . '">';
                echo '<input type="hidden" id="creationTime" name="creationTime" value="' . $data["creation_time"] . '">';
                echo '<input type="hidden" id="deletionDate" name="deletionDate" value="' . $deletionTime . '">';
                echo '<input type="hidden" id="CodeData" name="CodeData" value="">';
            echo '</form>';
            
            echo '<div id="Encryptedcodepan">'; 
                $CodeData =  $data["ps_data"];
                echo '<textarea id="encryptedData"rows="20" style="width:95%;resize: none;" readonly>';
                echo $CodeData;
                echo '</textarea>';
                echo '<br/>';
                echo '<br/>';
                echo '<div id="msg" style="color:red;"></div>';
                echo '<div class="input-group" >';
                echo '<input id="password" type="password" class="form-control" name="password" placeholder="Enter Password to decrypt">';
                echo '<br/><br/><button type="button" id="decrypt" class="form control btn btn-danger">Decrypt Text</button>';
                echo '</div>';
            echo '</div>';
        }


        //echo "encrypt_status : " .  $data["encrypt_status"] . "<br><br>";
        //echo "ps_data : " .  $data["ps_data"] . "<br><br>";
    
    } else if($type == "reg-user") {

        // getting the username from the database
        $uname = "anonymous";
        $sql = "select username from users where u_id = " . $data["pu_id"] . ";";
        if($result = mysqli_query($conn, $sql)) {
            if(mysqli_num_rows($result) == 1) {
                while($row = mysqli_fetch_assoc($result)) {
                    $uname = $row["username"];
                } 
            }
        } else {
            echo "<li style='color:red;'>Error: " . mysqli_error($conn) . "</li>";
        }
        
        echo '<div class="panel panel-default" style="background-color: #EEEBEA;">';
        echo '<div style="font-weight:bold;font-size: 18px;padding-top: 3px;padding-left:1%;padding-bottom:3px;">' . $data["title"] . '</div>';
        echo '<table style="margin-left:1%;font-size: 12px;"><tr>';
        echo '<td style="padding: 2px;"><a href="userpaste.php?uid=' . $data["pu_id"] . '"><img src="/webapp/frontend/images/user.png" height="15" width="15"/> ' . $uname . ' </a></td>';
        echo '<td style="padding: 2px;">Language: ' . $data["lang"] . ' </td>';
        echo '<td style="padding: 2px;" data-toggle="tooltip" data-placement="bottom" title="Paste Creation time"><img src="/webapp/frontend/images/cal.png" height="15" width="15"/> ' . $data["creation_time"] . ' </td>';
        echo '<td style="padding: 2px;" data-toggle="tooltip" data-placement="bottom" title="Paste Expiration time"><img src="/webapp/frontend/images/clock.png" height="15" width="15"/> ' . $deletionTime . ' </td>';
        echo '</tr></table>';
        echo '</div>';


        //checking the code if encrypted or not 
        if($data["encrypt_status"] == "0") {
            $CodeData =  base64_decode($data["ps_data"]);
            echo '<pre class="brush: ' . $Plang . ';" id="codepan">';
            echo htmlspecialchars($CodeData);
            echo '</pre>';
            echo '<textarea rows="1" cols="1" id="MainCode" style="display: none;">' . $data["ps_data"] . '</textarea>';
            echo '<button id="copyCode" class="btn btn-primary" style="height:30px;">Copy Code</button>';
            echo '<span id="fademsg"></span>';
        } else {
            // code is encrypted 

            // setting up the hidden field to send data in post
            
            echo '<form method="POST" action="p.php" id="myForm">';
                echo '<input type="hidden" id="pu_id" name="pu_id" value="' . $data["pu_id"] . '">';
                echo '<input type="hidden" id="uname" name="uname" value="' . $uname . '">';
                echo '<input type="hidden" id="brushSC" name="brushSC" value="' . $SLBrush . '">';
                echo '<input type="hidden" id="ptitle" name="ptitle" value="' . $data["title"] . '">';
                echo '<input type="hidden" id="plang" name="plang" value="' . $Plang . '">';
                echo '<input type="hidden" id="creationTime" name="creationTime" value="' . $data["creation_time"] . '">';
                echo '<input type="hidden" id="deletionDate" name="deletionDate" value="' . $deletionTime . '">';
                echo '<input type="hidden" id="CodeData" name="CodeData" value="">';
            echo '</form>';
            
            echo '<div id="Encryptedcodepan">'; 
                $CodeData =  $data["ps_data"];
                echo '<textarea id="encryptedData"rows="20" style="width:95%;resize: none;" readonly>';
                echo $CodeData;
                echo '</textarea>';
                echo '<br/>';
                echo '<br/>';
                echo '<div id="msg" style="color:red;"></div>';
                echo '<div class="input-group" >';
                echo '<input id="password" type="password" class="form-control" name="password" placeholder="Enter Password to decrypt">';
                echo '<br/><br/><button type="button" id="decrypt" class="form control btn btn-danger">Decrypt Text</button>';
                echo '</div>';
            echo '</div>';
        }

        // echo "Type : Reg-User<br/><br/>"; 
        // echo "p_id : " .  $data["p_id"] . "<br><br>";
        // echo "pu_id : " .   . "<br><br>";
        // echo "title : " .  $data["title"] . "<br><br>";
        // echo "lang : " .  $data["lang"] . "<br><br>";
        // echo "encrypt_status : " .  $data["encrypt_status"] . "<br><br>";
        // echo "ps_data : " .  $data["ps_data"] . "<br><br>";
        // echo "pasteExposure : " . $data["pasteExposure"] . "<br><br>";
        // echo "creation_time : " . $data["creation_time"] . "<br><br>";
        // echo "deletion_time : " . $data["deletion_time"] . "<br><br>";
    }
}

    echo "<ul>";
    if(($_SERVER['REQUEST_METHOD'] == 'GET') && isset($_GET["u"]) && !empty($_GET["u"])) {
        $pasteId =  preg_replace("/[^a-zA-Z0-9]/", "", filter_user_input($_GET["u"]));
    } else {

        // this code block is used to print the decrypted code which is send by this
        // page using jquery tricks
        if($_SERVER['REQUEST_METHOD'] =='POST') {
            echo '<script type="text/javascript" src="/webapp/frontend/syntaxHL/brushes/' . filter_user_input($_POST["brushSC"]) . '"></script>';
            echo '<div class="panel panel-default" style="background-color: #EEEBEA;margin-right:5%;">';
            echo '<div style="font-weight:bold;font-size: 18px;padding-top: 3px;padding-left:1%;padding-bottom:3px;">' . filter_user_input($_POST["ptitle"]) . '</div>';
            echo '<table style="margin-left:1%;font-size: 12px;"><tr>';
            if(!empty($_POST["pu_id"]) && !empty($_POST["uname"])) {
                echo '<td style="padding: 2px;"><a href="userpaste.php?uid=' . filter_user_input($_POST["pu_id"]) . '"><img src="/webapp/frontend/images/user.png" height="15" width="15"/>' . filter_user_input($_POST["uname"]) . ' </a></td>';
            }
            echo '<td style="padding: 2px;">Language: ' . filter_user_input($_POST["plang"]) . ' </td>';
            echo '<td style="padding: 2px;" data-toggle="tooltip" data-placement="bottom" title="Paste Creation time"><img src="/webapp/frontend/images/cal.png" height="15" width="15"/> ' . filter_user_input($_POST["creationTime"]) . ' </td>';
            echo '<td style="padding: 2px;" data-toggle="tooltip" data-placement="bottom" title="Paste Expiration time"><img src="/webapp/frontend/images/clock.png" height="15" width="15"/> ' . filter_user_input($_POST["deletionDate"]) . ' </td>';
            echo '</tr></table>';
            echo '</div>';
            echo '<pre class="brush: ' . filter_user_input($_POST["plang"]) . ';">';
            echo htmlspecialchars($_POST["CodeData"]);
            echo '</pre>';  
            echo '<textarea rows="1" cols="1" id="MainCode" style="display: none;">' . base64_encode($_POST["CodeData"]) . '</textarea>';
            echo '<button id="copyCode" class="btn btn-primary" style="height:30px;">Copy Code</button>';
            echo '<span id="fademsg"></span>';
            echo '<script type="text/javascript">SyntaxHighlighter.all();</script>';
            exit;
        } else {
            echo "<li style='color:red;'>There is no UID provided.</li>";
            exit;
        }
    }

    // check if paste is created by guest user by checking prefix
    if(preg_match("/^guest/", $pasteId)) {
        $sql = "select * from unreg_pastes where p_id = '$pasteId'";
        if($result = mysqli_query($conn, $sql)) {
            if(mysqli_num_rows($result) == 1) {
                while($row = mysqli_fetch_assoc($result)) {
                    DisplayPaste($row, "guest");         
                }
                mysqli_free_result($result);
            } else {
                echo "<li style='color:red;'>There is not any paste with ID $pasteId</li>"; 
            }
        } else {
            echo "<li style='color:red;'>Error: " . mysqli_error($conn) . "</li>";
        }
    } else {
        // otherwise check if paste is created by registered-user but within accessible with public mode
        $sql = "select * from reg_pastes where p_id = '$pasteId'";
        if($result = mysqli_query($conn, $sql)) {
            if(mysqli_num_rows($result) == 1) {
                while($row = mysqli_fetch_assoc($result)) {
                    if($row["pasteExposure"] == "public") {
                        DisplayPaste($row, "reg-user", $conn);         
                    } else {
                        if(isset($_SESSION["LoggedIn"]) && $_SESSION["LoggedIn"] == true) {
                            DisplayPaste($row, "reg-user", $conn);         
                        } else {
                            echo "<li style='color:red;'>This paste is only available for Registerd User. ID : $pasteId</li>"; 
                        }
                    }  
                }
                mysqli_free_result($result);
            } else {
                echo "<li style='color:red;'>There is not any paste with ID $pasteId</li>"; 
            }
        } else {
            echo "<li style='color:red;'>Error: " . mysqli_error($conn) . "</li>";
        }
    }

    // now check if the UID exists or not then print the whole data here
    // then prints the data here

    // make only public accessible paste searchable : store the results on idnexedDB 

    mysqli_close($conn);

    include "phplibs/UI/footer.php";
?>

<!-- Enables the syntax highlighting (!VIMP)-->
<script type="text/javascript">
  SyntaxHighlighter.all();
</script>  