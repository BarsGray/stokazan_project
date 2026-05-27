<?php

	/*
		Plugin Name: VZH.RU
		Version: 1.0
		Author URI: https://vzh.ru/
		Author: Ряховский Роман
	*/

	define('MAIN_PAGE',get_option('page_on_front'));
	define('SHOP',get_option('woocommerce_shop_page_id'));
	define('CART',get_option('woocommerce_cart_page_id'));
	define('VERSION','1.31');
	define('THEME',get_bloginfo('template_directory'));
	define('WP_POST_REVISIONS',10);
	define('IMG_LOGO',THEME.'/img/logo.png');
	
	register_nav_menus();
	add_theme_support('post-thumbnails');
	add_theme_support('woocommerce');
	add_theme_support('html5',array('script','style'));
	
	add_image_size('img_gallery',370,270,true);
	add_image_size('img_gallery_vertical',370,523,true);
	add_image_size('img_fotogallery',350,350,true);
	
	add_filter('aioseop_prev_link','__return_empty_string');
	add_filter('aioseop_next_link','__return_empty_string');
	add_filter('site_transient_update_plugins','filter_plugin_updates');
	//add_filter('xmlrpc_enabled','__return_false');
	add_filter('the_content','fancybox_activate');
	add_filter('document_title_parts','document_title_parts');
	add_filter('product_type_selector','disable_type_selector_wc',10,2);
	add_filter('product_type_options','disable_type_options_wc');
	add_filter('woocommerce_product_data_tabs','remove_admin_tabs_products',10,1);
	add_filter('woocommerce_add_to_cart_fragments','info_cart');
	add_filter('woocommerce_output_related_products_args','related_products_args',20);
	add_filter('woocommerce_checkout_fields','remove_fields_cart');
	add_filter('woocommerce_update_order_review_fragments','update_form_billing',99);
	add_filter('woocommerce_add_error','change_text_error_wc');
	add_filter('woocommerce_email_order_meta_keys','mail_custom_checkout_field');
	
	remove_filter('the_content_feed','wp_staticize_emoji');
	remove_filter('comment_text_rss','wp_staticize_emoji'); 
	remove_filter('wp_mail','wp_staticize_emoji_for_email');
	
	add_action('login_head','admin_logo');
	add_action('template_redirect','template_redirect');
	add_action('wp_enqueue_scripts','add_remove_js_css');
	add_action('admin_head','admin_head');
	add_action('kama_breadcrumbs_home_after','add_tax_custom',10,4);
	add_action('pre_get_posts','filter_product_query',100);
	add_action('pre_get_posts','search_query');
	add_action('wp_head','clear_wp_head',1);
	add_action('wp_ajax_ajax_update_cart','ajax_update_cart');
	add_action('wp_ajax_nopriv_ajax_update_cart','ajax_update_cart');
	add_action('wp_ajax_gdpr','gdpr_cookie');
	add_action('wp_ajax_nopriv_gdpr','gdpr_cookie');
	add_action('woocommerce_process_product_meta_simple','product_price_default');
	add_action('woocommerce_product_options_general_product_data','add_meta_product_fields');
	add_action('woocommerce_process_product_meta','save_meta_product_fields',10);
	add_action('wp_print_footer_scripts','script_update_shipping');
	add_action('woocommerce_before_order_notes','add_checkout_field');
	add_action('woocommerce_after_order_notes','add_checkout_field_after');
	add_action('woocommerce_checkout_update_order_meta','update_custom_checkout_field');
	add_action('woocommerce_after_checkout_validation','check_custom_fields_checkout',9999,2);
	add_action('woocommerce_admin_order_data_after_billing_address','view_custom_checkout_field_for_admin',10,1);
	add_action('woocommerce_cart_calculate_fees','extra_pay_card');
	add_action('user_register','fix_register_meta',10,1);
	
	remove_action('wp_head','feed_links',2);
	remove_action('wp_head','feed_links_extra',3);
	remove_action('wp_head','rsd_link');
	remove_action('wp_head','wlwmanifest_link');
	remove_action('wp_head','wp_generator'); 
	remove_action('wp_head','wp_shortlink_wp_head',10,0);
	remove_action('wp_head','adjacent_posts_rel_link_wp_head',10,0);
	remove_action('wp_head','wp_resource_hints',2);
	remove_action('wp_head','print_emoji_detection_script',7);
	remove_action('admin_print_scripts','print_emoji_detection_script');
	remove_action('wp_print_styles','print_emoji_styles');
	remove_action('admin_print_styles','print_emoji_styles');
	
	add_shortcode('acf_gal','gallery_post');

	function clear_wp_head(){
		remove_action('woocommerce_before_shop_loop','woocommerce_result_count',20);
		remove_action('woocommerce_before_shop_loop','woocommerce_catalog_ordering',30);
		remove_action('woocommerce_after_single_product_summary','woocommerce_output_product_data_tabs',10);
		remove_action('woocommerce_after_single_product_summary','woocommerce_upsell_display',15);
	}
	
	function fix_register_meta($user_id){
		update_user_meta($user_id,'shipping_country','RU');
		update_user_meta($user_id,'billing_country','RU');
	}
	
	function type_page(){
		return is_front_page() ? 'main' : 'inner';
	}
	
	function add_remove_js_css(){
		wp_dequeue_style('wp-block-library');
		wp_dequeue_style('wp-block-library-theme');
		wp_dequeue_style('global-styles');
		wp_deregister_style('wc-block-editor');
		wp_deregister_style('wc-block-style');
		wp_deregister_script('wp-embed');
		wp_deregister_script('jquery');
		wp_enqueue_style('site',THEME.'/style.css',array(),VERSION,'all');
		wp_enqueue_script('jquery',THEME.'/js/jquery.min.js',false,VERSION,true);
		wp_enqueue_script('owl',THEME.'/js/owl.carousel.min.js',array('jquery'),VERSION,true);
		wp_enqueue_script('fancybox',THEME.'/js/jquery.fancybox.min.js',array('jquery'),VERSION,true);
		wp_enqueue_script('nouislider',THEME.'/js/nouislider.js',array('jquery'),VERSION,true);
		wp_enqueue_script('site',THEME.'/js/site.js',array('jquery'),VERSION,true);
		wp_enqueue_style('nouislider',THEME.'/css/nouislider.css',array(),VERSION,'all');
		wp_enqueue_style('owl',THEME.'/css/owl.carousel.min.css',array(),VERSION,'all');
		wp_enqueue_style('fancybox',THEME.'/css/jquery.fancybox.min.css',array(),VERSION,'all');
	}
	function template_redirect(){
		if(is_post_type_archive() && !is_shop()){
			wp_redirect('/',301); exit;
		}
	}
	
	function document_title_parts($parts){
		if(isset($parts['site'])) unset($parts['site']);
		return $parts;
	}
	
	function filter_plugin_updates($value){
		unset($value->response['all-in-one-seo-pack/all_in_one_seo_pack.php']);
		return $value;
	}
	
	function admin_logo(){
		echo '<style type="text/css">body #login h1 a{background-image:url("'.IMG_LOGO.'");height:65px;width:100%;background-size:100% auto;}</style>';
	};
	
	function admin_head(){
		echo '<style type="text/css">#edittag{max-width:100%;}#edittag .form-field.term-description-wrap,.aioseop-preview-wrapper,._sale_price_field{display:none;}#woocommerce-product-data .woocommerce_options_panel .wp_radio_custom ul.wc-radios li{display:inline-block;margin:0 20px 0 0;}#woocommerce-product-data .woocommerce_options_panel .wp_radio_custom ul.wc-radios li label{margin:0;float:none;}</style>';
	}
	
	function gdpr_cookie(){
		setcookie('gdpr_site','gdpr',time()+86400000,'/');
		$_COOKIE['gdpr_site']='gdpr';
		wp_die();
	}
	
	function fancybox_activate($content){
		$pattern="/<a(.*?)href=('|\")(.*?).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>/i";
		$replacement='<a$1href=$2$3.$4$5 data-fancybox="content" $6>';
		$content=preg_replace($pattern,$replacement,$content);
		return $content;
	}
	
	function exp_text($text_old,$max_char){
		$text_old=strip_shortcodes($text_old);
		$text_old=preg_replace('~\[[^\]]+\]~','',$text_old);
		$text_old=strip_tags($text_old);
		
		if(iconv_strlen($text_old,'utf-8') < $max_char)
			return $text_old;
		else {
			$text_new=iconv_substr($text_old,0,$max_char,'utf-8');
			$text_new=preg_replace('@(.*)\s[^\s]*$@s','\\1 ...',$text_new);
			return $text_new;
		}
	}
	
	function bread_crumbs(){
		$kb=new Breadcrumbs();
		echo $kb->get_crumbs();
	}
	
	function add_tax_custom($false,$linkpatt,$sep,$ptype){
		if(!is_search()){
			$data_taxs=array(
				'product' => SHOP
			);
			foreach($data_taxs as $post_type => $id_page){
				if($ptype->name==$post_type || is_shop()){
					$page=get_post($id_page);
					if($q_obj->name==$post_type || is_shop())
						return $home_after=sprintf($linkpatt,get_permalink($page),$page->post_title); 
					else
						return $home_after=sprintf($linkpatt,get_permalink($page),$page->post_title).$sep;
				}
			}
		}
	}
	
	function gallery_post($atts){
		$acf_gal=get_field('acf_gal',get_queried_object());
		if(!empty($acf_gal))
			foreach($acf_gal as $item)
				if($item['name_gal']==$atts['name']){
					$ret_gal='';
					foreach($item['img_gal'] as $foto_single)
						$ret_gal.='<a href="'.$foto_single['url'].'" data-fancybox="gallery_'.$item['name_gal'].'" data-caption="'.$foto_single['alt'].'"><img src="'.$foto_single['sizes'][$item['view_gal']].'" alt="'.$foto_single['alt'].'" /></a>';
					return '<div class="acf_gal clr">'.$ret_gal.'</div>';
				}
	}
	
	function get_acf_main($field){
		return get_field($field,MAIN_PAGE);
	}
	function acf_main($field){
		echo get_acf_main($field);
	}
	
	function fc($text){
		return apply_filters('the_content',$text);
	}
	
	function clear_tel($tel){
		return strip_tags(str_replace(array(' ','(',')','-'),'',$tel));
	}
	
	function social(){
		$socials=array('vk','in');
		$soc_html='<div class="social">';
		foreach($socials as $soc){
			if(!empty(get_acf_main($soc)))
				$soc_html.='<a class="'.$soc.'" rel="nofollow" target="_blank" href="'.get_acf_main($soc).'">'.$soc.'</a>';
		}
		$soc_html.='</div>';
		echo $soc_html;
	}
	
	function post_title(){
		global $post;
		$post_title=get_field('alt_zag',$post)!='' ? get_field('alt_zag',$post) : get_the_title($post);
		echo '<h1>'.$post_title.'</h1>';
	}
	
	function cat_title(){
		$qo=get_queried_object();
		$cat_title=get_field('alt_zag',$qo)!='' ? get_field('alt_zag',$qo) : $qo->name;
		echo '<h1>'.$cat_title.'</h1>';
	}
	
	function declension($digit,$expr,$onlyword=false){
        if(!is_array($expr)) $expr=array_filter(explode(' ', $expr));
        if(empty($expr[2])) $expr[2]=$expr[1];
        $i= preg_replace('/[^0-9]+/s','',$digit) % 100;
        if($onlyword) $digit='';
        if($i>=5 && $i<=20) $res=$expr[2];
        else {
            $i%=10;
            if($i==1) $res=$expr[0];
            elseif($i>=2 && $i<=4) $res=$expr[1];
            else $res=$expr[2];
        }
        return trim($res);
    }
	
	function cf7_modal($id=null,$name=null,$title=null){
		if(!empty($id) && !empty($name) && !empty($title)){
			echo '<div class="modal_form" id="'.$name.'"><div><img src="'.THEME.'/img/logo.png" alt="" /><p class="title">'.$title.'</p>'.do_shortcode('[contact-form-7 id="'.$id.'"]').'</div></div>';
		}
	}
	
	function cost_format($price){
		return number_format($price,0,'.',' ');
	}
	
	function disable_type_selector_wc($product_types){
		unset($product_types['grouped']);
		unset($product_types['external']);
		unset($product_types['variable']);
		return $product_types;
	}
	
	function disable_type_options_wc($product_options){
		return array();
	}
	
	function remove_admin_tabs_products($tabs){
		// unset($tabs['shipping']);
		unset($tabs['linked_product']);
		unset($tabs['advanced']);
		return($tabs);
	}
	
	function related_products_args($args){
		$args['posts_per_page']=10;
		return $args;
	}
	
	function search_query($query){
		if(!is_admin() && $query->is_search()) $query->set('post_type','product');
	}
	
	function is_product_in_cart($id){
		foreach(WC()->cart->get_cart() as $cart_item_key => $values){
			$cart_product=$values['data'];
			if($id==$cart_product->id) return true;
		}
		return false;
	}
	
	function add_meta_product_fields(){
		global $woocommerce,$post,$thepostid;
		/*woocommerce_wp_text_input(array(
			'id' => 'product_weight',
			'label' => 'Масса, гр',
			'placeholder' => '',
			'desc_tip' => false,
			'custom_attributes' => array('step' => 1),
			'description' => '',
			'type' => 'number'
		));*/
		woocommerce_wp_radio(array(
			'id' => 'product_flag',
			'wrapper_class' => 'wp_radio_custom',
			'label' => 'Флажок',
			'desc_tip' => '',
			'description' => '',
			'options' => array('no' => 'Нет','new' => 'Новинка','hit' => 'Хит')
		));
	}
	
	function save_meta_product_fields($post_id){
		//if(isset($_POST['product_weight'])) update_post_meta($post_id,'product_weight',esc_attr($_POST['product_weight']));
		if(isset($_POST['product_flag'])) update_post_meta($post_id,'product_flag',esc_attr($_POST['product_flag']));
	}
	
	if(isset($_GET['products_order'])){
		setcookie('products_order',$_GET['products_order'],time()+432000,'/');
		$_COOKIE['products_order']=$_GET['products_order'];
	}
	
	function view_filter(){
		$qo=get_queried_object();
		if(is_product_category()){
			echo '<form class="filter_p" action="'.get_term_link($qo).'" method="GET"><div class="sort">';
				$pr_order=array(
					'name_up' => 'По названию',
					'name_down' => 'По названию',
					'cost_up' => 'По цене',
					'cost_down' => 'По цене'
				);
				if(!isset($_COOKIE['products_order']) && empty($_COOKIE['products_order'])) $_COOKIE['products_order']='name_up';
				foreach($pr_order as $order_val => $order_name){
					$active=$order_val==$_COOKIE['products_order'] ? ' checked' : '';
					echo '<span><input type="radio" name="products_order" value="'.$order_val.'" id="'.$order_val.'"'.$active.' /><label for="'.$order_val.'">'.$order_name.'</label></span>';
				}
				echo '</div>';
				
				$data_filters=array();
				$data_prices=array();
				$pa_filters=array();
				foreach(wc_get_attribute_taxonomies() as $data_attr)
					if($data_attr->attribute_name!='obyom-sto-kazanov')
						$pa_filters[$data_attr->attribute_label]='pa_'.$data_attr->attribute_name;

				$f_posts=get_posts(array(
					'tax_query' => array(array(
						'taxonomy' => $qo->taxonomy,
						'field' => 'id',
						'terms' => $qo->term_id
					)),
					'post_type' => 'product',
					'nopaging' => true
				));
				foreach($f_posts as $f_product){
					foreach($pa_filters as $pa_filter){
						$attr_data=wc_get_product_terms($f_product->ID,$pa_filter);
						$attr_price=get_field('_regular_price',$f_product->ID);
						if(!empty($attr_data))
							foreach($attr_data as $attr_data_s)
								$data_filters[$attr_data_s->taxonomy][$attr_data_s->term_id]=$attr_data_s->name;
						if(!empty($attr_price))
							$data_prices[]=$attr_price;
					}
				}
				
				foreach($data_prices as $data_p){
					if(!isset($data_prices['min']) || $data_prices['min']>=$data_p) $data_prices['min']=floor($data_p);
					if(!isset($data_prices['max']) || $data_prices['max']<=$data_p) $data_prices['max']=ceil($data_p);
				}

				if(!empty($data_prices['min']) && !empty($data_prices['max'])){
					echo '<div class="filter">';
						$select_min_price=isset($_GET['price_min']) && !empty($_GET['price_min']) ? $_GET['price_min'] : $data_prices['min'];
						$select_max_price=isset($_GET['price_max']) && !empty($_GET['price_max']) ? $_GET['price_max'] : $data_prices['max'];
						?>
						<div class="f_price" data-min="<?php echo $data_prices['min']; ?>" data-max="<?php echo $data_prices['max']; ?>" data-s-min="<?php echo $select_min_price; ?>" data-s-max="<?php echo $select_max_price; ?>">
							<p class="title">Цена, рублей</p>
							<div></div>
							<input type="text" name="price_min" value="<?php echo $select_min_price; ?>" inputmode="numeric" />
							<input type="text" name="price_max" value="<?php echo $select_max_price; ?>" inputmode="numeric" />
						</div>
						<?php
						if(isset($data_filters['pa_obyom']) && count($data_filters['pa_obyom'])>1 && $qo->term_id!=468){
							$data_volume=array();
							foreach($data_filters['pa_obyom'] as $pa_name){
								$data_p=(float)str_replace(array('л',','),array('','.'),$pa_name);
								if(!isset($data_volume['min']) || $data_volume['min']>=$data_p) $data_volume['min']=$data_p;
								if(!isset($data_volume['max']) || $data_volume['max']<=$data_p) $data_volume['max']=$data_p;
							}
							if($data_volume['max']>1){
								$data_volume['min']=floor($data_volume['min']);
								$data_volume['max']=ceil($data_volume['max']);
							}
							$select_min_volume=isset($_GET['volume_min']) && !empty($_GET['volume_min']) ? $_GET['volume_min'] : $data_volume['min'];
							$select_max_volume=isset($_GET['volume_max']) && !empty($_GET['volume_max']) ? $_GET['volume_max'] : $data_volume['max'];
							?>
							<div class="f_volume" data-min="<?php echo $data_volume['min']; ?>" data-max="<?php echo $data_volume['max']; ?>" data-s-min="<?php echo $select_min_volume; ?>" data-s-max="<?php echo $select_max_volume; ?>">
								<p class="title">Объем, литров</p>
								<div></div>
								<input type="text" name="volume_min" value="<?php echo $select_min_volume; ?>" inputmode="decimal" />
								<input type="text" name="volume_max" value="<?php echo $select_max_volume; ?>" inputmode="decimal" />
							</div>
							<?php
						}
						if(!empty($data_filters)){
							echo '<div class="select_filter">';
							foreach($pa_filters as $pa_name => $pa_filter){
								if(isset($data_filters[$pa_filter]) && count($data_filters[$pa_filter])>1 && $pa_filter!='pa_obyom'){
									$count=isset($_GET[$pa_filter]) ? ' ('.count($_GET[$pa_filter]).')' : '';
									?>
									<div class="item">
										<p><?php echo $pa_name.$count; ?></p>
										<div class="data">
											<?php
											asort($data_filters[$pa_filter]);
											foreach($data_filters[$pa_filter] as $pa_id => $pa_name){
												$checked=isset($_GET[$pa_filter]) && array_search($pa_id,$_GET[$pa_filter])!==false ? ' checked' : '';
												echo '<label><input type="checkbox"'.$checked.' name="'.$pa_filter.'[]" value="'.$pa_id.'" /><span>'.$pa_name.'</span></label>';
											}
											?>
										</div>
									</div>
									<?php
								}
							}
							echo '</div>';
							echo '<input type="submit" value="Подобрать" />';
							echo '<a class="reset" href="'.get_term_link($qo->term_id).'">Сбросить</a>';
						}
					echo '</div>';
				}
			echo '</form>';
		}
	}
	
	function filter_product_query($query){
		if(!is_admin() && is_product_category() && $query->is_main_query()){
			if(!isset($_COOKIE['products_order']) && empty($_COOKIE['products_order'])) $_COOKIE['products_order']='name_up';
			$pr_order=array();
			$pr_order['name_up']=array('orderby' => 'title','order' => 'ASC','meta_key' => '');
			$pr_order['name_down']=array('orderby' => 'title','order' => 'DESC','meta_key' => '');
			$pr_order['cost_up']=array('orderby' => '_regular_price','order' => 'ASC','meta_key' => '_regular_price');
			$pr_order['cost_down']=array('orderby' => '_regular_price','order' => 'DESC','meta_key' => '_regular_price');
			
			//$query->set('meta_key',$pr_order[$_COOKIE['products_order']]['meta_key']);
			$query->set('orderby',$pr_order[$_COOKIE['products_order']]['orderby']);
			$query->set('order',$pr_order[$_COOKIE['products_order']]['order']);
			$query->set('posts_per_page',18);
			
			$meta_query=array();
			if($_COOKIE['products_order']=='cost_up' || $_COOKIE['products_order']=='cost_down')
				$meta_query[$pr_order[$_COOKIE['products_order']]['orderby']]=array(
					'key' => $pr_order[$_COOKIE['products_order']]['meta_key'],
					'type' => 'NUMERIC'
				);
			if(isset($_GET['price_min']) && !empty($_GET['price_min']) && $_GET['price_min']!=0 && isset($_GET['price_max']) && !empty($_GET['price_max']) && $_GET['price_max']!=0){
				$meta_query['price_min']=array(
					'key' => '_regular_price',
					'value' => $_GET['price_min'],
					'compare' => '>=',
					'type' => 'NUMERIC'
				);
				$meta_query['price_max']=array(
					'key' => '_regular_price',
					'value' => $_GET['price_max'],
					'compare' => '<=',
					'type' => 'NUMERIC'
				);
			}
			$query->set('meta_query',$meta_query);
			
			$query_filter=array();
			$pa_filters=array();
			foreach(wc_get_attribute_taxonomies() as $data_attr)
				$pa_filters[$data_attr->attribute_label]='pa_'.$data_attr->attribute_name;
				
			foreach($pa_filters as $pa_name => $pa_filter){
				if(isset($_GET[$pa_filter])){
					$query_filter[]=array(
						array(
							'taxonomy' => $pa_filter,
							'field' => 'id',
							'terms' => $_GET[$pa_filter]
						)
					);
				}
			}
			if(isset($_GET['volume_min']) && !empty($_GET['volume_min']) && $_GET['volume_min']!=0 && isset($_GET['volume_max']) && !empty($_GET['volume_max']) && $_GET['volume_max']!=0){
				$data_terms_volume=get_terms(array(
					'taxonomy' => 'pa_obyom',
					'hide_empty' => false
				));
				$data_array_volume=array();
				foreach($data_terms_volume as $data_term_volume){
					$data_v=(float)str_replace(array('л',','),array('','.'),$data_term_volume->name);
					//$data_v=(float)str_replace('л','',$data_term_volume->name);
					if((float)$_GET['volume_min']<=$data_v && (float)$_GET['volume_max']>=$data_v)
						$data_array_volume[]=$data_term_volume->term_id;
				}
				if(!empty($data_array_volume))
					$query_filter[]=array(
						array(
							'taxonomy' => 'pa_obyom',
							'field' => 'id',
							'terms' => $data_array_volume
						)
					);
			}
			if(!empty($query_filter))
				$query->set('tax_query',$query_filter);
		}
	}
	
	function product_price_default($post_id){
		if(isset($_POST['_regular_price']) && (trim($_POST['_regular_price'])=='' || trim($_POST['_regular_price'])<0))
			update_post_meta($post_id,'_regular_price',0);
    }
	
	function ajax_update_cart(){
		if($_POST['product_id']>0){
			$qty=$_POST['qty'] ? $_POST['qty'] : 1;
			WC()->cart->add_to_cart($_POST['product_id'],$qty);
		}
		wp_die();
	}

	function info_cart($fragments){
		$fragments['header.main .h_bottom .cart_view']='<div class="cart_view"><a href="'.get_permalink(CART).'" class="count"><span>'.WC()->cart->get_cart_contents_count().'</span> '.declension(WC()->cart->get_cart_contents_count(),array('товар','товара','товаров')).'</a></div>';
		$fragments['.mobile_menu .cart_view']='<div class="cart_view"><a href="'.get_permalink(CART).'" class="count"><span>'.WC()->cart->get_cart_contents_count().'</span> '.declension(WC()->cart->get_cart_contents_count(),array('товар','товара','товаров')).'</a></div>';
		return $fragments;
	}
	
	function remove_fields_cart($fields){
		unset($fields['billing']['billing_address_2']);
		// unset($fields['billing']['billing_country']);
		unset($fields['billing']['billing_state']);
		unset($fields['billing']['billing_postcode']);
		unset($fields['billing']['billing_company']);
		unset($fields['shipping']['shipping_country']);
		$fields['billing']['billing_address_1']['placeholder']='';
		$fields['order']['order_comments']['label']='Примечания к Вашему заказу';
		$fields['order']['order_comments']['placeholder']='';
		
		$chosen_methods=WC()->session->get('chosen_shipping_methods');
		if('local_pickup:5'=== $chosen_methods[0]){
			unset($fields['billing']['billing_address_1']);
			unset($fields['billing']['billing_city']);
		}
		
		return $fields;
	}
	
	function update_form_billing($fragments){
		$checkout=WC()->checkout();
		ob_start();
		echo '<div class="woocommerce-billing-fields__field-wrapper">';
			$fields=$checkout->get_checkout_fields('billing');
			foreach($fields as $key => $field)
				woocommerce_form_field($key,$field,$checkout->get_value($key));
		echo '</div>';
		$add_update_form_billing=ob_get_clean();
		$fragments['.woocommerce-billing-fields']=$add_update_form_billing;

		return $fragments;
	}
	
	function script_update_shipping(){
		if(is_checkout()){ ?>
		<script>
            jQuery(document).ready(function($){
                $(document.body).on('updated_checkout updated_shipping_method',function(event,xhr,data){
                    $('input[name^="shipping_method"]').on('change',function (){
                        $('.woocommerce-billing-fields__field-wrapper').block({
                            message:null,
                            overlayCSS:{
                                background:'#fff',
                                'z-index':1000000,
                                opacity:0.3
                            }
                        });
                    });
                    var first_name=$('#billing_first_name').val(),
                        last_name=$('#billing_last_name').val(),
                        phone=$('#billing_phone').val(),
                        email=$('#billing_email').val();
                        billing_address_1=$('#billing_address_1').val();
                        billing_city=$('#billing_city').val();
                        order_comments=$('#order_comments').val();
                        
                    $(".woocommerce-billing-fields__field-wrapper").html(xhr.fragments[".woocommerce-billing-fields"]);
                    $(".woocommerce-billing-fields__field-wrapper").find('input[name="billing_first_name"]').val(first_name);
                    $(".woocommerce-billing-fields__field-wrapper").find('input[name="billing_last_name"]').val(last_name);
                    $(".woocommerce-billing-fields__field-wrapper").find('input[name="billing_phone"]').val(phone);
                    $(".woocommerce-billing-fields__field-wrapper").find('input[name="billing_email"]').val(email);
                    $(".woocommerce-billing-fields__field-wrapper").find('input[name="billing_address_1"]').val(billing_address_1);
                    $(".woocommerce-billing-fields__field-wrapper").find('input[name="billing_city"]').val(billing_city);
                    $(".woocommerce-billing-fields__field-wrapper").find('input[name="order_comments"]').val(order_comments);
                    $('.woocommerce-billing-fields__field-wrapper').unblock();
                });
            });
		</script>
		<?php
		}
	}
	
	function change_text_error_wc($error){
		$error=str_replace(' для выставления счета','',$error);
		return $error;
	}
	
	function add_checkout_field($checkout){
		woocommerce_form_field('discond_card',
			array(
				'type' => 'text',
				'class' => array('my-field-class form-row-wide'),
				'label' => 'Номер дисконтной карты',
				'placeholder' => ''
			),
			$checkout->get_value('discond_card')
		);
	}
	
	function add_checkout_field_after($checkout){
		woocommerce_form_field('select_help',
			array(
				'type' => 'radio',
				'class' => array('my-field-class form-row-wide'),
				'label' => '',
				'placeholder' => '',
				'custom_attributes' => array('required' => 'required'),
				'options' => array('Нужно уточнение заказа' => 'Мне нужно позвонить для уточнения заказа','Уточнение заказа не требуется' => 'Звонок не нужен, я подтверждаю правильность заказа и контактных данных')
			),
			$checkout->get_value('select_help')
		);
	}
	
	function update_custom_checkout_field($order_id){
		if(!empty($_POST['discond_card']))
			update_post_meta($order_id,'discond_card',sanitize_text_field($_POST['discond_card']));
		if(!empty($_POST['select_help']))
			update_post_meta($order_id,'select_help',sanitize_text_field($_POST['select_help']));
	}
	
	function check_custom_fields_checkout($fields,$errors){
		if(empty($_POST['select_help']))
			$errors->add('woocommerce_password_error','Выберите, нужно ли Вам позвонить и уточнить заказ');
	}

	function mail_custom_checkout_field($keys){
		$keys['Номер дисконтной карты']='discond_card';
		$keys['Помощь при заказе']='select_help';
		return $keys;
	}
	
	function view_custom_checkout_field_for_admin($order){
		echo '<p><strong style="display:block;">Номер дисконтной карты:</strong> '.get_post_meta($order->id,'discond_card',true).'</p>';
		echo '<p><strong style="display:block;">Помощь при заказе:</strong> '.get_post_meta($order->id,'select_help',true).'</p>';
	}
	
	function extra_pay_card(WC_Cart $cart){
		if(is_admin() && !defined('DOING_AJAX')) return;
		$extra_pay_card=2.5;
		// if(WC()->session->get('chosen_payment_method')=='sber' && $extra_pay_card!=0){
		if(WC()->session->get('chosen_payment_method')=='yookassa_epl' && $extra_pay_card!=0){
			$discount=$cart->subtotal*($extra_pay_card/100);
			$cart->add_fee('Комиссия банка '.$extra_pay_card.'%',$discount);
		}
	}
	
	function create_product_attribute($label_name){
		global $wpdb;
		$slug=substr(sanitize_title($label_name),0,28);

		if(strlen($slug)>=28) return '';
		elseif(wc_check_if_attribute_name_is_reserved($slug)) return '';
		elseif(taxonomy_exists(wc_attribute_taxonomy_name($label_name))) return '';
    
		$data=array(
			'attribute_label'   => $label_name,
			'attribute_name'    => $slug,
			'attribute_type'    => 'select',
			'attribute_orderby' => 'name',
			'attribute_public'  => 0
		);
		$results=$wpdb->insert("{$wpdb->prefix}woocommerce_attribute_taxonomies",$data);
		if(is_wp_error($results)) return '';
		$id=$wpdb->insert_id;
		
		do_action('woocommerce_attribute_added',$id,$data);
		wp_schedule_single_event(time(),'woocommerce_flush_rewrite_rules');
		delete_transient('wc_attribute_taxonomies');
	}

?>