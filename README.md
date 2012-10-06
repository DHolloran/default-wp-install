Default WordPress Install
=========================

**Author(s):** Dan Holloran  
**Author(s) email:** <Dan@freemanhelp.com>  
**Website:** [http://www.FreeManHelp.com]( http://www.freemanhelp.com)  
**Description:** For use at FreeMan Marketing as a good starting place for all new project  
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
- Implement H5BP into child theme
- Page to PDF creation
- Decided on what files require other files 

####Repsonsive TODOs
- Add more responsive helpers
- Add Adaptive.js????
- Add Enhance.js????
- Implement 360andup or some other sort of media-queries
- Implement /wp-content/themes/fm-child-theme/_assets/js/libs/jquery.timeago.js

####General TODOs
- Look for other libs to add that will make life easier
- Add explanations to LESS mixins
- Better information about what is available
- Add style guide/best practices
- Look over WordCamp notes
- Add launch checklist/punch-list
- Fill out license
- Add setup instructions/checklist
- Custom theme options page to transplant theme options client should have access too
- Added more information about what is included
- Add author meta tag to header as Freeman Marketing
- Add humans.txt
- Make robots.txt stop google from indexing until launch

####Completed TODOs

***
###Plugins Included
- [ALO Easymail](http://wordpress.org/extend/plugins/alo-easymail/)
- [Clean Notifications](http://wordpress.org/extend/plugins/clean-notifications/)
- [Debogger](http://wordpress.org/extend/plugins/debogger/) **Developer**
- [Debug Bar](http://wordpress.org/extend/plugins/debug-bar/) **Developer**
- [Debug Bar Cron](http://wordpress.org/extend/plugins/debug-bar-cron/) **Developer**
- [Developer](http://wordpress.org/extend/plugins/developer/) **Developer**
- [Disable Comments](http://wordpress.org/extend/plugins/disable-comments/)
- [Google XML Sitemaps V3 for qTranslate](http://wordpress.org/extend/plugins/google-xml-sitemaps-v3-for-qtranslate/)
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

***
###Shortcodes
- [fm-blog-info show="<value>"] Shortcode version of `get_bloginfo($value);` 
- **Directory Helpers**
	- [fm-site-url] Shortcode version of `site_url();`
	- [fm-home-url] Shortcode version of `home_url();`
	- [fm-template-url] Shortcode version of `get_template_directory_uri();`
	- [fm-child-theme-url] Shortcode version of `get_stylesheet_directory_uri();`
- **Date Helpers**
	- [fm-date date_string=""] Shortcode version of `date();`
	- [fm-day-of-week] Shortcode version of `date('l');` returns current textual day of week name
	- [fm-date] Shortcode version of `date('j');` returns current numerical date
	- [fm-month-name] Shortcode version of `date('F');` returns current textual month name
	- [fm-year] Shortcode version of `date('Y');` returns numerical current year
	
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
Copyright &copy; [Dan Holloran](mailto:dan@freemanhelp.com) for use at [FreeMan Marketing](http://www.freemanhelp.com)
