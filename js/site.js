jQuery(document).ready(function($){
	
	$(document).on('change','input[name="shipping_method[0]"]',function(){
        $(document.body).trigger('update_checkout');
	});
			
	var wid_sc=window.innerWidth;
	$(window).resize(function(){wid_sc=window.innerWidth;});
	
	//fix a
	$('a').click(function(e){
		if($(this).attr('href')=='#') e.preventDefault();
	});
	
	//fix_table
	$('.text').find('table').each(function(){
		$(this).wrap('<div class="over_table"></div>');
	});
	
	//view_gallery
	var delay1=1,delay2=10;
	$('.acf_gal > a').each(function(){
		if(delay2==100){ delay2=0;delay1++; }
		$(this).attr('style','-webkit-animation-delay:'+delay1+'.'+delay2+'s;animation-delay:'+delay1+'.'+delay2+'s;');
		delay2=delay2+10;
	});
	
	//sec-0-1
	if($('#sec-0-1 .sec_data').is('div')){
		$('#sec-0-1 .sec_data.owl').owlCarousel({
			loop:true,
			autoplay:true,
			autoplayHoverPause:true,
			autoplayTimeout:8000,
			items:1,
			margin:5,
			nav:false,
			dots:true
		});
	}
	
	//sec-1
	if($('#sec-1 .sec_data').is('div')){
		$('#sec-1 .sec_data.owl').owlCarousel({
			loop:true,
			autoplay:true,
			autoplayHoverPause:true,
			autoplayTimeout:8000,
			items:1,
			margin:5,
			nav:false,
			dots:true
		});
	}
	
	//tabs
	$('.tabs .tabs_name > div > span').click(function(){
		if(!$(this).hasClass('active')){
			$(this).addClass('active').siblings('span').removeClass('active').parent().siblings('p').text($(this).text()).closest('.tabs').find('.tabs_content > div').removeClass('active').eq($(this).index()).addClass('active');
		}
		if($(this).closest('.tabs_name').hasClass('compact'))
			$(this).parent().siblings('p').trigger('click');
	});
	if($('.tabs').is('div')){
		$(window).resize(function(){
			if($('.tabs .tabs_name:not(.compact) > div').height()>50)
				$('.tabs .tabs_name').addClass('compact');
		});
	}
	$('.tabs').on('click','.tabs_name.compact > p',function(){
		$(this).toggleClass('active').siblings('div').slideToggle();
	});
	
	//catalog
	if($('#sec-2 .catalog.owl, #sec-2_2 .catalog.owl').is('div')){
		$('#sec-2 .catalog.owl, #sec-2_2 .catalog.owl').owlCarousel({
			loop:true,
			dots:true,
			nav:false,
			autoplay:true,
			autoplayHoverPause:true,
			autoplayTimeout:8000,
			responsive:{
				0:{items:1,margin:5},
				601:{items:2,margin:40},
				901:{items:3,margin:40},
				1201:{items:4,margin:40}
			}
		});
	}
	if($('#content .product_page .related_products .catalog.owl').is('div')){
		$('#content .product_page .related_products .catalog.owl').owlCarousel({
			loop:true,
			dots:true,
			nav:false,
			autoplay:true,
			autoplayHoverPause:true,
			autoplayTimeout:8000,
			responsive:{
				0:{items:1,margin:5},
				541:{items:2,margin:30},
				791:{items:3,margin:30},
				851:{items:2,margin:30},
				1101:{items:3,margin:30}
			}
		});
	}
	
	//sale_block
	if($('.sale_block.owl').is('div')){
		$('.sale_block.owl').owlCarousel({
			loop:true,
			dots:true,
			nav:false,
			autoplay:true,
			autoplayHoverPause:true,
			autoplayTimeout:8000,
			items:1,
			margin:5
		});
	}
	
	//foto_block
	if($('#sec-5 .sec_data').is('div')){
		$('#sec-5 .sec_data.owl').owlCarousel({
			loop:true,
			dots:false,
			nav:true,
			navText:['',''],
			autoplay:true,
			autoplayHoverPause:true,
			autoplayTimeout:8000,
			responsive:{
				0:{items:1,margin:5},
				391:{items:2,margin:20},
				701:{items:3,margin:0},
				901:{items:4,margin:0}
			}
		});
	}
	
	//menu
	$('.over,.mobile_menu .close').click(function(){
		$('body').css('overflow','auto').find('.over').fadeOut().siblings('.mobile_menu').removeClass('active');
	});
	$('header.fixed .h_mobile > a.menu').click(function(){
		$('body').css('overflow','hidden').find('.over').fadeIn().siblings('.mobile_menu').addClass('active');
	});
	$('.mobile_menu ul > li.parent > a').click(function(e){
		e.preventDefault();
		$(this).parent().toggleClass('active').children('ul').slideToggle();
	});
	$('.mobile_menu > ul > li.parent > ul > li.current-menu-item').parent().slideToggle().parent().toggleClass('active');
	$('.mobile_menu > ul > li.parent > ul > li.parent > ul > li.current-menu-item').parent().slideToggle().parent().toggleClass('active').parent().slideToggle().parent().toggleClass('active');
	$('header.fixed .h_mobile > a.search').click(function(){
		$(this).parent().siblings('.h_search').toggleClass('active');
	});
	
	var scroll_scr=$('body').scrollTop();
	if(scroll_scr==0) scroll_scr=$('html').scrollTop();
	$(window).scroll(function(){
		scroll_scr=$('body').scrollTop();
		if(scroll_scr==0) scroll_scr=$('html').scrollTop();
	});
	
	//пусть пока так
	$('header.main .h_bottom > ul > li.parent').hover(function(){
		setTimeout(function(){
			$('header.main .h_bottom ul.catalog_menu > li > ul > li.parent').each(function(){
				$(this).children('ul').css({
					'left':$(this).offset().left+$(this).width()+'px',
					'top':$(this).offset().top-scroll_scr+'px',
					'position':'fixed'
				});
			});
		},500);
	});
	$(window).resize(function(){
		$('header.main .h_bottom ul.catalog_menu > li > ul > li.parent').each(function(){
			$(this).children('ul').css({
				'left':$(this).offset().left+$(this).width()+'px',
				'top':$(this).offset().top-scroll_scr+'px',
				'position':'fixed'
			});
		});
	});
	$(window).scroll(function(){
		$('header.main .h_bottom ul.catalog_menu > li > ul > li.parent').each(function(){
			$(this).children('ul').css({
				'left':$(this).offset().left+$(this).width()+'px',
				'top':$(this).offset().top-scroll_scr+'px',
				'position':'fixed'
			});
		});
	});
	
	//hide_text
	if($('.hide_text > div').is('div')){
		$(window).resize(function(){
			$('.hide_text > div').each(function(i,el){
				if(!$(this).parent().hasClass('active')){
					if($(this).height()+2>=el.scrollHeight)
						$(this).parent().addClass('deactivate');
					else
						$(this).parent().removeClass('deactivate');
				}
			});
		});
	}
	$('.hide_text > a').click(function(){
		$(this).parent().toggleClass('active');
		$(window).resize();
	});
	
	//catalog_menu
	$('.catalog_aside ul.catalog_menu > li > ul > li.parent > a').click(function(e){
		e.preventDefault();
		$(this).parent().toggleClass('active').children('ul').slideToggle();
	});
	$('.catalog_aside ul.catalog_menu > li > ul > li > ul > li.current-menu-item').parent().slideToggle().parent().toggleClass('active');
	$('#content aside ul.catalog_menu > li > a').click(function(e){
		e.preventDefault();
		if(wid_sc<851)
			$(this).toggleClass('active').siblings('ul').slideToggle();
	});
	
	//filter
	$('form.filter_p div.sort input[type="radio"]').click(function(){
		$('form.filter_p').trigger('submit');
	});
	$('#content .filter_p div.filter .item > p').click(function(){
		$(this).parent().toggleClass('active');
	});
	
	//qty
	$('body').on('click','.qty_block .change_quantity',function(e){
		e.preventDefault();
        var quantity_input=$(this).siblings('.product_quantity'),
			quantity=parseInt(quantity_input.val());
		
        if($(this).hasClass('minus')) quantity--;
        if($(this).hasClass('plus')) quantity++;
		
        quantity_input.val(quantity).trigger('change');
    });
	
	$('body').on('change','.qty_block .product_quantity',function(){
		var quantity=parseInt($(this).val()),
            min=$(this).data('min'),
            max=$(this).data('max');

        if(min==='') min=1;
        if(max==='' || max==-1) max=999;
		
        if(quantity<min) quantity=min;
        if(quantity>max) quantity=max;

        $(this).val(quantity);
		
		if($(this).parent().siblings('.cart_btn').is('div'))
			$(this).parent().siblings('div.cart_btn').children('a').attr('data-qty',quantity);
		
		update_page_cart();
	});
	
	//cart
	function update_header_cart(prod_id=0,qty=0){
		$.ajax({
			type:'post',
			url:'/wp-admin/admin-ajax.php',
			data:'action=ajax_update_cart&product_id='+prod_id+'&qty='+parseInt(qty),
			success:function(data){
				update_page_cart();
				$('body').trigger('wc_fragment_refresh');
				setTimeout(function(){$(window).resize();},500);
			}
		});
		setTimeout(function(){$(window).resize();},2200);
	}
	
	function update_page_cart(){
		$('.shop_table').find('.button.update_cart').removeAttr('disabled').trigger('click');
	}
	
	$('body').on('click','.catalog > div a.add_cart',function(e){
		e.preventDefault();
		if(!$(this).hasClass('is_cart')){
			update_header_cart($(this).data('id'),$(this).attr('data-qty'));
			$(this).text('добавлено').addClass('is_cart');
		}
	});
	
	$('#content .product_page .two_info_product .right_block .info_product .cart_btn > a').click(function(e){
		e.preventDefault();
		if(!$(this).hasClass('is_cart')){
			update_header_cart($(this).data('id'),$(this).attr('data-qty'));
			$(this).text('Товар в корзине').addClass('is_cart');
			$(this).parent().siblings('.qty_block').remove();
		}
	});
	
	$('body').on('click','#content .shop_table td.product-remove a',function(){
		update_header_cart();
	});
	
	//change_pay
	$('#content .woocommerce form.checkout').on('change','.wc_payment_method .input-radio',function(){
		$('body').trigger('update_checkout');
	});
	
	//product_slider
	if($('#content .product_page .two_info_product .left_block .main_img').hasClass('owl')){
		var product_slider=$('#content .product_page .two_info_product .left_block .main_img.owl').owlCarousel({
			items:1,
			loop:true,
			dots:true,
			nav:false,
			autoplay:true,
			autoplayHoverPause:true,
			autoplayTimeout:8000
		});
		var sub_product_slider=$('#content .product_page .two_info_product .left_block .sub_img.owl').owlCarousel({
			loop:false,
			dots:false,
			nav:false,
			autoplay:false,
			items:3,
			margin:14
		});
		product_slider.on('changed.owl.carousel',function(event){
			$('#content .product_page .two_info_product .left_block .sub_img .item').removeClass('active').parent().eq(event.page.index).children('.item').addClass('active');
			sub_product_slider.trigger('to.owl.carousel',event.page.index);
		});
		$('#content .product_page .two_info_product .left_block .sub_img .owl-item').click(function(){
			product_slider.trigger('to.owl.carousel',$(this).index());
		});
	}
	
	//range_price
	if($('form.filter_p .filter .f_price').is('div')){
		data_range_price=document.querySelector('form.filter_p .filter .f_price > div');
		data_price=$('form.filter_p .filter .f_price');
		noUiSlider.create(data_range_price,{
			start:[parseInt(data_price.data('s-min')),parseInt(data_price.data('s-max'))],
			connect:true,
			step:1,
			// tooltips:true,
			range:{
				min:parseInt(data_price.data('min')),
				max:parseInt(data_price.data('max'))
			},
			pips:{
				mode:'positions',
				values:[0,25,50,75,100],
				density:4
			}
		});
		data_range_price.noUiSlider.on('update',function(values,handle,unencoded,tap,positions,noUiSlider){
			$('form.filter_p .filter .f_price input[name="price_min"]').val(parseInt(values[0]));
			$('form.filter_p .filter .f_price input[name="price_max"]').val(parseInt(values[1]));
			$('form.filter_p .filter .f_price .noUi-tooltip').each(function(){
				$(this).text(parseInt($(this).text()));
			});
		});
		$('form.filter_p .filter .f_price').on('keyup','input',function(){
			if($(this).val().lenght>0)
				$(this).val(parseInt($(this).val()));
			set_range_price($(this));
		});
		
		var timer_set_range_price;
		function set_range_price(this_el){
			clearTimeout(timer_set_range_price);
			timer_set_range_price=setTimeout(function(){
				data_range_price.noUiSlider.set([$('form.filter_p .filter .f_price input[name="price_min"]').val(),$('form.filter_p .filter .f_price input[name="price_max"]').val()]);
			},1500);
		}
	}
	
	//range_volume
	if($('form.filter_p .filter .f_volume').is('div')){
		data_range_volume=document.querySelector('form.filter_p .filter .f_volume > div');
		data_volume=$('form.filter_p .filter .f_volume');
		step_volume=parseFloat(data_volume.data('max'))<=1 ? 0.1 : 1;
		mode_pips_volume=parseFloat(data_volume.data('max'))<=1 ? 'count' : 'positions';
		noUiSlider.create(data_range_volume,{
			start:[parseFloat(data_volume.data('s-min')),parseFloat(data_volume.data('s-max'))],
			connect:true,
			step:step_volume,
			// tooltips:true,
			range:{
				min:parseFloat(data_volume.data('min')),
				max:parseFloat(data_volume.data('max'))
			},
			pips:{
				mode:mode_pips_volume,
				values:[0,25,50,75,100],
				density:4
			}
		});
		data_range_volume.noUiSlider.on('update',function(values,handle,unencoded,tap,positions,noUiSlider){
			if(parseFloat(data_volume.data('max'))>1){
				$('form.filter_p .filter .f_volume input[name="volume_min"]').val(parseInt(values[0]));
				$('form.filter_p .filter .f_volume input[name="volume_max"]').val(parseInt(values[1]));
			
				$('form.filter_p .filter .f_volume .noUi-tooltip').each(function(){
					$(this).text(parseInt($(this).text()));
				});
			} else {
				$('form.filter_p .filter .f_volume input[name="volume_min"]').val(values[0]);
				$('form.filter_p .filter .f_volume input[name="volume_max"]').val(values[1]);
			}
		});
		$('form.filter_p .filter .f_volume').on('keyup','input',function(){
			set_range_volume($(this));
		});
		
		var timer_set_range_volume;
		function set_range_volume(this_el){
			clearTimeout(timer_set_range_volume);
			timer_set_range_volume=setTimeout(function(){
				if(this_el.val().lenght>0)
					this_el.val(parseFloat(this_el.val()));
				data_range_volume.noUiSlider.set([$('form.filter_p .filter .f_volume input[name="volume_min"]').val(),$('form.filter_p .filter .f_volume input[name="volume_max"]').val()]);
			},1500);
		}
	} else $('form.filter_p .filter .f_price').addClass('full');
	
	//gdpr
	$('.gdpr > a').click(function(){
		$.ajax({
			type:'post',
			url:'/wp-admin/admin-ajax.php',
			data:'action=gdpr',
			success:function(data){
				$('.gdpr').remove();
			}
		});
	});
	
	setTimeout(function(){$(window).resize();},250);
	
});