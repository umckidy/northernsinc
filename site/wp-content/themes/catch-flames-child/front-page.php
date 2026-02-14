<?php get_header(); ?>

<section class="hero">
    <img src="https://images.unsplash.com/photo-1544551763-47a0159f9234?auto=format&fit=crop&q=80&w=2000" alt="Brainerd Lake" class="hero-bg">
    <div class="hero-overlay"></div>
    <div class="container">
        <span style="display: inline-block; background: var(--brass); color: white; padding: 4px 12px; font-size: 0.7rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2rem;">Brainerd Lakes, Minnesota</span>
        <h1>The Pursuit of the <br/>Northern Pike.</h1>
        <p>A family of anglers dedicated to precision catch-and-release and the preservation of our world-class fisheries since 1975.</p>
        <div style="display: flex; gap: 1.5rem;">
            <a href="#tournaments" style="background: white; color: var(--pine-green); padding: 1rem 2rem; font-weight: 900; text-decoration: none; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.15em;">Tournament Center</a>
            <a href="#about" style="border: 2px solid white; color: white; padding: 1rem 2rem; font-weight: 900; text-decoration: none; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.15em;">Club History</a>
        </div>
    </div>
</section>

<main class="main-content" style="padding: 6rem 0;">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr; gap: 4rem; lg:grid-template-columns: 2fr 1fr;">
            
            <div class="main-column">
                <article class="card-journal">
                    <h2 style="font-size: 2.5rem; margin-bottom: 2rem; font-style: italic;">Notes from the Field</h2>
                    <div class="entry-content">
                        <span class="drop-cap">E</span>
                        <p style="font-size: 1.2rem; color: var(--lake-teal); font-family: var(--font-serif); font-style: italic; margin-bottom: 2rem; line-height: 1.6;">
                            "Every year this group acts like a fishing family that enjoys the time together on the water. Through practice and patience, some of the best pike fisherman in the world have passed through this club."
                        </p>
                        <p>
                            We are proud to look back on our recent Annual Banquet as a tremendous success. Our community remains a tight-knit family of anglers who value the heritage of the Brainerd Lakes. As we approach our 51st year, our commitment to conservation and catch-and-release is stronger than ever.
                        </p>
                        
                        <div style="background: var(--parchment); padding: 3rem; border: 1px solid var(--border-color); margin: 4rem 0; display: flex; gap: 2rem; align-items: center;">
                            <img src="https://images.unsplash.com/photo-1551698618-1fed5d965596?auto=format&fit=crop&q=80&w=300" alt="Pike Fishing" style="width: 150px; height: 150px; object-fit: cover; border: 1px solid var(--border-color);">
                            <div>
                                <h4 style="margin-bottom: 0.5rem; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.1em; color: var(--brass);">Coming Soon</h4>
                                <h3 style="margin-bottom: 0.5rem;">2024 International Tournament</h3>
                                <p style="font-size: 0.9rem; margin: 0; opacity: 0.7;">September 20 & 21st, 2024. Open for public and club registration.</p>
                            </div>
                        </div>

                        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                            <?php the_content(); ?>
                        <?php endwhile; endif; ?>
                    </div>
                </article>

                <div class="fishing-divider"></div>

                <section id="tournaments">
                    <h2 style="font-size: 2rem; margin-bottom: 3rem; text-align: center;">Official Resources</h2>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
                        <div class="card-journal" style="padding: 2.5rem; margin: 0; text-align: center;">
                            <span style="font-size: 2.5rem; display: block; margin-bottom: 1rem;">📜</span>
                            <h3 style="font-size: 1rem; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 1rem;">Club Rules</h3>
                            <p style="font-size: 0.85rem; opacity: 0.6; margin-bottom: 1.5rem;">Official catch-and-release guidelines for all sanctioned events.</p>
                            <a href="#" style="font-weight: 900; color: var(--pine-green); text-decoration: underline; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.1em;">Download PDF</a>
                        </div>
                        <div class="card-journal" style="padding: 2.5rem; margin: 0; text-align: center;">
                            <span style="font-size: 2.5rem; display: block; margin-bottom: 1rem;">📝</span>
                            <h3 style="font-size: 1rem; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 1rem;">Registration</h3>
                            <p style="font-size: 0.85rem; opacity: 0.6; margin-bottom: 1.5rem;">Secure your entry for the upcoming International Tournament.</p>
                            <a href="#" style="font-weight: 900; color: var(--pine-green); text-decoration: underline; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.1em;">Online Entry</a>
                        </div>
                    </div>
                </section>
            </div>

            <aside class="sidebar" style="padding-top: 4rem;">
                <div class="sidebar-widget">
                    <h4>Season Schedule</h4>
                    <div style="border-left: 2px solid var(--brass); padding-left: 1.5rem; margin-bottom: 2rem;">
                        <p style="font-size: 0.7rem; font-weight: 900; color: var(--brass); margin: 0; text-transform: uppercase;">Sept 20-21</p>
                        <p style="font-weight: 700; margin: 0.25rem 0;">International Pike Tournament</p>
                        <p style="font-size: 0.85rem; opacity: 0.6; margin: 0;">Brainerd Lakes, MN</p>
                    </div>
                </div>

                <div class="sidebar-widget" style="background: var(--pine-green); color: white;">
                    <h4 style="color: white; border-color: rgba(255,255,255,0.1);">Club Ethos</h4>
                    <p style="font-size: 0.9rem; font-family: var(--font-serif); font-style: italic; opacity: 0.8; line-height: 1.8;">
                        Established in 1975, we target Northern Pike in the Brainerd lakes area. We fish for fun and act as a fishing family that enjoys time together on the water.
                    </p>
                </div>
            </aside>

        </div>
    </div>
</main>

<?php get_footer(); ?>