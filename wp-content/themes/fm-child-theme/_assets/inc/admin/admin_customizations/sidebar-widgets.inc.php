<?php
// == Widgets =========================================================

	// Remove Default Widgets
		function fm_remove_default_widgets()
		{
			// Removes the default WordPress pages widget
		 // unregister_widget('WP_Widget_Pages');

			// Removes the default WordPress calendar widget
			unregister_widget('WP_Widget_Calendar');

			// Removes the default WordPress archives widget
			unregister_widget('WP_Widget_Archives');

			// Removes the default WordPress links widget
			unregister_widget('WP_Widget_Links');

			// Removes the default WordPress meta widget
			unregister_widget('WP_Widget_Meta');

			// Removes the default WordPress search widget
			// unregister_widget('WP_Widget_Search');

			// Removes the default WordPress text widget
			// unregister_widget('WP_Widget_Text');

			// Removes the default WordPress categories widget
			unregister_widget('WP_Widget_Categories');

			// Removes the default WordPress recent widget
			unregister_widget('WP_Widget_Recent_Posts');

			// Removes the default WordPress recent comments widget
			unregister_widget('WP_Widget_Recent_Comments');

			// Removes the default WordPress RSS widget
			unregister_widget('WP_Widget_RSS');

			// Removes the default WordPress tag cloud widget
			unregister_widget('WP_Widget_Tag_Cloud');

			// Removes the default WordPress custom menu widget
			// unregister_widget('WP_Nav_Menu_Widget');

			// Removes the default WordPress custom menu widget
			// :TODO not working WP v3.4.2
			unregister_widget('Twenty_Eleven_Ephemera_Widget');

		} // fm_remove_default_widgets()


// == Sidebars ========================================================
	function fm_unregister_sidebars()
	{
		// global $wp_registered_sidebars
		// pp($wp_registered_sidebars);
		// unregister_sidebar( $id );
	} // fm_unregister_sidebars()