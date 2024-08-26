<?php

class BW_Admin {

    function __construct() {

        add_action('admin_enqueue_scripts',array($this,'scripts'));
    
        $this->save();
    }

    function scripts() {

        switch( get_current_screen()->id) {

            case 'woocommerce_page_wc-settings':

                wp_enqueue_script( 'bw-sublocation-admin-script', get_template_directory_uri(  ) .'/assets/js/admin/cities.js', array('jquery'), false, true );
            break;
        }
    }

    function save() {

        function code($code) {

            $sum = '';

            for( $i = 0;$i < strlen($code);$i++ ) {
                $sum .= ord($code[$i]);
            }

            return $sum;
        }

        if( isset($_POST['save'])
        &&  isset($_POST['_wpnonce'])
        &&  isset($_POST['bw_sublocation'])
        &&  is_array($_POST['bw_sublocation']) ) {

            $locations = get_option('bw_locations_EGC',array());
            $new_locations = array();

            if( !empty($_POST['bw_sublocation']) && is_array($_POST['bw_sublocation']) ) {

                foreach( $_POST['bw_sublocation'] as $row ) {

                    $code = '';
                    if( empty($row['code']) )
                        $code = 'EGC_'. code($row['n']);
                    else
                        $code = $row['code'];

                    $new_locations[$code] = [
                        'n' => $row['n'],
                        'p' => $row['p'],
                        'e' => $row['e']
                    ];
                }

                print_r($new_locations);

                update_option( 'bw_locations_EGC', $new_locations );
            }
        }
    }
}

return new BW_Admin;
?>