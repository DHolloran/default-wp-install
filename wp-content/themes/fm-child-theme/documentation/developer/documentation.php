<div class="fm-documentation">
  <h3>Table of Contents</h3>

  <ol>
  <li><a href="#install_checklist">Install Check-list</a></li>
  <li><a href="#launch_checklist">Launch Check-list</a></li>
  <li><a href="#included_projects">Included Projects</a></li>
  <li><a href="#included_plugins">Included Plugins</a></li>
  <li><a href="#shortcodes">Shortcodes</a></li>
  <li><a href="#css_utilities">Css Utility Classes</a></li>
  <li><a href="#css_helpers">CSS Helpers</a></li>
  <li><a href="#less_mixins">LESS Mixins</a></li>
  </ol>


  <hr />

  <h3 id="install_checklist">Install Check-List</h3>


  <ul>
  <li><strong>Things that must be done before accessing from browser</strong>

  <ul>
  <li><strong>IMPORTANT: Do not name administrator account admin</strong></li>
  <li><strong>IMPORTANT: Make editor account for client to post content</strong></li>
  <li><strong>IMPORTANT: Change table prefix in wp-config.php</strong></li>
  <li><strong>IMPORTANT: Replace the salts and keys</strong></li>
  </ul>
  </li>
  <li>Set Settings > General timezone to  chicago</li>
  <li>Users >Your Profile check Disable the visual editor when writing</li>
  <li>Tools > Import /test-data.2011-01-17.xml
  <a href="https://api.wordpress.org/secret-key/1.1/salt/">https://api.wordpress.org/secret-key/1.1/salt/ </a></li>
  <li>Turn on database backup plugin</li>
  </ul>


  <hr />

  <h3 id="launch_checklist">Launch Check-List</h3>


  <ul>
  <li>Setup Helps With Spam in .htaccess with site url</li>
  <li>Setup Force WWW/no-WWW in .htaccess with site url</li>
  <li>Turn on wp-cache plugin</li>
  <li>Check contact forms for correct emails</li>
  <li>Update General > Settings email to clients main info email</li>
  <li>Update Users > Admin users email to main info email</li>
  <li>Associate any posts made by developer accounts with clients editor accounts</li>
  <li>Run W3C link check validator <a href="http://validator.w3.org/checklink">http://validator.w3.org/checklink</a> <strong>IMPORTANT: Make sure dev folder is renamed site-dev-name-moved</strong></li>
  <li>Turn on sitemap plugin</li>
  <li>Remove all developer plugins</li>
  <li>Parts to check

  <ul>
  <li>Favicon</li>
  <li>SEO title, meta, others</li>
  <li>Cross browser check</li>
  <li>Test all functionality</li>
  <li>Validate HTML/CSS</li>
  <li>Check RSS link</li>
  <li>Create analytics/web master tools account</li>
  <li>Add analytics</li>
  <li>Error pages</li>
  <li>Compress all static files CSS/JS</li>
  <li>Optimise images</li>
  <li>Proofread</li>
  </ul>
  </li>
  <li>Others <a href="<?php echo get_stylesheet_directory_uri(); ?>/documentation/developer/Ultimate-Web-Development-Checklist.pdf">Ultimate-Web-Development-Checklist.pdf</a></li>
  </ul>


  <hr />

  <h3 id="included_projects">Included Projects</h3>


  <ul>
  <li>Retina.js <a href="http://retinajs.com/">http://retinajs.com/</a> <em>Disabled by default</em></li>
  <li>HTML 5 Shim</li>
  <li>Prefix Free <a href="http://leaverou.github.com/prefixfree/">http://leaverou.github.com/prefixfree/</a> <em>Disabled by default</em></li>
  <li>jquery.timeago.js <a href="http://timeago.yarp.com">http://timeago.yarp.com</a> <em>Disabled by default</em></li>
  </ul>


  <hr />

  <h3 id="included_plugins">Included Plugins</h3>


  <ul>
  <li><a href="http://wordpress.org/extend/plugins/alo-easymail/">ALO Easymail</a></li>
  <li><a href="http://wordpress.org/extend/plugins/clean-notifications/">Clean Notifications</a></li>
  <li><a href="http://wordpress.org/extend/plugins/debogger/">Debogger</a> <strong>Developer</strong></li>
  <li><a href="http://wordpress.org/extend/plugins/debug-bar/">Debug Bar</a> <strong>Developer</strong></li>
  <li><a href="http://wordpress.org/extend/plugins/debug-bar-cron/">Debug Bar Cron</a> <strong>Developer</strong></li>
  <li><a href="http://wordpress.org/extend/plugins/developer/">Developer</a> <strong>Developer</strong></li>
  <li><a href="http://wordpress.org/extend/plugins/disable-comments/">Disable Comments</a></li>
  <li><a href="http://wordpress.org/extend/plugins/google-xml-sitemaps-v3-for-qtranslate/">Google XML Sitemaps V3 for qTranslate</a></li>
  <li><a href="http://wordpress.org/extend/plugins/jw-player-plugin-for-wordpress/">JW Player</a></li>
  <li><a href="http://wordpress.org/extend/plugins/log-deprecated-notices/">Log Deprecated Notices</a> <strong>Developer</strong></li>
  <li><a href="http://wordpress.org/extend/plugins/rewrite-rules-inspector/">Rewrite Rules Inspector</a> <strong>Developer</strong></li>
  <li><a href="http://wordpress.org/extend/plugins/share-this/">Share This</a></li>
  <li><a href="http://wordpress.org/extend/plugins/subscribe-to-comments/">Subscribe to Comments</a></li>
  <li><a href="http://wordpress.org/extend/plugins/theme-check/">Theme Check</a> <strong>Developer</strong></li>
  <li><a href="http://wordpress.org/extend/plugins/user-switching/">User Switching</a> <strong>Developer</strong></li>
  <li><a href="wordpress.org/extend/plugins/velvet-blues-update-urls/">Velvet Blues Update URLs</a> <strong>Developer</strong></li>
  <li><a href="http://wordpress.org/extend/plugins/w3-total-cache/">W3 Total Cache</a></li>
  <li><a href="http://wordpress.org/extend/plugins/wordpress-importer/">WordPress Importer</a></li>
  <li><a href="http://wordpress.org/extend/plugins/wordpress-seo/">WordPress SEO by Yoast</a></li>
  <li><a href="http://wordpress.org/extend/plugins/wp-dbmanager/">WP DBManager</a></li>
  <li><a href="http://wordpress.org/extend/plugins/wp-members/">WP Members</a></li>
  </ul>


  <hr />

  <h3  id="shortcodes">Shortcodes</h3>


  <ul>
  <li>[fm-blog-info show="<value>"] Shortcode version of <code>get_bloginfo($value);</code></li>
  <li><strong>Directory Helpers</strong>

  <ul>
  <li>[fm-site-url] Shortcode version of <code>site_url();</code></li>
  <li>[fm-home-url] Shortcode version of <code>home_url();</code></li>
  <li>[fm-template-url] Shortcode version of <code>get_template_directory_uri();</code></li>
  <li>[fm-child-theme-url] Shortcode version of <code>get_stylesheet_directory_uri();</code></li>
  </ul>
  </li>
  <li><strong>Date Helpers</strong>

  <ul>
  <li>[fm-date date_string=""] Shortcode version of <code>date();</code></li>
  <li>[fm-day-of-week] Shortcode version of <code>date('l');</code> returns current textual day of week name</li>
  <li>[fm-date] Shortcode version of <code>date('j');</code> returns current numerical date</li>
  <li>[fm-month-name] Shortcode version of <code>date('F');</code> returns current textual month name</li>
  <li>[fm-year] Shortcode version of <code>date('Y');</code> returns numerical current year</li>
  </ul>
  </li>
  </ul>


  <hr />

  <h3 id="css_utilities">CSS Utility Classes</h3>


  <ul>
  <li><code>.pull-left</code> Floats an element left</li>
  <li><code>.pull-right</code>  Floats an element right</li>
  <li><code>.unstyled</code> Removes default <code>&lt;ul&gt;</code> styling</li>
  <li><code>.hidden</code> Hides an element</li>
  <li><code>.zero-out</code> Removes all margin and padding from an element</li>
  <li><code>.no-padding-first</code> Removes padding for the first element in a series</li>
  <li><code>.no-padding-last</code> Removes padding for the last element in a series</li>
  <li><code>.no-margin-first</code> Removes margin for the first element in a series</li>
  <li><code>.np-margin-last</code> Removes margin for the last element in a series</li>
  <li><code>.no-margin-left</code> Removes margin from the left of an element</li>
  <li><code>.np-margin-right</code> Removes margin from the right of an element</li>
  <li><code>.transparent</code> Cross browser opacity for an element</li>
  <li><code>tbody.zebra</code> Zebra stripes a <code>&lt;table&gt;</code> element</li>
  <li><code>.clearfix</code> Clears floats after the element</li>
  <li><code>.clear</code> Used to clear floats before the element</li>
  <li><code>.blur</code> Creates blurry text</li>
  <li><code>.hide-text</code> Hides text useful with text image replacement</li>
  <li><code>.ellipsis</code> Truncates a one line string with an ellipsis (...)</li>
  </ul>


  <hr />

  <h3 id="css_helpers">CSS Helpers</h3>


  <ul>
  <li><code>a[href$=".pdf"]:after { content: " (PDF)"; }</code> Adds (PDF) to all .pdf file links</li>
  <li><code>a[href$=".pdf"][data-size]:after { content: " (PDF, " attr(data-size) ")"; }</code> Adds the size of the PDF to all .pdf file links if <code>data-size</code> attribute is included attribute is included</li>
  </ul>


  <hr />

  <h3 id="less_mixins">LESS Mixins</h3>


  <ul>
  <li><strong>General</strong>

  <ul>
  <li><code>.opacity(@opacity: 0.5)</code></li>
  <li><code>.columns(@colwidth: 250px, @colcount: 0, @colgap: 50px, @columnRuleColor: #EEE, @columnRuleStyle: solid, @columnRuleWidth: 1px)</code></li>
  <li><code>.min-height(@height)</code></li>
  <li><code>.arrow-up(@border-size: 30px, @color: #333)</code></li>
  <li><code>.arrow-down(@border-size: 30px, @color: #333)</code></li>
  <li><code>.arrow-right(@border-size: 30px, @color: #333)</code></li>
  <li><code>.arrow-left(@border-size: 30px, @color: #333)</code></li>
  <li><code>.drop-cap(@color:#903)</code></li>
  <li><code>.box-sizing (@type: border-box)</code></li>
  <li><code>.perspective (@value: 1000)</code></li>
  </ul>
  </li>
  <li><strong>Gradient</strong>

  <ul>
  <li><code>.gradient(@color: #F5F5F5, @start: #EEE, @stop: #FFF)</code></li>
  <li><code>.bw-gradient(@color: #F5F5F5, @start: 0, @stop: 255)</code></li>
  <li><code>.horizontal-gradient (@startColor: #eee, @endColor: white)</code></li>
  </ul>
  </li>
  <li><strong>Borders</strong>

  <ul>
  <li><code>.bordered(@top-color: #EEE, @right-color: #EEE, @bottom-color: #EEE, @left-color: #EEE)</code></li>
  <li><code>.border-top(@border-color: #333, @margin-top: 5px)</code></li>
  <li><code>.rounded(@radius: 2px)</code></li>
  <li><code>.border-top-right(@radius)</code></li>
  <li><code>.border-bottom-right(@radius)</code></li>
  <li><code>.border-top-leftt(@radius)</code></li>
  <li><code>.border-bottom-leftt(@radius)</code></li>
  <li><code>.border-radiuses (@topright: 0, @bottomright: 0, @bottomleft: 0, @topleft: 0)</code></li>
  </ul>
  </li>
  <li><strong>Drop Shadow</strong>

  <ul>
  <li><code>.drop-shadow(@x-axis: 0, @y-axis: 1px, @blur: 2px, @alpha: 0.1)</code></li>
  <li><code>.inner-shadow(@horizontal:0, @vertical:1px, @blur:2px, @alpha: 0.4)</code></li>
  <li><code>.box-shadow(@arguments)</code></li>
  <li><code>.text-shadow (@string: 0 1px 3px rgba(0, 0, 0, 0.25))</code></li>
  </ul>
  </li>
  <li><strong>Animation</strong>

  <ul>
  <li><code>.transition-duration(@duration: 0.2s)</code></li>
  <li><code>.rotation(@deg:5deg)</code></li>
  <li><code>.scale(@ratio:1.5)</code></li>
  <li><code>.transition(@duration:0.2s, @ease:ease-out)</code></li>
  <li><code>.translate(@x:0, @y:0)</code></li>
  <li><code>.transform-origin (@x:center, @y:center)</code></li>
  <li><code>.transform(@string)</code></li>
  <li><code>.skew (@deg, @deg2)</code></li>
  <li><code>.translate3d (@x, @y: 0, @z: 0)</code></li>
  <li><code>.animation (@name, @duration: 300ms, @delay: 0, @ease: ease)</code></li>
  </ul>
  </li>
  </ul>
</div> <!-- END .fm-documentation -->