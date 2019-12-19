<?php
/**
 * Theme setup
 */
add_action('after_setup_theme', function () {

    /**
     * Make theme available for translation.
     */
    load_theme_textdomain( 'btpc', get_template_directory() . '/languages' );

    /**
     * remove junk from head
     */
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wp_shortlink_wp_head', 10);
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');
    remove_action('wp_head', 'rest_output_link_wp_head', 10);
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');     
    add_filter('use_default_gallery_style', '__return_false');
    add_filter('emoji_svg_url', '__return_false');
    add_filter('show_recent_comments_widget_style', '__return_false');  
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'index_rel_link');
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'start_post_rel_link', 10, 0);
    remove_action('wp_head', 'parent_post_rel_link', 10, 0);

    /**
     * Enable automatic feed link
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#feed-links
     */
    add_theme_support( 'automatic-feed-links' );

    /**
     * Enable plugins to manage the document title
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Register navigation menus
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'header-menu' => __('Header Menu', 'btpc'),
        'footer-menu' => __('Footer Menu', 'btpc'),
        'social-menu' => __('Social Links Menu', 'btpc'),
    ]);

    /**
     * Enable post thumbnails
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size( 1568, 9999 );


    /**
     * Enable support for Post Thumbnails on posts and pages.
     */ 
    // add_image_size( 'company-logo', 500, 300 );
    // add_image_size( 'blog-post-small', 82, 82 );
    // add_image_size( 'core-user', 737, 1142, true);
    // add_image_size( 'core-user-thumnail', 300, 465, true);
    // add_image_size( 'blog-post-featured', 737, 484 );

    /**
     * Enable HTML5 markup support
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);

    /**
     * Enable selective refresh for widgets in customizer
     * @link https://developer.wordpress.org/themes/advanced-topics/customizer-api/#theme-support-in-sidebars
     */
    add_theme_support('customize-selective-refresh-widgets');


    /**
     * post formats
     */
    add_theme_support( 'post-formats', ['aside', 'gallery', 'link', 'image', 'quote', 'video', 'audio'] );

    /**
     * Add support for core custom logo.
     *
     * @link https://codex.wordpress.org/Theme_Logo
     */
    add_theme_support('custom-logo', [
            'height'      => 250,
            'width'       => 250,
            'flex-width'  => false,
            'flex-height' => false,
            'header-text' => ['site-title', 'site-description'],
    ]);

    /**
     * Add support for full and wide align images.
     * 
     */
    add_theme_support( 'align-wide' );

    /**
     * Add support for responsive embedded content.
     */
    add_theme_support( 'responsive-embeds' );

    /**
     * Use main stylesheet for visual editor
     * @see resources/assets/styles/layouts/_tinymce.scss
     */
    add_editor_style(get_template_directory_uri() . '/style.css');
}, 20);

/**
 * Theme assets
 */
add_action('wp_enqueue_scripts', function () {

    /*** Enqueue styles. ***/
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css?family=Roboto:400,500,700,900', false, false, 'all');
    wp_enqueue_style('plugins', get_theme_file_uri( '/css/plugins.css' ), false, wp_get_theme()->get( 'Version' ),'all');
    wp_enqueue_style('styles', get_stylesheet_uri(), false, wp_get_theme()->get( 'Version' ));

    /*** Enqueue scripts. ***/
    wp_enqueue_script('plugins', get_theme_file_uri( '/js/plugins.js' ), ['jquery'], wp_get_theme()->get( 'Version' ), true);
    wp_enqueue_script('scripts', get_theme_file_uri( '/js/scripts.js' ), ['jquery'], wp_get_theme()->get( 'Version' ), true);

}, 100);



/**
 * Autoloader Classes 
 */
spl_autoload_register( function ( $class_name ) {

  /**
   * If the class being requested does not start with our prefix,
   * we know it's not one in our project
   */
  if ( empty( $class_name ) ) {
    return;
  }

  // Compile our path from the current location
  $classes = dirname( __FILE__ ) . '/classes/'. $class_name .'.php';  

  // If a file is found
  if ( file_exists( $classes ) ) {
    // Then load it up!
    require( $classes );
  }

});


/**
 * Register sidebars
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>'
    ];
    register_sidebar([
        'name'          => __('Primary', 'btpc'),
        'id'            => 'sidebar-primary'
    ] + $config);
    register_sidebar([
        'name'          => __('Footer', 'btpc'),
        'id'            => 'sidebar-footer'
    ] + $config);
});

/**
 * Add ACF Options page
 */
if(function_exists('acf_add_options_page')) {
    // $option_page = acf_add_options_page(array(
    //   'page_title'  => 'General Settings',
    //   'menu_title'  => 'Theme Settings',
    //   'menu_slug'   => 'theme-general-settings',
    //   'capability'  => 'edit_posts',
    //   'redirect'    => true,
    //   'position' => 61,
    //   'icon_url'    => 'dashicons-hammer'
    // ));

    // $option_page = acf_add_options_sub_page(array(
    //   'page_title'  => 'Header Setup',
    //   'menu_title'  => 'Header Setup',
    //   'parent_slug' => 'theme-general-settings',
    // ));

    // $option_page = acf_add_options_sub_page(array(
    //   'page_title'  => 'Footer Setup',
    //   'menu_title'  => 'Footer Setup',
    //   'parent_slug' => 'theme-general-settings',
    // ));
    acf_add_options_page();
}

/**
 * custom excerpt length
 */
add_filter('excerpt_length', function($length) {
    return 20;
});

//add SVG to allowed file uploads
function my_custom_mime_types( $mimes ) {
    // New allowed mime types.
    $mimes['svg'] = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    $mimes['doc'] = 'application/msword';
     
    // Optional. Remove a mime type.
    unset( $mimes['exe'] );
     
    return $mimes;
}
add_filter( 'upload_mimes', 'my_custom_mime_types' );

/////////////////////////////////////////////////////////////////////////////////////////////////////
///

/**
 * Admin Styles & Scripts
 */
add_action('admin_enqueue_scripts', function () {
    wp_enqueue_style('icon-fonts', get_theme_file_uri( '/css/icon_fonts.css' ), false, wp_get_theme()->get( 'Version' ),'all');
}, 100);

/**
 * Remove Admin Toolbar
 */
show_admin_bar( false );
add_filter('show_admin_bar' , '__return_false');
add_theme_support('admin-bar', ['callback' => '__return_false']);

/////////////////////////////////////////////////////////////////////////////////////////////////////

/*** Gravity form user role */
function gforms_editor_access() {
    $role = get_role( 'editor' );
    $role->add_cap( 'gform_full_access' );
}
add_action( 'after_switch_theme', 'gforms_editor_access' );

/**
 * Clean up wp_nav_menu_args
 *
 * Remove the container
 * Remove the id="" on nav menu items
 */

add_filter('wp_nav_menu_args', function ($args = '') {
  $nav_menu_args = [];
  $nav_menu_args['container'] = false;

  if (!$args['items_wrap']) {
    $nav_menu_args['items_wrap'] = '<ul class="%2$s">%3$s </ul>';
  }

  if (!$args['walker']) {
    $nav_menu_args['walker'] = new bs4Navwalker();
  }

  return array_merge($args, $nav_menu_args);
});

// Remove ID from menu li items
add_filter('nav_menu_item_id', '__return_null');

/*** Reorder dashboard menu */
function reorder_admin_menu( $__return_true ) {
    return array(
        'index.php',                 // Dashboard
        'separator1',                // --Space--
        'acf-options',               // ACF Theme Settings
        'edit.php?post_type=page',   // Pages 
        'edit.php',                  // Posts
        'separator2',                // --Space--
        'gf_edit_forms',             // Gravity Forms
        'upload.php',                // Media
        'wpseo_dashboard',           // Yoasta
        'gadash_settings',           // Google Analytics
        'themes.php',                // Appearance
        'edit-comments.php',         // Comments 
        'users.php',                 // Users
        'tools.php',                 // Tools
        'options-general.php',       // Settings
        'plugins.php',               // Plugins
    );
}
add_filter( 'custom_menu_order', 'reorder_admin_menu' );
add_filter( 'menu_order', 'reorder_admin_menu' );

/*** Remove dashboard menu */
function remove_admin_menus() {
    remove_menu_page( 'edit.php' );              // Comments
    remove_menu_page( 'edit-comments.php' );              // Comments
    remove_menu_page( 'tools.php' );                      // Tools
    remove_menu_page( 'plugins.php' );                    // Plugings
    remove_menu_page( 'sharethis-general' );          // share this
    remove_menu_page( 'edit.php?post_type=acf-field-group' ); // Custom Field 
    remove_menu_page( 'pods' );                         // Pods Custom post type
}
//add_action( 'admin_menu', 'remove_admin_menus', 999);



function hidedev_remove_menu_pages() {
    //remove_menu_page( 'edit.php?post_type=acf-field-group' ); //ACF
}
//add_action( 'admin_init', 'hidedev_remove_menu_pages' );

/*** Limit blog text */
function limit_text($text, $limit=30) {
    $array = explode( "\n", wordwrap( $text, $limit));
    return $array['0'];
}

/*** Customized header title */
function get_customized_title($title){

    if (is_home()) {
        if ($home = get_option('page_for_posts', true)) {
            return get_the_title($home);
        }
        return __('Latest Posts', 'btpc');
    }
    if (is_category() || is_author() || is_archive()) {
        return get_the_archive_title();
    }
    if (is_search()) {
        return sprintf(__('Search Results for %s', 'btpc'),  '<strong>' . get_search_query() . '</strong>');
    }
    if (is_404()) {
        return __('Not Found', 'btpc');
    }
    return get_the_title();
}



/*** Basic Page setup */
function basic_content( $group_data ){    
    echo '<div class="basic-content-wrapper">';
        echo $group_data['contnet'];
    echo '</div>';
    
    $add_button = $group_data['add_button'];
    if( $add_button ):
        $button_setting   = $group_data['button_setting'];
        $external_button  = $button_setting['external_button'];
        $link       = '';
        if( $external_button )
        { 
            $link = $button_setting['url']; 
        } 
        else 
        { 
            $link = $button_setting['page_link']; 
        }
    ?>
    <a href="<?php echo $link; ?>" target="<?php echo ( $external_button ) ? '_blank' : ''; ?>" class="btn text-uppercase">
      <?php echo $button_setting['title']; ?>       
    </a>

  <?php
    endif; // button setting

    //check Form
    $add_form     = $group_data['add_form'];
    echo '<div class="basic-form">';
        if( $add_form ):
            $form_title = $group_data['form_title'];
            $form_id  = $group_data['select_form'];
            echo ( $form_title )?'<h3>'.$form_title.'</h3>':'';
            echo do_shortcode('[gravityform id='.$form_id.' title=false description=false ajax=true tabindex=49]');
        endif;
    echo '</div>';
}

/*** Breadcrumb */
function btpc_breadcrumb() {

    $delimiter = '<span class="angle-right">/</span>';
    $home = 'Home'; 
    $before = '<span class="current-page">'; 
    $after = '</span>'; 

    $events = get_post_type_object('tribe_events');
   
    if ( !is_front_page() ) {
   
    echo '<nav class="breadcrumb">';
   
    global $post;
    $homeLink = get_bloginfo('url');
    $blogTitle = get_the_title( get_option( 'page_for_posts' ) );
    $blogLink = get_permalink( get_option( 'page_for_posts' ) );
    echo '<a href="' . home_url() . '">' . $home . '</a> ' . $delimiter . ' ';
   
    if ( is_home() ) {

        echo $before . ' ' . $blogTitle . ' ' . $after;

    } elseif ( is_category() ) {

        global $wp_query;
        $cat_obj = $wp_query->get_queried_object();
        $thisCat = $cat_obj->term_id;
        $thisCat = get_category($thisCat);
        $parentCat = get_category($thisCat->parent);
        if ($thisCat->parent != 0) echo(get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' '));
        echo '<a href="' . $blogLink . '">'.$blogTitle.'</a> ' . $delimiter . ' ';
        echo $before . single_cat_title('', false) . $after;

    } elseif ( is_day() ) {
        echo '<a href="' . $blogLink . '">'.$blogTitle.'</a> ' . $delimiter . ' ';
        echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
        echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
        echo $before . get_the_time('d') . $after;
   
    } elseif ( is_month() ) {

        echo '<a href="' . $blogLink . '">'.$blogTitle.'</a> ' . $delimiter . ' ';
        echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
        echo $before . get_the_time('F') . $after;
   
    } elseif ( is_year() ) {

        echo '<a href="' . $blogLink . '">'.$blogTitle.'</a> ' . $delimiter . ' ';
        echo $before . get_the_time('Y') . $after;
   
    } else if ( is_author() ) {
        global $author;
        $userdata = get_userdata( $author );
            
        echo '<a href="' . $blogLink . '">'.$blogTitle.'</a> ' . $delimiter . ' ';
        echo $before . ' ' . $userdata->display_name . ' ' . $after;
           
      } elseif ( is_single() && !is_attachment() ) {

        global $wp_query;
        $cat_obj = $wp_query->get_queried_object();

        if ($cat_obj->post_type === 'tribe_events') {

            echo '<a href="' . $homeLink . '/' . $events->rewrite['slug'] . '/">'.$events->rewrite['slug'].'</a> ' . $delimiter . ' ';
            echo $before . $cat_obj->post_title . $after;

        } elseif ( get_post_type() != 'post' ) {

            $post_type = get_post_type_object(get_post_type());
            $slug = $post_type->rewrite;
            echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ' . $delimiter . ' ';
            echo $before . get_the_title() . $after;

        } else {

            $cat = get_the_category(); $cat = $cat[0];
            echo '<a href="' . $blogLink . '">'.$blogTitle.'</a> ' . $delimiter . ' ';
            //echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
            echo $before . get_the_title() . $after;

        }
    } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() && !is_search() ) {
        $post_type = get_post_type_object(get_post_type());
    
        if ($events) {
            echo $before . $events->rewrite['slug'] . $after;
        } else {
            echo $before . $post_type->labels->singular_name . $after;
        }
   
    } elseif ( is_attachment() ) {

        $parent = get_post($post->post_parent);
        $cat = get_the_category($parent->ID); $cat = $cat[0];
        echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
        echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
        echo $before . get_the_title() . $after;
   
    } elseif ( is_page() && !$post->post_parent ) {

        echo $before . get_the_title() . $after;

    } elseif ( is_page() && $post->post_parent ) {

        $parent_id = $post->post_parent;
        $breadcrumbs = array();
        while ($parent_id) {
            $page = get_page($parent_id);
            $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
            $parent_id = $page->post_parent;
        }

        $breadcrumbs = array_reverse($breadcrumbs);
        foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
            echo $before . get_the_title() . $after;
   
    } elseif ( is_search() ) {

        echo $before . ' ' . $blogTitle . ' ' . $after;
        echo $before . 'Search Results for: "' . get_search_query() . '"' . $after;

    } elseif ( is_tag() ) {
        echo '<a href="' . $blogLink . '">'.$blogTitle.'</a> ' . $delimiter . ' ';
        echo $before . 'Posts with the tag "' . single_tag_title('', false) . '"' . $after;

    } elseif ( is_tag() ) {

        echo '<a href="' . $blogLink . '">'.$blogTitle.'</a> ' . $delimiter . ' ';
        echo $before . 'Posts with the tag "' . single_tag_title('', false) . '"' . $after;

    } elseif ( is_404() ) {

        echo $before . 'Error 404' . $after;
    }
   
    if ( get_query_var('paged') ) {
        if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
          echo ': ' . __('Page') . ' ' . get_query_var('paged');
        if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
    }
    echo '</nav>';
    } 
}

/////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * GRAVITY FORMS STYLE SUBMIT BUTTON
 * @param  [type] $button [description]
 * @param  [type] $form   [description]
 * @return [type]         [description]
 */
add_filter("gform_submit_button", function($button, $form) {

    return "<button type='submit' id='gform_submit_button_{$form["id"]}' class='btn btn-primary py-3 btn-block' onclick='if(window[&quot;gf_submitting_{$form["id"]}&quot;]){return false;}  window[&quot;gf_submitting_{$form["id"]}&quot;]=true;  ' onkeypress='if( event.keyCode == 13 ){ if(window[&quot;gf_submitting_{$form["id"]}&quot;]){return false;} window[&quot;gf_submitting_{$form["id"]}&quot;]=true;  jQuery(&quot;#gform_{$form["id"]}&quot;).trigger(&quot;submit&quot;,[true]); }'>{$form['button']['text']}</button>";
}, 10, 2);

/**
 * GRAVITY FORMS
 */

add_filter( 'gform_enable_password_field', '__return_true' );

/**
 * FORM GRAVITY SIGN UP - ID:2
 */
add_filter( 'gform_submit_button_2', function ( $button, $form ) {

    $html = "<div class='text-center py-3'> - or -</div>      
            <a href='" . site_url() . "' class='btn btn-secondary py-3 btn-block'>Sign In</a>";
 
    return $button .= $html;
}, 10, 2 );

/*** Get all page id */ 
function getPageID() {
    global $post;
    $postid = $post->ID;
    if(is_home() && get_option('page_for_posts')) {
        $postid = get_option('page_for_posts');
    }else {
        $postid = get_post_type('service');
    }
    return $postid;
}

