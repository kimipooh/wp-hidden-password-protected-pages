=== WP Hidden Password Protected Pages ===
Contributors: Kimiya Kitani
Tags: Password Protected
Requires at least: 4.0
Tested up to: 6.0
Stable tag: 1.2.3
License: GPL v2

The plugin is for hiding the password protected pages (posts) in WordPress.
 
== Description ==

When the plugin is turned on, the password protected pages will be hidden. The user who knows the access URL continues to be able to access to the pages.

Optional settings: The unlocked password protected page will be locked again after the idle time (Value of Idle time for Password Protected Pages).

== Installation ==

First of all, please install this plugin and activate it.

Then, if you want to change the lock idle time for the password protected page, please input the value of "Idle time for Password Protected Cookie".

= Usage =

About the detail information, Please see "WP Hidden Password Protected Pages Settings" in Setting menu.

deveploping version: https://github.com/kimipooh/wp-hidden-password-protected-pages

== Screenshots ==

1. Turn off this plugin (Normal View)
2. Turn on this plugin (Hidden Password Protected Page)
3. Setting Menu
4. View list typed post, page, aaaa (custom post) in functions.php

== Frequently Asked Questions ==

If you use custom posts or want to change the view list of password protected pages, please use add_filter; "whppp_get_protected_page_args".


== Changelog ==
= 1.2.3 =
* Fixed some variable check.
* Fixed the load_plugin_textdomain parameter. 
* Add the function for deleting the setting values from the DB when the plugin is removed.
* Tested up WordPress 5.8 with PHP 8.0.
* Tested up WordPress 6.0.

= 1.2.2 =
* Added the response of CSRF (Cross-Site Request Forgery) vulnerability for this plugin's settings.
* Tested up WordPress 5.3.2 and php 7.4.2.
* Tested up WordPress 5.6 and php 7.4.2.

= 1.2.1 =
* Tested up 5.2.2 with PHP 7.3

= 1.2.0 =
* Tested up 5.0.3.
* Added the function for hiding password protected pages in previous post and next post.

= 1.1.3 =
* Tested up 4.9.

= 1.1.2 =
* Tested up 4.8 and PHP 7.1

= 1.1.1 =
* Fixed for wp_get_archives function
 
= 1.1.0 =
* Tested up 4.7.1
* Fixed language translation for GlotPress.
* Added the view function of the list of password protected page.

= 1.0.6 =
* Tested up 4.7.

= 1.0.5 =
* Tested up 4.6.

= 1.0.4 =
* Tested up 4.5.
* Preparing to migrate the translation function to GlotPress.

= 1.0.3 =
* Tested up 4.4.1.
* Fixed the explanation in the setting.

= 1.0.2 =
* Tested up 4.3.

= 1.0.1 =
* Tested up 4.1.1.

= 1.0.0 =
* First Released.
