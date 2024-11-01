=== Plugin Name ===

Contributors: Ryan Huff, The CodeTree
Donate link: http://mycodetree.com/donations/
Tags: contact, form, contact form, webinar, event, feedback, email, ajax, akismet, multilingual

Requires at least: 2.9
Tested up to: 3.2.1
Stable tag: 2.5

Displays seminar event information with an automatic feature to add to Google calendars and download an ICS file.

== Description ==

The CodeTree SEManager plugin is designed to store seminar event information and display the information formatted within Wordpress pages.

When an event is rendered the plugin will provide a pre-formatted *Add to Google Calendar* button and a link to download an auto generated .ics calendar file. 

The plugin also offers a feature to allow users to format event to their local time zones and then add the event to Google Calendar or download an .ics file for desktop mail clients.

== Installation ==

1. Upload the plugin archive file into Wordpress using the 'Add New' option under the plugin menu in the Administration area.
1. Alternatively, you can unzip the plugin archive and FTP the contents to the wp-content/plugin/ folder of Wordpress
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Navigate to 'CodeTree SEManager' under the settings menu.
1. Use the 1-Click Registration form to get a FREE registration key.
1. Create an event with the 'add event' button or click the 'Video Manual for 
SEManager' under 'Helpful Links' in the SEManager Help box. 

== Frequently Asked Questions ==

= Date calendar doesn't work in the event menu =

When you attempt to add or create an event, a jQuery Calendar control should popup and allow you to select a date. If you do not see the calendar control popup, the most likely cause is because there is another theme or plugin that has already loaded the jQuery UI library. In this case, you need to goto the settings menu of the CodeTree SEManager and set the compatibility mode option to yes. This will stop the SEManager from loading the jQuery UI library since it is probably 
already being loaded.

= Parse errors =

This indicates that the web server is using an unsupported version of PHP. The CodeTree SEManager requires PHP version 5.0 or higher. Please upgrade the version of PHP that your web server is using or talk to your web host to see if they can upgrade for you. If you are considering moving your web site to a different web host, MyCodeTree recommends
<a href='http://secure.hostgator.com/~affiliat/cgi-bin/affiliates/clickthru.cgi?id=rthcon' target='_blank'>http://hostgator.com</a> as the official web host of
choice for all CodeTree plugins.

== Screenshots ==

1. Example of the plugin's settings menu.
2. Example of a rendered event

== Changelog ==

= 2.0 =
* Launch Version

= 2.1 =
* Added Rare Earth Theme
* Bug Fix: Minor typographical error 

== Upgrade Notice ==

= 2.0 =
* Launch version

= 2.1 =
* Added Rare Earth Theme
* Bug Fix: Minor typographical error

= 2.5 =
* Bug fixed in registration system that didn't allow registration under certain circumstances