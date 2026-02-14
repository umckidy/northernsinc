<?php get_header(); ?>

<div class="hero hero-small" style="height: 40vh; min-height: 300px; background-image: url('https://images.unsplash.com/photo-1544551763-47a0159f9234?auto=format&fit=crop&q=80&w=2000');">
    <div class="hero-overlay" style="background: rgba(15, 23, 42, 0.8);"></div>
    <div class="container">
        <div class="hero-content">
            <h1 style="font-size: 3.5rem;"><?php the_title(); ?></h1>
        </div>
    </div>
</div>

<div class="container" style="margin-top: -3rem; position: relative; z-index: 20; padding-bottom: 5rem;">
    <div class="grid-main">
        <div class="content-area">
            <article class="card">
                <div class="entry-content" style="font-size: 1.125rem; color: var(--slate-700);">
                    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                        <?php the_content(); ?>
                    <?php endwhile; endif; ?>
                </div>
            </article>
        </div>
        <div class="sidebar-area">
             <?php if ( is_active_sidebar( 'primary-sidebar' ) ) : ?>
                <?php dynamic_sidebar( 'primary-sidebar' ); ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>