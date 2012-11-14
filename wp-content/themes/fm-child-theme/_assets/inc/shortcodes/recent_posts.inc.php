<?php
function fm_recent_posts( $values = array() ) {
	/* Values Array Example
	'limit'						=>	'3',
  'offset'					=>	'0',
  'category'				=>	'',
  'parent'					=>	'',
  'title'						=>	'Recent Posts',
  'show_content'		=> 	'1',
  'content_length'	=>	'100',
  'img_width'				=>	'100',
  'img_height'			=>	'100'
	*/
	// Extract the array passed by user
	extract( $values );
	// Setup the posts array for required values.
	$post_arr = array( 'numberposts'  => $limit, 'offset' => $offset );

	// Check if the user has supplied a category if so add it to the posts array.
	if ( $category != '' ) $post_arr['category'] = $category;

	// Check if the user has supplied an offset if so add it to the posts array.
	if ( $offset != '' ) $post_arr['offset'] = $offset;

	// Execute get posts.
	$recent_posts = get_posts( $post_arr );
	echo '<div class="fm-recent-posts pull-left">';
	echo "<h3>$title</h3>";
	echo '<ul class="unstyled">';
	foreach ( $recent_posts as $post ) {
		$id = $post->ID;
		$author = $post->post_author;
		$date = $post->post_date;
		$post_title = $post->post_title;
		$url = $post->guid;
		$comments = $post->comment_count;
		// Check if the user wants to output content.
		if ( (int)$show_content ) {
			// Check if the post except has any content...
			if ( $post->post_excerpt == '' ) {
				// ...if it does not then use the post content shortened by the supplied content length...
				$content = substr( $post->post_content, 0, (int)$content_length );
			} else {
				// ...if the excerpt does have content then use that.
				$content = $post->post_excerpt;
			} // if ($post->post_excerpt == '')
		} // if((int)$show_content)
		if ( has_post_thumbnail( $id ) ) {
			$thumbnail = get_the_post_thumbnail( $id );
		} else {
			$thumbnail = "<span id=\"default_thumb\" style=\"float:left;width:100px;height:100px;background-color:grey\">&nbsp;</span>";
		}
?>
			<li class="pull-left" id="recent_post_$id">
				<div class"rp-thumb pull-left"><?php echo $thumbnail; ?></div>
				<div class="rp-center pull-left">
					<h4 class="rp-title"><?php echo $post_tile; ?></h4>
					<p class="rp-date"><?php echo $date; ?></p>
					<p class="rp-content"><?php echo $content; ?></p>
				</div>
				<a href="<?php echo $url; ?>" title="<?php echo $post_title; ?>" class="btn pull-left"><?php echo $post_title; ?></a>
			</li>
			<?php
	} // foreach($recent_posts as $post)
	echo '</ul></div>';
} // function fm_recent_posts($values = array())
