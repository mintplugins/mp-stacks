<?php
/**
 * Wecome Page for Mp Stacks
 *
 * @link http://mintplugins.com/doc/
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

/**
 * MP_Stacks_Welcome Class
 *
 * A general class for About and Credits page.
 *
 * @since 1.4
 */
class MP_Stacks_Welcome {

	/**
	 * @var string The capability users should have to view the page
	 */
	public $minimum_capability = 'edit_pages';

	/**
	 * Get things started
	 *
	 * @since 1.4
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menus') );
		add_action( 'admin_head', array( $this, 'admin_head' ) );
		add_action( 'admin_init', array( $this, 'welcome'    ) );
	}

	/**
	 * Register the Dashboard Pages which are later hidden but these pages
	 * are used to render the Welcome and Credits pages.
	 *
	 * @access public
	 * @since 1.4
	 * @return void
	 */
	public function admin_menus() {
		// About Page
		add_menu_page(
			__('MP Stacks', 'mp_stacks'),
			__('MP Stacks', 'mp_stacks'),
			$this->minimum_capability,
			'mp-stacks-about',
			array( $this, 'about_screen' ),
			NULL,
			'26.9283746566547773382'
		);
		
		//Show link to manage stacks in submenu
		add_submenu_page( 
			'mp-stacks-about', 
			__('Manage Stacks', 'mp_stacks'), 
			__('Manage Stacks', 'mp_stacks'), 
			$this->minimum_capability, 
			'edit-tags.php?taxonomy=mp_stacks&post_type=mp_brick',
			'' 
		);

		// Getting Started Page
		add_dashboard_page(
			__( 'What\'s New With MP Stacks', 'mp_stacks' ),
			__( 'What\'s New With MP Stacks', 'mp_stacks' ),
			$this->minimum_capability,
			'mp-stacks-whats-new',
			array( $this, 'whats_new_screen' )
		);

		// Credits Page
		add_dashboard_page(
			__( 'The people that build MP Stacks', 'mp_stacks' ),
			__( 'The people that build MP Stacks', 'mp_stacks' ),
			$this->minimum_capability,
			'mp-stacks-credits',
			array( $this, 'credits_screen' )
		);
	}

	/**
	 * Hide Individual Dashboard Pages
	 *
	 * @access public
	 * @since 1.4
	 * @return void
	 */
	public function admin_head() {
		remove_submenu_page( 'index.php', 'mp-stacks-about' );
		remove_submenu_page( 'index.php', 'mp-stacks-whats-new' );
		remove_submenu_page( 'index.php', 'mp-stacks-credits' );

		// Badge for welcome page
		$badge_url = MP_STACKS_PLUGIN_URL . 'assets/icon-256x256.png';
		?>
		<style type="text/css" media="screen">
		/*<![CDATA[*/
		.mp-stacks-badge {
			padding-top: 225px;
			height: 62px;
			width: 185px;
			color: #666;
			font-weight: bold;
			font-size: 14px;
			text-align: center;
			text-shadow: 0 1px 0 rgba(255, 255, 255, 0.8);
			margin: 0 -5px;
			background: url('<?php echo $badge_url; ?>') no-repeat;
			background-size: 185px;
		}

		.about-wrap .mp-stacks-badge {
			position: absolute;
			top: 0;
			right: 0;
		}

		.edd-welcome-screenshots {
			float: right;
			margin-left: 10px!important;
		}
		/*]]>*/
		</style>
		<?php
	}

	/**
	 * Navigation tabs
	 *
	 * @access public
	 * @since 1.9
	 * @return void
	 */
	public function tabs() {
		$selected = isset( $_GET['page'] ) ? $_GET['page'] : 'mp-stacks-about';
		?>
		<h2 class="nav-tab-wrapper">
			<a class="nav-tab <?php echo $selected == 'mp-stacks-about' ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'mp-stacks-about' ), 'index.php' ) ) ); ?>">
				<?php _e( 'Getting Started', 'mp_stacks' ); ?>
			</a>
            <?php /*
            <a class="nav-tab <?php echo $selected == 'mp-stacks-whats-new' ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'mp-stacks-whats-new' ), 'index.php' ) ) ); ?>">
				<?php _e( "What's New", 'mp_stacks' ); ?>
			</a>
			
            <a class="nav-tab <?php echo $selected == 'mp-stacks-credits' ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'mp-stacks-credits' ), 'index.php' ) ) ); ?>">
				<?php _e( 'Credits', 'mp_stacks' ); ?>
			</a>
			
			*/ ?>
		</h2>
		<?php
	}

	/**
	 * Render About Screen
	 *
	 * @access public
	 * @since 1.4
	 * @return void
	 */
	public function about_screen() {
		list( $display_version ) = explode( '-', MP_STACKS_VERSION );
		?>
		<div class="wrap about-wrap">
			<h1><?php printf( __( 'Welcome to MP Stacks %s', 'mp_stacks' ), $display_version ); ?></h1>
			<div class="about-text"><?php printf( __( 'Thank you for creating with MP Stacks! An amazing way to build pages without coding.', 'mp_stacks' ), $display_version ); ?></div>
			<div class="mp-stacks-badge"><?php printf( __( 'Version %s', 'mp_stacks' ), $display_version ); ?></div>

			<?php $this->tabs(); ?>

			<div class="changelog">
				<h3><?php _e( 'How to make your first Stack', 'mp_stacks' );?></h3>

				<div class="getting-started-steps-section">

					<ol>
                    	<li><?php _e( 'Go to "Pages" > "Add New" on the left hand side of this page', 'mp_stacks' );?></li>
                        <li><?php _e( 'Click on the "Add Stack" Button just above the content area', 'mp_stacks' );?></li>
                    </ol>
				
                <h3><?php _e( 'How to add Bricks to your Stack', 'mp_stacks' );?></h3>
                	
                    <ol>
                    	<li><?php _e( 'View the page you made by clicking "View Page"', 'mp_stacks' );?></li>
                        <li><?php _e( 'When the page loads, there will be a red area that says "Add New Brick". Click on it and go!', 'mp_stacks' );?></li>
                    </ol>
                                     
                 <h3><?php _e( 'Video Tutoral: "Your First Stack"', 'mp_stacks' );?></h3>  
                 <p><?php _e( 'This video walks you through creating your first stack from start to finish:', 'mp_stacks' ); ?></p>
                 <iframe width="560" height="315" src="//www.youtube.com/embed/_xK0qg6NuBs?modestbranding=1&showinfo=0&rel=0" frameborder="0" allowfullscreen></iframe>
                 
                 <h3><?php _e( '1 Minute Run-Through Video:', 'mp_stacks' );?></h3>  
                 <iframe width="560" height="315" src="//www.youtube.com/embed/SGG6QCqC6AA?modestbranding=1&showinfo=0&rel=0" frameborder="0" allowfullscreen></iframe>
                 
                 <p>
                 <a class="button" href="http://mintplugins.com/support/mp-stacks-support/" target="_blank"><?php _e( 'Open this video in a new window', 'mp_stacks' );?></a>
                 </p> 
			
				</div>
			</div>

		</div>
		<?php
	}

	/**
	 * Render Getting Started Screen
	 *
	 * @access public
	 * @since 1.9
	 * @return void
	 */
	public function whats_new_screen() {
		list( $display_version ) = explode( '-', MP_STACKS_VERSION );
		?>
		<div class="wrap about-wrap">
			<h1><?php printf( __( 'Welcome to MP Stacks %s', 'mp_stacks' ), $display_version ); ?></h1>
			<div class="about-text"><?php printf( __( 'Thank you for creating with MP Stacks! An amazing way to build pages without coding.', 'mp_stacks' ), $display_version ); ?></div>
			<div class="mp-stacks-badge"><?php printf( __( 'Version %s', 'mp_stacks' ), $display_version ); ?></div>

			<?php $this->tabs(); ?>

			<p class="about-description"><?php _e( 'Use the tips below to get started using MP Stacks. You will be up and running in no time!', 'mp_stacks' ); ?></p>

			<div class="changelog">
				<h3><?php _e( 'Stacks Anywhere', 'mp_stacks' );?></h3>

				<div class="feature-section">

					<img src="<?php echo EDD_PLUGIN_URL . 'assets/images/screenshots/purchase-link.png'; ?>" class="mp_stacks-welcome-screenshots"/>

					<h4><?php _e( 'The <em>[mp_stack]</em> Short Code','mp_stacks' );?></h4>
					<p><?php _e( 'With easily accessible short codes to display Stacks, you can make any page/post amazing by just placing the shortcode on it.', 'mp_stacks' );?></p>

					<h4><?php _e( 'Buy Now Buttons', 'mp_stacks' );?></h4>
					<p><?php _e( 'Purchase buttons can behave as either Add to Cart or Buy Now buttons. With Buy Now buttons customers are taken straight to PayPal, giving them the most frictionless purchasing experience possible.', 'mp_stacks' );?></p>

				</div>
			</div>

			<div class="changelog">
				<h3><?php _e( 'Need Help?', 'mp_stacks' );?></h3>

				<div class="feature-section">

					<h4><?php _e( 'Phenomenal Support','mp_stacks' );?></h4>
					<p><?php _e( 'We do our best to provide the best support we can. If you encounter a problem or have a question, post a question at the <a href="https://mintplugins.com/support">support page</a>.', 'mp_stacks' );?></p>

					<h4><?php _e( 'Need Even Faster Support?', 'mp_stacks' );?></h4>
					<p><?php _e( 'Our <a href="https://mintplugins.com/support/pricing/">Priority Support</a> is for customers that need faster and/or more in-depth assistance.', 'mp_stacks' );?></p>

				</div>
			</div>

			<div class="changelog">
				<h3><?php _e( 'Stay Up to Date', 'mp_stacks' );?></h3>

				<div class="feature-section">

					<h4><?php _e( 'Get Notified of Extension Releases','mp_stacks' );?></h4>
					<p><?php _e( 'New extensions that make MP Stacks even more powerful are released nearly every single week. Subscribe to the newsletter to stay up to date with our latest releases. <a href="http://eepurl.com/kaerz" target="_blank">Signup now</a> to ensure you do not miss a release!', 'mp_stacks' );?></p>

					<h4><?php _e( 'Get Alerted About New Tutorials', 'mp_stacks' );?></h4>
					<p><?php _e( '<a href="http://eepurl.com/kaerz" target="_blank">Signup now</a> to hear about the latest tutorial releases that explain how to take MP Stacks further.', 'mp_stacks' );?></p>

				</div>
			</div>

			<div class="changelog">
				<h3><?php _e( 'Extensions for Everything', 'mp_stacks' );?></h3>

				<div class="feature-section">

					<h4><?php _e( 'Over 190 Extensions','mp_stacks' );?></h4>
					<p><?php _e( 'Add-on plugins are available that greatly extend the default functionality of MP Stacks. There are extensions for contact forms, Post Grids, Newsletter integrations, and many, many more.', 'mp_stacks' );?></p>

					<h4><?php _e( 'Visit the Extension Store', 'mp_stacks' );?></h4>
					<p><?php _e( '<a href="https://mintplugins.com/mp-stacks-addons" target="_blank">The Add-On Shop</a> has a list of all available extensions, including convenient category filters so you can find exactly what you are looking for.', 'mp_stacks' );?></p>

				</div>
			</div>

		</div>
		<?php
	}

	/**
	 * Render Credits Screen
	 *
	 * @access public
	 * @since 1.4
	 * @return void
	 */
	public function credits_screen() {
		list( $display_version ) = explode( '-', MP_STACKS_VERSION );
		?>
		<div class="wrap about-wrap">
			<h1><?php printf( __( 'Welcome to MP Stacks %s', 'mp_stacks' ), $display_version ); ?></h1>
			<div class="about-text"><?php printf( __( 'Thank you for creating with MP Stacks! An amazing way to build pages without coding.', 'mp_stacks' ), $display_version ); ?></div>
			<div class="mp_stacks-badge"><?php printf( __( 'Version %s', 'mp_stacks' ), $display_version ); ?></div>

			<?php $this->tabs(); ?>

			<p class="about-description"><?php _e( 'MP Stacks is created by a worldwide team of developers who aim to provide the best and most fun Website-Designing experience', 'mp_stacks' ); ?></p>

			<?php echo $this->contributors(); ?>
		</div>
		<?php
	}


	/**
	 * Render Contributors List
	 *
	 * @since 1.4
	 * @uses MP_Stacks_Welcome::get_contributors()
	 * @return string $contributor_list HTML formatted list of all the contributors for EDD
	 */
	public function contributors() {
		$contributors = $this->get_contributors();

		if ( empty( $contributors ) )
			return '';

		$contributor_list = '<ul class="wp-people-group">';

		foreach ( $contributors as $contributor ) {
			$contributor_list .= '<li class="wp-person">';
			$contributor_list .= sprintf( '<a href="%s" title="%s">',
				esc_url( 'https://github.com/' . $contributor->login ),
				esc_html( sprintf( __( 'View %s', 'mp_stacks' ), $contributor->login ) )
			);
			$contributor_list .= sprintf( '<img src="%s" width="64" height="64" class="gravatar" alt="%s" />', esc_url( $contributor->avatar_url ), esc_html( $contributor->login ) );
			$contributor_list .= '</a>';
			$contributor_list .= sprintf( '<a class="web" href="%s">%s</a>', esc_url( 'https://github.com/' . $contributor->login ), esc_html( $contributor->login ) );
			$contributor_list .= '</a>';
			$contributor_list .= '</li>';
		}

		$contributor_list .= '</ul>';

		return $contributor_list;
	}

	/**
	 * Retreive list of contributors from GitHub.
	 *
	 * @access public
	 * @since 1.4
	 * @return array $contributors List of contributors
	 */
	public function get_contributors() {
		$contributors = get_transient( 'mp_stacks_contributors' );

		if ( false !== $contributors )
			return $contributors;

		$response = wp_remote_get( 'https://api.github.com/repos/mintplugins/mp-stacks/contributors', array( 'sslverify' => false ) );

		if ( is_wp_error( $response ) || 200 != wp_remote_retrieve_response_code( $response ) )
			return array();

		$contributors = json_decode( wp_remote_retrieve_body( $response ) );

		if ( ! is_array( $contributors ) )
			return array();

		set_transient( 'mp_stacks_contributors', $contributors, 3600 );

		return $contributors;
	}

	/**
	 * Sends user to the Welcome page on first activation of EDD as well as each
	 * time EDD is upgraded to a new version
	 *
	 * @access public
	 * @since 1.4
	 * @global $mp_stacks_options Array of all the EDD Options
	 * @return void
	 */
	public function welcome() {
		global $mp_stacks_options;

		// Bail if no activation redirect
		if ( ! get_transient( '_mp_stacks_activation_redirect' ) )
			return;

		// Delete the redirect transient
		delete_transient( '_mp_stacks_activation_redirect' );

		// Bail if activating from network, or bulk
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) )
			return;

		$upgrade = get_option( 'mp_stacks_version_upgraded_from' );

		if( ! $upgrade ) { // First time install
			wp_safe_redirect( admin_url( 'index.php?page=mp-stacks-whats-new' ) ); exit;
		} else { // Update
			wp_safe_redirect( admin_url( 'index.php?page=mp-stacks-about' ) ); exit;
		}
	}
}
new MP_Stacks_Welcome();


/**
 * Add Custom Menu Link
 */
function mp_stacks_menu_button(){
	
	add_menu_page( __('MP Stacks', 'mp_stacks'), __('MP Stacks', 'mp_stacks'), 'manage_options', 'mp_stacks_menu_item', 'mp_stacks_menu_item_callback', NULL, 500 );
	
	//Show link to manage stacks in submenu
	add_submenu_page( 'mp_stacks_menu_item', __('Manage Stacks', 'mp_stacks'), __('Manage Stacks', 'mp_stacks'), 'manage_options', 'edit-tags.php?taxonomy=mp_stacks&post_type=mp_brick', '' );
	
}
//add_action( 'admin_menu', 'mp_stacks_menu_button' );

/**
 * WP Home Page for MP Stacks
 */
function mp_stacks_menu_item_callback(){
	
}