<?php

class BW_WC_Admin {

    function __construct() {

        //Cities
        add_filter('woocommerce_get_sections_shipping',array($this,'cities_section'));
        add_filter('woocommerce_get_settings_shipping',array($this,'cities_fields'),PHP_INT_MAX,2);

        add_action('woocommerce_admin_field_bw_select_location',array($this,'select_location'));
    }

    function select_location($values) { ?>
    <style>
        #bw-sublocations-table {

            max-width:500px;
        }

        button.bw-btn[class],a.bw-btn[class] {

            display:inline-flex;
            align-items:center;
            justify-content:center;
        }
    </style>
    <table class = 'form-table'>
        <tr>
            <th>
                <label>
                    <strong>
                        <?php echo ! empty($values['name']) ? $values['name']:__("Sub Locations",'keg');?>
                    </strong>
                </label>
            </th>
            <td>
                <table class = 'widefat wrap' id="bw-sublocations-table">
                    <thead>
                        <tr>
                            <td>#</td>
                            <td>الكود</td>
                            <td>الأسم</td>
                            <td>السعر</td>
                            <td>الوقت المقدر للتوصيل</td>
                            <td class="operations"><!-- Main Operations --></td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $bw_counter = 1;$bw_locations = get_option('bw_sublocations_EGC',array());if( !empty($bw_locations) ):
                            foreach($bw_locations as $code => $location):?>

                            <tr>
                                <td><?php echo $bw_counter;?></td>
                                <td><input type = 'text' name = 'bw_sublocation[<?php echo $bw_counter;?>][code]' value = '<?php echo $code;?>' readonly /></td>
                                <td><input type = 'text' name = 'bw_sublocation[<?php echo $bw_counter - 1;?>][n]' value = '<?php echo $location['n'];?>' /></td>
                                <td><input type = 'number' name = 'bw_sublocation[<?php echo $bw_counter - 1;?>][p]' value = '<?php echo $location['p'];?>' /></td>
                                <td><input type = 'number' name = 'bw_sublocation[<?php echo $bw_counter - 1;?>][e]' value = '<?php echo $location['e'];?>' /></td>
                                <td><button class = 'bw-remove-sublocation button bw-btn' type = 'button'><i class = 'dashicons dashicons-trash'></i></button></td>
                            </tr>

                    <?php $bw_counter++;endforeach;endif;?>
                    </tbody>
                    <tfoot>
                        <button type = 'button' class="button" id = 'bw-add-new-sublocation'>
                            <?php _e("Add New +",'keg');?>
                        </button>
                    </tfoot>
                </table>
            </td>
        </tr>
    </table>
    <?php }

    function cities_fields($settings,$current_section) {

        if( $current_section == 'sublocations' ) {

            $custom_settings = array(
                array('type' => 'title','name' => 'مناطق القاهرة'),
                array(
                    'type'      => 'bw_select_location',
                    'name'      => 'المناطق'
                )
            );

            return $custom_settings;
        }

        return $settings;
    }

    function cities_section($section) {

        $section['sublocations'] = 'المناطق الفرعية';

        return $section;
    }
}

return new BW_WC_Admin;
?>