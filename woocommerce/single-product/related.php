<?php
/**
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     3.9.0
 */

if(!defined('ABSPATH'))	exit;
if($related_products){ ?>
	<div class="related_products">
		<p class="related_title">С этим товаром покупают:</p>
			<div class="catalog owl">
			<?php
				foreach($related_products as $related_product){
					$post_object=get_post($related_product->get_id());
					setup_postdata($GLOBALS['post']=&$post_object);
					wc_get_template_part('content','product');
				}
			?>
			</div>
	</div>
<?php
}
wp_reset_postdata();