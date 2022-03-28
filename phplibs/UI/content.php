<!-- includes sjcl library-->
<script src="libs/sjcl.js"></script> 

<div class="main-container">
<div class="main-content">
  <form method="post" action="createpaste.php" >
  <div style="max-width: 50%; margin-left:20px;">
    <div class="input-group">
      <span class="input-group-addon">Author</span>
          <?php
              // php code to check if logged in or not
              if(isset($_SESSION["LoggedIn"]) && $_SESSION["LoggedIn"] === true){
                echo '<input id="author" type="text" class="form-control" name="author" value="'. $_SESSION["username"] . '" readonly >';
              } else {
                echo '<input id="author" type="text" class="form-control" name="author" value="Guest" readonly >';
              } 
          ?>
    </div>
    <br/>
    <div class="input-group">
      <span class="input-group-addon">Title</span>
      <input id="title" type="text" class="form-control" name="title" placeholder="Paste Title" required>
    </div>
    <br/>
    <div class="input-group">
       <span class="input-group-addon">Select Language</span>
       <select class="form-control" id="language" name="SelectedLang">
        <option value="Plain">Plain Text</option>
        <option value="Bash">Bash</option>
        <option value="C">C</option>
        <option value="C++">C++</option>
        <option value="CSS">CSS</option>
        <option value="CSharp">CSharp</option>
        <option value="Java">Java</option>
        <option value="JavaScript">JavaScript</option>
        <option value="Perl">Perl</option>
        <option value="Php">PHP</option>
        <option value="PowerShell">PowerShell</option>
        <option value="Python">Python</option>
        <option value="Ruby">Ruby</option>
        <option value="Sql">Sql</option>
        <option value="VB">VB</option>
        <option value="XML">XML</option>
       </select>
    </div>
  </div>
    <br/>

    <p class="h5" style="margin-left:20px;font-weight:bold;">Paste Area <span id="EmptyMSG" style="font-weight: normal;color: red;"></span></p>
    <div class="form-group" style="padding-left:20px;padding-right:100px;">
      <textarea class="form-control" id="DataToBePasted" name="PasteData" rows="10" required></textarea>
    </div>
  
  <div style="max-width: 50%; margin-left:20px;">
    <div class="mychklabel">
      <input type="checkbox" id="enc_status" name="encryptData" value="1">
    <div class="slidinggroove"></div>
      <label class="mychklabel" for="enc_status" name="encrypted_pasted"><p class="labelterm">Create Encrypted Text</p></label>
    </div>

    <br/>

    <div class="input-group">
      <span class="input-group-addon">Paste Expiration</span>
      <select class="form-control" id="expire" name="pastexp">
        <option value="NEVER">Never</option>
        <option value="30 SECOND">30 Seconds</option>
        <option value="1 MINUTE">1 Minutes</option>
        <option value="10 MINUTE">10 Minutes</option>
        <option value="1 HOUR">1 Hour</option>
        <option value="1 DAY">1 Day</option>
        <option value="1 WEEK">1 Week</option>
        <option value="2 WEEK">2 Week</option>
        <option value="1 MONTH">1 Month</option>
        <option value="6 MONTH">6 Month</option>
        <option value="1 YEAR">1 Year</option>
      </select>
    </div>
    <br/>
    <div class="input-group">
      <span class="input-group-addon">Paste Exposure</span>
      <select class="form-control" id="exposure" name="exposure">
        <option value="public">Public</option>
        <?php
          // php code to check if logged in or not
          if(isset($_SESSION["LoggedIn"]) && $_SESSION["LoggedIn"] === true){
            echo '<option value="reg-only">Registered-Only</option>';
          }
        ?>
      </select>
    </div>
    <br/>
    <button type="submit" class="btn btn-success">Create Paste</button>
  </form>
  </div>
</div>

<script>
  
  $(document).ready(function(){

    $('input[type="checkbox"]').click(function(){
      if($(this).prop("checked") == true){
         $('#EncEvent').trigger("click");
      } 
    });

    $('#EncryptData').click(function(){
      $('#EmptyMSG').html("");
      if($('#DataToBePasted').val() == "") {
            $('#enc_status').prop("checked", false);
            $('#CloseDialog').trigger("click");
            $('#EmptyMSG').html("It must not be empty");
      } else {
        var pass = $('#EncPassword').val();
        if(pass == "") {
            $('#PassModalMessage').html("<b><span style='color:red;font-size:20px;'>!!</span>  Please enter a Password first");
        } else {
            $('#CloseDialog').trigger("click");
            var NormalData =  $('#DataToBePasted').val();
             var EncryptedData = sjcl.encrypt(pass, NormalData);
              console.log(EncryptedData);
              // encoding it to base64
              $('#DataToBePasted').val(btoa(EncryptedData));
              pass = "";
        }
      }

      // decoding process 
      //  first decode the data with atob() then decrypt the data with sjcl.decrypt("password", "encrypted-data").  
      //
    });

      // just for pasteArea (in case the data from this area will deleted then it will reset the "Create Encrypted Text" button)
    $('#DataToBePasted').focusout(function(){
      if($('#DataToBePasted').val() == "") {
            $('#enc_status').prop("checked", false);
      }
    });

  });
  
 </script>

<!-- code for passwordPopupBox-->

<!-- hidden anchor for triggering passwordPopup -->
<a href="#passwordForm" data-toggle="modal" id="EncEvent" style="display:none;">Click Here</a>
<!-- popup form structure -->
<div id="passwordForm" class="modal fade" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-login">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="PassModalMessage" style="text-align: center;font-weight: bold;">Enter password to encrypt</h5>
				 <button type="button" class="close" id="CloseDialog" data-dismiss="modal" aria-hidden="true" style="display: none;">&times;</button>	
			</div>
			<div class="modal-body">
					<div class="form-group">
						<input type="text" class="form-control" id="EncPassword" name="username" placeholder="Enter Password" required>		
					</div>       
					<div class="form-group">
						<button id="EncryptData" class="btn btn-primary btn-lg btn-block login-btn" >Encrypt Data</button>
					</div>
			</div>
		</div>
	</div>
</div>     

<!-- code for pastelist -->
<?php

  require_once "phplibs/config.php";
  echo '<div class="main-pastelist">';
  if(isset($_SESSION["LoggedIn"]) && $_SESSION["LoggedIn"] === true){
    $sql = "select p_id, pu_id, title, lang,encrypt_status, creation_time, deletion_time from reg_pastes union select p_id, null, title, lang,encrypt_status, creation_time, deletion_time from unreg_pastes order by creation_time desc limit 0, 15"; 
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $rowcount = mysqli_num_rows($result);
    for($i = 0; $i < $rowcount; $i++) {
        $PID = $row[$i]["p_id"];
        $TITLE = $row[$i]["title"];
        $LANG = $row[$i]["lang"];
   
        // printing the data        
        echo '<div class="pasteItem"><a class="pasteSummery" href="p.php?u=' . $PID .  '">' . $TITLE . '</a> <span style="color:gray;">| ' . $LANG . ' |</span></div><hr class="divider">';
    }
    mysqli_close($conn);

} else {

    $sql = "select p_id, pu_id, title, lang,encrypt_status, creation_time, deletion_time from reg_pastes where pasteExposure = 'public' union select p_id, null, title, lang,encrypt_status, creation_time, deletion_time from unreg_pastes order by creation_time desc limit 0, 15"; 
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $rowcount = mysqli_num_rows($result);
    for($i = 0; $i < $rowcount; $i++) {
        $PID = $row[$i]["p_id"];
        $TITLE = $row[$i]["title"];
        $LANG = $row[$i]["lang"];
   
        // printing the data        
        echo '<div class="pasteItem"><a class="pasteSummery" href="p.php?u=' . $PID .  '">' . $TITLE . '</a> <span style="color:gray;">| ' . $LANG . ' |</span></div><hr class="divider">';
    }
    mysqli_close($conn);
}

echo '</div>';

?>

</div>