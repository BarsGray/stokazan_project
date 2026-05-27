<?php
/**
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

defined('ABSPATH') || exit;
do_action('woocommerce_before_cart'); ?>

<form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
	<?php do_action('woocommerce_before_cart_table'); ?>

	<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
		<thead>
			<tr>
				<th class="product-number">№</th>
				<th class="product-thumbnail">Фото</th>
				<th class="product-name"><?php esc_html_e('Product','woocommerce'); ?></th>
				<th class="product-price">Цена</th>
				<th class="product-quantity">Кол-во</th>
				<th class="product-subtotal"><?php esc_html_e('Total','woocommerce'); ?></th>
				<th class="product-remove">&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<?php
			do_action('woocommerce_before_cart_contents');
			$ii=1;
			foreach(WC()->cart->get_cart() as $cart_item_key => $cart_item){
				$_product=apply_filters('woocommerce_cart_item_product',$cart_item['data'],$cart_item,$cart_item_key);
				$product_id=apply_filters('woocommerce_cart_item_product_id',$cart_item['product_id'],$cart_item,$cart_item_key);

				if($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible',true,$cart_item,$cart_item_key)){
					$product_permalink=apply_filters('woocommerce_cart_item_permalink',$_product->is_visible() ? $_product->get_permalink($cart_item) : '',$cart_item,$cart_item_key);
					?>
					<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class','cart_item',$cart_item,$cart_item_key)); ?>">
						<td class="product-number" data-title="№">
							<?php echo $ii; $ii++ ?>
						</td>
						
						<td class="product-thumbnail" data-title="Фото">
							<?php
							$thumbnail=apply_filters('woocommerce_cart_item_thumbnail',$_product->get_image(),$cart_item,$cart_item_key);
							if(!$product_permalink) echo $thumbnail;
							else printf('<a href="%s">%s</a>',esc_url($product_permalink),$thumbnail);
							?>
						</td>

						<td class="product-name" data-title="<?php esc_attr_e('Product','woocommerce'); ?>">
							<?php
							if(!$product_permalink)
								echo wp_kses_post(apply_filters('woocommerce_cart_item_name',$_product->get_name(),$cart_item,$cart_item_key).'&nbsp;');
							else
								echo wp_kses_post(apply_filters('woocommerce_cart_item_name',sprintf('<a href="%s">%s</a>',esc_url($product_permalink),$_product->get_name()),$cart_item,$cart_item_key));

							do_action('woocommerce_after_cart_item_name',$cart_item,$cart_item_key);
							echo wc_get_formatted_cart_item_data($cart_item);

							if($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity']))
								echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification','<p class="backorder_notification">'.esc_html__('Available on backorder','woocommerce').'</p>',$product_id));
							?>
						</td>

						<td class="product-price" data-title="Цена">
							<?php
								echo apply_filters('woocommerce_cart_item_price',WC()->cart->get_product_price($_product),$cart_item,$cart_item_key);
							?>
						</td>

						<td class="product-quantity" data-title="Кол-во">
							<?php
							if($_product->is_sold_individually()) {
								$product_quantity=sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />',$cart_item_key);
							} else {
								$max_prod=$_product->get_max_purchase_quantity()==-1 ? 999 : $_product->get_max_purchase_quantity();
								$product_quantity='<div class="qty_block"><a href="#" class="change_quantity minus">-</a>';
								$product_quantity.='<input name="cart['.$cart_item_key.'][qty]" type="text" class="input-text qty text product_quantity" value="'.$cart_item['quantity'].'" title="Кол-во" aria-labelledby="'.$_product->get_name().'" data-min="1" data-max="'.$max_prod.'" />';
								$product_quantity.='<a href="#" class="change_quantity plus">+</a></div>';
								
								/*$product_quantity=woocommerce_quantity_input(array(
									'input_name'   => "cart[{$cart_item_key}][qty]",
									'input_value'  => $cart_item['quantity'],
									'max_value'    => $_product->get_max_purchase_quantity(),
									'min_value'    => '0',
									'product_name' => $_product->get_name(),
								),$_product,false);*/
							}

							echo apply_filters('woocommerce_cart_item_quantity',$product_quantity,$cart_item_key,$cart_item);
							?>
						</td>

						<td class="product-subtotal" data-title="<?php esc_attr_e('Total','woocommerce'); ?>">
							<?php
								echo apply_filters('woocommerce_cart_item_subtotal',WC()->cart->get_product_subtotal($_product,$cart_item['quantity']),$cart_item,$cart_item_key);
							?>
						</td>
						
						<td class="product-remove">
							<?php
								echo apply_filters('woocommerce_cart_item_remove_link',sprintf(
									'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
									esc_url(wc_get_cart_remove_url($cart_item_key)),
									__('Remove this item','woocommerce'),
									esc_attr($product_id),
									esc_attr($_product->get_sku())
								),$cart_item_key);
							?>
						</td>
					</tr>
					<?php
				}
			}
			do_action('woocommerce_cart_contents');
			do_action( 'woocommerce_cart_actions' );
			wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' );
			do_action('woocommerce_after_cart_contents');
			echo '<tr class="hidden"><th colspan="7"><button type="submit" class="button update_cart" name="update_cart" value=""></button></th></tr>';
			?>
		</tbody>
	</table>
	<?php do_action('woocommerce_after_cart_table'); ?>
</form>


<div class="cart-collaterals">
	<?php
		/**
		 * Cart collaterals hook.
		 *
		 * @hooked woocommerce_cross_sell_display
		 * @hooked woocommerce_cart_totals - 10
		 */
		do_action('woocommerce_cart_collaterals');
	?>
</div>

<?php do_action('woocommerce_after_cart'); ?>
