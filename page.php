<?php
	get_header();
	the_post();
	$acf_fields=get_fields();
?>

	<div id="content">
		<div class="wr text clr">
			<?php
				bread_crumbs();
				post_title();
				if(is_page(19)) contact_post();
				the_content();
				
				echo fc($acf_fields['text_after']);
			?>
		</div>
	</div>

<?php get_footer(); ?>