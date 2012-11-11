=== Modal Dialog ===
Contributors: jackdewey
Donate link: http://yannickcorner.nayanna.biz/wordpress-plugins/modal-dialog/
Tags: modal, dialog, pop-up, window, multiple
Requires at least: 2.8
Tested up to: 3.4.1
Stable tag: trunk

The purpose of this plugin is to allow users to create one or more modal dialog(s) / pop-up window(s) that will appear when a user visits their site. The number of times that these can load is configurable. They can load content from external sites or custom HTML code into the dialog.

== Description ==

The purpose of this plugin is to allow users to create one or more modal dialog(s) / pop-up window(s) that will appear when a user visits their site. The number of times that these can load is configurable. They can load content from external sites or custom HTML code into the dialog. This plugin can be used to invite people to register to a newsletter, respond to a survey, or simply welcome come to a site upon their first visit.

== Installation ==

1. Download the plugin and unzip it.
1. Upload the modal-dialog folder to the /wp-content/plugins/ directory of your web site.
1. Activate the plugin in the Wordpress Admin.
1. Using the Configuration Panel for the plugin, configure as desired
1. Dialogs can also be assigned to posts/pages within the editor

== Changelog ==

= 2.4 =
* Re-wrote dialog display decision code to be cleaner
* Upgraded fancybox to version 1.3.4

= 2.3.9 =
* Changed Show on front page option to control display on front page whether or not the "Only show on specific pages and single posts"

= 2.3.8 =
* Added option field to specify pages where the dialog should NOT be shown

= 2.3.7 =
* Fixed "Delete all cookies" button on admin page

= 2.3.6 =
* Fixed compatibility issue with WP 3.4 related to jQuery queuing call

= 2.3.5 =
* Second fix to display logic count when selecting a display period and a maximum number of displays

= 2.3.4 =
* Fixed display count logic

= 2.3.3 =
* Added new option to force hiding of the scroll bars

= 2.3.2 =
* Second fix to IE 7 check

= 2.3.1 =
* Fixed display logic code
* Small correction to IE 7 if check for W3C validation

= 2.3 =
* Added option to disable modal dialog on mobile browsers

= 2.2.5 =
* Corrected problem with Activate option not working correctly

= 2.2.4 =
* Added mechanism to register callback function to be called when the Modal Dialog is closed

= 2.2.3 =
* Added option to display modal dialog when a new comment is posted (as a thank you note)

= 2.2.2 =
* Added call to do_shortcode on dialogtext so that shortcodes can be inserted and processed for the content

= 2.2.1 =
* Fixed issue with Modal Dialog not showing up after upgrading

= 2.2 =
* Added option to only display dialog every X number of times that page is reloaded
* Added option to not display dialog to users that are logged on to site

= 2.1.1 =
* Updated version of jQuery Cookies script to try and resolve a problem with Chrome on PC

= 2.1 =
* Changed IE-specific code only to display in IE7 or older
* Tested against Wordpress 3.2

= 2.0.6 =
* Renamed jQuery.cookie.js to cookie.js to resolve a user issue

= 2.0.5 =
* Declared global variable to hold plugin class to allow for external calls

= 2.0.4 =
* Added back modal_dialog_init function. Should be called as $modal_dialog_init. No argument anymore.

= 2.0.3 =
* Fixed error in Screen Options tab
* Fixed admin page layout

= 2.0.2 =
* Restored Manual Display option when calling Modal Dialog functions manually

= 2.0.1 =
* Fixed internal error problem that appeared in version 2.0 on some systems

= 2.0 =
* Adds ability to define multiple modal dialogs
* Adds ability of assigning a modal dialog to a post/page in editor

= 1.2.3 =
* Added button to easily delete all cookies for a site from admin panel to facilitate testing

= 1.2.2 =
* Fixed code to get Modal Dialog only to show on specific pages or posts

= 1.2.1 =
* Added ability to display Modal Dialog on specific single post pages

= 1.2 =
* Tested for Wordpress 3.1 Compatibility

= 1.1.9 =
* Added call to reset query mechanism so that Modal Dialog properly writes code to page footer

= 1.1.8 =
* Renamed installation function to avoid conflicts with plugin Mobile Detector

= 1.1.7 =
* Added option to auto-close dialog after a user-defined period (set in milliseconds)

= 1.1.6 =
* Made cookies global to the site instead of specific to a page

= 1.1.5 =
* Added new option to control overlay opacity

= 1.1.4 =
* Extended manual cookie creation to session cookies that control only showing the dialog once per session

= 1.1.3 =
* Added option to tell Modal Dialog that you will manually set the cookie manually after the dialog is displayed

= 1.1.2 =
* Added parameters to API calls to allow user to call on specific page templates

= 1.1.1 =
* Added option to only show modal dialog once on a site per browser session
* Added option to keep modal dialog centered on page when user scrolls
* Added option to hide close button to allow user to control dialog closing through code

= 1.1 =
* Added option to force user the specification of pages on which Modal Dialog should be displayed
* Added option to display Modal Dialog on front page

= 1.0.9 =
* Removed border color option to avoid conflicts with other plugins using fancybox

= 1.0.8 =
* Changed way that background color and text color are applied to avoid conflicts with other plugins using fancybox

= 1.0.7 =
* Prevent modal-dialog code from executing when viewing admin pages to avoid conflicts with admin page scripts

= 1.0.6 =
* Fixed: Background Color and Text Color now work as expected
* Added: Border Color Option

= 1.0.5 =
* Fixed: Images (border / close icon) not showing up correctly in Internet Explorer
* Fixed: Dialog Width and Height configuration parameters were ignored

= 1.0.4 =
* Removed unnecessary debug statement

= 1.0.3 =
* Fixed: Removed reference to missing function to change plugin icon

= 1.0.2 =
* Corrected problem with special HTML characters getting stripped out of dialog content when specifying in text box.
* Added option to auto-resize dialog

= 1.0.1 =
* Upgraded fancybox plugin to latest version
* Added option to specify number of times to display dialog
* Added option to specify how modal dialog can be closed.

= 1.0 = 
* First release

== Frequently Asked Questions ==

= Why is Modal Dialog not showing up on my web site? =

There are typically two main reasons why Modal Dialog does not show up correctly on web pages:

1- You have another plugin installed which uses jQuery on your site that has its own version of jQuery instead of using the default version that is part of the Wordpress install. To see if this is the case, go to your site and look at the page source, then search for jQuery. If you see some versions of jQuery that get loaded from plugin directories, then this is most likely the source of the problem as they would conflict with the jQuery 1.3.2 that is delivered with Wordpress.

2- The other thing to check is to see if your theme has the wp_head and wp_footer functions in the theme's header. If these functions are not present, then the plugin will not work as expected.

You can send me a link to your web site if these solutions don't help you so that I can see what is happening myself and try to provide a solution.

= How can I close the Modal Dialog Window Manually? =

You can create a button or other control that calls the following javascript:

parent.jQuery.fancybox.close();

= How can I manually set the cookie if I ask Modal Dialog to let me do it manually?

Call the following javascript / jQuery function, setting the cookie-name to match the name entered in the Modal Dialog settings, the cookievalue and the duration to any duration that you deem acceptable.

jQuery.cookie('cookie-name', cookievalue, { expires: 365 });

== Screenshots ==

Check out the Modal Dialog site for a live demonstration.