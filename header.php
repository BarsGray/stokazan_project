<!DOCTYPE html>
<html lang="ru-RU">
<head>
	<title><?php echo wp_get_document_title(); ?></title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="format-detection" content="telephone=no">
	<link rel="icon" href="<?php echo THEME; ?>/img/favicon.png" type="image/png" />
	<link rel="shortcut icon" href="<?php echo THEME; ?>/img/favicon.png" type="image/png" />
	<meta name="yandex-verification" content="263b37eebbdaf7ea" />
	<meta name="google-site-verification" content="DdReNltRbP413w2E6rL9PQrc5-a7JAdomXAfnb1vXT4" />
	<?php wp_head(); ?>
</head>
<body>
	<div class="over"></div>
	<div class="mobile_menu">
		<span></span>
		<div class="close"></div>
		<ul>
			<li<?php if(is_singular() && $post->ID==MAIN_PAGE) echo ' class="current-menu-item"'; ?>>
				<a href="/"><?php echo get_the_title(MAIN_PAGE); ?></a>
			</li>
		</ul>
		<?php
			OLD_view_cat_catalog('catalog_mobile');
			view_manufacturer();
			wp_nav_menu('menu=header_menu&container=false&depth=1');
		?>
		<ul>
			<li<?php if(is_singular() && $post->ID==19) echo ' class="current-menu-item"'; ?>>
				<a href="<?php the_permalink(19); ?>"><?php echo get_the_title(19); ?></a>
			</li>
		</ul>
		<div class="cart_view">
			<a href="<?php the_permalink(CART); ?>" class="count"><span>0</span> товаров</a>
		</div>
		<a class="kredit" href="<?php the_permalink(1333); ?>"><img src="<?php echo THEME; ?>/img/1_pokupay_button_credit_white.png" alt="" /></a>
		<a class="kredit" href="<?php the_permalink(1333); ?>"><img src="<?php echo THEME; ?>/img/1_pokupay_button_rass_white.png" alt="" /></a>
	</div>
	
	<header class="fixed">
		<div class="wr">
			<div class="h_mobile">
				<a href="tel:<?php echo clear_tel(get_acf_main('tel_1')); ?>" class="tel"></a>
				<a href="mailto:<?php acf_main('email'); ?>" class="email"></a>
				<a href="#" class="search"></a>
				<a href="<?php the_permalink(19); ?>" class="map"></a>
				<a href="<?php the_permalink(CART); ?>" class="cart"></a>
				<a href="#" class="menu"></a>
			</div>
			<form action="/" method="GET" class="h_search">
				<input type="text" name="s" placeholder="Поиск по сайту" />
				<input type="submit" value="" />
			</form>
			<div class="h_desktop">
				<form action="/" method="GET">
					<input type="text" name="s" placeholder="Поиск по сайту" />
					<input type="submit" value="" />
				</form>
				<?php wp_nav_menu('menu=header_menu&container=false&depth=1'); ?>
			</div>
		</div>
	</header>
	
	<header class="main">
		<div class="wr">
			<div class="h_top">
				<a href="/" class="logo">
					<img src="<?php echo THEME; ?>/img/logo_red.png" alt="" />
					<span>Сто казанов</span>
					<span>оптово-розничная компания</span>
				</a>
				<div class="h_top_block adr">
					<p>Как нас найти:</p>
					<p><?php acf_main('adr'); ?></p>
				</div>
				<div class="h_top_block tel">
					<p>Контактный телефон:</p>
					<p><?php acf_main('tel_1'); ?></p>
				</div>
				<?php social(); ?>
			</div>
			<?php /*<p class="warn">На сайте ведутся технические работы! Уточняйте цены по телефону +7 (920) 453-06-65!</p>*/ ?>
			<div class="h_bottom">
				<div class="cart_view">
					<a href="<?php the_permalink(CART); ?>" class="count"><span>0</span> товаров</a>
				</div>
				<a class="kredit" href="<?php the_permalink(1333); ?>"><img src="<?php echo THEME; ?>/img/1_pokupay_button_rass_white.png" alt="" /></a>
				<a class="kredit" href="<?php the_permalink(1333); ?>"><img src="<?php echo THEME; ?>/img/1_pokupay_button_credit_white.png" alt="" /></a>
				<ul>
					<li class="home">
						<a href="/"></a>
					</li>
				</ul>
				<?php
					OLD_view_cat_catalog();
					view_manufacturer();
				?>
				<ul>
					<li<?php if(is_singular() && $post->ID==19) echo ' class="current-menu-item"'; ?>>
						<a href="<?php the_permalink(19); ?>"><?php echo get_the_title(19); ?></a>
					</li>
				</ul>
			</div>
		</div>
	</header>
	
	<?php //wp_nav_menu('menu=header_menu&container=false&depth=1'); ?>
		