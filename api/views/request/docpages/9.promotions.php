<section id="section_promotions" class="section">
	<br/>
	<br/>
	<h3 class="alert-success">Акции</h3>
	<div class="section-body">
		<p></p>
		<div class="data-item">
			<b>url:</b> <code>{baseUrl}/promotion/</code>

		</div>
		<h4>Запрос:</h4>
		<div class="data-item">
			<b>Параметры:</b> 
			<ul class="data-ul">
				<li>
					<b>page</b> - <i>int</i> - <u>optional</u> - Номер страницы при пагинации. По умолчанию 1. <span class="text-muded"></span>
				</li>
				<li>
					<b>gloc</b> - <i>object</i> - <u>required</u>  - Объект геолокации. Содержит:
					<ul>
						<li>
							<b>coord</b> - <i>object</i> - <u>required</u> - Объект координат:
							<ul>
								<li>
									<b>lon</b> - <i>float</i> - <u>required</u> - долгота на карте
								</li>
								<li>
									<b>lat</b> - <i>float</i> - <u>required</u> - широта на карте
								</li>
							</ul>
						</li>
						<li>
							<b>region_id</b> - <i>int</i> - <u>optional</u> - ID региона. По умолчанию region_id=0 (при этом регион определяется автоматически).
						</li>
					</ul>
					
				</li>
			</ul>
		</div>
		<h4>Ответ:</h4>
		<div class="data-item">
			<b>Данные:</b> 
			
			<ul class="data-ul">
				<li>
					<b>page</b> - <i>int</i> - Номер страницы при пагинации.
				</li>
				<li>
					<b>pageSize</b> - <i>int</i> - Количество объектов в странице.
				</li>
				<li>
					<b>allCount</b> - <i>int</i> - Количество всех найденных объектов.
				</li>
				<li>
					<b>list</b> - <i>array</i> - Список акций. Каждый элемент является объектом акции
					<ul>
						<li>
							<b>id</b> - <i>int</i> - ID акции
						</li>
						<li>
							<b>name</b> - <i>string</i> - Название
						</li>
						<li>
							<b>description</b> - <i>string</i> - Описание
						</li>
						<li>
							<b>start_time</b> - <i>unixtime</i> - Дата начало акции
						</li>
						<li>
							<b>end_time</b> - <i>unixtime</i> - Дата окончании акции
						</li>
						<li>
							<b>min_price</b> - <i>int</i> - Минимальная сумма
						</li>
						<li>
							<b>discount_type</b> - <i>string</i> - тип дисконта. Возможные знаачения: <b>percentage</b> - проценты, <b>price</b> - стоимость, <b>free_delivery</b> - бесплатная доставка
						</li>
						<li>
							<b>discount_amount</b> - <i>string</i> - Значение дисконта
						</li>
						<li>
							<b>code</b> - <i>string</i> - Код
						</li>
						<li>
							<b>code_text</b> - <i>string</i> - Текст кода
						</li>
						<li>
							<b>image</b> - <i>link</i> - Ссылка на изображение
						</li>
						<li>
							<b>restaurant_id</b> - <i>int</i> - ID ресторана. Если возврашается <code>0</code> необходимо перейти по ссылке <b>info_link</b> на Webview
						</li>
						<li>
							<b>restaurant_image</b> - <i>link</i> -Изображение ресторана
						</li>
						<li>
							<b>info_link</b> - <i>link</i> - Ссылка на Webview
						</li>
					</ul>
					
				</li>
			</ul>

		</div>
		
		<h4>Пример</h4>
		<div class="data-item">
			<b>Запрос:</b>
	<pre>
	POST {baseUrl}/promotion/      HTTP/1.1
	Content-Type: text/json; charset=UTF-8

	{
		"params":{
			"page": 1,
			"gloc":{
				"coord":{
					"lon":76.888121,
					"lat":43.243933
				},
				"region_id": 0
			}
		}
	}
	</pre>

		</div>
		<div class="data-item">
			<b>Ответ:</b>
	<pre>
	{
		"status": 200,
		"error": "",
		"message": "",
		"data": {
			"page": 1,
			"pageSize": 10,
			"allCount": "2",
			"gloc": {
				"coord": {
					"lat": 43.243933,
					"lon": 76.888121
				},
				"region_id": 1
			},
			"list": [
				{
					"id": 1,
					"name": "Акция 1",
					"description": "Sea sapientem ocurreret laboramus te, pri in adhuc bonorum verterem. Dicit noster mei et, ut paulo habemus adipisci nam, eos summo dicam patrioque in. Ex vel populo deterruisset, ex facer debet eum. Ut nam brute justo inimicus, ea modus laboramus qui. Quodsi consulatu ne vim, quis salutandi te vix. No pro nisl regione, duis adhuc ignota est ex. An virtute incorrupte sed, lorem solet ne per.",
					"start_time": 1507725444,
					"end_time": 1510403844,
					"min_price": 50000,
					"discount_type": "percentage",
					"discount_amount": 0,
					"code": "111",
					"code_text": "Код:111",
					"image": "http://foodexpress.spg.uz/images/none.png",
					"restaurant_id": 1,
					"restaurant_image": "http://foodexpress.spg.uz/images/none.png"
				},
				{
					"id": 2,
					"name": "Акция 2",
					"description": "Sea sapientem ocurreret laboramus te, pri in adhuc bonorum verterem. Dicit noster mei et, ut paulo habemus adipisci nam, eos summo dicam patrioque in. Ex vel populo deterruisset, ex facer debet eum. Ut nam brute justo inimicus, ea modus laboramus qui. Quodsi consulatu ne vim, quis salutandi te vix. No pro nisl regione, duis adhuc ignota est ex. An virtute incorrupte sed, lorem solet ne per.",
					"start_time": 1507725444,
					"end_time": 1510403844,
					"min_price": 60000,
					"discount_type": "percentage",
					"discount_amount": 0,
					"code": "222",
					"code_text": "Код:222",
					"image": "http://foodexpress.spg.uz/images/none.png",
					"restaurant_id": 2,
					"restaurant_image": "http://foodexpress.spg.uz/images/none.png"
				}
			]
		},
		"req_id": 506
	}
	</pre>

		</div>
	</div>
	
</section>