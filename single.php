<?php  get_header();?>

    <main id = 'page'>
        <h2>
            <?php echo get_the_title(  );?>
        </h2>

        <?php if ( have_posts() ) : ?>
            <div class="row">
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php the_content();?>
                <?php endwhile; ?>
            </div>
		<?php endif; ?>

            <?php comments_template( );?>

        <?php get_sidebar(); ?>
    </main>

<?php  get_footer();?>