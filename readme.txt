=== WP Comment Encode ===
Contributors: dbmartin
Tags: comments, wysiwyg, quicktags, encode
Requires at least: 3.8
Tested up to: 3.9
Stable tag: 1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily include raw code in comments.

== Description ==

Easily include raw markup languages (HTML, CSS, PHP, etc.) in comments using custom quicktags.  No need to encode, the filter does it
for you.  Creates convenient "encode" buttons for comment authors in the HTML editor.

Also adds basic quicktag buttons on the comment forms, using the Quicktag API built into WordPress (as of 3.3).

== Installation ==

1. Upload the `wp-comment-encode` folder to the your plugins directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.

== Changelog ==
= 1.0 =
* updated quicktags to work with the new [Quicktag API](https://codex.wordpress.org/Quicktags_API)
* Added in checks to only load JS/CSS when on a page with a comment form
* Added failure if not WordPress 3.7 or greater

== Upgrade Notice ==
