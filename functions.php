<?php
class BW_NUTS {

    function __construct() {

        //Styling & Scripts
        add_action('wp_enqueue_scripts',array($this,'scripts'));

        //Disable Emojies Scripts ( Performance Sake )
        add_action('init',array($this,'cancel_emoji'));
    
        //Update Through GitHub Repo
        add_filter('pre_set_site_transient_update_themes', array($this,'updating_theme'), 100, 1);
    }

    function updating_theme($data) {
      // Theme information
      $theme   = get_stylesheet();
      $current = wp_get_theme()->get('Version');
        
      $user = 'loay-ali';
      $repo = 'bw-nuts';
        
      $file = @json_decode(@file_get_contents('https://api.github.com/repos/'.$user.'/'.$repo.'/releases/latest', false,
          stream_context_create(['http' => ['header' => "User-Agent: ".$user."\r\n"]])
      ));
        
      if($file) {
    	$update = filter_var($file->tag_name, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
          
        if($update > $current) {
      	  $data->response[$theme] = array(
    	      'theme'       => $theme,
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
        add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
       }

    function scripts() {

        //Google Fonts
        wp_enqueue_style( 'bw-nut-font', 'https://fonts.googleapis.com/css2?family=Lalezar&display=swap' ,array(), '1.0');

        //Font Awesome Icons
        wp_enqueue_style( 'bw-nut-icon', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' ,array(), '4.7.0' );
    
        //Load Main Styling
        wp_enqueue_style( 'bw-nut-style', get_stylesheet_directory_uri() .'/style.css', array('bw-nut-font','bw-nut-icon'), false);
    }
}

return new BW_NUTS;
?>
