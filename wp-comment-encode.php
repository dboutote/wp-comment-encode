<?php
/*
 Plugin Name: WP Comment Encode
 Plugin URI: http://darrinb.com
 Description: Easily include raw markup languages (HTML, CSS, PHP, etc.) in WordPress comments using custom quicktags.
 Version: 1.0
 Author: Darrin Boutote
 Author URI: http://darrinb.com

 This file is part of WP Comment Encode, a plugin for WordPress.
 Based off of "bb_encodeit" used in bbPress comment system.

 WP Comment Encode is free software: you can redistribute it and/or
 modify it under the terms of the GNU General Public License as published
 by the Free Software Foundation, either version 2 of the License, or
 (at your option) any later version.

 WP Comment Encode is distributed in the hope that it will be
 useful, but WITHOUT ANY WARRANTY; without even the implied warranty
 of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with WordPress.  If not, see <http://www.gnu.org/licenses/>.
*/

global $wp_version;

if( version_compare($wp_version,"3.7","<") ) {
	exit( __('This plugin requires WordPress 3.7', 'wp-comment-encode') );
}

class WPCommentEncode {

	public function __construct() {
		add_action('init', array(&$this, 'register_scripts_frontend'));
		add_action('init', array(&$this, 'register_styles_frontend'));
		add_action('wp_enqueue_scripts', array(&$this, 'add_styles_frontend'));
		add_action('wp_enqueue_scripts', array(&$this, 'add_scripts_frontend'));
		add_filter('preprocess_comment', array(&$this, 'comment_code_filter'), 1, 1);
	}

	/**
	 * Comment Code Filter
	 * updated: 4/15/10
	 * Filters comment before it's entered into the database. This allows for comment authors to enter
	 * html tags (in markup code samples) that would normally be stripped by kses filter.
	 * Calls "comment_encode" for the actual encoding.
	 */
	public function comment_code_filter( $text ) {
		$text = str_replace(array("\r\n", "\r"), "\n", $text);  // replace any carriage returns and/or new lines with a new line.
		$text = preg_replace_callback("#(`)(.*?)`#", array(&$this, 'comment_encode'), $text);
		$text = preg_replace_callback("#(^|\n)`(.*?)`#s", array(&$this, 'comment_encode'), $text);
		return $text;
	}


	/**
	 * Filters code in post comments between backticks (`).
	 * updated: 4/15/10
	 * This will double-encode anything between those tags.  For ex. "&amp;" will become "&amp;amp;"
	 */
	public function comment_encode( $matches ) {
		$charset = get_bloginfo('charset');
		$text = trim($matches[2]);                              // trim any whitespace between backticks
		$text = htmlspecialchars($text, ENT_QUOTES, $charset);  // encode the html chars
		$text = str_replace(array("\r\n", "\r"), "\n", $text);  // replace any carriage returns and/or new lines with a new line.
		$text = preg_replace("#\n\n\n+#", "\n\n", $text);       // replace any multiple new lines with 2 new lines.
		$text = "<code>$text</code>";                           // wrap the encoded text in <code> tags
		if ( "`" != $matches[1] ) {                             // wrap in <pre> tags if there's new lines in the code
			$text = "<pre>$text</pre>";
		}
		return $text;
	}

	// Registers plugin scripts
	public function register_scripts_frontend(){
		if( !is_admin() ) {
			wp_register_script(
				'wpce-quicktags',
				plugins_url(dirname(plugin_basename(__FILE__)) . '/quicktags.js'),
				array("quicktags","jquery"),
				"3.1",
				1
			);
		}
	}

	// register stylesheets for front end
	function register_styles_frontend(){

		wp_register_style(
			'wpce-quicktags',
			plugins_url(dirname(plugin_basename(__FILE__)) . '/quicktags.css'),
			'',
			'1.0',
			'all'
		);
	}

	// Load front-end styles
	function add_styles_frontend() {
		if ( is_singular() && comments_open() ) {
			wp_enqueue_style( 'wpce-quicktags' );
		}
	}

	// Load front-end scripts
	function add_scripts_frontend() {
		if ( is_singular() && comments_open() ) {
			wp_enqueue_script('wpce-quicktags');
		}
	}

}

new WPCommentEncode();
