=== Plugin Name ===
Contributors: Matthew Phillips
Donate link: http://smheart.org/donations
Tags: repost-oldest, posting, reposting, posts, post, recycle
Requires at least: 2.6
Tested up to: 2.9
Stable tag: trunk

Once per day this plugin identifies the oldest post and sends out a notification via Ping.fm and HelloTXT.com social notification networks.

== Description ==

This plugin allows for the automatic daily reposting of the oldest post.  The selected post has the oldest post_modified date.  The post_modified date is changed to the current time and Ping.fm and HelloTXT.com social notification networks are updated with information from this post.  Any 'Update Services' configured in the Writing Settings in your Wordpress installation are also notified of the repost.  Plugin was formerly known as rePost Oldest.

Accounts on Ping.FM and HelloTXT.com are required for this plug-in to function.

* Format for the information sent is (see screenshot): 
(Preamble to the message) (Post title) (Post Link) (Post Content -truncated to keep message no larger than 140 characters) (Ellipse)
* Sample message would resemble:
Flashback: Here is the Post Title: http://posturl.com/post - This is the first several works of the past...



== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the folder 'post-recycler' to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Visit the Post Recycler settings page to configure plugin.
1. Plugin will resend notification out on a daily basis.



== Frequently Asked Questions ==

= Do I need accounts on Ping.FM and HelloTXT.com? =

Yes, accounts on Ping.FM and HelloTXT.com are required for this plugin to function.

= Can I change the frequency to something other than daily? =

At this time the only frequency for reposting is once per day.

== Screenshots ==

1. This screenshot is of the existing configuration screen for the plugin.


== Changelog ==

= 1.0 =
* Initial Release 16 December 2009


== Known Bugs ==

= Major =
* none

= Minor =
* email addresses for both Ping.FM and HelloTXT.com are needed for plugin operation.  If only one is used php errors may be recorded on the server log.

== Feature Roadmap ==

= UI Features = 
* add options for additional repost times, hourly, twice daily, etc.
* add link to most recent repost
* add category selections
* add user selectable time for repost
* add support for other languages
* add user selectability for notification services.

= Backend Features =
* Add option for Admin notification of next future repost
* migrate to helloTXT.com and Ping.fm API

== License ==

* Post Recycler is released under the GNU GPL version 3.0 or later.
