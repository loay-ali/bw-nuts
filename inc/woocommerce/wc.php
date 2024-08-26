<?php

class BW_Woocommerce {

    function __construct() {

        if( is_admin() ) {

            require_once __DIR__ .'/admin.php';
        }

        //Dequeue Default Scripts & Styling
        //add_action('wp_enqueue_scripts',array($this,'dequeue'),PHP_INT_MAX);
        add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

        //Enqueue Woocommerce Scripts & Styles
        add_action( 'wp_enqueue_scripts', array($this,'enqueue') );

        //Amount In Add To Cart Card
        add_action('woocommerce_after_shop_loop_item',array($this,'card_amount'));

        //Add KG In Front Of Kilo Type Products
        add_action('woocommerce_shop_loop_item_title',array($this,'product_price_kilo'));

        //Product Meta Data For Inevntory
        add_action('woocommerce_product_options_inventory_product_data',array($this,'product_meta'),9);

        //Save Product Meta
        add_action('woocommerce_process_product_meta',array($this,'save_meta'));

        //Block Assets
        add_action('enqueue_block_assets',array($this,'block_assets'),1);

        //Sale Flash Content
        add_filter('woocommerce_sale_flash',array($this,'sale_flash'));

        //Plugin support
        add_action('init',array($this,'plugin_supports'));

        //Cart Fragment
        add_filter('add_to_cart_fragments',array($this,'cart_fragment'));
    
        //Handle Shortcodes
        add_shortcode( 'bw_wc', array($this,'shortcodes') );

        //Display Varients
        //add_action('woocommerce_after_shop_loop_item_title',array($this,'varients'));

        //Areas ( Cities ) Json Endpoint
        add_action('rest_api_init',array($this,'cities_api'));
    
        //Sub Location Select
        add_filter('woocommerce_default_address_fields',array($this,'wc_billing_fields'),PHP_INT_MAX);
		
		add_filter('woocommerce_formatted_address_replacements',array($this,'cart_shippment_rep'),10,2);
		add_filter('woocommerce_my_account_my_address_formatted_address',array($this,'profile_shippment_details'));
	
        add_action('init',array($this,'wc_sidebar_area'),99998);
        add_action('woocommerce_sidebar',array($this,'filters_sidebar'),PHP_INT_MAX);
    }

    function wc_sidebar_area() {
        register_sidebar( [
            'id'    => 'bw_sidebar',
            'name'  => 'الترشيحات للمنتجات',
            'description'    => 'الجزء الخاص بانتقاء المنتجات فى صفحة تجميعة المنتجات',
            'before_sidebar' => '<button type = "button" class = "bw-open-window" id = "bw-filter-toggle" bw-window = "bw-wc-filters"><i class = "fa fa-filter"></i></button><section id = "bw-wc-filters" class = "bw-section bw-phone-window"><header><h3 class = "bw-section-head"><i class = "fa fa-filter" style = "margin-inline-end:10px;"></i>إنتقاء المنتجات</h3><button type = "button" class = "bw-close-window"><i class = "fa fa-close"></i></button></header><section>',
            'after_sidebar'  => '</section></section>',
            'before_widget'  => '<div class = "bw-filter-widget">',
            'after_widget'   => '</div>',
            'before_title'   => '<h3 class = "bw-section-head">',
            'after_title'    => '</h3>'
        ] );
    }

    function filters_sidebar() {

        return dynamic_sidebar( 'bw_sidebar' );
    }

	function profile_shippment_details($args) {
		
		$cities = get_option('bw_sublocations_EGC',array());
		
		if( array_key_exists($args['city'],$cities) ) {
			
			$args['city'] = $cities[$args['city']]['n'];
		}
		
		return $args;	
	}

	function cart_shippment_rep($rep,$args) {
			
		$cities = get_option('bw_sublocations_EGC',array());
		
		if( array_key_exists($args['city'],$cities) ) {
			
			$rep['{city}'] = $cities[$args['city']]['n'];
		}
		
		return $rep;
	}

    function wc_billing_fields ($fields) {
	
		//Disable Some Fields
		// unset($fields['billing']['billing_postcode']);
		// unset($fields['billing']['billing_address_2']);
		// unset($fields['billing']['billing_state']);
		// unset($fields['billing']['billing_country']);
		// unset($fields['billing']['billing_company']);
		
		unset($fields['postcode']);
		unset($fields['address_2']);
		unset($fields['state']);
		unset($fields['company']);
		unset($fields['country']);
		
		$fields['address_1']['type'] = 'textarea';
	
		$cities = get_option('bw_sublocations_EGC',array());
		$bw_cities_row = ['' => 'اختر المنطقة'];
		
		foreach( $cities as $code => $city )
			$bw_cities_row[$code] = $city['n'];
	
        $fields['city']['priority'] = 51;
        $fields['city']['type'] = 'select';
        $fields['city']['options'] = $bw_cities_row;

        return $fields;
    }

    function get_cities_api() {

        echo json_encode(get_option( 'bw_sublocations_EGC', array() ));
    }

    function cities_api() {

        register_rest_route(
            'bw/v1',
            'cities',
            array(
                'methods' => 'GET',
                'callback' => array($this,'get_cities_api'),
                'permission_callback' => function(){ }
            )
        );
    }

    function varients($product) {

        global $post;
        $product = wc_get_product($post->ID);

        if( $product->is_type('variable') == false || is_string($product->get_variation_attributes()) )
            return;

        ?>
        <span class = 'bw-flex justify-space-between align-center bw-varient-container'>
            <?php echo implode(' , ',$product->get_variation_attributes());?>
        </span>
        <?php
    }

    function shortcodes($attrs,$content = '') {

        if( empty($attrs['type']) || !is_string($attrs['type']) )
            $attrs['type'] = '';

        switch( $attrs['type'] ) {

            case 'shopping-cart':
                echo $this->shopping_cart();
            break;

            case 'profile':
                echo $this->profile_btn();
            break;
        }
    }

    function profile_btn() {

        ?>
        <a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') );?>" class="button nobg icon">
            <i class="fa fa-user-circle"></i>
        </a>
        <?php
    }

    function shopping_cart() {

        global $woocommerce;

        ob_start();?>

        <a href="<?php echo wc_get_cart_url();?>" class="button nobg icon" style = 'position:relative;'>
            <span class="bw-badge"><?php echo $woocommerce->cart->cart_contents_count;?></span>
            <i class = 'fa fa-shopping-cart'></i>
        </a><?php

        return ob_get_clean();
    }

    function cart_fragment( $frag ) {

        $frag['.bw-cart-container'] = $this->shopping_cart();

        return $frag;
    }

    function plugin_supports() {

        remove_theme_support( 'wc-product-gallery-zoom' );
        remove_theme_support( 'wc-product-gallery-lightbox' );
        remove_theme_support( 'wc-product-gallery-slider' );
    }

    function sale_flash() { 
        
        global $post;

        $product = wc_get_product( $post->ID );
        $regular = $product->get_regular_price();
        $sale = $product->get_sale_price();
        ?>
        <span class="onsale bw-flex align-center justify-center">
            <?php echo round((($regular - $sale) / $regular) * 100) .'% خصم';?>
        </span>
    <?php }

    function block_assets() {

        wp_deregister_style( 'wc-blocks-packages-style' );
        wp_deregister_style( 'wc-blocks-style' );
        wp_deregister_style( 'wc-blocks-style-cart' );
        wp_deregister_style( 'wc-blocks-style-checkout' );
    }

    function product_price_kilo() {

        global $post;

        $amount_type = get_post_meta($post->ID,'bw_amount_type',true);

        if( is_string($amount_type) && $amount_type == 'kg' ) {

            echo '<span>( لكل كيلو )</span>';
        }
    }

    function save_meta($product_id) {

        $amount_type = empty($_POST['bw_amount_type']) ? 'bag':$_POST['bw_amount_type'];

        update_post_meta( $product_id, 'bw_amount_type', $amount_type );
    }

    function product_meta() { 
        
        global $post;
        
        $amount_type = get_post_meta($post->ID,'bw_amount_type',true);?>
        <p class = 'form-field'>
            <label for="bw_amount_type">
                كمية المنتج
            </label>
            <select name="bw_amount_type" id="bw_amount_type">
                <option value="bag" <?php selected($amount_type,'bag');?>>كمية</option>
                <option value="kg" <?php selected($amount_type,'kg');?>>بالكيلو</option>
            </select>
        </p>
    <?php }

    function card_amount() {

        woocommerce_quantity_input();
    }

    function enqueue() {

        wp_enqueue_style( 'bw-nuts-wc-style', get_template_directory_uri(  ) .'/assets/css/woocommerce/wc.css', array('bw-nut-style'), false );
    
        wp_deregister_style( 'packages' );
    }

    function dequeue() {
        wp_dequeue_script( 'woocommerce' );
        wp_dequeue_script( 'woocommerce-general' );
        wp_dequeue_script( 'woocommerce-layout' );
        wp_dequeue_script( 'woocommerce-inline' );
        wp_dequeue_script( 'woocommerce-rtl' );
    }
}

return new BW_Woocommerce;
?>