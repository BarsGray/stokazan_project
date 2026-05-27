<?php
/**
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     2.3.6
 */

if(!defined('ABSPATH'))	exit;
?>
<div class="cart_totals <?php echo(WC()->customer->has_calculated_shipping()) ?'calculated_shipping':''; ?>">
	<?php do_action('woocommerce_before_cart_totals'); ?>

	<table cellspacing="0" class="shop_table shop_table_responsive">

		<?php
			/*
			foreach(WC()->cart->get_fees() as $fee) : ?>
			<tr class="fee">
				<th><?php echo esc_html($fee->name); ?></th>
				<td data-title="<?php echo esc_attr($fee->name); ?>"><?php wc_cart_totals_fee_html($fee); ?></td>
			</tr>
		<?php endforeach;
			*/
		?>

		<?php if(wc_tax_enabled() && ! WC()->cart->display_prices_including_tax()) :
			$taxable_address = WC()->customer->get_taxable_address();
			$estimated_text  = WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping()
					? sprintf('<small>'. __('(estimated for %s)','woocommerce') .'</small>', WC()->countries->estimated_for_prefix($taxable_address[0]) . WC()->countries->countries[ $taxable_address[0] ])
					:'';

			if('itemized'=== get_option('woocommerce_tax_total_display')) : ?>
				<?php foreach(WC()->cart->get_tax_totals() as $code => $tax) : ?>
					<tr class="tax-rate tax-rate-<?php echo sanitize_title($code); ?>">
						<th><?php echo esc_html($tax->label) . $estimated_text; ?></th>
						<td data-title="<?php echo esc_attr($tax->label); ?>"><?php echo wp_kses_post($tax->formatted_amount); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr class="tax-total">
					<th><?php echo esc_html(WC()->countries->tax_or_vat()) . $estimated_text; ?></th>
					<td data-title="<?php echo esc_attr(WC()->countries->tax_or_vat()); ?>"><?php wc_cart_totals_taxes_total_html(); ?></td>
				</tr>
			<?php endif; ?>
		<?php endif; ?>

		<?php do_action('woocommerce_cart_totals_before_order_total'); ?>

		<tr class="order-total">
			<th>Сумма заказа</th>
			<td data-title="Сумма заказа"><?php wc_cart_totals_order_total_html(); ?></td>
		</tr>

		<?php do_action('woocommerce_cart_totals_after_order_total'); ?>

	</table>

	<div class="wc-proceed-to-checkout">
		<?php do_action('woocommerce_proceed_to_checkout'); ?>
	</div>

	<?php do_action('woocommerce_after_cart_totals'); ?>

</div>
