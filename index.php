<?php
	/* 
		Template Name: Главная
		Template Post Type: page
	*/
	get_header();
	the_post();
?>

	<!--<div class="alt_b">
		<img src="<?php echo THEME; ?>/img/8m.png" alt="" />
		<img src="<?php echo THEME; ?>/img/8m2.jpg" alt="" />
	</div>-->
	
	<?php /*
	<div id="sec-0-1">
		<div class="sec_data owl">
			<div class="item">
				<img src="<?php echo THEME; ?>/img/sale_1.jpg" alt="" />
				<img src="<?php echo THEME; ?>/img/sale_1_mob.jpg" alt="" />
			</div>
			<div class="item">
				<img src="<?php echo THEME; ?>/img/sale_2.jpg" alt="" />
				<img src="<?php echo THEME; ?>/img/sale_2_mob.jpg" alt="" />
			</div>
		</div>
	</div>
	*/ ?>
	
	<div id="sec-1">
		<div class="sec_data owl">
			<?php
			foreach(get_acf_main('main_slider') as $item){
				$link=!empty($item['main_slider_link']) ? get_permalink($item['main_slider_link']) : '';
				$link=empty($link) ? $item['main_slider_link_cat'] : $link;
				
				echo !empty($link) ? '<a href="'.$link.'" class="item">' : '<div class="item">';
				?>
					<div class="wr">
						<div class="left_block">
							<div>
								<?php
								if(!empty($item['main_slider_title']))
									echo '<p class="title">'.$item['main_slider_title'].'</p>';
								?>
								<p class="sub_title"><?php echo $item['main_slider_name']; ?></p>
								<?php
								if(!empty($item['main_slider_description']))
									echo '<p class="description">'.$item['main_slider_description'].'</p>';
								if(!empty($item['main_slider_price']))
									echo '<p class="price">'.$item['main_slider_price'].' <span>руб.</span></p>';
								?>
							</div>
						</div>
						<div class="right_block">
							<span><img src="<?php echo $item['main_slider_img']['url']; ?>" alt="" /></span>
						</div>
					</div>
				<?php
				echo !empty($link) ? '</a>' : '</div>';
			}
			?>
		</div>
	</div>
	
	<!--<div id="sec-2">
		<div class="wr">
			<a href="https://xn--80aagsnvcfuk.xn--p1ai/wp-content/uploads/2023/12/banner.png" data-fancybox><img src="https://xn--80aagsnvcfuk.xn--p1ai/wp-content/uploads/2023/12/banner.png" alt="" /></a>
		</div>
	</div>-->

	<div id="sec-2">
		<div class="wr">
		<!--<p class="sec_title_ng">График работы магазина: 31 декабря с 9:00 до 14:00,</br>1, 2 января - выходной,</br>далее в штатном режиме</p>-->
			<p class="sec_title"><?php acf_main('tabs_title'); ?></p>
			<?php
			if(!empty(get_acf_main('tabs_description')))
				echo '<p class="sec_subtitle">'.get_acf_main('tabs_description').'</p>';
			?>
			<div class="tabs">
				<?php
				$ii=1;
				/*$tx_terms=get_terms(array(
					'taxonomy' => 'product_cat',
					'hide_empty' => false,
					'orderby' => 'menu_order',
					'parent' => 0,
					'exclude' => 15
				));*/
				$tx_terms=get_acf_main('tabs_terms');
				$tx_posts_new=get_posts(array(
					'post_type' => 'product',
					'orderby' => 'rand',
					'numberposts' => 12,
					'meta_query' => array(
						array(
							'key' => 'product_flag',
							'value' => 'new'
						)
					)
				));
				$tx_posts_hit=get_posts(array(
					'post_type' => 'product',
					'orderby' => 'rand',
					'numberposts' => 12,
					'meta_query' => array(
						array(
							'key' => 'product_flag',
							'value' => 'hit'
						)
					)
				));
				?>
				<div class="tabs_name">
					<p><?php echo !empty($tx_posts_new) || !empty($tx_posts_hit) ? 'Хиты продаж' : $tx_terms[0]->name; ?></p>
					<div>
						<?php
						if(!empty($tx_posts_new) || !empty($tx_posts_hit)) echo '<span class="active">Хиты продаж</span>';
						foreach($tx_terms as $term_tab){
							$active=$ii==1 && (empty($tx_posts_new) && empty($tx_posts_hit)) ? ' class="active"' : '';
							echo '<span'.$active.'>'.$term_tab->name.'</span>';
							$ii++;
						}
						?>
					</div>
				</div>
				<div class="tabs_content">
					<?php
						if(!empty($tx_posts_new) || !empty($tx_posts_hit)){
							?>
							<div class="active">
								<?php
								if(!empty($tx_posts_hit)){
									echo '<div class="catalog owl">';
										foreach($tx_posts_hit as $post_tab){
											setup_postdata($post_tab);
											wc_get_template_part('content','product');
										}
									echo '</div>';
								}
								if(!empty($tx_posts_new)){
									echo '<div class="catalog owl">';
										foreach($tx_posts_new as $post_tab){
											setup_postdata($post_tab);
											wc_get_template_part('content','product');
										}
									echo '</div>';
								}
								?>
							</div>
							<?php
						}

						$ii=1;
						foreach($tx_terms as $term_tab){
							$tx_posts=get_posts(array(
								'tax_query' => array(array(
									'taxonomy' => $term_tab->taxonomy,
									'field' => 'id',
									'terms' => $term_tab->term_id
								)),
								'post_type' => 'product',
								'orderby' => 'rand',
								'numberposts' => 12
							));
							$active=$ii==1 && (empty($tx_posts_new) && empty($tx_posts_hit)) ? ' class="active"' : '';
							?>
							<div<?php echo $active; ?>>
								<div class="catalog owl">
									<?php
									foreach($tx_posts as $post_tab){
										setup_postdata($post_tab);
										wc_get_template_part('content','product');
									}
									?>
								</div>
							</div>
							<?php
							$ii++;
						}
						wp_reset_postdata();
					?>
				</div>
			</div>
		</div>
	</div>
	
	<div id="sec-2_2">
		<div class="wr">
			<p class="sec_title"><?php acf_main('tabs_title_1'); ?></p>
			<?php
			if(!empty(get_acf_main('tabs_description_1')))
				echo '<p class="sec_subtitle">'.get_acf_main('tabs_description_1').'</p>';
			?>
			<div class="tabs">
				<?php
				$ii=1;
				/*$tx_terms=get_terms(array(
					'taxonomy' => 'product_cat',
					'hide_empty' => false,
					'orderby' => 'menu_order',
					'parent' => 0,
					'exclude' => 15
				));*/
				$tx_terms=get_acf_main('tabs_terms_1');
				$tx_posts_new=get_posts(array(
					'post_type' => 'product',
					'orderby' => 'rand',
					'numberposts' => 12,
					'meta_query' => array(
						array(
							'key' => 'product_flag',
							'value' => 'new'
						)
					)
				));
				$tx_posts_hit=get_posts(array(
					'post_type' => 'product',
					'orderby' => 'rand',
					'numberposts' => 12,
					'meta_query' => array(
						array(
							'key' => 'product_flag',
							'value' => 'hit'
						)
					)
				));
				?>
				<div class="tabs_name">
					<p><?php echo !empty($tx_posts_new) || !empty($tx_posts_hit) ? 'Хиты продаж' : $tx_terms[0]->name; ?></p>
					<div>
						<?php
						if(!empty($tx_posts_new) || !empty($tx_posts_hit)) echo '<span class="active">Хиты продаж</span>';
						foreach($tx_terms as $term_tab){
							$active=$ii==1 && (empty($tx_posts_new) && empty($tx_posts_hit)) ? ' class="active"' : '';
							echo '<span'.$active.'>'.$term_tab->name.'</span>';
							$ii++;
						}
						?>
					</div>
				</div>
				<div class="tabs_content">
					<?php
						if(!empty($tx_posts_new) || !empty($tx_posts_hit)){
							?>
							<div class="active">
								<?php
								if(!empty($tx_posts_hit)){
									echo '<div class="catalog owl">';
										foreach($tx_posts_hit as $post_tab){
											setup_postdata($post_tab);
											wc_get_template_part('content','product');
										}
									echo '</div>';
								}
								if(!empty($tx_posts_new)){
									echo '<div class="catalog owl">';
										foreach($tx_posts_new as $post_tab){
											setup_postdata($post_tab);
											wc_get_template_part('content','product');
										}
									echo '</div>';
								}
								?>
							</div>
							<?php
						}

						$ii=1;
						foreach($tx_terms as $term_tab){
							$tx_posts=get_posts(array(
								'tax_query' => array(array(
									'taxonomy' => $term_tab->taxonomy,
									'field' => 'id',
									'terms' => $term_tab->term_id
								)),
								'post_type' => 'product',
								'orderby' => 'rand',
								'numberposts' => 12
							));
							$active=$ii==1 && (empty($tx_posts_new) && empty($tx_posts_hit)) ? ' class="active"' : '';
							?>
							<div<?php echo $active; ?>>
								<div class="catalog owl">
									<?php
									foreach($tx_posts as $post_tab){
										setup_postdata($post_tab);
										wc_get_template_part('content','product');
									}
									?>
								</div>
							</div>
							<?php
							$ii++;
						}
						wp_reset_postdata();
					?>
				</div>
			</div>
		</div>
	</div>
		
	<?php if(!empty(get_acf_main('sales'))){
		ob_start();
		foreach(get_acf_main('sales') as $item){
			if(
				(
					!empty($item['sales_start']) &&
					!empty($item['sales_end']) && 
					strtotime(date('d.m.Y'))>=strtotime($item['sales_start']) && 
					strtotime(date('d.m.Y'))<=strtotime($item['sales_end'])
				) 
				|| 
				(
					empty($item['sales_start']) &&
					empty($item['sales_end'])
				)
			){
				?>
				<a class="item clr" href="<?php echo !empty($item['sales_link']) ? get_term_link($item['sales_link']) : '#'; ?>">
					<div class="left_block<?php if(empty($item['sales_img'])) echo ' full'; ?>">
						<?php if(!empty($item['sales_type'])) echo '<p class="title">'.$item['sales_type'].'</p>'; ?>
						<p class="subtitle"><?php echo $item['sales_name']; ?></p>
						<p class="description"><?php echo $item['sales_data']; ?></p>
						<?php
						if(!empty($item['sales_start']) && !empty($item['sales_end']))
							echo '<p class="date">Акция продлится с <span>'.wp_date('j F',strtotime($item['sales_start'])).'</span> до <span>'.wp_date('j F',strtotime($item['sales_end'])).'</span></p>';
						?>
					</div>
					<?php
					if(!empty($item['sales_img']))
						echo '<div class="right_block"><img src="'.$item['sales_img']['url'].'" alt="" /></div>';
					?>
				</a>
				<?php
			}
		}
		$buffer_sales=ob_get_contents();
		ob_end_clean();
		if(!empty($buffer_sales)){
			?>
			<div id="sec-3">
				<div class="wr">
					<div class="sec_data">
						<div class="sale_block owl">
							<?php echo $buffer_sales; ?>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	}
	?>

	<div id="sec-4">
		<div class="wr">
			<div class="sec_data">
				<div class="left_block">
					<?php
					$top_text_img=get_acf_main('top_text_img'); ?>
					<img src="<?php echo $top_text_img['url']; ?>" alt="" />
				</div>
				<div class="right_block hide_text">
					<div class="text">
						<?php echo fc(get_acf_main('top_text')); ?>
					</div>
					<a href="#"></a>
				</div>
			</div>
		</div>
	</div>
	
	<?php
	if(!empty(get_acf_main('fotogallery'))){
		?>
		<div id="sec-5">
			<div class="wr">
				<p class="sec_title">Фотогалерея</p>
				<div class="sec_data owl">
					<?php
					foreach(get_acf_main('fotogallery') as $f_item){
						?>
						<div class="item">
							<a href="<?php echo $f_item['url']; ?>" data-fancybox="home_fotogallery" data-caption="<?php echo $f_item['caption']; ?>">
								<img src="<?php echo $f_item['sizes']['img_fotogallery']; ?>" alt="<?php echo $f_item['alt']; ?>" />
							</a>
						</div>
						<?php
					}
					?>
				</div>
			</div>
		</div>
		<?php
	}
	?>
	
	<?php
	if(!empty(get_acf_main('main_text'))){
		?>
		<div id="sec-6">
			<div class="wr hide_text">
				<div class="text clr">
					<?php echo fc(get_acf_main('main_text')); ?>
				</div>
				<a href="#"></a>
			</div>
		</div>
		<?php
	}
	?>
	
	
<?php get_footer(); ?>