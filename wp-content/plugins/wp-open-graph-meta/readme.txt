=== WP Open Graph Meta ===
Contributors: shaselboeck
Plugin Name: WP Open Graph Meta
Donate link: http://omaxis.de
Tags: open graph, opengraph, open-graph, meta, metatags, facebook, google+, xing, wpseo, all in one seo,
Requires at least: 3.0
Tested up to: 3.3.2
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds Facebook Open Graph Meta Elements to blog posts/pages to avoid no thumbnail, wrong title/description issue etc.

== Description ==

[Zur deutschen Beschreibung des Plugins](http://omaxis.de/wordpress-plugins/wp-open-graph-meta/ "Deutsche Beschreibung von WP Open Graph Meta")

This plugin adds several Open Graph Meta Elements to the header of your theme for blog posts and pages to avoid no thumbnail, wrong title / description issue etc. It is compatible with the WordPress SEO plugins "wpSEO" and "All in One SEO Pack". When a title or description is stored by one of these SEO plugins, WP Open Graph Meta will use them.

The Open Graph protocol enables any web page to become a rich object in a social graph. For instance, this is used on Facebook to allow any web page to have the same functionality as any other object on Facebook.

To turn your posts and pages into graph objects, the plugin adds the following meta elements to webpage header:

<h4>For post/page</h4>

* og:title - The title of your post/page (compatible with "wpSEO" and "All in One SEO Pack")
* og:type - The type of your post/page. Depending on the type it is possible to specify automatically other properties.
* og:url - The canonical URL of your post/page
* og:description - The excerpt of your post/page (compatible with "wpSEO" and "All in One SEO Pack")
* og:site_name - The title of website.
* og:locale - The locale these tags are marked up in.
* og:image - An image URL (featured image if exists) which should represent your post/page

<h4>For post only</h4>

* article:tag - Tag words associated with your post.

Plugin support page: [omaxis.de](http://omaxis.de/wordpress-plugins/wp-open-graph-meta/ "omaxis - online marketing expertise")

== Installation ==

1. Upload folder "wp-open-graph-meta" with all its contents into your WordPress plugin directory (/wp-content/plugins/).
2. Go to WordPress admin area and activate the plugin through the 'Plugins' menu.

== Frequently Asked Questions ==

= What is the Open Graph protocol? =
The The [Open Graph protocol](http://ogp.me/ "Open Graph protocol") enables any web page to become a rich object in a social graph. For instance, this is used on Facebook to allow any web page to have the same functionality as any other object on Facebook.

= I don't use social media, why would I use this? =

Lots of People share links on social media sites. This plugin gives you some control over how your content is presented on platforms such as Facebook, Google+ and XING.

== Screenshots ==

1. **Without WP Open Graph Meta** - Wrong thumbnail (banner ad) and title.
2. **With WP Open Graph Meta** - Correct thumbnail and optimized title.

== Languages ==

WP Open Graph Meta is language independent.

== Changelog ==

= 1.1 =
* Removed meta tag "og:published_time"
* Converted locale of meta tag "og:locale" to lowercase

= 1.0 =
* Initial release

== Upgrade Notice ==

= 1.0 =
* Initial release