<?php
	/**
	 * @see     https://docs.woocommerce.com/document/template-structure/
	 * @package WooCommerce/Templates
	 * @version 3.6.0
	 */

	defined('ABSPATH') || exit;
	global $product;
	if(post_password_required()){
		echo get_the_password_form();
		return;
	}
	$acf_fields=get_fields();
	$product_gallery=$product->get_gallery_image_ids();
	$price=$product->get_regular_price()!='' ? $product->get_regular_price() : 0;
	$product_flag=get_field('product_flag',$product->get_id());
	$flag=$product_flag!='no' && !empty($product_flag) ? $product_flag : '';
	$flag=$product->get_stock_quantity()==0 ? 'no' : $flag;
?>
	
	<div class="product_page">
		<div class="two_info_product clr">
			<div class="left_block">
				<div class="main_img<?php if(!empty($product_gallery)) echo ' owl'; ?>">
					<div class="item">
						<?php
						if(has_post_thumbnail())
							echo '<a data-fancybox="prod-'.$post->ID.'" href="'.get_the_post_thumbnail_url().'">'.get_the_post_thumbnail($post,'large').'</a>';
						else
							echo '<img src="'.IMG_LOGO.'" alt="" />';
						?>
					</div>
					<?php
					if(!empty($product_gallery))
						foreach($product_gallery as $product_img)
							echo '<div class="item"><a data-fancybox="prod-'.$post->ID.'" href="'.wp_get_attachment_image_url($product_img,'full').'"><img src="'.wp_get_attachment_image_url($product_img,'large').'" alt="" /></a></div>';
					?>
				</div>
				<?php
				if(!empty($product_gallery)){
					echo '<div class="sub_img owl">';
						echo '<div class="item active">'.get_the_post_thumbnail($post,'large').'</div>';
						foreach($product_gallery as $product_img)
							echo '<div class="item"><img src="'.wp_get_attachment_image_url($product_img,'medium').'" alt="" /></div>';
					echo '</div>';
				}
				?>
			</div>
			<div class="right_block">
				<?php 
					post_title();
					echo '<div class="main_params">';
						if(!empty(get_field('_code',$product->get_id())))
							echo '<p><span>Код:</span> '.get_field('_code',$product->get_id()).'</p>';
						$attributes=$product->get_attributes();
						if(!empty($attributes)){
							foreach($attributes as $attribute){
								$values='';
								if($attribute->is_taxonomy() && !empty($attribute->get_options())){
									foreach($attribute->get_options() as $value){
										$data_term=get_term($value);
										$values.=$data_term->name.', ';
									}
									echo '<p><span>'.$attribute->get_taxonomy_object()->attribute_label.':</span> '.mb_substr($values,0,-2).'</p>';
								}
							}
						}
					echo '</div>';
				?>
				<div class="info_product">
					<p class="cost_main">Цена: <span><?php echo cost_format($price); ?></span> руб.</p>
					<p class="cost_description">Цена действительна на <?php echo date('d.m.Y'); ?></p>
					<?php
					if(!empty($product->get_price_html()) && $price!=0 && $product->get_stock_quantity()!=0 && !is_product_in_cart($product->get_id())){
						echo '<div class="qty_block">';
							$max_prod=$product->get_max_purchase_quantity()==-1 ? 999 : $product->get_max_purchase_quantity();
							$product_quantity='<a href="#" class="change_quantity minus">-</a>';
							$product_quantity.='<input type="text" class="input-text qty text product_quantity" value="1" title="Кол-во" aria-labelledby="'.$product->get_name().'" data-min="1" data-max="'.$max_prod.'" />';
							$product_quantity.='<a href="#" class="change_quantity plus">+</a>';
							echo $product_quantity;
						echo '</div>';
					}
					?>
					<div class="prod_stock">
						<?php
						if($product->get_stock_quantity()!=0)
							echo '<p class="yes">В наличии</p>';
						else
							echo '<p class="no">Нет в наличии</p>';
						?>
					</div>
					<?php
					if($price>=3000){
						?>
						<a class="kredit" href="<?php the_permalink(1333); ?>" target="_blank"><img src="<?php echo THEME; ?>/img/1_pokupay_button_credit_white_alt.png" alt="" /></a>
						<a class="kredit" href="<?php the_permalink(1333); ?>" target="_blank"><img src="<?php echo THEME; ?>/img/2_pokupay_button_rass_white_alt.png" alt="" /></a>
						<?php
					}
					?>
					<div class="cart_btn">
					<?php
						if(!empty($product->get_price_html() && $price!=0 && $product->get_stock_quantity()!=0))
							if(is_product_in_cart($product->get_id()))
								echo '<a href="#" class="is_cart">Товар в корзине</a>';
							else
								echo '<a href="#" data-id="'.$product->get_id().'" data-qty="1">Добавить в корзину</a>';
					?>
					</div>
				</div>
			</div>
		</div>
		
		<?php
		$data_tabs=array();
		if(!empty(get_the_content()))
			$data_tabs[]=array('name' => 'Описание','data' => apply_filters('the_content',get_the_content()));
		if(!empty($acf_fields['product_params'])){
			$data_params='<div class="prod_params">';
			foreach($acf_fields['product_params'] as $item_param)
				$data_params.='<div class="clr"><span>'.$item_param['product_params_name'].'</span><span>'.$item_param['product_params_value'].'</span></div>';
			$data_tabs[]=array('name' => 'Характеристики','data' => $data_params.'</div>');
		}
		
		if(!empty($data_tabs)){
			?>
			<div class="tabs">
				<div class="tabs_name">
					<p><?php echo $data_tabs[0]['name']; ?></p>
					<div>
						<?php
						$ii=1;
						foreach($data_tabs as $data_tab){
							$active=$ii==1 ? ' class="active"' : ''; $ii++;
							echo '<span'.$active.'>'.$data_tab['name'].'</span>';
						}
						?>
					</div>
				</div>
				<div class="tabs_content">
					<?php
					$ii=1;
					foreach($data_tabs as $data_tab){
						$active=$ii==1 ? ' class="active"' : ''; $ii++;
						echo '<div'.$active.'>'.$data_tab['data'].'</div>';
					}
					?>
				</div>
			</div>
			<?php
		}
		
		do_action('woocommerce_after_single_product_summary');
		?>
	</div>