<?php
/*
Description:  qTranslate Support for Google XML Sitemaps Generator for WordPress
Copyright(c): 2011, DSmidge, http://blog.slo-host.com/
License:      GNU GPL, http://www.gnu.org/licenses/gpl-3.0.txt

This code is based on changes between plugin "Google XML Sitemaps Generator
with qTranslate Support for WordPress, v3.1.6.3" by NeoEGM and "Google XML
Sitemaps Generator for WordPress, v3.1.6" by Arne Brachhold.

Integration into "sitemap.php".
Initialization:
	require_once("sitemap-qtranslate.php");
	$qt = qt_settings();
Calling permalink translation (correct "variables" without $), around "$this->AddUrl(":
	if (!$qt["enabled"])
		$this->AddUrl(...ORIGINAL CODE
	qt_permalink($qt, permalink, post_content/null(available/all langs), modified_time, change_freq, priority, $this);
*/

// Is qTranslate enabled; read settings
function qt_settings() {
	$qt = array("enabled" => false);
	if (function_exists('qtrans_getAvailableLanguages') && function_exists('qtrans_convertURL')) {
		global $q_config;
		$qt["enabled"] = true;
		$qt["default_language"] = $q_config['default_language'];
		$qt["enabled_languages"] = $q_config['enabled_languages'];
		$qt["hide_default_language"] = $q_config['hide_default_language'];
	}
	return $qt;
}

// Add additional qTranslate language permalniks
function qt_permalink($qt, $permalink, $post_content, $modified_time, $change_freq, $priority, &$sitemap) {
	if ($qt["enabled"]) {
		// Get modified time
		if ($modified_time != 0) {
			$modified_time = $sitemap->GetTimestampFromMySql($modified_time);
		}
		// Get a list of languages
		if ($post_content) {
			// Get available languages form post
			$languages = qtrans_getAvailableLanguages($post_content);
		} else {
			// Get all available languages
			$languages = $qt["enabled_languages"];
		}
		// Add an extra permalink url for every non-default qTranslate language
		foreach ($languages as $language) {
			if ($qt["hide_default_language"] == 1 && $qt["default_language"] == $language) {
				$sitemap->AddUrl($permalink, $modified_time, $change_freq, $priority);
			} else {
				$sitemap->AddUrl(qtrans_convertURL($permalink, $language, true), $modified_time, $change_freq, $priority);
			}
		}
	}
}
?>
