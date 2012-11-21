<?php
if ( !empty( $_POST['dh_site_options_submit'] ) ) {
	// code...
} // if('dh_site_options_submit')
$contact_email = ( get_option( 'dh_contact_email' ) ) ? get_option( 'dh_contact_email' ) : get_bloginfo( 'admin_email' );
$submit_url = site_url(). '/wp-admin/admin.php?page=site-options';
?>
<form action="<?php echo $submit_url; ?>" method="post" accept-charset="utf-8"  id="dh_site_options_form">
	<ul class="unstyled">

		<!-- Contact Email -->
		<li class="pull-left clearfix">
			<label for="dh_site_options_contact_email" class="pull-left">Contact Email:&nbsp;</label>
				<input type="text" class="pull-left input-xlarge" name="dh_site_options_contact_email" id="dh_site_options_contact_email" value="<?php echo $contact_email; ?>" placeholder="Main contact email.">
		</li> <!-- END Contact Email -->

		<!-- Form Submit -->
		<li class="pull-left clearfix">
			<input type="submit" class="button-primary pull-left" name="dh_site_options_submit" id="dh_site_options_submit" value="Save Options">
		</li> <!-- END Form Submit -->

	</ul>
</form>
