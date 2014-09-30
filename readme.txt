=== MP Stacks ===
Contributors: johnstonphilip
Donate link: http://mintplugins.com/
Tags: message bar, header
Requires at least: 3.5
Tested up to: 4.0
Stable tag: 1.0.1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

An amazing Page Builder for WordPress. Content-Types go in a Brick, Bricks go in a Stack, Stacks go on a page.

== Description ==

Build pages using the MP Stacks plugin by making "Bricks". Each Brick can have its own background image, colour, size, and 2 "Content-Types". 

There are 3 Content-Types" built into the MP Stacks Plugin: Video, Image, and Text.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the 'mp-stacks' folder to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Build Bricks under the "Stacks and Bricks" menu. 
4. Publish your bricks into a "Stack".
5. Put Stacks on pages using the shortcode or the "Add Stack" button.

== Frequently Asked Questions ==

See full instructions at http://mintplugins.com/doc/mp-stacks

== Screenshots ==


== Changelog ==

= 1.0.1.3 = September 30, 2014
* Fixed other taxonomies' "edit" buttons weren't returned and so, were not linking to anything.

= 1.0.1.2 = September 30, 2014
* Fixed create_stack_from_template function not properly checking repeaters for attachments
* Changed name of Add-Ons page from "Add Ons" to "Add on Shop"
* Added Split Percentage Control - to control Left/Right Content-Type width percentage
* Changed brick's post_class to no longer show the .hentry class
* Removed mp_brick_container_css Filter Hook
* Added mp_brick_first_container_css Filter Hook
* Added mp_brick_second_container_css Filter Hook
* Changed Text Content Type to be repeatable 
* Added backwards compatibility function for text content types to auto update their formatting to be repeaters
* Added Grid Functions for Add-ons to share and use
* Added Brick/Stack Titles on each Brick
* Removed previously deprecated JS which set the font size in TinyMCE for text areas.
* Added user-required confirmation for inserting existing stack - users were getting confused
* Removed misc options for Drafts, Dates etc for Bricks as they aren't needed.
* Added dismissable "Tip" to let people know they can double click a brick to edit it.
* Added support description in Brick editor.
* Made Lightbox close upon the deletion of a brick.
* Added action hook "mp_brick_metabox" so brick metaboxes only load on brick admin screens
* Added activation hook and redirection to Welcome Page upon activation
* Changed Capability Type for MP Stacks to Page instead of Post. This way authors don't have access.
* Added Directory page for Stack Templates
* Added notice at the top of Directory pages about the master license
* Added auto text-resize system for mobile
* If Content Types max width is blank, default to 1000

= 1.0.1.1 = June 8, 2014
* Left/Right Brick widths go from, 37% to 50%
* Content-Type areas get "position:relative;", Width 100%, no left/right margins by default, border box sizing.
* Added a jQuery trigger called "mp_stacks_content_type_change" when a content type is changed
* Added magnific popup to admin for use in managing back-end options
* Added Content-Type width and float controls
* Added "Full-Width" option for content-types (to remove default 10px padding)
* Changed edit stack settings save callback to admin_init instead of edited_mp_stacks hook
* Added Filter hook for "mp_brick_first_content_type_css_filter"
* Added Filter hook for "mp_brick_first_content_type_mobile_css_filter"
* Added Filter hook for "mp_brick_second_content_type_css_filter"
* Added Filter hook for "mp_brick_second_content_type_mobile_css_filter"
* Added arguments for content-types to the mp_brick_additional_css filter
* Added body class to the stack-only pages
* Added "Optimize for MP Stacks" Page Template if no Stack Page Template exists

= 1.0.1.0 = May 8, 2014
* Stack template functions added
* Added functions for getting brick titles in a stack
* Move to Mint

= 1.0.0.9 = April 16, 2014
* Updated TinyMCE Plugin for TinyMCE 4.0
* CSS is now output in the header instead of the footer
* Added "Stack Only" pages and rewrite parameter
* Changed variable for mp_brick_additional_css filter from $css_output to null
* JS Scripts are now enqueued to show in the footer instead of header
* Smooth Scrolling and anchor points added for bricks

= 1.0.0.8 = March 22, 2014
* Removed priority of 1 from Brick size settings metabox action
* Add-on directory now links to https://mintplugins 

= 1.0.0.7 = March 8, 2014
* Enqueue Magnific in normal wp_enqueue_scripts vs mp_stacks_enqueue

= 1.0.0.6 = March 8, 2014
* Prevent default event for brick edit links - wasn't working in Firefox

= 1.0.0.5 = March 5, 2014
* Better responsive Handling
* Double click on bricks to edit them 
* mp_stacks_metabox becomes mp_stacks_size_metabox
* Content Type Margins added to size metabox

= 1.0.0.4 = March 5, 2014
* Added context to footer css to prevent double style tags in footer

= 1.0.0.3 = March 5, 2014
* Attached Brick CSS to footer instead of scanning posts for shortcodes

= 1.0.0.2 = February 23, 2014
* Upgraded Security using Nonces for Ajax
* Fix wp_query to show all bricks in stack rather than posts_per_page
* Improved code documentation on Custom Post Type file
* Added 15px margins below links in Text Areas - except if last-child


= 1.0.0.1 = February 19, 2014
* Added Video Tutorials for all default Metaboxes
* Added MP Stacks Dashboard/Welcome Page
* Improved Default Responsive Styling

= 1.0.0.0 = February 10, 2014
* Original release
