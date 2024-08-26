<?php
class BW_NUTS {

    function __construct() {

        //Admin Side
        if( is_admin() )
            require_once __DIR__ .'/inc/admin.php';

        //Styling & Scripts
        add_action('wp_enqueue_scripts',array($this,'scripts'));

        //Disable Emojies Scripts ( Performance Sake )
        add_action('init',array($this,'cancel_emoji'));

        // Automatic theme updates from the GitHub repository
        add_filter('pre_set_site_transient_update_themes',array($this,'update_theme'),100);
    
        //Register Widgets
        add_action('widgets_init',array($this,'widgets'));

        //Theme Supports
        add_action('after_setup_theme',array($this,'supports'));

        //Navigation Menus
        add_action('init',array($this,'nav_menus'));
    
        //Inlcude WooCommerce Functionality
        add_action('init',function() {

            if( class_exists('woocommerce') ) {

                require_once __DIR__ .'/inc/woocommerce/wc.php';
            }
        });
    }

    function nav_menus() {

        register_nav_menus( array(
            'primary' => 'الرئيسية'
        ) );
    }

    function supports() {

        add_theme_support('custom-logo');

        add_theme_support('woocommerce');
    }

    function widgets() {

        //Header Widget
        register_sidebar(array(
            'id' => 'header',
            'name' => 'Header Widget Area',
            'before_widget' => '',
            'after_widget' => ''
        ));

        //Footer Widgets Placeholders
        for( $w = 1;$w <= 4;$w++ ) {
            register_sidebar( array(
                'id'    => 'footer-'. $w,
                'name'  => 'Footer '. $w,
                'before_widget' => "<section class = 'bw-widget-container' id = 'bw-footer-widget-". $w ."'>",
                "after_widget" => "</section>"
            ) );
        }
    }

    function update_theme($data) {
        // Theme information
        $theme   = get_stylesheet(); // Folder name of the current theme
        $current = wp_get_theme()->get('Version'); // Get the version of the current theme
        // GitHub information
        $user = 'loay-ali'; // The GitHub username hosting the repository
        $repo = 'bw-nuts'; // Repository name as it appears in the URL
        // Get the latest release tag from the repository. The User-Agent header must be sent, as per
        // GitHub's API documentation: https://developer.github.com/v3/#user-agent-required
        $file = @json_decode(@file_get_contents('https://api.github.com/repos/'.$user.'/'.$repo.'/releases/latest', false,
            stream_context_create(['http' => ['header' => "User-Agent: ".$user."\r\n"]])
        ));
        if($file) {
            $update = filter_var($file->tag_name, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            var_dump($update);
            // Only return a response if the new version number is higher than the current version
            if($update > $current) {
            $data->response[$theme] = array(
                'theme'       => $theme,
                // Strip the version number of any non-alpha characters (excluding the period)
                // This way you can still use tags like v1.1 or ver1.1 if desired
                'new_version' => $update,
                'url'         => 'https://github.com/'.$user.'/'.$repo,
                'package'     => $file->assets[0]->browser_download_url,
            );
            }
        }
        return $data;
    }

    function cancel_emoji() {
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
        remove_action( 'admin_print_styles', 'print_emoji_styles' );
        remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
        remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
        if( function_exists('disable_emojis_tinymce') )
            add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
       }

    function scripts() {

        //Google Fonts
        wp_enqueue_style( 'bw-nut-font', 'https://fonts.googleapis.com/css2?family=Lalezar&display=swap' ,array(), '1.0');

        //Font Awesome Icons
        wp_enqueue_style( 'bw-nut-icon', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' ,array(), '4.7.0' );
    
        //Load Main Styling
        wp_enqueue_style( 'bw-nut-style', get_stylesheet_directory_uri() .'/style.css', array('bw-nut-font','bw-nut-icon'), false);
    
        //Load Main Script
        wp_enqueue_script( 'bw-nut-script', get_template_directory_uri(  ) .'/assets/js/app-min.js', array('jquery'), false, true );
    }
}

return new BW_NUTS;
?>