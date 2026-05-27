<?php

	/**
	 * @see https://docs.woocommerce.com/document/template-structure/
	 * @package WooCommerce/Templates
	 * @version 3.4.0
	 */

	defined('ABSPATH') || exit;
	
	get_header();
	$qo=get_queried_object();
	if(is_shop()){$qo=get_post(SHOP); $term_id=0;}
	else $term_id=$qo->term_id;
	
	$alt_zag=get_field('alt_zag',$qo);
	$text_before=is_shop() ? get_post_field('post_content',$qo) : get_field('text_before',$qo);
	$text_after=get_field('text_after',$qo);
	if(is_shop()) $h1=$alt_zag!='' ? $alt_zag : $qo->post_title;
	else $h1=$alt_zag!='' ? $alt_zag : $qo->name;
	
	$tx_terms=get_terms(array(
		'taxonomy' => 'product_cat',
		'hide_empty' => false,
		'orderby' => 'menu_order',
		'parent' => $term_id,
		'exclude' => 15
	));

?>

	<section id="content">
		<div class="wr clr">
		<?php
			bread_crumbs();
			get_sidebar();
			echo '<div class="content_with_sidebar text"><div class="fix_clear">';
				echo '<h1>'.$h1.'</h1>';
				echo fc($text_before);
				
				if(!empty($tx_terms)){
					echo '<div class="cat_catalog">';
					foreach($tx_terms as $tx_term){
						?>
						<div class="item">
							<a class="img" href="<?php echo get_term_link($tx_term); ?>">
								<?php
								$id_img_cat=get_field('img_cat',$tx_term);
								$img_cat_src=!empty($id_img_cat) ? $id_img_cat['sizes']['medium'] : IMG_LOGO;
								echo '<img src="'.$img_cat_src.'" alt="'.$tx_term->name.'" />';
								?>
							</a>
							<a class="title" href="<?php echo get_term_link($tx_term); ?>"><?php echo $tx_term->name; ?></a>
						</div>
						<?php
					}
					echo '</div>';
				} elseif(have_posts()){
					do_action('woocommerce_before_shop_loop');
					view_filter();
					echo '<div class="catalog" itemtype="https://schema.org/ItemList" itemscope>';
						while(have_posts()){
							the_post();
							wc_get_template_part('content','product');
						}
					echo '</div>';
					wp_pagenavi();
				} else {
					view_filter();
					do_action('woocommerce_before_shop_loop');
					do_action('woocommerce_no_products_found');
				}
				if(!is_paged()) echo fc($text_after);
			echo '</div></div>';
		?>
		</div>
	</section>

<?php get_footer(); ?>