<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Gridster
 */
?>

<div id="main">
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>> <a href="<?php the_permalink(); ?>">
<?php the_post_thumbnail('post-full', array('class' => 'postimage')); ?>
</a>
<div id="content">
<div id="postheading">
<h1>
<?php the_title(); ?>
</h1>
</div>
<br />
<?php the_content(); ?>
<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'gridster' ),
				'after'  => '</div>',
			) );
?>
<?php edit_post_link( __( 'Edit', 'gridster' ), '<span class="edit-link">', '</span>' ); ?>
<div id="comments">
<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || '0' != get_comments_number() )
					comments_template();
?>
<ul id="meta">
<li class="tagmeta"><strong>ALSI:</strong>&nbsp;<?php the_field('alsi'); ?></li>
<li class="tagmeta"><strong>ALIO:</strong>&nbsp;<?php the_field('alio'); ?></li>
<li class="tagmeta"><strong>CPI:</strong>&nbsp;<?php the_field('cpi'); ?></li>
<li class="tagmeta"><strong>GDP:</strong>&nbsp;<?php the_field('gdp'); ?></li>
<li class="tagmeta"><strong>MWI:</strong>&nbsp;<?php the_field('mwi'); ?></li>
<li class="tagmeta"><strong>HPI:</strong>&nbsp;<?php the_field('hpi'); ?></li>
<li class="tagmeta"><strong>BN:</strong>&nbsp;<?php the_field('bn'); ?></li>
<li class="tagmeta"><strong>PVRTY:</strong>&nbsp;<?php the_field('pvrty'); ?>%</li>
</ul>
</div>
<!-- #post-## -->
</div>
<!-- comments -->
<?php gridster_content_nav( 'nav-below' ); ?>
</div>
<!-- content -->
