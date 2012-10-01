Default WordPress Install
=========================

**Author(s):** Dan Holloran  
**Author(s) email:** <Dan@freemanhelp.com>  
**Website:** http://www.FreeManHelp.com  
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

####Random TODOs
- Look for other libs to add that will make life easier
- Fill in TODO's in read me
- Better information about what is available
- Add style guide/best practices
- Add suggested downloads links
- Look over WordCamp notes
- Add launch checklist/punch-list
- Fill out license
- Add setup instructions/checklist
- Custom theme options page to transplant theme options client should have access too

####Completed TODOs
- ~~Ajax Helper~~
- ~~Upload zip~~
- ~~Make private Repo~~
- ~~Make screen shot for theme switcher~~
- ~~Make sure contact information is only accessible by owner/admin~~
- ~~Implement /wp-content/themes/fm-child-theme/_assets/js/libs/retina.js~~

***
###Install Check-List
- Set Settings > General timezone to  chicago
- Users >Your Profile check Disable the visual editor when writing
- Tools > Import /test-data.2011-01-17.xml
- TODO



***
###Launch Check-List
- Setup Helps With Spam in .htaccess with site url
- Setup Force WWW/no-WWW in .htaccess with site url
- TODO




***
###Included Projects
- Retina.js [http://retinajs.com/](http://retinajs.com/) *Disabled by default*
- HTML 5 Shim
- Prefix Free [http://leaverou.github.com/prefixfree/](http://leaverou.github.com/prefixfree/) *Disabled by default*
- jquery.timeago.js [http://timeago.yarp.com](http://timeago.yarp.com) *Disabled by default*



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
- TODO



***
Copyright &copy; [Dan Holloran](mailto:dan@freemanhelp.com) for use at [FreeMan Marketing](http://www.freemanhelp.com)
