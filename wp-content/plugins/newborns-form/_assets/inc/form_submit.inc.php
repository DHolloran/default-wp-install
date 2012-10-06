<?php

if (!empty($_POST['newborns_submit'])) {

	$newborn_name = ($_POST['newborn_name']) ? $_POST['newborn_name'] : '';
	$newborn_birth_month = ($_POST['newborn_birth_month']) ? $_POST['newborn_birth_month'] : '';
	$newborn_birth_date = ($_POST['newborn_birth_date']) ? $_POST['newborn_birth_date'] : '';
	$newborn_birth_year = ($_POST['newborn_birth_year']) ? $_POST['newborn_birth_year'] : '';
	$newborn_birth_time = ($_POST['newborn_birth_time']) ? $_POST['newborn_birth_time'] : '';
	$newborn_lbs = ($_POST['newborn_lbs']) ? $_POST['newborn_lbs'] : '';
	$newborn_oz = ($_POST['newborn_oz']) ? $_POST['newborn_oz'] : '';
	$newborn_length = ($_POST['newborn_length']) ? $_POST['newborn_length'] : '';
	$newborn_code_word = ($_POST['newborn_code_word']) ? $_POST['newborn_code_word'] : '';
	$newborn_image = ($_POST['upload_image']) ? $_POST['upload_image'] : '';

	$newborn_post_content = "<div class=\"new-born-wrap\">

		<!-- Newborn Image -->
		<div class=\"newborn-image-wrap\" style=\"float: left; margin-right: 20px;\">
			<img src=\"{$newborn_image}\" alt=\"{$newborn_name}\" width=\"140\" height=\"160\">
		</div> <!-- END newborn-image-wrap -->

		<!-- Newborn Info -->
		<ul style=\"float: left; padding-top: 10px; list-style: none;\">
			<li>{$newborn_name}</li>
			<li>Born on: {$newborn_birth_month}/{$newborn_birth_date}/{$newborn_birth_year} @ {$newborn_birth_time}</li>
			<li>Weight: {$newborn_lbs} lbs. {$newborn_oz} oz.</li>
			<li>Length: {$newborn_length} Inches</li>
			<li>Code Word: {$newborn_code_word}</li>
		</ul>

	</div> <!-- .new-born-wrap -->";


	// Get User ID
	$current_user = wp_get_current_user();

	// Add New Post
	$newborn_cat_id = get_cat_ID('Newborns');
	$newborn_post = array(
	  'post_title'    => "{$newborn_name} - born {$newborn_birth_month}/{$newborn_birth_date}/{$newborn_birth_year} @ {$newborn_birth_time}",
	  'post_content'  => $newborn_post_content,
	  'post_status'   => 'publish',
	  'post_name'     => "{$newborn_name}_{$newborn_birth_month}_{$newborn_birth_date}_{$newborn_birth_year}",
  	'post_type'     => 'post',
	  'post_author'   => $current_user->ID,
	  'post_category' => array($newborn_cat_id)
	 );

	// Insert the post into the database
	$inserted_post = wp_insert_post( $newborn_post );
	if($inserted_post){
		$post_permalink = get_permalink($inserted_post);
		echo "<span style=\"color: green; height: 15px; line-height: 15px;font-weight:bold;\">Post added successfully <a href=\"{$post_permalink}\" target=\"_blank\">View your post</a></span>";
	} else {
		echo "<span style=\"color: red; height: 15px; line-height: 15px;font-weight:bold;\">Something went wrong please try again</span>";
	} // if($inserted_post)

} // if(!empty($_POST['newborns_submit']))


