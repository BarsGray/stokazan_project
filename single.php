<?php
	get_header();
	the_post();
?>

	<div id="content">
		<div class="wr text clr">
			<?php
				bread_crumbs();
				post_title();
				the_content();
			?>
		</div>
	</div>

<?php get_footer(); ?>