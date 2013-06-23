=== Trailblaze ===
Contributors: wearepixel8
Tags: breadcrumbs, breadcrumb, navigation, menu
Requires at least: 3.1
Compatible up to: 3.5.1
Tested up to: 3.5.1
Stable tag: 1.0.0
License: GPLv2

Add breadcrumb navigation to your posts, pages and custom post types with a template tag.

== Description ==

With Trailblaze, you can add breadcrumb navigational links to your WordPress theme by using the template tag, `wap8_trailblaze()`. This template tag can be added to the single post, page, single custom post type and/or archive, including custom post type archive, templates.

== Installation ==

You can install Trailblaze either via the WordPress Dashboard or by uploading the extracted `trailblaze` folder to your `/wp-content/plugins/` directory. Once the plugin has been successfully installed, simply activate the plugin through the Plugins menu in your WordPress Dashboard.

Once the plugin has been activated, visit the Settings page to customize the Home link label and the breadcrumbs separator.

To add the template tag to your theme, insert the following code where you would like the breadcrumb trail to appear.

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
To add the template tag to your theme, insert the following code where you would like the breadcrumb trail to appear.

`<?php if ( function_exists( 'wap8_trailblaze' ) ) {
     wap8_trailblaze();
} ?>`

== Screenshots ==

1. Trailblaze Settings screen

== Changelog ==

= 1.0.0 =
* Initial release