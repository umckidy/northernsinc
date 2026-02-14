<?php get_header(); ?>

<div class="hero hero-small" style="height: 50vh; min-height: 400px; background-image: url('<?php echo get_the_post_thumbnail_url() ? get_the_post_thumbnail_url() : "https://images.unsplash.com/photo-1544551763-47a0159f9234?auto=format&fit=crop&q=80&w=2000"; ?>');">
    <div class="hero-overlay" style="background: linear-gradient(to top, rgba(15,23,42,1), transparent);"></div>
    <div class="container">
        <div class="hero-content" style="max-width: 800px;">
            <span style="font-weight: 800; text-transform: uppercase; color: var(--brand-sky); font-size: 0.75rem; letter-spacing: 0.1em;"><?php the_date(); ?></span>
            <h1 style="font-size: 4rem; margin-top: 1rem;"><?php the_title(); ?></h1>
        </div>
    </div>
</div>

<div class="container" style="padding-top: 4rem; padding-bottom: 5rem;">
    <div class="grid-main">
        <div class="content-area">
            <article class="card" style="padding: 4rem;">
                <div class="entry-content" style="font-size: 1.25rem; color: var(--slate-700); line-height: 1.8;">
                    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                        <?php the_content(); ?>
                    <?php endwhile; endif; ?>
                </div>
                
                <div style="margin-top: 4rem; padding-top: 2rem; border-top: 1px solid var(--slate-100); display: flex; gap: 1rem;">
                    <?php the_tags('<span style="font-size: 0.75rem; padding: 0.4rem 1rem; background: var(--slate-50); border-radius: 999px; color: var(--slate-500); font-weight: 600;">#', '</span> <span style="font-size: 0.75rem; padding: 0.4rem 1rem; background: var(--slate-50); border-radius: 999px; color: var(--slate-500); font-weight: 600;">#', '</span>'); ?>
                </div>
            </article>
        </div>
        
        <div class="sidebar-area">
            <div class="card" style="padding: 2rem;">
                <h4 style="margin-top: 0; margin-bottom: 1.5rem;">Recent Updates</h4>
                <?php
                $recent_posts = wp_get_recent_posts(array('numberposts' => 5, 'post_status' => 'publish'));
                foreach($recent_posts as $post) : ?>
                    <a href="<?php echo get_permalink($post['ID']); ?>" style="display: block; padding: 1rem 0; border-bottom: 1px solid var(--slate-50); font-size: 0.875rem; color: var(--slate-900); font-weight: 600;">
                        <?php echo $post['post_title']; ?>
                    </a>
                <?php endforeach; wp_reset_query(); ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>