<?php
####################################################################################################
# FORM THE PAGE BASED UPON THE FOLLOWING PARAMETERS
error_reporting(E_ALL ^ E_DEPRECATED);
require_once(__DIR__ . '/_inc/layout.php');

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
		
		<p class="contact">Want Citagora to search your API? 
		<br /><a href="">Let us know</a></p>
		
		</span>
		
		
		
		
	</div>
		
	<div class="two-thirds column" id="pane-results">
	
	<h2>Latest Activity</h2>
	
	
	<?php
	require '_inc/simplepie.inc';  
	$feed = new SimplePie('feed.xml.rss');  
	$feed->handle_content_type();  
	$i = 0;
	?>
	
	  
            <?php foreach($feed->get_items(0, 10) as $item) : ?> 
            <?php if ($i&1) { echo '<div class="row odd">'; } else { echo '<div class="row even">'; } ?> 
            
                <?php 
                #echo $item->get_description(); 
                ?>  
                <div class="activity">
                <p class="activity"><?php echo $item->get_description(); ?> by <a href=""><?php if ($author = $item->get_author())
	{
		echo $author->get_email();
	} ?></a>
		<br /><span class="date"><?php echo $item->get_date(); ?></span>
                </p>
                </div> 
                
                <div class="record">
                <h4><a href="<?php echo $item->get_permalink(); ?>"><?php echo $item->get_title(); ?></a></h4> 
                <p class="authors">Jones, D., Smith, A. <span class="publication">Journal of Applied Journals, 2011</span></p> 
                </div>
                
                <div class="share-bear">
                	<div class="cites share">
                	<img src="_img/icon_small_comments.png" alt="" border="0" />
                	<p><a href="#dialog" name="modal">Comments</a> (5)</p>
                	</div>
                	<div class="ratings share">
                	<img src="_img/icon_small_ratings.png" alt="" border="0" />
                	<p><a href="#dialog" name="modal">Ratings</a> (11)</p>
                	</div>
                	<div class="share-this share">
                	<p>Share This Plugin</p>
                	</div>
                	<div class="export share">
                	<p>Export</p>
                	<a href=""><img src="_img/icon_small_mendeley.png" alt="" border="0" /></a>
                	<a href=""><img src="_img/icon_small_zotero.png" alt="" border="0" /></a>
                	</div>
                </div>
            
            </div> 
            
            <?php $i++; ?> 
            <?php endforeach; ?>  
      
        
	
	</div>
	
	</div>
	
	
	
	
	
	
</div><!-- CONTENT -->
	

<?php
#######################################################
# FOOTER
display_footer($vars)
?>