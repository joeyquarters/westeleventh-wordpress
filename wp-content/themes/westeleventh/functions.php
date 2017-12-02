<?php

if ( ! class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function() {
		echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php') ) . '</a></p></div>';
	});

	add_filter('template_include', function($template) {
		return get_stylesheet_directory() . '/static/no-timber.html';
	});

	return;
}

// Load theme lib files
$files = array('lib/WestEleventh/Episode.php');
foreach ($files as $file) {
	require_once(trailingslashit(get_template_directory()) . $file);
}

Timber::$dirname = array('templates', 'views');

class WestEleventhSite extends TimberSite {

	function __construct() {
		add_theme_support( 'post-formats' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'menus' );
		add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
		add_filter( 'timber_context', array( $this, 'add_to_context' ) );
		// add_filter( 'get_twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		/* Custom RSS Feed */
		remove_all_actions( 'do_feed_rss2' );
		add_action( 'do_feed_rss2', array( $this, 'route_rss_feeds' ), 10, 1 );
		parent::__construct();
	}

	/**
	 * Register post types for the theme
	 * @return void
	 * @since  0.0.1
	 */
	function register_post_types() {
		// Episode (podcast)
		$episode_labels = array(
			'name'                  => _x( 'Episodes', 'Post Type General Name', 'westeleventh' ),
			'singular_name'         => _x( 'Episode', 'Post Type Singular Name', 'westeleventh' ),
			'menu_name'             => __( 'Episodes', 'westeleventh' ),
			'name_admin_bar'        => __( 'Episode', 'westeleventh' ),
			'archives'              => __( 'Episode Archives', 'westeleventh' ),
			'attributes'            => __( 'Episode Attributes', 'westeleventh' ),
			'parent_item_colon'     => __( 'Parent Episode:', 'westeleventh' ),
			'all_items'             => __( 'All Episodes', 'westeleventh' ),
			'add_new_item'          => __( 'Add New Episode', 'westeleventh' ),
			'add_new'               => __( 'Add New', 'westeleventh' ),
			'new_item'              => __( 'New Episode', 'westeleventh' ),
			'edit_item'             => __( 'Edit Episode', 'westeleventh' ),
			'update_item'           => __( 'Update Episode', 'westeleventh' ),
			'view_item'             => __( 'View Episode', 'westeleventh' ),
			'view_items'            => __( 'View Episodes', 'westeleventh' ),
			'search_items'          => __( 'Search Episode', 'westeleventh' ),
			'not_found'             => __( 'Not found', 'westeleventh' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'westeleventh' ),
			'featured_image'        => __( 'Featured Image', 'westeleventh' ),
			'set_featured_image'    => __( 'Set featured image', 'westeleventh' ),
			'remove_featured_image' => __( 'Remove featured image', 'westeleventh' ),
			'use_featured_image'    => __( 'Use as featured image', 'westeleventh' ),
			'insert_into_item'      => __( 'Insert into episode', 'westeleventh' ),
			'uploaded_to_this_item' => __( 'Uploaded to this episode', 'westeleventh' ),
			'items_list'            => __( 'Episodes list', 'westeleventh' ),
			'items_list_navigation' => __( 'Episodes list navigation', 'westeleventh' ),
			'filter_items_list'     => __( 'Filter episodes list', 'westeleventh' ),
		);
		$episode_args = array(
			'label'                 => __( 'Episode', 'westeleventh' ),
			'description'           => __( 'Podcast episodes', 'westeleventh' ),
			'labels'                => $episode_labels,
			'supports'              => array( 'title', 'editor', 'thumbnail', ),
			'taxonomies'            => array( 'show', 'category', 'post_tag' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-microphone',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'page',
			'show_in_rest'          => true,
			'rest_controller_class' => 'WP_REST_Episodes_Controller',
		);
		register_post_type( 'episode', $episode_args );
	}

	/**
	 * Register taxonomies for the theme
	 * @return void
	 * @since  0.0.1
	 */
	function register_taxonomies() {
		// Show
		$show_labels = array(
			'name'                       => _x( 'Podcasts', 'Taxonomy General Name', 'westeleventh' ),
			'singular_name'              => _x( 'Podcast', 'Taxonomy Singular Name', 'westeleventh' ),
			'menu_name'                  => __( 'Podcast', 'westeleventh' ),
			'all_items'                  => __( 'All Podcasts', 'westeleventh' ),
			'parent_item'                => __( 'Parent Podcast', 'westeleventh' ),
			'parent_item_colon'          => __( 'Parent Podcast:', 'westeleventh' ),
			'new_item_name'              => __( 'New Podcast Name', 'westeleventh' ),
			'add_new_item'               => __( 'Add New Podcast', 'westeleventh' ),
			'edit_item'                  => __( 'Edit Podcast', 'westeleventh' ),
			'update_item'                => __( 'Update Podcast', 'westeleventh' ),
			'view_item'                  => __( 'View Podcast', 'westeleventh' ),
			'separate_items_with_commas' => __( 'Separate Podcasts with commas', 'westeleventh' ),
			'add_or_remove_items'        => __( 'Add or remove Podcasts', 'westeleventh' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'westeleventh' ),
			'popular_items'              => __( 'Popular Podcasts', 'westeleventh' ),
			'search_items'               => __( 'Search Podcasts', 'westeleventh' ),
			'not_found'                  => __( 'Not Found', 'westeleventh' ),
			'no_terms'                   => __( 'No Podcasts', 'westeleventh' ),
			'items_list'                 => __( 'Podcasts list', 'westeleventh' ),
			'items_list_navigation'      => __( 'Podcasts list navigation', 'westeleventh' ),
		);
		$show_args = array(
			'labels'                     => $show_labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => false,
			'show_in_rest'               => true,
			'rest_controller_class'      => 'WP_REST_Shows_Controller',
		);
		register_taxonomy( 'podcast', array( 'episode' ), $show_args );
	}

	/**
	 * Routes RSS feed queries to their respective templates
	 */
	function route_rss_feeds() {
		if (get_query_var('podcast')) {
			get_template_part('feed', 'podcast');
		} else {
			get_template_part('feed', 'rss2');
		}
	}

	// SAMPLE FUNCTIONS FOR TIMBER/TWIG

	function add_to_context( $context ) {
		$context['menu'] = new TimberMenu();
		$context['site'] = $this;
		return $context;
	}

	function myfoo( $text ) {
		$text .= ' bar!';
		return $text;
	}

	function add_to_twig( $twig ) {
		/* this is where you can add your own functions to twig */
		$twig->addExtension( new Twig_Extension_StringLoader() );
		$twig->addFilter('myfoo', new Twig_SimpleFilter('myfoo', array($this, 'myfoo')));
		return $twig;
	}

}

new WestEleventhSite();
