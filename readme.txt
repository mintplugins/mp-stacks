=== MP Stacks ===
Contributors: johnstonphilip
Donate link: http://mintplugins.com/
Tags: message bar, header
Requires at least: 3.5
Tested up to: 4.1
Stable tag: 1.0.2.3
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

= 1.0.2.3 = April 5, 2015
* Added HTML note about inline JS
* Fixed Bug with some WordPress installations not firing the '99' function priority for 'save_post'. This was causing Bricks to not be placed into Stacks upon creation. Now the mp_stacks_order function that fires upon 'save_post' fires at priority 11 instead of 99.

= 1.0.2.2 = April 3, 2015
* Added lightbox.js as dependancy for admin.js enqueue
* Added default height of 250px for "no brick" brick.
* Upon new brick save, double check it is published. In firefox it was saving the bricks as drafts.
* Added return true to mp_stacks_theme_bundle_create_default_pages function.

= 1.0.2.1 = March 30, 2015
* Bug Fix: Empty Stacks Weren't Adding Bricks.

= 1.0.2.0 = March 26, 2015
* Brick ordering bug fix: Remove menu_order from all Brick WP Queries. This forces it to use meta_value_num and mp_stacks_oder{stack_id} so the order won't get changed when it shouldn't.

= 1.0.1.9 = March 24, 2015
* TinyMCE Fix: Only re-initialize TinyMCE after Content-Type Reorders if it was previously set to be in “Visual” mode. Otherwise don’t reinitialize. This fixes the issue of having multiple text areas in one area upon re-ordering.
* Better styling for wp admin footer in minimal mode
* vertical-align grid items to "top" in front end css.
* Only show the "Edit this Stack's Bricks" if developer
* Added tip for video popup size to image link
* Add the Slug of a brick to the Brick Editor Footer
* Right Side-Items in Brick Editor are now “closed” by default.
* Updated plugin checker to version in mp_core 1.0.1.2
* Changed priority of MP Stacks front end Enqueue to 1 so it's styles are output before other styles (was a lag on some machines where MP Stacks styles 'kicked-in' after load).
* Added bg inner div  which now holds the brick backgrounds
* Bricks-In-Stack list added to TinyMCE "Link" creator
* Brick Auto Scroll is now 100ms instead of 500ms
* Added CSS ID Selectors to Content Types and their container divs
* Added inline_js output in wp_footer
* Upon new Brick added to Stack, we loop through all Bricks in the Stack and order them starting at 1000 and going up by 10 each time. This fixes a bug where Stacks randomly had Bricks out of order.

= 1.0.1.8 = March 1, 2015
* Added versions to all enqueues
* mp_core_post_submitted Jquery Trigger Added.
* Grid Background CSS Added
* Made it more difficult to accidentally insert existing stack by adding longer Warning message.
* Set default for leftright brick alignment using mp_core_get_post_meta
* Updated plugin checker to coincide with MP Core 1.0.1.1
* CSS Vertical Alignment change for first/second content type areas to “bottom” instead of “baseline”.
* Removed Mobile text size check. It’s too much of a hog for mobile devices.
* Removed float left from grid layouts (and also post_counter variable as it tracked the need for a float clear).

= 1.0.1.7 = February 4, 2015
* Default Stacks for Theme Bundles: Use the Stack Title in the template for defaults.
* Add Stack Description to Stack Template code if developer add-on is active.

= 1.0.1.6 = February 3, 2015
* Fixed bug with lightbox popups where they wouldn’t open because of the alternate URL.

= 1.0.1.5 = February 1, 2015
* Fix for text areas when re-ordering contentTypes. TinyMce’s weren’t being re-set right when Content-Types were re-ordered.
* New option for Space Below: This allows the user to change the margin below the content-type area independently from the top (above) margin. Prevously, above and below had to match
* Moved Minimum height Meta option under the “Margin” showhider.
* Removed “Minimum” from space above/below option descriptions.
* Apply default of 20px below 1st content type on mobile sizes (<600px)
* Install change: if there is a parent plugin installing, it won't redirect to the welcome page
* Improved Font Shrinking for mobile by wrapping each word in a span tag and checking its width.
* Make CSS for short codes external instead of inline.
* Pseudo Knapstack Installation incorrectly required a license - but it doesn’t anymore.
* Added Theme Bundle Function to create Default Stacks mp_stacks_theme_bundle_create_default_pages
* Made Lightbox Popup YouTube videos have no related videos at end and minimal YT branding by default
* Improved Button Styling inside text areas so they don’t bump into each other on mobile.
* Fixed bug where, on some WP installs bricks weren’t getting added to Stacks because a priority was assigned to the save_post function - the priority was 100 - but 100 wasn’t firing at all on Brick saves. This fixes that.
* Better Masonry Handling for Grids
* Main Image Lightbox has moved from an Add-On to MP Stacks core.
* Made Image links css default to 100% width (only images without a link were doing this - although for most scenarios it isn’t an issue because of the max-width setting).
* For Lightboxes, added ability for alternate URL
* CSS Vertical Alignment change for img types to “baseline” instead of “text-bottom”.

= 1.0.1.4 = January 4, 2015
* Grid Overlays function for Mobile Styles added
* Fixed custom width and height lightbox js functions
* Added a Pseudo Version of Knapstack to the Appearance>Themes page if not installed.
* Changed Add-On Directory page to be a single category.
* Added Stack Widget so Stacks can be used in sidebars.
* Changed Text Content-Types from being “Text Pairs” to being single areas that repeat. This only applies to new bricks as backwards compatibility has been ensured for old bricks.
* Color-Coded content-types so they are easier to follow upon first-glance.
* When duplicating a stack, you now have the option to preview the stack you are duplicating.
* Added some error checking when making a new stack via the stack template function.
* In grids, we remove the title attribute upon mouse over so we don’t have it covering the  grid item
* Removed “Edit” link from bottom of Stack Page Template.
* Made matched-height lightboxes animate to the correct size.
* New Filter hook called mp_stacks_brick_head_output which is used to output meta information based on a brick’s content.

= 1.0.1.3 = December 1, 2014
* Fixed:  Other taxonomies' "edit" buttons weren't returned and so, were not linking to anything.
* First and Second Content Type divs set to inline-block so they wrap around content no matter what.
* Changed: No longer removes left right custom margins on mobile. They remain no matter what now - where before they were reset to 0.
* Fixed issue with Text Resizing javascript (If text is too big for the screen it shrinks it down until it fits - removed an inefficiency).
* Added more Grid Functions in PHP, CSS, and JS
* Fixed Page template error on 404 pages where there is no Post ID.
* Added Rewrite rules for Grids pagination
* Fixed Content-Type Width + Float Center bug
* Fixed ‘Grip’ icon not showing on Re-Order Bricks Page
* Changed the position number for the MP Stacks Menu - it was cancelling out the comments menu. Comments menu will show up again now.
* Fixed redirection upon install issue. Now auto redirects to install needed plugins if needed before redirecting to welcome page.
* Added activation tracking

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
