<?php
/*
# ------------------------------------------------------------------------
# Модуль ajax формы связи для  Joomla 2.5.x - Joomla 3.x
# ------------------------------------------------------------------------
# Copyright (C) 2014 webislife.ru.
# @license - :)
# Author: webislife.ru
# Websites:  http://webislife.ru
# Date modified: 14/07/2014 - 
# ------------------------------------------------------------------------
*/

// no direct access
defined('_JEXEC') or die;

// $app = JFactory::getApplication();
// $inp = $app->input;
// $task =$inp->getString('tmpl', false);

?>
<style>
#calc-form{ width: 50%;margin-left:30%; }
fieldset{border: solid 1px #ccc; border-radius: 4px; padding: 2em;  }
#calc-form .param{ font-size: 1.2em;margin-right:  1em; }
#get-price{ width: 10em; text-align: center;border: solid 1px #ccc; border-radius: 4px;padding:0 1em; margin-right: 1em; margin-left:50%;  }
#price-is{display: inline-block; float: left;}
#send-photo-btn{border: solid 1px #ccc; border-radius: 4px; padding: 1em 2em; margin: 0 auto; }
</style>
<!-- Форма 1 для подсчета цены в зависимости от выбраных параметров -->
<form id="calc-form" action="index.php" class="form">
	<h3>Что бы узнать стоимость выберете параметы!</h3>	<br>
	<fieldset>
	<span class="param">Тип бумаги:</span>
		<input type="radio" name="paper-type" id="matt"  checked>матовая 
		<input type="radio" name="paper-type" id="gloss">глянцевая <br>

	<span class="param">Плотность:</span>
		<input type="radio" name="thickness" id="300mg" checked>300 гр\м2 
		<input type="radio" name="thickness" id="350mg">350 гр\м2 
		<br> 
	
	<span class="param">2 сторонняя печать:</span>
		<input type="radio" name="side" id="1-1" checked> Да 
		<input type="radio" name="side" id="1-0"> Нет 
		<br>  
	
	<span class="param">Напечаать за:</span> 
		<input type="radio" name="howLong" id="1day" checked> за 1 день 
		<input type="radio" name="howLong" id="2day" > за 2 дня 
		<input type="radio" name="howLong" id="3day" > за 3 дня 
		<br>
		  
	<span class="param">Тираж:</span>	
	<select id="howMuch">
	  <option value="1000">1000шт</option>
	  <option value="2000">2000шт</option>
	  <option value="3000">3000шт</option>	  
	  <option value="5000">5000шт</option>
	  <option value="10 000">10 000шт</option>
	  <option value="20 000">20 000шт</option>
	  <option value="30 000">30 000шт</option>
	  <option value="40 000">40 000шт</option>
	</select><br>
		
		
		<h2 id="price-is"></h2> <div id="get-price"><h3>Посчитать</h3></div><br>
	 	<br>
		<!-- Форма 2 для загрузки файла (макет визитки)  -->
		<form enctype="multipart/form-data" method="post">
	   		ФИО <input type="text" name="fio"><br>
	   		Телефон <input type="text" name="phone">
	   		<p>Загрузить дизайн визитки   <input type="file" name="f">
	   		<input id="send-photo-btn" type="submit" value="Заказать!"></p>
		</form> 
	

	</div>
</div>

<script>
	jQuery( document ).ready(function() {
		var files;
		jQuery('input[type=file]').change(function(){
			files = this.files;
		});

		// Вешаем функцию ан событие click и отправляем AJAX запрос с данными файлов
 
		jQuery('#send-photo-btn').click(function( event ){
		    event.stopPropagation(); // Остановка происходящего
		    event.preventDefault();  // Полная остановка происходящего
		 
		    // Создадим данные формы и добавим в них данные файлов из files
		 
		    var data = new FormData();
		    jQuery.each( files, function( key, value ){
		        data.append( key, value );
		    });

		    /* недописаное Формирование цены в зависимости  от отмеченых чекбоксов. По вандальски но у них нет никакого алгоритма ценообразования:) 
				это не проблема  не обращай внимания на этот блок*/
			/*if( jQuery("#matt").is(':checked') ){var paper_type = 'матовая';} else {var paper_type = 'глянцевая';}
		 	if( jQuery("#300mg").is(':checked') ){var paper_weight = '300 мг/м2';} else {var paper_weight = '300 мг/м2';}
		 	if( jQuery("#1-1").is(':checked') ){var side = 'двухсторонняя';} else {var side = 'односторонняя';}
		 	if( jQuery("#1day").is(':checked') ){var howLong = 'на завтра';} else if( jQuery("#2day").is(':checked') ){var howLong = 'на послезавтра';} else if( jQuery("#3day").is(':checked') ){var howLong = '3 дня';}
		 	var howMuch = jQuery('#howMuch').find(":selected").val();
			*/
		 	
		   
			/*Отправляю Ajax Формы 2 для загрузки файла (макет визитки)
				Нада в этом(или создать еще один) запросе принять все чекбоксы выше,
				проверить прикрепил ли пользователь каринку (может и н е загрузит макет)
				и отправить значиние чекбоксов и макет(если загружен) на мыло"
				обрабаттывается файлом /tmpl/upload.php
			*/
		    jQuery.ajax({
		        url: './modules/mod_webislifeajaxform/tmpl/upload.php?uploadfiles',
		        type: 'POST',
		        data: data,
		        cache: false,
		        dataType: 'json',
		        processData: false, // Не обрабатываем файлы (Don't process the files)
		        contentType: false, // Так jQuery скажет серверу что это строковой запрос
		        success: function( respond, textStatus, jqXHR ){
		            // Если все ОК
		            if( typeof respond.error === 'undefined' ){
		                // Файлы успешно загружены, делаем что нибудь здесь
		                // выведем пути к загруженным файлам в блок '.ajax-respond'
		                var files_path = respond.files;
		                var html = '';
		                jQuery.each( files_path, function( key, val ){ html += val +'<br>'; } )
		                jQuery('.ajax-respond').html( html );
		            }
		            else{
		                console.log('ОШИБКИ ОТВЕТА сервера: ' + respond.error );
		            }
		        },
		        error: function( jqXHR, textStatus, errorThrown ){
		            console.log('ОШИБКИ AJAX запроса: ' + textStatus );
		        }
		    });
			
		});
		jQuery('div #get-price').click(function(){ jQuery('#price-is').text('90 грн.');});
	});
</script>
