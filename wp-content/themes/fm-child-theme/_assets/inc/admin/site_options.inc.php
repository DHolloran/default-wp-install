<?php
if ( !empty( $_POST['fm_site_options_submit'] ) ) {
	// code...
} // if('fm_site_options_submit')
$contact_email = ( get_option( 'fm_contact_email' ) ) ? get_option( 'fm_contact_email' ) : get_bloginfo( 'admin_email' );
$submit_url = site_url(). '/wp-admin/admin.php?page=site-options';
?>
<form action="<?php echo $submit_url; ?>" method="post" accept-charset="utf-8"  id="fm_site_options_form">
	<ul class="unstyled">

		<!-- Contact Email -->
		<li class="pull-left clearfix">
			<label for="fm_site_options_contact_email" class="pull-left">Contact Email:&nbsp;</label>
				<input type="text" class="pull-left input-xlarge" name="fm_site_options_contact_email" id="fm_site_options_contact_email" value="<?php echo $contact_email; ?>" placeholder="Main contact email.">
		</li> <!-- END Contact Email -->

		<!-- Form Submit -->
		<li class="pull-left clearfix">
			<input type="submit" class="button-primary pull-left" name="fm_site_options_submit" id="fm_site_options_submit" value="Save Options">
		</li> <!-- END Form Submit -->

	</ul>
</form>
