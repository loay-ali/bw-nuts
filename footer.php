
        <footer style = 'background:#f7f2ec;'>

            <button type = "button" class = 'secondary square font-lg' id = "back-to-top">
                <i class="fa fa-chevron-up"></i>
            </button>

            <section id = 'bw-footer-widgets'>
                <?php for( $w = 1;$w <= 4;$w++ ):?>
                    <section id = 'bw-footer-widget-<?php echo $w;?>' class = 'bw-widget-container'>
                        <?php dynamic_sidebar('footer-'. $w);?>
                    </section>
                <?php endfor;?>
            </section>

            <p id = 'footer-below-bar'>
                جميع الحقوق محفوظة <?php echo date('Y');?>
            </p>
        </footer>

        <?php wp_footer();?>
    </body>
</html>