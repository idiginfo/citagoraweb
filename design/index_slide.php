<?php
####################################################################################################
# FORM THE PAGE BASED UPON THE FOLLOWING PARAMETERS
require_once(dirname(__FILE__) .'/_inc/layout.php');
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
	
	<div class="container" id="boxes">
		<div class="one-third column">
		<h2>Find a Scientific Document</h2>
		
		<form method="post" action="results.php">
		<input type="text" name="identifier" size="30" placeholder="Enter a DOI, URI, Title, author or keyword" />
		<input type="submit" value="go" />
		</form>
		
		<span class="knockout">
		
		<h2>Currently Searching:</h2>
		
		<ul class="disc">
		<li>Web of Science</li>
		<li>Wiley</li>
		<li>Etc</li>
		</ul>
		
		<p>Want Citagora to search your API? <a href="">Let us know</a></p>
		
		</span>
		
		
		
		
	</div>
		
	<div class="two-thirds column" id="pane">
	
	<h2>Latest Activity</h2>
	
	
	<?php
	require '_inc/simplepie.inc';  
	$feed = new SimplePie('feed.xml.rss');  
	$feed->handle_content_type();  

	?>
	
	<ul id="widget">  
            <?php foreach($feed->get_items(0, 15) as $item) : ?>  
            <li> <div class="paper-record"> 
                <?php #echo $item->get_description(); ?>  
                <div class="paper">
                <p class="activity"><?php echo $item->get_description(); ?> by <a href=""><?php if ($author = $item->get_author())
	{
		echo $author->get_email();
	} ?></a>		
				: <span class="date"><?php echo $item->get_date(); ?></span>
                </p> 
                <h4><a href="<?php echo $item->get_permalink(); ?>"><?php echo $item->get_title(); ?></a></h4> 
                <p class="authors">Jones, D., Smith, A. <span class="publication">Journal of Applied Journals, 2011</span></p> 
                </div>
                <a href="#dialog" name="modal"><img src="_img/share-bear.jpg" alt="" border="0" style="margin-top: 3px;" /></a>
                </div> 
            </li>  
            <?php endforeach; ?>  
        </ul>  
        
	
	</div>
	
	</div>
	
	
	
	
	
	
</div><!-- CONTENT -->
	

<?php
#######################################################
# FOOTER
display_footer($vars)
?>