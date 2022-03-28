<div class="container">
    <div class="row centered-form" style="margin-top: 60px;">
    <div class="col-xs-12 col-sm-8 col-md-4 col-sm-offset-2 col-md-offset-4">
        <div class="panel panel-default" style="background: rgba(255, 255, 255, 0.8);box-shadow: rgba(0, 0, 0, 0.3) 20px 20px 20px;">
        	<div class="panel-heading">
				<h3 class="panel-title" style="text-align: center;">Sign-up Here <small>It's free!</small></h3>
			</div>
			<div class="panel-body">
			<form role="form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
			    <div class="row">
			        <div class="col-xs-12 col-sm-12 col-md-12">
			    	 	<div class="form-group">
			                <input type="text" name="name" id="name" class="form-control input-sm" placeholder="Enter Your name" required>
			    		</div>
			    	</div>
			    </div>
 
			    <div class="form-group">
			        <input type="email" name="email" id="email" class="form-control input-sm" placeholder="Email Address" required>
			    </div>

	            <div class="row">
			        <div class="col-xs-6 col-sm-6 col-md-6">
			    	    <div class="form-group">
			    		    <input type="password" name="password" id="password" class="form-control input-sm" placeholder="Password" min="6" max="255" required>
			    		</div>
			    	</div>
			    	<div class="col-xs-6 col-sm-6 col-md-6">
			    	    <div class="form-group">
			    		  	<input type="password" name="confirm_password" id="confirm_password" class="form-control input-sm" placeholder="Confirm Password" min="6" max="255" required>
			    		</div>
			    	</div>
			    </div>
			    <input type="submit" name="submit" value="Register" class="btn btn-info btn-block">	
			</form>
		   </div>
	   </div>
    </div>
    </div>
 </div>