<?php

	function view_cat_catalog($class='catalog_menu'){
		echo '<ul class="'.$class.'"><li class="parent"><a href="'.get_permalink(SHOP).'">Каталог</a><ul>';
			if(is_tax(array('product_cat'))) $current_id=get_queried_object_id();
			$main_cats=get_terms(array(
				'taxonomy' => 'product_cat',
				'parent' => 0,
				// 'parent' => $current_id->term_id,
				'hide_empty' => false,
				'exclude' => 15
			));
			foreach($main_cats as $main_cat){
				$id_img_cat=get_field('thumbnail_id',$main_cat);
				$img_cat_src=!empty($id_img_cat) ? wp_get_attachment_image_url($id_img_cat,'medium') : '';
				$active=isset($current_id) && $current_id==$main_cat->term_id ? ' class="current-menu-item"' : '';
				echo '<li'.$active.'><a href="'.get_term_link($main_cat).'" style="background-image:url('.$img_cat_src.')">'.$main_cat->name.'</a></li>';
			}
		echo '</ul></li></ul>';
	}

	function OLD_view_cat_catalog($class='catalog_menu'){
		echo '<ul class="'.$class.'"><li class="parent"><a href="'.get_permalink(SHOP).'">Каталог</a><ul>';
			if(is_tax(array('product_cat'))) $current_id=get_queried_object_id();
			$main_cats=get_terms(array(
				'taxonomy' => 'product_cat',
				'parent' => 0,
				'hide_empty' => false,
				'exclude' => 15
			));
			foreach($main_cats as $main_cat){
				$sub_cats=get_terms(array(
					'taxonomy' => $main_cat->taxonomy,
					'parent' => $main_cat->term_id,
					'hide_empty' => false
				));
				$parent=!empty($sub_cats) ? ' class="parent"' : '';
				$id_img_cat=get_field('thumbnail_id',$main_cat);
				$img_cat_src=!empty($id_img_cat) ? wp_get_attachment_image_url($id_img_cat,'medium') : '';
				echo '<li'.$parent.'><a href="'.get_term_link($main_cat).'" style="background-image:url('.$img_cat_src.')">'.$main_cat->name.'</a>';
				
				if(!empty($sub_cats)){
					echo '<ul>';
						foreach($sub_cats as $sub_cat){
							$id_img_cat=get_field('thumbnail_id',$sub_cat);
							$img_cat_src=!empty($id_img_cat) ? wp_get_attachment_image_url($id_img_cat,'medium') : '';
							$active=isset($current_id) && $current_id==$sub_cat->term_id ? ' class="current-menu-item"' : '';
							echo '<li'.$active.' style="background-image:url('.$img_cat_src.')"><a href="'.get_term_link($sub_cat).'">'.$sub_cat->name.'</a></li>';
						}
					echo '</ul>';
				}
				echo '</li>';
			}
		echo '</ul></li></ul>';
	}
	
	function view_manufacturer(){
		$manufacturers=get_terms(array(
			'taxonomy' => 'pa_proizvoditel',
			'hide_empty' => false
		));
		if(!empty($manufacturers)){
			if(is_tax(array('pa_proizvoditel'))) $current_id=get_queried_object_id();
			?>
			<ul class="manufacturers">
				<li class="parent">
					<a href="#">Производители</a>
					<ul>
					<?php
						foreach($manufacturers as $manufacturer){
							$active=isset($current_id) && $current_id==$manufacturer->term_id ? ' class="current-menu-item"' : '';
							echo '<li'.$active.'><a href="'.get_term_link($manufacturer).'">'.$manufacturer->name.'</a></li>';
						}
					?>
					</ul>
				</li>
			</ul>
			<?php
		}
	}
	
	function contact_post(){
		?>
		<div class="contact_block clr">
			<div class="left_block">
				<div class="adr">
					<p>Как нас найти:</p>
					<div>
						<p>Воронежская область, <?php acf_main('adr'); ?></p>
					</div>
				</div>
				<div class="tels">
					<p>Контактный телефон:</p>
					<div>
						<p><a href="tel:<?php echo clear_tel(get_acf_main('tel_1')); ?>"><?php acf_main('tel_1'); ?></a></p>
						<?php
						if(!empty(get_acf_main('tel_2')))
							echo '<p><a href="tel:'.clear_tel(get_acf_main('tel_2')).'">'.get_acf_main('tel_2').'</a></p>';
						?>
					</div>
				</div>
				<div class="worktime">
					<p>Время работы:</p>
					<div>
						<p><?php acf_main('worktime'); ?></p>
					</div>
				</div>
				<div class="email">
					<p>E-mail:</p>
					<div>
						<p><a href="mailto:<?php acf_main('email'); ?>"><?php acf_main('email'); ?></a></p>
					</div>
				</div>
			</div>
			<div class="right_block">
				<p class="h1">Карта</p>
				<div class="map">
					<?php acf_main('map'); ?>
				</div>
			</div>
		</div>
		
		<?php /*<img src="/wp-content/uploads/2021/01/IMG_0808-scaled.jpg" alt="" style="display:block;margin:15px 0;" />*/ ?>
		
		<div class="contact_feedback">
			<p class="h1">Задать вопрос</p>
			<?php echo do_shortcode('[contact-form-7 id="209"]'); ?>
		</div>
		<?php
	}

?>