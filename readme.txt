=== MP Stacks ===
Contributors: johnstonphilip, mintplugins
Donate link: https://mintplugins.com/
Tags: page, shortcode, design
Requires at least: 3.5
Tested up to: 5.7
Stable tag: 1.0.6.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

An amazing Page Builder for WordPress. Content-Types go in a Brick, Bricks go in a Stack, Stacks go on a page.

== Description ==

Build pages using the MP Stacks plugin by making "Bricks". Each Brick can have its own background image, colour, size, and 2 "Content-Types".

There are 3 Content-Types built into the MP Stacks Plugin: Video, Image, and Text.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the 'mp-stacks' folder to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. On any page or post, click the "Add Stack" button, save, and view the page/post.
4. Add Bricks into the "Stack" by clicking "Add a Brick to this Stack" when viewing the page or post.

== Frequently Asked Questions ==

See full instructions at http://mintplugins.com/support/mp-stacks-support/

== Screenshots ==

1. Example page built entirely using MP Stacks

2. Another example page built entirely using MP Stacks

3. Creating/Adding a new Stack.

4. Adding Bricks to Stacks.

5. Re-Ordering Bricks by dragging and dropping.


== Changelog ==

= 1.0.6.8 = June 9, 2021
* Add label wrapper around isotope sorting options, and fix vertical alignment when used alongside other filter buttons.

= 1.0.6.7 = February 23, 2021
* Remove unused containing_page_url variable from brick editor URL.

= 1.0.6.6 = November 26, 2020
* Add label wrapper around isotope select fields

= 1.0.6.5 = August 12, 2020
* Fix WordPress 5.5 metabox title breakage issue

= 1.0.6.4 = November 30, 2018
* Fix widgets for PHP 7.2

= 1.0.6.3 = March 7, 2018
* Fix Stack Templater to fallback to CURL for sites with allow_url_fopen disabled
* Update plugin checker/installer for allow_url_fopen fix

= 1.0.6.2 = March 5, 2018
* Fixed: Reset the loop after header CSS output

= 1.0.6.1 = March 1, 2018
* Fixed: Implement workaround for problems caused by Jetpack Photon
* Fixed: No longer use get_the_content so that oEmbeds are properly executed within text areas.

= 1.0.6.0 = October 10, 2017
* Fixed: PHP7 issue with brick split percentage

= 1.0.5.9 = September 29, 2017
* Change/Fix: Removed knapstack plugin checker function which was being used instead of correct function from theme bundles.
* Change: For single stack views, make the page title the title of the stack.

= 1.0.5.8 = February 12, 2017
* Fix: Stacks were not updating properly after update.

= 1.0.5.7 = November 14, 2016
* Fix: $act_as_login was not defined if a Stack was removed from the page in the mp_stack function.

= 1.0.5.6 = November 3, 2016
* Make sure $mp_stacks_on_page['css_complete'] is array before using it as one.

= 1.0.5.5 = October 11, 2016
* Load updater on init (returning if not admin later) to make it work with wpcli updates.

= 1.0.5.4 = September 20, 2016
* Added filters for mp_stacks_css_required and mp_stacks_act_as_logged_in. This is to add compatibility with plugins like Yoast SEO.
* Added compatibility functions for Yoast SEO.

= 1.0.5.3 = July 30, 2016
* Set isotope filter buttons to have CSS properly "cursor:pointer". This fixes a strange bug in iPhone safari which made them unclickable.
* Make images in text areas max to 100%. This prevents an image in a text-area from being wider than a Brick.
* Add do_shortcode to image, video, and background image fields.

= 1.0.5.2 = June 3, 2016
* Fixes for Thickbox Popup Styling
* Make Grids with no spacing have their items overlap by 1px
* Fix for animation-skip CSS on iPhone/iPad

= 1.0.5.1 = April 21, 2016
* Fixed issue with enter/newline key not working in text areas.

= 1.0.5.0 = April 17, 2016
* Further fixes to the Brick anchor scrolls

= 1.0.4.9 = April 14, 2016
* Smooth Scroll change for jquery update in WordPress 4.5.

= 1.0.4.8 = April 4, 2016
* Added fix for Grid Text placements

= 1.0.4.7 = April 4, 2016
* Remove height setting from Brick Text Options in Brick Editor
* Remove inline-block css from .mp-brick * - was affecting all items and wasn't necessary.
* When updating Brick, remove all Jquery events that were attached to the brick class.
* Added option for grid text positions to include only what they need.
* Added mp_stacks_brick_css_page function which creates a CSS file for a Brick's css.
* Remove the (now confusing) "View Page" instruction from MP Stacks Welcome page. This button was removed by WordPress itself.
* Changed "main image link url" metabox to be text so that #hashtag urls can be entered.
* Added License File so people know that MP Stacks is GPL :)
* Added vertical alignment controls for Content Types.

= 1.0.4.6 = December 24, 2015
* Dequeue promblematic script from jetpack called "devicepx".
* Isotope Buttons to skip now uses strpos instead of in_array.
* Remove matching theme upon activation if the user accidentally uploaded a Theme Bundle as a "Theme" instead of as a "Plugin".

= 1.0.4.5 = December 10, 2015
* Fix bug with WordPress 4.4 where Brick Descriptions were entered as NULL - they need to be a blank string now.
* Add relative positioning to brick container divs
* Make sure MP Stacks fronend enqueue only runs once.
* Add filter for mp_stacks_brick_alignment
* Remove support notice in Brick Editor - was causing a weird jumping effect.
* Update elementQuery js script.
* Changed min height default from 50px to 10px to have better preset for short bricks (like navigation).

= 1.0.4.4 = November 22, 2015
* Js Function "mp_stacks_update_brick" changed to "mp_stacks_load_ajax_brick". Also a new function called "mp_stacks_load_ajax_stack" was added to allow the ajax loading of a stack.
* Ajax callback function for loading a Brick via ajax changed from "mp_stacks_update_brick" to "mp_stacks_brick_ajax". Also a new function added called "mp_stacks_stack_ajax" for loading a full stack via ajax.
* Custom Shortcode button for "Add Link" is now removed with TinyMCE plugins now properly loading in MP Core 1.0.2.7.
* Added MP Stack class to style tags for brick called "mp-stack-css-STACKID".
* Added filter flag called "mp_stacks_execute_mp_brick_in_mp_stack" which allows you to check whether a Stack's output should be executed. For example, you might want to require a password before a Brick's/Stack's Content is executed. This fixes a bug where Brick styles and scripts were still enqueued even if the HTML for the Brick was not.
* Added filter flag called "mp_stacks_execute_content_types_in_brick" which allows to check whether a Brick's Content Types should be executed or if alternate output should be applied.
* Added filter to Bricks for alternate Content output if Content Types shouldn't be shown (IE for a Password Protected Brick) called "mp_stacks_brick_non_content_output".

= 1.0.4.3 = November 18, 2015
* Stack Templater now does a search for attachments in text areas.
* Make Double Click for Brick Editor only fire if logged in. This previously didn't open the Brick Editor - but it would try - resulting in a js error if double clicked and logged out.
* Make buttons in text areas have better default margins for mobile.
* Removed JS for Brick URLS on Page Load. This happens without that script and was causing some bugs.

= 1.0.4.2 = November 10, 2015
* Icon fix for isotope buttons. Should fix vertical alignment issue for isotope filter buttons.
* Add ability have to have Theme Bundles set up a Primary Menu when doing installation.
* Move the "mp_stacks_theme_bundle_install_menu_items" filter inside the check if it exists

= 1.0.4.1 = November 6, 2015
* Make Isotope layout_mode use the array key 'layout_mode' instead of a number because the order is different in firefox.

= 1.0.4.0 = November 6, 2015
* Fix: Remove all Isotope Layout Modes that aren't included by default with Isotope.

= 1.0.3.9 = November 5, 2015
* Make Grid Isotope Filter Buttons have gaps between them when stack on mobile.
* Added Layout Mode controls for Isotope Filtering.

= 1.0.3.8 = October 29, 2015
* Fix bug where class names get squished for Bricks from get_post_class change in 1.0.3.7

= 1.0.3.7 = October 29, 2015
* Bricks no longer use the get_post_class classes as they don't act like normal WordPress posts. This fixes compatibility issues with BuddyPress
* Make default content setup for Theme Bundles happen via ajax to prevent as many page refreshes - which was confusing for some users.

= 1.0.3.6 = October 26, 2015
* Upgraded Font Awesome to 4.4.0
* Added "mp_stacks_refresh_this_script_upon_brick_update" url arg handling so that js scripts that need to be refreshed when bricks are updated, can be.
* If adding a new brick, wait 1 second before refreshing so it is created prior to the refresh for sure.

= 1.0.3.5 = October 17, 2015
* Make sure content type values exist for Stack Templater before filtering them.
* For any Grid Isotope Filter Buttons, add an 'active' class when clicked. The class is called "mp-stacks-isotope-filter-button-active".

= 1.0.3.4 = October 6, 2015
* Default Stack template Preview image path changed to use plugins_url. This is more precise if the wp-content directory is moved by some web hosts.
* Hide all default notice types on Brick Editor as they are not needed or helpful there.
* Added ability to import/export individual Bricks.
* Fixed smooth scrolls for Brick URLs UPON page loads. This was broken since version 1.0.2.9
* We no longer enqueue the minimal admin js. This js added the minimal admin URL variable to all links on the Brick Editor. Not needed anymore with style updates.
* Only show the "Oops this stack doesn't exist" if the user is admin. It's not super helpful to front-end users.
* Made some minor changes to the Stack Templater functions to simplify.
* Changed "Extend" to "Expand: for add-ons link in Brick Editor.
* Added "No Stacks found" for if there's no Stacks
* Moved all Theme Bundle installation functions to new file dedicated to those functions from misc-functions to theme-bundle-installation.php
* Attempt to set_time_limit for Stack Template creation functions. This is a backup in case some smaller servers have timeout limitations for PHP. Will only work if safe_mode is off.

= 1.0.3.3 = September 28, 2015
* Bugfix: Widget CSS (and other non-shortcode added stacks) weren't properly getting their CSS output since 1.0.2.9 because of the scripts now being enqueued only-as-needed by the Content-Filters themselves. They are now re-output for any "leftover" stacks added to the page after the fact at priority 99 in wp_footer and shifted to the head.
* Security strengthen: Added intval() to mp_stack_id updates.

= 1.0.3.2 = September 27, 2015
* The mp_brick function has arg change now including the Stack ID which is optionally passed to the function. Additional arguments are now stored in an $args array in the 4th argument position. The Stack ID is now set using the Stack ID arg passed to the function. If using ajax, no Stack ID variable is passed and it is ignored or uses the $saved_stack_id.
* When a brick is created using the MP Stacks templater, use new Stack Id for the mp_stack_id meta key.
* Make sure that $current_screen->post_type isset before using it.
* CSS fix for grid text holders (was outputting 2 semi-colons when only 1 was needed).
* Add current Stack ID, in addition to the Brick ID, to the body when editing a brick using JS.

= 1.0.3.1 = September 24, 2015
* Added function to get grid posts_per_row ratio value called mp_stacks_grid_posts_per_row_percentage.
* Hide the "Add New Brick" button in the Brick Editor as it isn't needed in this location.
* Changed brick slugs to "brick" - was previously 'stack'.
* Make mp-stacks-queried-object-id value for ajax return "false" if "Stack Only" page as there is no Queried Object Id (parent page).
* Hide all admin notices in brick editor as they aren't needed or helpful here.
* Make sure that the Admin Bar and Admin sidebar are always hidden in Brick Editor - even if not open in iframe.
* Use correct table prefixes for wpdb calls using $wpdb->base_prefix in mp-stack.php.
* Enqueue dashicons on Stack Only pages so the paintbrush icon will display on the "Edit This Brick" button.

= 1.0.3.0 = September 22, 2015
* Added check for css_required before looping through it in the footer. Also moved function priority to 99 so it runs after Stacks that might have been after in the wp_footer.

= 1.0.2.9 = September 21, 2015
* Remove the "View Bricks" option if the MP Stacks + Developer Plugin is not active
* Load related bricks (to be seen in the "link" creator mp TinyMce) in admin upon "window.load".
* Add CSS shadow to all magnified lightbox popups
* Upon Brick Update, reload JUST the brick via ajax instead of reloading the entire page.
* Change removing hentry to Brick's ID  available in post_class filter instead of global $post->ID
* Make isotope only filter items within the current brick using the Brick's ID selector in the JS output
* Fire Stack CSS output at wp_head above wp_enqueue_scripts. This way, if any subsequent action needs to be called using wp_enqueue_scripts, it can (ie Google Fonts).
* Stack/Brick CSS is no longer loaded as a separate document. All Stack/Brick CSS is now minimized and placed in the <head>.
* CSS Style Tags now wrap around each Brick's CSS - so that CSS can be removed/replaced via ajax when Bricks are reloaded.
* The mp_brick function now gets the Stack ID from the Brick ID by using the mp_stack_order_STACKID meta field. Once the Brick gets its Stack ID, it saves it as postmeta under "mp_stack_id".
* When a new Brick is created, save the mp_stack_id there and then.
* Added action hook for new single stack page options called "mp_stacks_single_edit_page_options_table_bottom" which allows add-ons to output additional Single Stack options.
* Added action hook which fires when a Single Stack has been updated after the nonce has been verified to allow for the saving of additional options "mp_stacks_update_stack_options"
* Added filter hook for "/stack/" slug so people can change the slug to anything they wish. The filter is called "mp_stacks_stack_only_slug".
* Removed word-break:break-all for items in p tags. This was causing words to break to new lines when they shouldn't.
* MAJOR UPDATE: Brick Editor Loads Content Types via ajax now. All Add-On Metaboxes must be loaded through the "mp_brick_ajax_metabox" hook and will require MP Core 1.0.2.1 and MP Stacks 1.0.2.9
* MAJOR UPDATE: Instead of loading the whole page again when a Brick is updated, it now only re-loads that brick via ajax.
* Added "mp_stacks_centered_content_types" Filter Hook so add-ons can let MP Stacks know they work better as "Centered" and Brick's will auto set to be so.
* Lightbox padding change .mfp-iframe-scaler
* Brick Editor: Page contents are no longer hidden until it is all loaded.
* Brick Text Controls: Added Font/Text control for different device sizes: Desktop, Tablet, Mobile.
* Hide WooCommerce notices on Brick Editor as they are only needed in actual Dashboard.
* Brick a tags no longer have dispay:inline-block.
* Style updates to "Edit Brick" button. It now includes the paintbrush icon and better font style.
* Font Awesome Added as Utility Font.
* All Metaboxes in the Brick Editor now load-in on-demand using the Ajax load-in provided by MP Core 1.0.2.1.
* Add Stack now uses "mp_core_shortcode_setup" hook.
* New media-buttons.php file added for new "Add Link" option. MP Buttons install button functions are also moved here.
* New hook for metaboxes is now called mp_brick_ajax_metabox
* Time saving on Brick Saves: Page die() after Brick saved (if loaded in iframe).
* Added jQuery Namespaces to grid animations so they properly cancel the animations upon Ajax reload of a brick.
* Brick targets attr is now "mp_stacks_brick_target".
* Removed Action Hook: "mp_stacks_before_brick_css". Use the "mp_brick_css" Filter Hook and return required Brick CSS that way instead.

= 1.0.2.8 = May 17, 2015
* Fixed bug with mp_stacks_resize_complete firing
* Added proper meta keys for bg content meta array
* Fixed bug if Stack permalink was changed. When previewing the stack to duplicate it would have the wrong slug.
* Added filters for stack template meta. Filters added: mp_stacks_template_metafield_value, mp_stacks_template_extra_meta

= 1.0.2.7 = May 13, 2015
* Make the "Update Brick" button stick to the top of the Brick Editor.
* Added orderby options for Grids.

= 1.0.2.6 = May 1, 2015
* Bug Fix: used esc_url_raw instead of mp_core_add_query_arg for installing mp_core

= 1.0.2.5 = April 30, 2015
* Fixed "undefined var typenow" error when making a new page - error was introduced in 1.0.2.4
* Added word-break:break-all for items in p tags

= 1.0.2.4 = April 24, 2015
* Add all grid css to new, separate stylesheet
* Set grid image holders to 100% width instead of 100.2%
* Localized JS String: "Click here to get more Content Types" for easy translation.
* Added link to AddOns on right side of Brick Editor
* Moved all grid-based js to it's own js file which is only enqueued by the add-on using it.
* Moved all grid-based php to their own files.
* Changed default space above/below Brick Content from 20px to 50px.
* One-Click "MP Buttons" installer added above Brick Text areas to make it easy for users who need this plugin.
* Security Fix: All "add_query_arg" function changed to "mp_core_add_query_arg" to properly sanitize the URLs
* Security Fix: All "remove_query_arg" function changed to "mp_core_remove_query_arg" to properly sanitize the URLs
* Added default Isotope Filtering functions for Grids.

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
