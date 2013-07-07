=== Trailblaze ===
Contributors: wearepixel8
Tags: breadcrumbs, breadcrumb, navigation, menu
Requires at least: 3.1
Compatible up to: 3.5.2
Tested up to: 3.5.2
Stable tag: 1.0.6
License: GPLv2

Add breadcrumb navigation to your posts, pages and custom post types with a template tag.

== Description ==

With Trailblaze, you can add breadcrumb navigational links to your WordPress theme by using the template tag, `wap8_trailblaze()`. This template tag can be added to the single post, page, single custom post type and/or archive, including custom post type archive, templates.

== Installation ==

You can install Trailblaze either via the WordPress Dashboard or by uploading the extracted `trailblaze` folder to your `/wp-content/plugins/` directory. Once the plugin has been successfully installed, simply activate the plugin through the Plugins menu in your WordPress Dashboard.

Once the plugin has been activated, visit the Settings page to customize the Home link label and the breadcrumbs separator.

To add the template tag to your theme, insert the following code, preferably outside of the loop, where you would like the breadcrumb trail to appear.

`<?php if ( function_exists( 'wap8_trailblaze' ) ) {
     wap8_trailblaze();
} ?>`

The HTML elements that will wrap the breadcrumbs are:

* The wrapping element is `<nav class="breadcrumbs" itemprop="breadcrumbs">`.
* The breadcrumb separator will be wrapped with `<span class="crumb-separator">`.
* The current, non anchored breadcrumb will be wrapped with `<span class="current-crumb">`.

== Frequently Asked Questions ==

= Why was this plugin developed? =
We found that we were reproducing this functionality for many of our clients and premium themes and thought it would be better suited as a freely available plugin instead of a theme template.

= How do I add the template tag to my theme? =
To add the template tag to your theme, insert the following code, preferably outside of the loop, where you would like the breadcrumb trail to appear.

`<?php if ( function_exists( 'wap8_trailblaze' ) ) {
     wap8_trailblaze();
} ?>`

= Are there any known limitations? =
Yes. Though we have added custom taxonomy archives to the breadcrumbs, the breadcrumbs will not return a hierarchical list when browsing a custom taxonomy archive that is hierarchical.

== Screenshots ==

1. Trailblaze Settings screen

== Changelog ==

= 1.0.0 =
* Initial release

= 1.0.1 =
* Fixed an issue with the paginated breadcrumb display

= 1.0.2 =
* Once pagination begins, the page number becomes the current crumb

= 1.0.3 =
* Fixed an issue where breadcrumbs were not displaying on paginated pages and single posts

= 1.0.4 =
* Fixed an issue where the page order was being echoed in the breadcrumb

= 1.0.5 =
* Fixed an issue where the custom post type name was singular

= 1.0.6 =
* The custom post type name should universally be plural
* Added custom taxonomy archives condition to the breadcrumbs