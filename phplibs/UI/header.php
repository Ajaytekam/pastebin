<?php
          // Initialize the session
          ob_start();
          session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/webapp/frontend/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/webapp/frontend/main.css">
    <script src="/webapp/frontend/bootstrap/jquery/jquery.min.js"></script>
    <script src="/webapp/frontend/bootstrap/js/bootstrap.min.js"></script>
    <title>PasteBin Service</title>
</head>
<body>
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">PasteBin-Service</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="index.php">Home</a></li>
      <li><a href="listpastes.php">Paste-list</a></li>
      <?php 
        if(isset($_SESSION["LoggedIn"]) && $_SESSION["LoggedIn"] === true){
          echo '<li><a href="userlist.php">User-List</a></li>';
        } 
      ?>
    </ul>

    <form method="get" class="navbar-form navbar-left" action="/webapp/searchpaste.php">
      <div class="form-group">
        <input type="text" class="form-control" name="q" placeholder="Search All Paste">
      </div>
      <button type="submit" class="btn btn-default">Search</button>
    </form>

    <ul class="nav navbar-nav navbar-right">
      <?php
      // php code to check if logged in or not
          if(isset($_SESSION["LoggedIn"]) && $_SESSION["LoggedIn"] === true){
            echo '<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><img src="/webapp/frontend/images/login.webp" height="10" width="10"/>  ' . $_SESSION["username"] . '<span class="caret"></span></a>';
            echo '<ul class="dropdown-menu">';
            echo '<li><a href="userpaste.php?uid=' . $_SESSION["u_id"] . '">My Pastes</a></li>';
            echo '<li><a href="logout.php">Log-Out</a></li>';
            echo "</ul>";
            echo "</li>";
          } else {
            echo '<li><a href="signup.php"><img src="/webapp/frontend/images/signup.webp" height="10" width="10"/> Sign Up</a></li>';
            echo '<li><a href="#loginForm" id="login" data-toggle="modal"><img src="/webapp/frontend/images/login.webp" height="10" width="10"/> Login</a></li>';
          } 
      ?>
    </ul>
  </div>
</nav>

<!-- Modal HTML -->
<div id="loginForm" class="modal fade">
	<div class="modal-dialog modal-login">
		<div class="modal-content">
			<div class="modal-header">
				<div class="avatar">
					<img src="/webapp/frontend/images/avatar.png" alt="Avatar">
				</div>				
				<h4 class="modal-title">Member Login</h4>	
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">
				<form method="post" action="signin.php" >
					<div class="form-group">
						<input type="text" class="form-control" name="username" placeholder="Username" required="required">		
					</div>
					<div class="form-group">
						<input type="password" class="form-control" name="password" placeholder="Password" required="required">	
						<input type="hidden" name="redirectURL" value="<?php echo $_SERVER["PHP_SELF"]; ?>">	
					</div>        
					<div class="form-group">
						<button type="submit" class="btn btn-primary btn-lg btn-block login-btn">Login</button>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<a href="signup.php">Register-Here</a>
			</div>
		</div>
	</div>
</div>     
