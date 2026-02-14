<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
    <div class="container">
        <div class="header-inner">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo">
                Northern Inc. <span style="font-style: normal; font-weight: 300; opacity: 0.4; font-size: 0.7rem; vertical-align: middle; margin-left: 10px;">EST. 1975</span>
            </a>

            <nav class="main-navigation">
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'container'      => false,
                    'fallback_cb'    => false,
                    'items_wrap'     => '<ul>%3$s<li><a href="#membership" class="btn-join">Member Registration</a></li></ul>',
                ) );
                ?>
            </nav>
        </div>
    </div>
</header>