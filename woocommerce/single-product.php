<?php
/**
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if(!defined('ABSPATH'))	exit;
	get_header();
	the_post();
?>
	<section id="content">
		<div class="wr clr">
		<?php
			bread_crumbs();
			get_sidebar();
		?>
			<div class="content_with_sidebar text">
				<div class="fix_clear">
					<?php wc_get_template_part('content','single-product'); ?>
				</div>
			</div>
		</div>
	</section>

<?php get_footer(); ?>