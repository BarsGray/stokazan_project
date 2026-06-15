<?php

	/*******************************
	** Импорт товаров
	*******************************/
	
	require_once('/home/d/dachniksh/public_html/wp-load.php');
	global $wpdb;
	$ERR=0;$OK=0;$UPDATE=0;
	function add_log($text){
		$logfile=fopen('/home/d/dachniksh/public_html/import/log/log_'.date('Y-m-d').'.txt','a');
		$mytext=date('H:i:s')." ".$text."\r";
		fwrite($logfile,$mytext); 
		fclose($logfile);
	}
	
	/*******************************
	** Распаковка архива
	*******************************/
	add_log('Распаковка архива ftp_'.date('Y-m-d').'.zip');
	$zip=new ZipArchive;
	$is_zip=0;
	$res=$zip->open('/home/d/dachniksh/public_html/import/ftp_'.date('Y-m-d').'.zip');
	if($res===TRUE){
		$zip->extractTo('/home/d/dachniksh/public_html/import/');
		$zip->close();
		$is_zip=1;
		add_log('Архив ftp_'.date('Y-m-d').'.zip успешно распакован');
	} else add_log('Ошибка распаковки архива ftp_'.date('Y-m-d').'.zip');
	
	if($is_zip==0){
		$res=$zip->open('/home/d/dachniksh/public_html/import/ftp_'.date('Y-m-d').'(1).zip');
		if($res===TRUE){
			$zip->extractTo('/home/d/dachniksh/public_html/import/');
			$zip->close();
			$is_zip=1;
			add_log('Архив ftp_'.date('Y-m-d').'(1).zip успешно распакован');
		} else
		add_log('Ошибка распаковки архива ftp_'.date('Y-m-d').'(1).zip');
	}

	if($is_zip==0){
		$res=$zip->open('/home/d/dachniksh/public_html/import/ftp_'.date('Y-m-d').'(2).zip');
		if($res===TRUE){
			$zip->extractTo('/home/d/dachniksh/public_html/import/');
			$zip->close();
			$is_zip=1;
			add_log('Архив ftp_'.date('Y-m-d').'(2).zip успешно распакован');
		} else
		add_log('Ошибка распаковки архива ftp_'.date('Y-m-d').'(2).zip');
	}
	
	/*******************************
	** Старт
	*******************************/

	$xml_source='/home/d/dachniksh/public_html/import/offers0_1.xml';
	if(file_exists($xml_source) && $is_zip==1){
		copy('/home/d/dachniksh/public_html/import/offers0_1.xml','/home/d/dachniksh/100_kazan/public_html/import/offers0_1.xml');
		copy('/home/d/dachniksh/public_html/import/import0_1.xml','/home/d/dachniksh/100_kazan/public_html/import/import0_1.xml');
		add_log('Старт импорта');
		add_log('Файл offers0_1.xml найден');
		
		/********code******/
		$xml_import='/home/d/dachniksh/public_html/import/import0_1.xml';
		$data_codes=array();
		$xml_off_import=simplexml_load_file($xml_import);
		$tovars_import=$xml_off_import->Каталог->Товары->Товар;
		foreach($tovars_import as $tovar){
			foreach($tovar->ЗначенияРеквизитов->ЗначениеРеквизита as $r_item){
				if((string)$r_item->Наименование=='Код'){
					$data_codes[(string)$tovar->Ид]=substr(trim((string)$r_item->Значение),6);
					break;
				}
			}
		}
		
		$xml_offers=simplexml_load_file($xml_source);
		$tovars_offers=$xml_offers->ПакетПредложений->Предложения->Предложение;
		if(count($tovars_offers)>0){
			/*******************************
			** Очистка остатков
			*******************************/
			$all_products_site=$wpdb->get_results('SELECT id FROM `wp_posts` WHERE `post_type`="product" AND `post_status`="publish"','ARRAY_A');
			add_log('Удаление остатков у '.count($all_products_site).' товаров на сайте');
			foreach($all_products_site as $product_site)
				$wpdb->query('UPDATE `wp_postmeta` SET `meta_value`="0" WHERE `meta_key`="_stock" AND `post_id`='.$product_site['id']);
			add_log('Удалены остатки у товаров на сайте');
			
			/*******************************
			** Цикл по файлу
			*******************************/
			add_log('Количество товаров в offers0_1.xml '.count($tovars_offers));
			add_log('Запуск прохода по товарам');
			$ii=1;
			foreach($tovars_offers as $tovar){
				/*******************************
				** Переменные товара
				*******************************/
				$tovar_name=(string)$tovar->Наименование;
				$tovar_count=(int)$tovar->Количество;
				$tovar_type_cost=(string)$tovar->БазоваяЕдиница->attributes()->НаименованиеПолное;
				$tovar_cost=0;
				$tovar_sku=(string)$tovar->Ид;
				$tovar_cats=(string)$tovar->Артикул;
				$tovar_cod=isset($data_codes[$tovar_sku]) ? $data_codes[$tovar_sku] : '';
				
				/*******************************
				** Проверки
				*******************************/
				$er_c=0;
				//получает тип цены
				if($tovar_type_cost=='шт') $type_cost='sh';
				elseif($tovar_type_cost=='килограмм') $type_cost='kg';
				else {
					add_log('ERROR: Ошибка получения типа цены у товара '.$tovar_name);
					$er_c++;
				}
				
				//проверяем категории товара
				$rez_cat='';
				if(preg_match('/#(.*)#/',$tovar_cats,$rez_cat)) $rez_cat=explode(',',$rez_cat[1]);
				else{
					add_log('ERROR: Ошибка получения категорий ('.$tovar_cats.') у товара '.$tovar_name);
					$er_c++;
				}
				
				foreach($tovar->Цены->Цена as $price){
					if((string)$price->ИдТипаЦены=='7fd1ac74-2592-11e7-9fcf-38d5470e47f9')
						$tovar_cost=(float)$price->ЦенаЗаЕдиницу;
				}
				
				//проверяем наличие категории на сайте
				$er_cat=0;
				foreach($rez_cat as $cat){
					$search_term=$wpdb->get_results('SELECT term_id FROM `wp_term_taxonomy` WHERE `taxonomy`="product_cat" AND `term_id`='.$cat,'ARRAY_A');
					if(!empty($search_term)) $er_cat++;
					else add_log('ERROR: Категория '.$cat.' у товара '.$tovar_name.' отсутствует на сайте');
				}
				
				/*******************************
				** Добавление/обновление товара
				*******************************/
				if($er_c==0 && $er_cat!=0){
					$search_tovar=$wpdb->get_results('SELECT a.id FROM `wp_posts`=a,`wp_postmeta`=b WHERE a.post_type="product" AND a.post_status="publish" AND a.id=b.post_id AND b.meta_key="_sku" AND b.meta_value="'.$tovar_sku.'"','ARRAY_A');
					//товар отсутствует, добавление
					if(empty($search_tovar)){
						add_log('Товар '.$tovar_name.' отсутствует на сайте');
						$_stock_status=$tovar_count>0 ? 'instock' : 'outofstock';
						$post_data=array(
							'post_title' => $tovar_name,
							'post_type' => 'product',
							'post_status' => 'publish',
							'meta_input' => array(
								'_sku' => $tovar_sku,
								'_stock' => $tovar_count,
								'_regular_price' => $tovar_cost,
								'_price' => $tovar_cost,
								'_manage_stock' => 'yes',
								'unit' => $type_cost,
								'_stock_status' => $_stock_status,
								'_code' => $tovar_cod
							)
						);
						$post_id=wp_insert_post(wp_slash($post_data));
						add_log('INSERT: Товар '.$tovar_name.' создан id='.$post_id);
						foreach($rez_cat as $cat){
							$search_term=$wpdb->get_results('SELECT term_id FROM `wp_term_taxonomy` WHERE `taxonomy`="product_cat" AND term_id='.$cat,'ARRAY_A');
							if(!empty($search_term)){
								$wpdb->query("INSERT INTO `wp_term_relationships` (`object_id`,`term_taxonomy_id`,`term_order`) VALUES ('$post_id','$cat','0')");
								add_log('INSERT_CAT: Товар '.$tovar_name.' был добавлен в категорию '.$cat);
							}
							else add_log('ERROR: Товар '.$tovar_name.' не был добавлен в категорию '.$cat);
						}
						$OK++;
						//товар существует, обновление
					} elseif($search_tovar[0]['id']>0 && count($search_tovar)==1){
						add_log('Товар '.$tovar_name.' существует id='.$search_tovar[0]['id']);
						$_stock_status=$tovar_count>0 ? 'instock' : 'outofstock';
						$wpdb->query('UPDATE `wp_postmeta` SET `meta_value`="'.$tovar_count.'" WHERE `meta_key`="_stock" AND `post_id`='.$search_tovar[0]['id']);
						$wpdb->query('UPDATE `wp_postmeta` SET `meta_value`="'.$tovar_cost.'" WHERE `meta_key`="_regular_price" AND `post_id`='.$search_tovar[0]['id']);
						$wpdb->query('UPDATE `wp_postmeta` SET `meta_value`="'.$tovar_cost.'" WHERE `meta_key`="_price" AND `post_id`='.$search_tovar[0]['id']);
						$wpdb->query('UPDATE `wp_postmeta` SET `meta_value`="'.$_stock_status.'" WHERE `meta_key`="_stock_status" AND `post_id`='.$search_tovar[0]['id']);
						$wpdb->query('UPDATE `wp_posts` SET `post_title`="'.htmlspecialchars($tovar_name).'" WHERE `id`='.$search_tovar[0]['id']);
						
						/****code***/
						if(empty(get_field('_code',$search_tovar[0]['id'])))
							$wpdb->insert('wp_postmeta',array('meta_key' => '_code','meta_value' => $tovar_cod,'post_id' => $search_tovar[0]['id']));
						else
							$wpdb->query('UPDATE `wp_postmeta` SET `meta_value`="'.$tovar_cod.'" WHERE `meta_key`="_code" AND `post_id`='.$search_tovar[0]['id']);
						
						add_log('UPDATE: Данные о товаре '.$tovar_name.' обновлены');
						$UPDATE++;
					} else {
						add_log('ERROR: Ошибка поиска товара '.$tovar_name);
						$ERR++;
					}
				} else {
					add_log('ERROR: Ошибка разбора товара '.$tovar_name);
					$ERR++;
				}
				add_log('-------');
			}
			if(file_exists('/home/d/dachniksh/public_html/import/ftp_'.date('Y-m-d').'.zip')){
				unlink('/home/d/dachniksh/public_html/import/ftp_'.date('Y-m-d').'.zip');
				add_log('Удаление файла ftp_'.date('Y-m-d').'.zip');
			}
			if(file_exists('/home/d/dachniksh/public_html/import/ftp_'.date('Y-m-d').'(1).zip')){
				unlink('/home/d/dachniksh/public_html/import/ftp_'.date('Y-m-d').'(1).zip');
				add_log('Удаление файла ftp_'.date('Y-m-d').'(1).zip');
			}
			if(file_exists('/home/d/dachniksh/public_html/import/ftp_'.date('Y-m-d').'(2).zip')){
				unlink('/home/d/dachniksh/public_html/import/ftp_'.date('Y-m-d').'(2).zip');
				add_log('Удаление файла ftp_'.date('Y-m-d').'(2).zip');
			}
		} else add_log('ERROR: Не найдены товары в offers0_1.xml');
	} else add_log('ERROR: Файл offers0_1.xml не найден');
	
	add_log('Конец импорта | Всего: '.count($tovars_offers).' | Добавлено: '.$OK.' | Обновлено: '.$UPDATE.' | С ошибками: '.$ERR);
	add_log('-----------------------------------------------------------------------');

?>
