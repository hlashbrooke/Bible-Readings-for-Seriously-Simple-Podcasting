=== Bible Readings for Seriously Simple Podcasting ===
Contributors: hlashbrooke
Tags: seriously simple podcasting, bible, reading, podcast, podcasting, sermons
Requires at least: 4.4
Tested up to: 5.3
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add linked Bible readings to sermons published with Seriously Simple Podcasting.

== Description ==

Seriously Simple Podcasting is a great plugin for publishing your church's weekly sermons, but no sermon is really complete without a relevant Bible reading - that's what this plugin allows you to add to your sermons published with Seriously Simple Podcasting.

Simply put - this plugin adds a new 'Bible reading' text field to your episodes where you can specify the reading for that sermon. For your site visitors, this will add the reading to the episode meta data that is displayed beneath the media player with the reading linked to biblegateway.com for the full text.

> This plugin is an add-on for [Seriously Simple Podcasting](https://wordpress.org/plugins/seriously-simple-podcasting/) and requires at least **v1.15** of Seriously Simple Podcasting in order to work.

**Primary Features**

- Adds a new field to your episodes for adding the Bible reading for the sermon
- Adds the Bible reading alongside the rest of the episode details on your episodes, linked to the full passage on biblegateway.com
- Allows you to select your preferred version from 60 different English Bible versions
- Includes dynamic filtering (see FAQ) to use any other version that is available on biblegateway.com

**How to contribute**

If you want to contribute to Bible Readings for Seriously Simple Podcasting, you can [fork the GitHub repository](https://github.com/hlashbrooke/Bible-Readings-for-Seriously-Simple-Podcasting) - all pull requests will be reviewed and merged if they fit into the goals for the plugin.

== Installation ==

Installing "Bible Readings for Seriously Simple Podcasting" can be done either by searching for "Bible Readings for Seriously Simple Podcasting" via the "Plugins > Add New" screen in your WordPress dashboard, or by using the following steps:

1. Download the plugin via WordPress.org
2. Upload the ZIP file through the 'Plugins > Add New > Upload' screen in your WordPress dashboard
3. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. The episode details fields with the Bible reading field included.
2. The linked Bible reading as it appears alongside your epiosde meta data.

== Frequently Asked Questions ==

= What version of Seriously Simple Podcasting does this plugin require? =

In order to use this plugin you need to have at least v1.15 of [Seriously Simple Podcasting](https://wordpress.org/plugins/seriously-simple-podcasting/). If you do not have Seriously Simple Podcasting active or you are using a version older than v1.15 then this plugin will do nothing.

= What site is linked to for the actual Bible passage? =

The Bible readings link to biblegateway.com and are displayed on that site using the version selected in ther settings. You can use the `ssp_bible_readings_url` filter to modify the URL for the link if you would like it to point it to a different site. [See some example code here](https://gist.github.com/hlashbrooke/68d2f2e88cc50fbafbce1b6688117d97).

= How do I change the Bible version in use? =

You will find a new setting at the bottom of the "Podcast > Settings" page where you can select which Bible version is used - it defaults to the New International Version (NIV). You can filter this to use [any version that biblegateway.com offers](https://www.biblegateway.com/versions/) using the `ssp_bible_readings_version` filter. This filter includes the episode ID as an additional parameter, so you can use it to specify a different version for specific episodes. [See some example code here](https://gist.github.com/hlashbrooke/42df1ebaec9adf4c2f09350b6f7e1e47).

= Can I include multiple readings in one episode? =

Yes, you can! Just separate each passage with a comma (`,`). The following formats (and their combinations/variations) will all work:

* [Proverbs 16](https://www.biblegateway.com/passage/?search=proverbs+16&version=NIV)
* [Proverbs 16:1-9](https://www.biblegateway.com/passage/?search=proverbs+16:1-9&version=NIV)
* [Proverbs 16:1-9, 12-14](https://www.biblegateway.com/passage/?search=proverbs+16:1-9,+12-14&version=NIV)
* [Proverbs 16:1-9, Acts 17:22-31](https://www.biblegateway.com/passage/?search=proverbs+16:1-9,+acts+17:22-31&version=NIV)
* [Proverbs 16:1-9, Acts 17:22-31, Mark 1:1-5](https://www.biblegateway.com/passage/?search=proverbs+16:1-9,+acts+17:22-31,+mark+1:1-5&version=NIV)
* [Proverbs 16-17](https://www.biblegateway.com/passage/?search=proverbs+16-17&version=NIV)
* [Proverbs 16:12-17:5](https://www.biblegateway.com/passage/?search=proverbs+16:12-17:5&version=NIV)

You can string any amount of passages together like that and they will all display on the same page when opened, in the order that you added them.

= Why does the media player in the screenshot look different to the normal WP media player? =

It is the same media player that is built into WordPress itself, but I have styled it using the [Media Player Style Kit plugin](https://wordpress.org/plugins/media-player-style-kit/).

== Changelog ==

= 1.0 =
* 2019-10-27
* Initial release

== Upgrade Notice ==

= 1.0 =
* Initial release
