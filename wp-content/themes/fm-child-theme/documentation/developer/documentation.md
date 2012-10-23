###Table of Contents
1. [Install Check-list](#install_checklist)
2. [Launch Check-list](#launch_checklist)
3. [Included Projects](#included_projects)
4. [Included Plugins](#included_plugins)
5. [Shortcodes](#shortcodes)
6. [Css Utility Classes](#css_utilities)
7. [CSS Helpers](#css_helpers)
8. [LESS Mixins](#less_mixins)


***
<h3 id="install_checklist">Install Check-List</h3>
- **Things that must be done before accessing from browser**
	- **IMPORTANT: Do not name administrator account admin**
	- **IMPORTANT: Make editor account for client to post content**
	- **IMPORTANT: Change table prefix in wp-config.php**
	- **IMPORTANT: Replace the salts and keys**
- Set Settings > General timezone to  chicago
- Users >Your Profile check Disable the visual editor when writing
- Tools > Import /test-data.2011-01-17.xml
[https://api.wordpress.org/secret-key/1.1/salt/ ](https://api.wordpress.org/secret-key/1.1/salt/ )
- Turn on database backup plugin

***
<h3 id="launch_checklist">Launch Check-List</h3>
- Setup Helps With Spam in .htaccess with site url
- Setup Force WWW/no-WWW in .htaccess with site url
- Turn on wp-cache plugin
- Fill out humans.txt
- Remove disallow all from robots.txt
- Check contact forms for correct emails
- Update General > Settings email to clients main info email
- Update Users > Admin users email to main info email
- Associate any posts made by developer accounts with clients editor accounts
- Create and add All icons for devices/favicon in /wp-content/themes/fm-child-theme/_assets/img/icons
- Run W3C link check validator [http://validator.w3.org/checklink](http://validator.w3.org/checklink) **IMPORTANT: Make sure dev folder is renamed site-dev-name-moved**
- Turn on sitemap plugin
- Remove all developer plugins
- Parts to check
	- Favicon
	- SEO title, meta, others
	- Cross browser check
	- Test all functionality
	- Validate HTML/CSS
	- Check RSS link
	- Create analytics/web master tools account
	- Add analytics
	- Error pages
	- Compress all static files CSS/JS
	- Optimise images
	- Proofread
- Others [Ultimate-Web-Development-Checklist.pdf](Ultimate-Web-Development-Checklist.pdf)

***
<h3 id="included_projects">Included Projects</h3>
- Retina.js [http://retinajs.com/](http://retinajs.com/) *Disabled by default* *Activate CSS in site.less*
- HTML 5 Shim
- Prefix Free [http://leaverou.github.com/prefixfree/](http://leaverou.github.com/prefixfree/) *Disabled by default*
- jquery.timeago.js [http://timeago.yarp.com](http://timeago.yarp.com) *Disabled by default*
- jquery.pikaday.js [https://github.com/dbushell/Pikaday](https://github.com/dbushell/Pikaday) *Disabled by default* *Activate CSS in site.less*

***
<h3 id="included_plugins">Included Plugins</h3>
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
<h3  id="shortcodes">Shortcodes</h3>
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
<h3 id="css_utilities">CSS Utility Classes</h3>
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
<h3 id="css_helpers">CSS Helpers</h3>
- `a[href$=".pdf"]:after { content: " (PDF)"; }` Adds (PDF) to all .pdf file links
- `a[href$=".pdf"][data-size]:after { content: " (PDF, " attr(data-size) ")"; }` Adds the size of the PDF to all .pdf file links if `data-size` attribute is included attribute is included

***
<h3 id="less_mixins">LESS Mixins</h3>
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
