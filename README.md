Default WordPress Install
=========================

**Author(s):** Dan Holloran @dholloran
**Author(s) email:** <Dan@danholloran.com>
**Website:** [http://www.DanHolloran.com]( http://www.danholloran.com)
**Description:** A good starting place for all new Wordpress projects.
**Acknowledgments:** Thank you to everyone that has created an open project that I have included in this project which allows me to make my job/life easier

***
###Change Log
- **1.0.0** To many to list first upload
- **Since 1.0.0**
	- Removed TwentyTen default theme
 	- Setup default .sublime-project
 	- Moved maintenance files into maintenance directory
 	- Added more ShortCodes
 	- Merged TODO.md and INSTALL.md into README.md
 	- Added AJAX helper
 	- Added child theme screenshot
 	- Fixed user role checking
 	- Added slides custom post type for use with slideshows
 	- Added retina.js
 	- Added prefix-free.js
 	- Added jquery.timeago.js
 	- Add setup instructions/checklist to developer documentation
 	- Added JW Player plugin
 	- Added Facebook open graph plugin
 	- Added wp-optimize plugin
	- Added Sitemap Generator plugin
	- Added Google Drive DB backup plugin
	- Added documentation on the available PHP helper functions
	- Removed unused plugins
	- Added icons from icomoon
	- Added applicaiton icons
	- Updated docs added available plugins information and links to there plugin page
	- Added prelaunch check list to developer documentation
	- And much more

***
###TODO

####Forms TODOs
- Include JavaScript form validation http://rickharrison.github.com/validate.js/
- Find PHP form validation

####Icons/Fonts TODOs
- Add more icon fonts
- Add Google/Adobe Edge fonts support

####Add/Edit Files TODOs
- Complete /wp-content/sample-db-error.php for custom DB error page
- Make error pages
- Page to PDF creation

####Repsonsive TODOs
- Add more responsive helpers
- Add Adaptive.js????
- Add Enhance.js????
- Add grid system

####General TODOs
- Look for other libs to add that will make life easier
- Add style guide/best practices
- Make contact us form
- Re-test dh_get_thumbnail_url()
- Code/Directory Cleanup

####Completed TODOs
- ~~Make a function that unregisters sidebars use global $wp_registered_sidebars to get the sidebar id~~
- ~~Add humans.txt~~
- ~~Add author meta tag to header as Dan Holloran~~
- ~~Make robots.txt stop google from indexing until launch~~
- ~~Add explanations to LESS mixins~~
- ~~Implement H5BP into child theme~~
- ~~Fill out license~~
- ~~Print Stylesheet~~
- ~~List of shortcodes available~~

***
###Plugins Included
- [ALO Easymail](http://wordpress.org/extend/plugins/alo-easymail/)
- [Backup](http://wordpress.org/extend/plugins/backup/)
- [Clean Notifications](http://wordpress.org/extend/plugins/clean-notifications/)
- [Debogger](http://wordpress.org/extend/plugins/debogger/) **Developer**
- [Debug Bar](http://wordpress.org/extend/plugins/debug-bar/) **Developer**
- [Debug Bar Cron](http://wordpress.org/extend/plugins/debug-bar-cron/) **Developer**
- [Developer](http://wordpress.org/extend/plugins/developer/) **Developer**
- [Disable Comments](http://wordpress.org/extend/plugins/disable-comments/)
- [Google XML Sitemaps](http://wordpress.org/extend/plugins/google-sitemap-generator/)
- [JW Player](http://wordpress.org/extend/plugins/jw-player-plugin-for-wordpress/)
- [Log Deprecated Notices](http://wordpress.org/extend/plugins/log-deprecated-notices/) **Developer**
- [Rewrite Rules Inspector](http://wordpress.org/extend/plugins/rewrite-rules-inspector/) **Developer**
- [Share This](http://wordpress.org/extend/plugins/share-this/)
- [Subscribe to Comments](http://wordpress.org/extend/plugins/subscribe-to-comments/)
- [Theme Check](http://wordpress.org/extend/plugins/theme-check/) **Developer**
- [User Switching](http://wordpress.org/extend/plugins/user-switching/) **Developer**
- [Velvet Blues Update URLs](wordpress.org/extend/plugins/velvet-blues-update-urls/) **Developer**
- [W3 Total Cache](http://wordpress.org/extend/plugins/w3-total-cache/)
- [WordPress Importer](http://wordpress.org/extend/plugins/wordpress-importer/)
- [WordPress SEO by Yoast](http://wordpress.org/extend/plugins/wordpress-seo/)
- [WP DBManager](http://wordpress.org/extend/plugins/wp-dbmanager/)
- [WP Members](http://wordpress.org/extend/plugins/wp-members/)
- [WP Open Graph Meta](http://wordpress.org/extend/plugins/wp-open-graph-meta/)
- [WP Optimize] (http://wordpress.org/extend/plugins/wp-optimize/)

***
###Shortcodes
- [dh-blog-info show="<value>"] Shortcode version of `get_bloginfo($value);`
- **Directory Helpers**
	- [dh-site-url] Shortcode version of `site_url();`
	- [dh-home-url] Shortcode version of `home_url();`
	- [dh-template-url] Shortcode version of `get_template_directory_uri();`
	- [dh-child-theme-url] Shortcode version of `get_stylesheet_directory_uri();`
- **Date Helpers**
	- [dh-date date_string=""] Shortcode version of `date();`
	- [dh-day-of-week] Shortcode version of `date('l');` returns current textual day of week name
	- [dh-date] Shortcode version of `date('j');` returns current numerical date
	- [dh-month-name] Shortcode version of `date('F');` returns current textual month name
	- [dh-year] Shortcode version of `date('Y');` returns numerical current year

***
###CSS Utility Classes
- `.pull-left` Floats an element left
- `.pull-right`  Floats an element right
- `.unstyled` Removes default `<ul>` styling
- `.hidden` Hides an element
- `.zero-out` Removes all margin and padding from an element
- `.no-padding-first` Removes padding for the first element in a series
- `.no-padding-last` Removes padding for the last element in a series
- `.no-margin-first` Removes margin for the first element in a series
- `.np-margin-last` Removes margin for the last element in a series
- `.no-margin-left` Removes margin from the left of an element
- `.np-margin-right` Removes margin from the right of an element
- `.transparent` Cross browser opacity for an element
- `tbody.zebra` Zebra stripes a `<table>` element
- `.clearfix` Clears floats after the element
- `.clear` Used to clear floats before the element
- `.blur` Creates blurry text
- `.hide-text` Hides text useful with text image replacement
- `.ellipsis` Truncates a one line string with an ellipsis (...)

***
###CSS Helpers
- `a[href$=".pdf"]:after { content: " (PDF)"; }` Adds (PDF) to all .pdf file links
- `a[href$=".pdf"][data-size]:after { content: " (PDF, " attr(data-size) ")"; }` Adds the size of the PDF to all .pdf file links if `data-size` attribute is includedattribute is included



***
###LESS Mixins
- **General**
	- `.opacity(@opacity: 0.5)`
	- `.columns(@colwidth: 250px, @colcount: 0, @colgap: 50px, @columnRuleColor: #EEE, @columnRuleStyle: solid, @columnRuleWidth: 1px)`
	- `.min-height(@height)`
	- `.arrow-up(@border-size: 30px, @color: #333)`
	- `.arrow-down(@border-size: 30px, @color: #333)`
	- `.arrow-right(@border-size: 30px, @color: #333)`
	- `.arrow-left(@border-size: 30px, @color: #333)`
	- `.drop-cap(@color:#903)`
	- `.box-sizing (@type: border-box)`
	- `.perspective (@value: 1000)`
- **Gradient**
	- `.gradient(@color: #F5F5F5, @start: #EEE, @stop: #FFF)`
	- `.bw-gradient(@color: #F5F5F5, @start: 0, @stop: 255)`
	- `.horizontal-gradient (@startColor: #eee, @endColor: white)`
- **Borders**
	- `.bordered(@top-color: #EEE, @right-color: #EEE, @bottom-color: #EEE, @left-color: #EEE)`
	- `.border-top(@border-color: #333, @margin-top: 5px)`
	- `.rounded(@radius: 2px)`
	- `.border-top-right(@radius)`
	- `.border-bottom-right(@radius)`
	- `.border-top-leftt(@radius)`
	- `.border-bottom-leftt(@radius)`
	- `.border-radiuses (@topright: 0, @bottomright: 0, @bottomleft: 0, @topleft: 0)`
- **Drop Shadow**
	- `.drop-shadow(@x-axis: 0, @y-axis: 1px, @blur: 2px, @alpha: 0.1)`
	- `.inner-shadow(@horizontal:0, @vertical:1px, @blur:2px, @alpha: 0.4)`
	- `.box-shadow(@arguments)`
	- `.text-shadow (@string: 0 1px 3px rgba(0, 0, 0, 0.25))`
- **Animation**
	- `.transition-duration(@duration: 0.2s)`
	- `.rotation(@deg:5deg)`
	- `.scale(@ratio:1.5)`
	- `.transition(@duration:0.2s, @ease:ease-out)`
	- `.translate(@x:0, @y:0)`
	- `.transform-origin (@x:center, @y:center)`
	- `.transform(@string)`
	- `.skew (@deg, @deg2)`
	- `.translate3d (@x, @y: 0, @z: 0)`
	- `.animation (@name, @duration: 300ms, @delay: 0, @ease: ease)`
***
###PHP Helper Functions
- `dh_to_array($obj);` Used to turn a Object into an Associative Array returns `Array`.
- `dh_to_object($array);` Used to Turn an Array into an Object returns `STDOBJ`.
- `dh_the_breadcrumb();` Adds breadcrumbs to pages and posts returns `NULL`.
- `dh_is_localhost();` Checks if the current working directory is on localhost 127.0.0.1 returns `TRUE` or `FALSE`.
- `dh_default_thumbnail($post_id, $default_thumb=false);` Checks to see if the post has a thumbnail image if not it adds the default thumbnail found in _assets/img/thumbs/default_thumb.png or the path to the default thumbnail that was passed to the function returns `NULL`.
- `dh_session_start();` Checks to see if there is a session started and if not starts a session returns `NULL`.
- `dh_get_thumbnail($id, $size='thumbnail', $attr='' );` Checks if the post has a thumbnail if not it returns `FALSE` if it does have a thumbnail returns `get_the_post_thumbnail()`.
- `dh_get_thumbnail_url();` Checks if the post has a thumbnail if not it returns `FALSE` if it does have a thumbnail it returns `$image_url`.
- `dh_get_page_id_by_slug($page_slug);` Gets the page by the slug returns `$page->ID` on success and `FALSE` on failure.
- `dh_get_user_ip();` Gets the users IP information returns `$_SERVER["HTTP_X_FORWARDED_FOR"]`, `$_SERVER["HTTP_CLIENT_IP"]`, or `$_SERVER["REMOTE_ADDR"]`.



***
Copyright &copy; [Dan Holloran](mailto:dan@freemanhelp.com) for use at [FreeMan Marketing](http://danholloran.com)