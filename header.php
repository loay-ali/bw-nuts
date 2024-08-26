<!DOCTYPE html>
<html lang="<?php bloginfo('language');?>" dir = '<?php echo is_rtl() ? 'rtl':'ltr';?>'>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo get_bloginfo('name') .' | '. get_the_title();?></title>
    <?php wp_head();?>
</head>
<body class = '<?php body_class();?>' style = 'background:#f7f2ec;'>
<?php wp_body_open(  );?>

    <header>
        <a id="bw-header-identity" href = '<?php bloginfo('url');?>'>
            <?php
            
            $bw_custom_logo_id = get_theme_mod( 'custom_logo' );

            if( $bw_custom_logo_id != '' ):

                ?>
                <img src="<?php echo wp_get_attachment_image_src( $bw_custom_logo_id, 'thumbnail')[0];?>" alt="الصورة الرئيسية للمتجر" height = '50' />
                <?php

            else:?>
                <h1>
                    <?php bloginfo('name');?>
                </h1>
            <?php endif;
            ?>
        </a>
        <section id = 'bw-header-main-menu'>
            <div id="bw-header-menu" class = 'bw-screen-only'>
                <button type="button" class="bw-phone-only bw-toggle-menu secondary">
                    <i class="fa fa-close"></i>
                </button>
                <?php wp_nav_menu( [
                    'menu' => 'primary',
                    'menu_class' => 'bw-flex align-center justify-between',
                    'container_id' => 'bw-header-menu-container'
                ] );?>
            
                <div id="bw-header-widgets" class="bw-flex align-center">
                    <?php dynamic_sidebar( 'header' );?>
                </div>
            </div>

            <button type="button" class="bw-phone-only bw-toggle-menu nobg" style = 'padding:0;margin-inline:15px;font-size:18px;'>
                <i class="fa fa-bars"></i>
            </button>
        </section>
    </header>