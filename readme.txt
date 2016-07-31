=== Drafty In Here ===
Contributors: AronMS
Tags: productivity, focus, motivation, drafts, draft posts, notify, emails, drafty, draft, reminders, notifications, blog
Requires at least: 4.3
Tested up to: 4.6
Stable tag: 1.2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Get email notifications of draft posts sitting in your WordPress Blog waiting to be published.

== Description ==

Get email notifications of draft posts sitting in your WordPress Blog waiting to be published.

This plugin aims to help get your productivity back on track after you abandon writing your next amazing post, by sending you friendly motivational email reminders when you specify.

Features:

* Schedule email reminders for unpublished draft posts
* Specify which email address reminders are sent to
* Send optional test emails when you save changes

== Installation ==

1. Unzip `drafty-in-here.zip` inside the `/wp-content/plugins/` directory (or install via the built-in WordPress plugin installer)
2. Activate the plugin through the 'Plugins' admin menu in WordPress
3. Set your email address and how frequently you want to be updated about unfinished draft posts

== Frequently Asked Questions ==
= Why did I not receive any email? = 
Please read our [Email Trouble Shooting Guide](https://wordpress.org/plugins/drafty-in-here/other_notes/) for help. If you do not find your answer please post in the [support forum](http://wordpress.org/support/plugin/drafty-in-here) and I will try to help you where I can.

= How can I contribute to this plugin? =
The code for [Drafty In Here is on GitHub](https://github.com/aronmarriott-smith/drafty-in-here), so if you If you are a WordPress developer feel free to take a look there - any pull request to the development branch are welcome.

= How can I report a bug? =
Bug reports for Drafty In Here are [welcomed on GitHub](https://github.com/aronmarriott-smith/drafty-in-here/issues/). Please note GitHub is *not* a support forum and issues that aren't properly qualified as bugs will be closed.

= Where did you get your cool WordPress.org plgin banner? =
The banner is a derivative of the original image ['University Life 143'](https://www.flickr.com/photos/francisco_osorio/9513730462/) created by [Francisco Osorio](https://www.flickr.com/photos/francisco_osorio/) and posted on Fickr under the [Creative Commons Attribution 2.0 Generic license](https://creativecommons.org/licenses/by/2.0/).

== Email Trouble Shooting Guide ==
*Not receiving Drafty In Here emails?*
= 1. Do you have any draft posts? =
The email will never be sent automaticly if you do not have any draft posts. If you do not have draft posts but wish to send a test email check the box that says `Send a test e-mail when you save changes`, then save changes.

= 2. Have you scheduled your email? =
You can check this in the plugin settings screen (see [screenshots](https://wordpress.org/plugins/drafty-in-here/screenshots/)).

= 3. Is your email address correct? =
You can check this in the plugin settings screen (see [screenshots](https://wordpress.org/plugins/drafty-in-here/screenshots/)).

= 4. Have you checked your email spam folder? =
Sometimes email may end up in your spam folder.

= 5. Are you receiving ANY WordPress emails from your site? =
If you are not receiving emails for example when someone post a comment  or WordPress automatically updates, there may be something wrong with the way your WordPress site is set up to send email. For more help on this issue please check out this excellent guide: [How to Fix WordPress Not Sending Email Issue](http://www.wpbeginner.com/wp-tutorials/how-to-fix-wordpress-not-sending-email-issue/)

= 6. Is your blog receiving enough traffic? =
Our plugin works off the 'WordPress Cron' mechanism which means your email can only be sent when someone lands on your site. This can be a problem if you do not have enough traffic. For more help on this issue please check out this guide from Host Gator: [How to Replace WP-Cron With a Linux Cron Job](http://support.hostgator.com/articles/specialized-help/technical/wordpress/how-to-replace-wordpress-cron-with-a-real-cron-job)

== Screenshots ==

1. The Drafty In Here settings screen is located under the main WordPress settings menu.

== Changelog ==

= 1.0 =
* Initial release
= 1.1 =
* Added "support" for the users running php5.2
= 1.1.1 =
* Fixed several typos
* Fixed bug when saving frequency
* Replaced Carbon with PHP DateTime
* Changed the minimum version of PHP required by Drafty In Here to 5.3.2
= 1.1.2 =
* Fixed a bug which was causing a PHP fatal error in the Drafty In Here settings page.
= 1.1.3 =
* Fixed potential namespacing issue
* Several bug fixes identified while writing unit tests
= 1.2.0 =
* Updated screenshot
* Updated translation pot file
* Improvements to plugin documentation
* Added links to FAQ and reviews in the plugins screen
* Updated some translation strings so that the brand name is irrelevant
* Fixed issue where link to setting screen was not appearing on the plugins screen.
* Changed Drafty In Here emails to be plain text rather than multipart (due to broken WordPress core implementation which may be fixed in 4.6)

== Discussion / Support ==

Have any questions, comments, or suggestions? Please provide them via the plugin’s WordPress.org [support forum](http://wordpress.org/support/plugin/drafty-in-here). I’ll do my best to reply in a timely fashion and help as best I can.

Unfortunately, I cannot provide guaranteed support, nor do I provide support via any other means.

Was this plugin useful to you? Consider giving it a rating. If you’re inclined to give it a poor rating, please first post to the support forum to give me a chance to address or explain the situation.