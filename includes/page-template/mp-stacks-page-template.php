<?php 
/**
 * This is the default Page Template for MP Stacks which users can choose from.
 *
 * @link http://mintplugins.com/doc/
 * @since 1.0.0
 *
 * @package     MP Stacks
 * @subpackage  Page Templates
 *
 * @copyright   Copyright (c) 2014, Mint Plugins
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author      Philip Johnston
 */
 
get_header(); 

while ( have_posts() ) : the_post();  ?>
   
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'mp-stacks-default-page-template' ); ?>>

        <div class="entry-content">        
            <?php the_content(); ?>
        </div><!-- .entry-content -->
           
    </article><!-- #post-## --> 

<?php endwhile; // end of the loop.

get_footer(); 