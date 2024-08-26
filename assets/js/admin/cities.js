(function($){

    const sublocation_table = $('#bw-sublocations-table tbody');
    
    $('#bw-add-new-sublocation').click(({currentTarget}) => {

        const current_index = sublocation_table[0].children.length;

        const $new_ele = $("<tr></tr>");

        $new_ele.append('<td></td>');
        $new_ele.append("<td><input type = 'text' bw-type = 'code' readonly /></td>");
        $new_ele.append("<td><input type = 'text' bw-type = 'name' name = 'bw_sublocation["+ current_index +"][n]' /></td>");
        $new_ele.append("<td><input type = 'number' bw-type = 'price' name = 'bw_sublocation["+ current_index +"][p]' /></td>");
        $new_ele.append("<td><input type = 'number' bw-type = 'estimated_delivery_time' name = 'bw_sublocation["+ current_index +"][e]' /></td>");
        $new_ele.append("<td><button class = 'bw-remove-sublocation button bw-btn' type = 'button'><i class = 'dashicons dashicons-trash'></i></button></td>")

        sublocation_table.append($new_ele);

        $('.bw-remove-sublocation').off('click').on('click',removeFunction);
    });
    
    $('.bw-remove-sublocation').on('click',removeFunction);

    function removeFunction({currentTarget}) {

        $(currentTarget).parent().parent().remove();

        reOrganizeIndexes();
    }

    function reOrganizeIndexes() {

        var index = 0;

        sublocation_table.children().each((ind,ele) => {

            const $ele = $(ele);
            $ele.find('input').each((ind,ele) => {

                const $input_ele = $(ele);

                const name_attr = $input_ele.attr('id');
                const new_name_attr = name_attr.split('][')[0] + '][' + index +']['+ $input_ele.attr('bw-type') +']';

                $input_ele.attr('id',new_name_attr);
                
            });

            index++;
        });
    }

})(jQuery);