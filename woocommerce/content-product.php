<?php
/**
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

	defined('ABSPATH') || exit;
	global $product;
	if(empty($product) || !$product->is_visible()) return;
	$attr_proizvoditel=wc_get_product_terms($post->ID,'pa_proizvoditel');
	$price=$product->get_regular_price()!='' ? $product->get_regular_price() : 0;
	$product_flag=get_field('product_flag',$product->get_id());
	$flag=$product_flag!='no' && !empty($product_flag) ? $product_flag : '';
	$flag=$product->get_stock_quantity()==0 ? 'no' : $flag;
	$flag=$price>=3000 ? $flag.' sber' : $flag;
?>

	<div class="item<?php echo ' '.$flag; ?>" itemtype="https://schema.org/Product" itemscope>
		<a itemprop="url" class="img" href="<?php the_permalink($product->get_id()); ?>">
			<?php
				if(has_post_thumbnail($product->get_id())) echo get_the_post_thumbnail($product->get_id(),'large');
				else echo '<img src="'.IMG_LOGO.'" alt="" />';
			?>
			<?php if(has_post_thumbnail($product->get_id())){?>
				<meta itemprop="image" content="<?php echo get_the_post_thumbnail_url($product->get_id(),'full');?>">
			<?php } else { ?>
				<meta itemprop="image" content="<?php echo IMG_LOGO; ?>">
			<?php } ?>
		</a>
		
		<a class="title" href="<?php the_permalink($product->get_id()); ?>"><span itemprop="name"><?php echo $product->get_name(); ?></span></a>
		<p class="weight" itemprop="description"><?php if(!empty($attr_proizvoditel)) echo $attr_proizvoditel[0]->name; ?></p>
		<p class="price" itemtype="https://schema.org/Offer" itemprop="offers" itemscope>
			<span><?php echo cost_format($price); ?></span> руб.
			<meta itemprop="price" content="<?php echo $price; ?>">
			<meta itemprop="priceCurrency" content="RUB">
		</p>
		<?php
		if(!empty($product->get_price_html()) && $price!=0 && $product->get_stock_quantity()!=0){
			if(is_product_in_cart($product->get_id()))
				echo '<a href="#" class="add_cart is_cart">добавлено</a>';
			else
				echo '<a href="#"  class="add_cart" data-id="'.$product->get_id().'" data-qty="1">в корзину</a>';
		}
		?>
	</div>