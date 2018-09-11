<?php
/**
 * Largo functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Largo
 */

if ( ! function_exists( 'largo_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function largo_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Largo, use a find and replace
	 * to change 'largo' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'largo', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'menu-1' => esc_html__( 'Primary', 'largo' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'largo_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );
}
endif;
add_action( 'after_setup_theme', 'largo_setup' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function largo_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'largo' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'largo' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	$homepage_sections = is_customize_preview() ? 5 : get_theme_mod( 'largo_homepage_layout_settings', 5 );
	if ( $homepage_sections || is_customize_preview() ) {
		$count = 1;
		while ( $count <= $homepage_sections ) {
			$columns = is_customize_preview() ? 4 : get_theme_mod( "largo_homepage_layout_settings_$count", 4 );
			$column_count = 1;
			while ( $column_count <= $columns ) {
				register_sidebar( array(
					'name'          => esc_html__( "Homepage Section $count Column $column_count", 'largo' ),
					'id'            => "section-$count-column-$column_count",
					'description'   => esc_html__( 'Add widgets here.', 'largo' ),
					'before_widget' => '<section id="%1$s" class="widget %2$s">',
					'after_widget'  => '</section>',
					'before_title'  => '<h2 class="widget-title">',
					'after_title'   => '</h2>',
				) );
				$column_count++;
			}
			$count++;
		}
	}

	register_widget( 'largo_author_widget' );
	register_widget( 'largo_site_blurb' );
}
add_action( 'widgets_init', 'largo_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function largo_scripts() {
	wp_enqueue_style( 'largo-style', get_stylesheet_uri() );

	wp_enqueue_script( 'largo-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'largo-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_customize_preview() ) {
		wp_enqueue_style( 'largo-customizer-preview-style', get_template_directory_uri() . '/customizer.css' );
		wp_enqueue_script( 'jquery-scrollto', get_theme_file_uri( '/js/jquery.scrollTo.js' ), array( 'jquery' ), '2.1.2', true );
	}
}
add_action( 'wp_enqueue_scripts', 'largo_scripts' );

/**
 * Print CSS set in the customizer.
 */
function largo_customizer_css() {
	$site_width = intval( get_theme_mod( 'layout_width' ) ) > 0 ? get_theme_mod( 'layout_width' ) : 1600; // Set fallback value for site width if unset - also defined in customize-preview.js
	?>
	<style type="text/css" id="largo-customizer-styles" <?php if ( is_customize_preview() ) { echo 'data-sitewidth="' . $site_width . '"'; } ?>>
		#page {
			width: <?php echo absint( $site_width ); ?>px;
			max-width: 90%;
			margin: 0 auto;
		}
	</style>
	<?php
}
add_action( 'wp_head', 'largo_customizer_css' );

/**
 * Require_once all Largo files
 */
$requires = array(
	'/inc/custom-header.php', // Custom Header feature
	'/inc/template-tags.php', // Custom template tags for this theme
	'/inc/extras.php', // Custom functions that act independently of the theme templates
	'/inc/customizer/customizer.php', // Customizer additions
	'/inc/jetpack.php', // Jetpack compatibility file
	'/inc/settings.php', // Additional site settings
	'/inc/taxonomy-archive-sidebars.php', // Custom sidebars for taxonomy archives
	'/inc/update.php', // Largo Update stuff
	'/inc/widgets/author-bio.php',
	'/inc/widgets/site-blurb.php',
);
foreach ( $requires as $require_once ) {
	require_once( get_template_directory() . $require_once );
}
