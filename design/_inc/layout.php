<?php
####################################################################################################
# LANGUAGE
ini_set ('display_errors', 1);
error_reporting (E_ALL & ~E_NOTICE);



####################################################################################################
# GENERAL FUNCTIONS
#require_once($_SERVER['DOCUMENT_ROOT'].'/_inc/general.php');

####################################################################################################
# HEADER
function display_header($vars) {
	global $path;
	# CONSTANTS
	define('_SITE_NAME', 'Citagora');
	# PASSED VARIABLES
	$section    = trim($vars['section']);
	$subsection = trim($vars['subsection']);
	$body       = trim($vars['body']);
	if (isset($vars['meta_description'])) {
		$meta_description = trim($vars['meta_description']);
	} else {
		$meta_description = "";
	}
	if (isset($vars['meta_keywords'])) {
		$meta_keywords = trim($vars['meta_keywords']);
	} else {
		$meta_keywords = "";
	}
	# PROCESS PAGE SPECIFIC INFORMATION
	if ($vars['db_connect']) {
		require_once($_SERVER['DOCUMENT_ROOT'].'/_inc/db/info.php');
	}
	if ($vars['session']) {
		require_once($_SERVER['DOCUMENT_ROOT'].'/_inc/session.php');
	}
	if ($vars['forms']) {
		require_once($_SERVER['DOCUMENT_ROOT'].'/_inc/forms.php');
	}
	if ($section) {
		$page_title = _SITE_NAME.': '.$section;
	} else {
		$page_title = _SITE_NAME;
	}
	if ($subsection) {
		$section_subsection = $section.': <span class="subsection">'.$subsection.'</span>';
		$page_title = $page_title.': '.$subsection;
	}
	?>
	
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>

	<!-- Basic Page Needs
  ================================================== -->
	<meta charset="utf-8">
	<title><?php echo $page_title; ?></title>
	<meta name="author" content="Institute for Digital Information and Scientific Communication">
	
	<meta name="robots" content="NOODP">
	<meta name="robot" content="index,follow" />
	<meta name="robots" content="index,follow" />
	<meta name="description" content="<?php echo $meta_description; ?>" />
	<meta name="keywords" content="<?php echo $meta_keywords; ?>" />
	
	<!-- Mobile Specific Metas
  	================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- CSS
  	================================================== -->
  	<link href='http://fonts.googleapis.com/css?family=Trocchi|Signika:300,400,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="_css/base.css">
	<link rel="stylesheet" href="_css/skeleton.css">
	<link rel="stylesheet" href="_css/layout.css">

	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<style type="text/css">
		label { display: block!important; }
		</style>
	<![endif]-->
	
	
	
	<script type="text/javascript" src="http://code.jquery.com/jquery-latest.pack.js"></script>

	
	<?php
	# ALTERNATIVE STYLESHEETS AND JAVASCRIPT
	if ( ($vars['forms']) ) {
		#print '	<style type="text/css" media="screen">@import "/css/forms.css";</style>'."\n";
	?>
	<script src="/js/jquery.uniform.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" charset="utf-8">
      $(function(){
        $("input, textarea, select, button").uniform();
      });
    </script>
    <!--[if lt IE 9]>
	<style type="text/css">
	label { display: block!important; }
	</style>
	<![endif]-->
    <link rel="stylesheet" href="/css/uniform.default.css" type="text/css" media="screen">
    <?php } 
    
    # ALTERNATIVE STYLESHEETS AND JAVASCRIPT
	if ( $vars['body'] == 'detail' ) {
		print '<script type="text/javascript" src="http://code.jquery.com/jquery-latest.pack.js"></script>'."\n";
		print '	<script src="_js/jquery.tabify.js" type="text/javascript" charset="utf-8"></script>'."\n";
	?>
	<script>
	$(document).ready(function() {	
		$('#tabs').tabify();
	});
	</script>
	<?php }
	
    
	
	# /ALTERNATIVE STYLESHEETS AND JAVASCRIPT
?>
<!--[if lte IE 6]>
	<script src="/js/png-fix.js" type="text/javascript"></script>
	<style type="text/css" media="screen">@import "/css/ie-fix.css";</style>
<![endif]-->

	<!-- Favicons
	================================================== -->
	<link rel="shortcut icon" href="/_img/favicon.ico">
	<link rel="apple-touch-icon" href="/_img/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="72x72" href="/_img/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="/_img/apple-touch-icon-114x114.png">
	
	<?php if ($vars['map']) {
	?>
	<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
    <script type="text/javascript">
function initialize()
{
    var latlng = new google.maps.LatLng(30.472698, -84.280957);
    var myOptions =
    {
        zoom: 15,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
     var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
    var myMarker = new google.maps.Marker(
               {
                position: latlng,
                map: map,
                title:"Trew Media"
               });
}
</script>
	<?php } ?>
	
</head>



<body class="default pages class-<?php print $body;?>">

	<div id="toolbar">
	<div class="container" style="padding: 0;">
		<div class="sixteen columns" style="text-align: right; padding: 0;">
			<ul>
				<li><a href="">Help</a></li>
				<li><a href="">About</a></li>
				<li><a href="login.php">Login</a></li>
			</ul>
		</div>
	</div>
	</div>

	
	
	
	
<?php
	if ($session) {
		check_session($body, $_SESSION['session_time']); # SESSION CHECK
	}
	if ($dbc_message) {
		print_r($dbc_message);
		display_footer($vars);
		exit;
	}
	if ($dbm_message) {
		print_r($dbm_message);
		display_footer($vars);
		exit;
	}
	return $vars;
}





####################################################################################################
# FOOTER
function display_footer($vars) {

if ($vars['body'] == 'home') {
?>
<script type="text/javascript" src="_js/jquery.newsScroll.js"></script>  
<script type="text/javascript">  
    $('#widget').newsScroll({  
        speed: 1000,  
        delay: 6000  
    });  
  
    // or just call it like:  
    // $('#widget').newsScroll();  
</script>  
<?php } 
?>
	<div id="footer">
	<div class="container">
		<a href="https://www.idiginfo.org" target="_blank"><img src="_img/idiginfo.png" alt="iDigInfo" border="0" /></a>
	</div>
	</div>
		
<!-- End Document
================================================== -->
</body>
</html>
<?php

	if ($vars['db_connect']) {
		mysql_close();
	}
}



?>