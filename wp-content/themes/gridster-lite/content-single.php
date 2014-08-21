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
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?> <a href="<?php the_permalink(); ?>">
	<ul id="meta">
	<li class="tagmeta"><strong>Region:</strong>&nbsp;<?php the_field('region_name'); ?></li>
	<li class="tagmeta"><strong>City:</strong>&nbsp;<a href="<?php the_field('country'); ?>"><?php the_field('city_name')	?></a></li>
	<li class="tagmeta"><strong>Hub:</strong>&nbsp;<?php the_field('hub'); ?></li>
	</ul>
<div id="company-logo">	
<p><?php the_post_thumbnail('post-full', array('class' => 'postimage alignleft')); ?></p>
</div>
<div id="company-data">
<p><h7>NUMBER OF EMPLOYEES</h7><br />
Employees 2014:&nbsp;<?php the_field('number_of_employees_2014'); ?></br>
Employees 2013:&nbsp;<?php the_field('number_of_employees_2013'); ?></br>
Employees 2012:&nbsp;<?php the_field('number_of_employees_2012'); ?></p>

<p><h7>REVENUE</h7><br />
2014 Revenue:&nbsp;<?php the_field('2014_revenue'); ?></br>
2013 Revenue:&nbsp;<?php the_field('2013_revenue'); ?></br>
2012 Revenue:&nbsp;<?php the_field('2012_revenue'); ?></p>

<p><h7>OTHER DETAILS</h7><br />
Total Investment to Date:&nbsp;<?php the_field('investment_capital_to_date'); ?></br>
Total Grants to Date:&nbsp;<?php the_field('grants_received_to_date'); ?></br>
Years in Operation:&nbsp;<?php the_field('years_operating'); ?>
</p>
</div>
<?php the_content(); ?>
<!-- #post-## -->
</div>
</div>
<!-- content -->
