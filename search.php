<?php get_header(); ?>

	<section id="content">
		<div class="wr clr">
			<?php
				bread_crumbs();
				get_sidebar();
				echo '<div class="content_with_sidebar text"><div class="fix_clear">';
					echo '<h1>Результаты поиска - '.$_GET['s'].'</h1>';
					if(have_posts() && !empty($_GET['s'])){
						echo '<div class="catalog">';
						while(have_posts()){
							the_post();
							wc_get_template_part('content','product');
						}
						echo '</div>';
						wp_pagenavi();
					} else echo '<p>Ничего не найдено</p>';
				echo '<div><div>';
			?>
		</div>
	</section>
	
<?php get_footer(); ?>