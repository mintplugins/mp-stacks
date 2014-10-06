<?php
/**
 * Creates a Page template for MP Stacks if none exists with the name "MP Stacks - 100% Width"
 *
 * @link https://github.com/HarriBellThomas/page-templater
 * @since 1.0.0
 *
 * @package     MP Stacks
 * @subpackage  Functions
 *
 * @copyright   Copyright (c) 2014, Mint Plugins
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author      Philip Johnston
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class MP_Stacks_Page_Templaer {

		/**
         * A Unique Identifier
         */
		 protected $plugin_slug;

        /**
         * A reference to an instance of this class.
         */
        private static $instance;

        /**
         * The array of templates that this plugin tracks.
         */
        protected $templates;


        /**
         * Returns an instance of this class. 
         */
        public static function get_instance() {

                if( null == self::$instance ) {
                        self::$instance = new MP_Stacks_Page_Templaer();
                } 

                return self::$instance;

        } 

        /**
         * Initializes the plugin by setting filters and administration functions.
         */
        private function __construct() {

                $this->templates = array();


                // Add a filter to the attributes metabox to inject template into the cache.
                add_filter(
					'page_attributes_dropdown_pages_args',
					 array( $this, 'register_project_templates' ) 
				);


                // Add a filter to the save post to inject out template into the page cache
                add_filter(
					'wp_insert_post_data', 
					array( $this, 'register_project_templates' ) 
				);


                // Add a filter to the template include to determine if the page has our 
				// template assigned and return it's path
                add_filter(
					'template_include', 
					array( $this, 'view_project_template') 
				);


                // Add your templates to this array.
                $this->templates = array(
                        'mp-stacks-page-template.php'     => 'Optimize page for MP Stacks',
                );
				
        } 


        /**
         * Adds our template to the pages cache in order to trick WordPress
         * into thinking the template file exists where it doens't really exist.
         *
         */

        public function register_project_templates( $atts ) {

		// Get theme object
		$theme = wp_get_theme();

                // Create the key used for the themes cache
                $cache_key = 'page_templates-' . md5( $theme->get_theme_root() . '/' . $theme->get_stylesheet() );
					
                // Retrieve existing page templates
				$templates = $theme->get_page_templates();
				
				//Default for stack_template_exists var
				$stack_template_exists = false;
				
				//If this theme does not have a page template for MP Stacks already
				foreach( $templates as $template_name ){
					
					if ( strpos( 'Stack', $template_name) !== false ){
						$stack_template_exists = true;
					}
					
				}
				
				//Check the title of the default page template as well - This filter: https://core.trac.wordpress.org/ticket/27178
				$default_page_template_title = apply_filters( 'default_page_template_title', __('Default Template') );
				
				if ( strpos( $default_page_template_title, 'Stack' ) !== false ){
					$stack_template_exists = true;
				}
				
				if ( !$stack_template_exists ){
				
					// Add our template(s) to the list of existing templates by merging the arrays
					$templates = array_merge( $templates, $this->templates );
	
					// Replace existing value in cache
					wp_cache_set( $cache_key, $templates, 'themes', 1800 );
				}

                return $atts;

        } 

        /**
         * Checks if the template is assigned to the page
         */
        public function view_project_template( $template ) {

                global $post;

                if (!isset($this->templates[get_post_meta( 
					$post->ID, '_wp_page_template', true 
				)] ) ) {
					
                        return $template;
						
                } 

                $file = plugin_dir_path(__FILE__). get_post_meta( 
					$post->ID, '_wp_page_template', true 
				);
				
                // Just to be safe, we check if the file exist first
                if( file_exists( $file ) ) {
                        return $file;
                } 
				else { echo $file; }

                return $template;

        } 


} 

add_action( 'plugins_loaded', array( 'MP_Stacks_Page_Templaer', 'get_instance' ) );