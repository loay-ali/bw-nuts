<?php  get_header();?>

    <main id = 'page'>
        <h2>
            <?php echo get_the_title(  );?>
        </h2>

        <?php if ( have_posts() ) : ?>
                <div class="row">
						<?php /* Start the Loop */ ?>
						<?php while ( have_posts() ) : the_post(); ?>

                            <?php the_content();?>

							<?php //get_template_part( 'template-parts/content', get_post_format() );?>

						<?php endwhile; ?>
                </div>

			<?php endif; ?>

            <?php get_sidebar(); ?>
    </main>

<?php  get_footer();?>