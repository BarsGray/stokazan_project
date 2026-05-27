	<footer>
		<div class="wr">
			<?php
			if(type_page()=='main'){
				?>
				<div class="footer_top">
					<p>Форма обратной связи</p>
					<?php echo do_shortcode('[contact-form-7 id="7"]'); ?>
				</div>
				<?php
			}
			?>
			<div class="footer_middle">
				<div class="adr">
					<p>Как нас найти:</p>
					<div>
						<p><?php acf_main('adr'); ?></p>
					</div>
				</div>
				<div class="tel">
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
					<p>Электронная почта:</p>
					<div>
						<p><a href="mailto:<?php acf_main('email'); ?>"><?php acf_main('email'); ?></a></p>
					</div>
				</div>
			</div>
			<div class="footer_bottom">
				<a href="/" class="logo">
					<img src="<?php echo THEME; ?>/img/logo_white.png" alt="" />
					<span>Сто казанов</span>
					<span>оптово-розничная компания</span>
				</a>
				<?php wp_nav_menu('menu=footer_menu&container=false&depth=1'); ?>
			</div>
			
			<? /* <div class="vzh">
				<?php if( is_front_page() ){ ?>
					<a href="https://vzh.ru/service/internet-magazin/?from=<?php echo $_SERVER['HTTP_HOST'];?>" target="_blank"><img src="<?php bloginfo('template_directory');?>/img/vzh.png" alt="разработка интернет-магазина под продвижение в аспект"></a>
				<?php } else { ?>
					<img src="<?php bloginfo('template_directory');?>/img/vzh.png" alt="разработка интернет-магазина под продвижение в аспект">
				<?php } ?>
			</div> */ ?>
		</div>
	</footer>
	<?php
	if(!isset($_COOKIE['gdpr_site']))
		echo '<div class="gdpr"><p>Наш сайт использует файлы cookie, чтобы улучшить работу сайта, повысить его эффективность и удобство. Продолжая использовать сайт стоказанов.рф, вы соглашаетесь <a href="'.get_permalink(4804).'" target="_blank">на использование файлов cookie</a>.</p><a href="#">Хорошо</a></div>';
	?>
	<?php wp_footer(); ?>
	<!-- Yandex.Metrika counter --> <script type="text/javascript" > (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)}; m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)}) (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym"); ym(68852359, "init", { clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true }); </script> <noscript><div><img src="https://mc.yandex.ru/watch/68852359" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter -->
	<script src="//code.jivosite.com/widget/ApRUJv7xch" async></script>
</body>
</html>