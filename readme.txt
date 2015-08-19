=== Trailblaze ===
Contributors: heavyheavy, wearepixel8
Tags: breadcrumbs, breadcrumb, navigation, menu
Requires at least: 3.1
Compatible up to: 4.3
Tested up to: 4.3
Stable tag: 1.1.0
License: GPLv2

Add breadcrumb navigation to your posts, pages and custom post types with a template tag.

== Description ==

With Trailblaze, you can add breadcrumb navigational links to your WordPress theme by using the template tag, `wap8_trailblaze()`. This template tag can be added to the following templates:

* Archive Templates (categories, tags, post formats, custom post types, custom taxonomies, date based archives)
* Singular Templates (post, page, custom post type)
* Search Template
* 404 Template

For more information, please see this [blog post](http://erikford.me/plugins/trailblaze-breadcrumbs-plugin/).

== Installation ==

You can install Trailblaze either via the WordPress Dashboard or by uploading the extracted `trailblaze` folder to your `/wp-content/plugins/` directory. Once the plugin has been successfully installed, simply activate the plugin through the Plugins menu in your WordPress Dashboard.

Once the plugin has been activated, visit the Settings page to customize the Home link label and the breadcrumbs separator.

To add the template tag to your theme, insert the following code, preferably outside of the loop, where you would like the breadcrumb trail to appear.

`<?php if ( function_exists( 'wap8_trailblaze' ) ) {
     wap8_trailblaze();
} ?>`

The markup contains structured data for search engine optimization and contain class names for easy styling:

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
2. Trailblaze output on date based archive
3. Trailblaze output on single post entry

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

= 1.0.7 =
* Added a condition for when Reading Settings for blog posts has a Posts page set

= 1.0.8 =
* Properly escaping HTML in the output
* Updated the base language file

= 1.0.9 =
* Changed text domain name space and updated language files

= 1.1.0 =
* Added structured data to the markup
* Added support for post format archives
* Fixed an issue where the post categories were not hierarchical