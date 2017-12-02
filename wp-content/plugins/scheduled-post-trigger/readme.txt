=== Plugin Name ===
Contributors: mossifer
Donate link: http://mosswebworks.com/donate/
Tags: scheduled posts, missed schedule, missed scheduled posts
Requires at least: 3.0.1
Tested up to: 4.9.1
Stable tag: 4.9.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Checks to see if any scheduled posts have been missed. If so, it publishes them.

== Description ==
When anyone loads your site, this lightweight script checks to see if any scheduled posts have been missed. If so, it publishes them immediately. 

== Installation ==
1. Go to Plugins, Add New, Upload Plugin.
2. Upload the ZIP file.
3. Activate the plugin through the 'Plugins' screen in WordPress

NOTE: In order for the posts to get published, you have to load the (public) website, as it’s attached to your header.

Make sure that your timezone is set correctly in Settings->General.

== Frequently Asked Questions ==

= How often does it check the posts? =

Every time someone loads your site.

= I’ve activated the plugin-and the posts are not publishing =

You have to load your actual website—-not just the admin panel—-as the plugin depends on the header rendering.


== Changelog ==

= 1.8 =
Tightened up code. Will not go into the publish loop unless there is a missed post.

= 1.7 =
Small change to integrate with WP posting function.

= 1.0 =
* Initial release

== Upgrade Notice ==

= 1.7 =
Minor changes to plugin.