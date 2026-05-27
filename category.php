<?php
	get_header();
	$qo=get_queried_object();
	$text_before=get_field('text_before',$qo);
	$text_after=get_field('text_after',$qo);
	query_posts(array(
		'cat' => $cat,
		'paged' => get_query_var('paged') ? get_query_var('paged') : 0
	));
?>

	<div id="content">
		<div class="wr text clr">
			<?php
			bread_crumbs();
			cat_title();
			echo fc($text_before);

			if(have_posts()){
				echo '<div class="cat_view">';
				while(have_posts()){ the_post();
					?>
					<div>
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						<p><?php echo exp_text(get_the_content(),250); ?></p>
					</div>
					<?php
				}
				echo '</div>';
				wp_pagenavi();
			} else echo '<p>Раздел не заполнен</p>';

			if(!is_paged() && !empty($text_after)) echo fc($text_after);
			?>
		</div>
	</div>

<?php get_footer(); ?>