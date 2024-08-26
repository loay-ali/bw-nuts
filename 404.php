<?php get_header();?>
    <main style = 'text-align:center;'>
        <img src = '<?php echo get_template_directory_uri(  ). '/assets/imgs/404.png';?>' style = 'width:712px;margin:0 25px;'/>
        <h2 style = 'font-size:40px;'>
            لا يمكننا العثور على نتائج البحث
        </h2>
        <a href = '<?php echo get_permalink( wc_get_page_id( 'shop' ) );?>' class = 'button'>
            العودة الى المتجر
        </a>
    </main>
<?php get_footer();?>