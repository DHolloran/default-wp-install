<?php
// == Post/Page Meta Box =============================================
// Add Meta Box Content
function fm_add_meta_box_content( $post ) {
	// pp($post);
	// get_post_meta($post->ID, 'fm_meta_key_name', $single) $single = true for string false for array
	// add_post_meta($post->ID, 'fm_meta_key_name', $meta_value, $unique);
	// update_post_meta($post->ID, 'fm_meta_key_name', $meta_value, $prev_value);
} // fm_add_meta_box_content()

// Add Meta Boxes
function fm_add_meta_boxes() {
	// FMM Title
	add_meta_box( 'fm-meta-box', 'FMM Title', 'fm_add_meta_box_content', 'post', 'normal', 'high' );
} // fm_add_meta_boxes()

// Remove Meta Boxes
function fm_remove_meta_boxes() {
	// Links
	// Removes Links > Target
	// remove_meta_box('linktargetdiv', 'link', 'normal');

	// Removes Links > Link Relationship (XFN)
	// remove_meta_box('linkxfndiv', 'link', 'normal');

	// Removes Links > Advanced
	// remove_meta_box('linkadvanceddiv', 'link', 'normal');

	// Removes Links > Categories
	// remove_meta_box('linkcategorydiv', 'link', 'normal');

	// Posts
	// Posts > Excerpt
	// remove_meta_box('postexcerpt', 'post', 'normal');

	// Posts > Send Trackbacks
	// remove_meta_box('trackbacksdiv', 'post', 'normal');

	// Posts > Discussion
	// remove_meta_box('commentstatusdiv', 'post', 'normal');

	// Posts > Custom Fields
	// remove_meta_box('postcustom', 'post', 'normal');

	// Posts > Author
	// remove_meta_box('authordiv', 'post', 'normal');

	// Posts > Slug
	// remove_meta_box('sqpt-meta-tags', 'post', 'normal');

	// Posts > Tags
	// remove_meta_box('tagsdiv-post_tag', 'post', 'side');

	// Pages
	// Pages > Discussion
	// remove_meta_box('commentstatusdiv', 'page', 'normal');

	// Pages > Custom Fields
	// remove_meta_box('postcustom', 'page', 'normal');

	// Pages > Author
	// remove_meta_box('authordiv', 'page', 'normal');

	// Pages > Slug
	// remove_meta_box('slugdiv', 'page', 'normal');

	// Pages > Comments
	// remove_meta_box('commentsdiv', 'page', 'normal');

} // fm_remove_meta_boxes()

// == Posts ===========================================================
function fm_remove_posts_meta_boxes() {
	// Remove revisions metabox.
	// remove_meta_box( 'revisionsdiv', 'post', 'normal' );

	// == Sidebar Meta Boxes ==
	// Remove formats metabox.
	// remove_meta_box( 'formatdiv', 'post', 'side' );

	// Remove categories metabox.
	// remove_meta_box( 'categorydiv', 'post', 'side' );

	// Remove tags metabox.
	// remove_meta_box( 'tagsdiv-post_tag', 'post', 'side' );

	// Remove featured image metabox.
	// remove_meta_box( 'postimagediv', 'post', 'side' );

	// == Main Meta Boxes ==
	// Remove excerpt metabox.
	// remove_meta_box( 'postexcerpt', 'post', 'normal' );

	// Remove trackbacks metabox.
	// remove_meta_box( 'trackbacksdiv', 'post', 'normal' );

	// Remove custom fields metabox.
	// remove_meta_box( 'postcustom', 'post', 'normal' );

	// Remove comments status metabox (discussion).
	// remove_meta_box( 'commentstatusdiv', 'post', 'normal' );

	// Remove comments metabox.
	// remove_meta_box( 'commentsdiv', 'post', 'normal' );

	// Remove slug metabox.
	// remove_meta_box( 'slugdiv', 'post', 'normal' );

	// Remove author metabox
	// remove_meta_box( 'authordiv', 'post', 'normal' );

} // fm_remove_posts_meta_boxes()

// == Pages ===========================================================
function fm_remove_pages_meta_boxes() {
	// Remove revisions metabox.
	// remove_meta_box( 'revisionsdiv', 'page', 'normal' );

	// == Sidebar Meta Boxes ==
	// Remove formats metabox.
	// remove_meta_box( 'formatdiv', 'page', 'side' );

	// Remove attributes metabox.
	// remove_meta_box( 'pageparentdiv', 'page', 'side' );

	// Remove featured image metabox.
	// remove_meta_box( 'postimagediv', 'page', 'side' );

	// == Main Meta Boxes ==
	// Remove custom fields metabox.
	// remove_meta_box( 'postcustom', 'page', 'normal' );

	// Remove comments status metabox (discussion).
	// remove_meta_box( 'commentstatusdiv', 'page', 'normal' );

	// Remove comments metabox.
	// remove_meta_box( 'commentsdiv', 'page', 'normal' );

	// Remove slug metabox.
	// remove_meta_box( 'slugdiv', 'page', 'normal' );

	// Remove author metabox
	// remove_meta_box( 'authordiv', 'page', 'normal' );

} // fm_remove_pages_meta_boxes()
