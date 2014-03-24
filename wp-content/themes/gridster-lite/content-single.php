<?php
/**
 * @package Gridster
 */
?>

<div id="main">
<div id="content">
<div id="postheading">
<h1>
<?php the_title(); ?>
</h1>
</div>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>> <a href="<?php the_permalink(); ?>">
<?php the_post_thumbnail('post-full', array('class' => 'postimage alignright')); ?>
</a>
<ul id="meta">
	<li class="tagmeta"><strong>Region:</strong>&nbsp;<?php the_field('region'); ?></li>
	<li class="tagmeta"><strong>Country:</strong>&nbsp;<a href="<?php the_field('country'); ?>">
	<?php echo get_fields(); ?>
	</a></li>
	<li class="tagmeta"><strong>Hub:</strong>&nbsp;<?php the_field('hub'); ?></li>
</ul>
<?php the_content(); ?>
<strong>Number of Employees:</strong>&nbsp;<?php the_field('Employees'); ?></br>
<strong>2013 Revenue:</strong>&nbsp;<?php the_field('2013_revenue'); ?></br>
<strong>2012 Revenue:</strong>&nbsp;<?php the_field('2012_revenue'); ?></br>
<strong>Investment to Date:</strong>&nbsp;<?php the_field('investment_capital_to_date'); ?></br>
<strong>Grants to Date:</strong>&nbsp;<?php the_field('grants_received_to_date'); ?></br>
<strong>Years in Operation:</strong>&nbsp;<?php the_field('years_operating'); ?></br>
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
<!-- Drop Meta for Companies/Posts -->
</div>
<!-- #post-## -->
</div>
<!-- comments -->
<?php gridster_content_nav( 'nav-below' ); ?>
</div>
<!-- content -->
