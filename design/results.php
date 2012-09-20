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
	'body'         => 'results',
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
		<input type="button" value="Export all these results" />
	
		<h2>Filter these results</h2>
		
		<form>
		<select><option>Ratings</option></select>
		<select><option>Comments</option></select>
		<select><option>Year Published</option></select>
		
		</form>
		
		<h2>Find another Document</h2>
		
		<form method="post" action="results.php">
		<input type="text" name="identifier" size="30" placeholder="Enter a DOI, URI, Title, author or keyword" />
		<input type="submit" value="go" />
		</form>
		
		
		
		
		
	</div>
		
	<div class="two-thirds column" id="pane-results">
	
		
		<p style="float: right; width: 300px; padding: 0px 0; margin: 0; text-align: right;">Sort by: <select><option> -- Select --</option><option>Title</option><option>Author</option><option>Etc</option></select></p>
		
		<p><em>Showing 1-10 of 24,034</em></p>
		
		
	
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
      
        
			1 2 3
	</div>
	
	</div>
	
	
	
	
	
	
</div><!-- CONTENT -->
	

<?php
#######################################################
# FOOTER
display_footer($vars)
?>