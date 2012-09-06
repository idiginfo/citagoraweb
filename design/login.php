<?php
####################################################################################################
# FORM THE PAGE BASED UPON THE FOLLOWING PARAMETERS
require_once(dirname(__FILE___) .'/_inc/layout.php');
$vars = array (
	'db_connect'   => FALSE,
	'session'      => FALSE,
	'forms'        => FALSE,
	'login'        => FALSE,
	'section'      => 'Document Discovery',
	'subsection'   => '',
	'body'         => 'home',
	'gallery'  => TRUE
);
display_header($vars); # FORMING PAGE
?>
	
<div id="content">
 
  
	<div class="container" style="margin-top: 30px">
		<div class="sixteen columns">
			<h1><a href="index.php"><img src="_img/citagora.png" alt="Citagora" border="0" /></a></h1>
		</div>
		
		
		
	</div><!-- container -->
	
	<div class="container" id="login">
		<div class="one-third column" id="social-login">
		
		<img src="_img/facebook_signin.png" alt="Signin with Facebook" style="margin-top: 12px;" />
			
			<img src="_img/twitter_signin.png" alt="Signin with Twitter" style="margin-top: 4px;" />
			
			<img src="_img/linkedin_signin.png" alt="Signin with LinkedIn" style="margin-top: 4px;" />
			
			<img src="_img/google_signin.png" alt="Signin with Google" style="margin-top: 4px;" />
			
			<img src="_img/openid_signin.png" alt="Signin with OpenID" style="margin-top: 4px;" />
			
		
		
		
		</div>
		
		<div class="two-thirds column" id="pane">
	
		<h2>Login to Citagora</h2>
		<p>Login to Citagora to comment and rank documents, and to share and export content you find.</p>
	
		 <form method="post" action="results.php">
		<input type="text" name="username" size="30" placeholder="Username" />
		<br /><input type="text" name="password" size="30" placeholder="Password" />
		<br /><input type="submit" value="Login" />
		</form>
        
        <p><a href="">Create an account</a> | <a href="">Forgot your password?</a></p>
        
        
		</div>
	</div>
	
	
	
	
	
</div><!-- CONTENT -->
	

<?php
#######################################################
# FOOTER
display_footer($vars)
?>