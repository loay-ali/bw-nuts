<!DOCTYPE html>
<html lang="<?php bloginfo('language');?>" dir = '<?php bloginfo('text_direction');?>'>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo get_bloginfo('name') .' | '. get_the_title();?></title>
    <?php wp_head();?>
</head>
<body class = '<?php body_class();?>'>
<?php wp_body_open(  );?>