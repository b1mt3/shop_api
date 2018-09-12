<section id="section_restaurants" class="section">
	<br/>
	<br/>
	<h3 class="alert-success">Список ресторанов</h3>
	<div class="section-body">
		<p>Возврашает доступные рестораны по регионам. При первом запросе можно не отправить или отправить со значением 0 <code>region_id</code> (при этом регион определяется автоматический). А в ответе в объекте геолокации (<code>gloc</code>) возвращается ID региона (<code>region_id</code>) и в дальнейших запросах фильтрации предпочитается отправить это значение</p>
		<div class="data-item">
			<b>url:</b> <code>{baseUrl}/restaurants/</code>

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
				<li>
					<b>filter</b> - <i>object</i>  - <u>optional</u> - Фильтр. 
				</li>
			</ul>
			<br/>
			
		</div>
		<hr/>
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
					<b>gloc</b> - <i>object</i> - Объект геолокации. Содержит:
					<ul>
						<li>
							<b>coord</b> - <i>object</i> - Объект координат:
							<ul>
								<li>
									<b>lon</b> - <i>float</i> - долгота на карте
								</li>
								<li>
									<b>lat</b> - <i>float</i> - широта на карте
								</li>
							</ul>
						</li>
						<li>
							<b>region_id</b> - <i>int</i> - ID региона. 
						</li>
					</ul>
				</li>
				<li>
					<b>list</b> - <i>array</i> - Список ресторанов. Каждый массив является объектом ресторана:
					<ul>
						<li>
							<b>id</b> - <i>int</i> - ID ресторана. 
						</li>
						<li>
							<b>name</b> - <i>string</i> - Название 
						</li>
						<li>
							<b>flags</b> - <i>array</i> - Флаги ("новый" или "акция"):
							<ul>
								<li>
									<b>type</b> - <i>string</i> - Тип. Возможные значения: <b>promotion</b> - Акция, <b>new</b> - Новый
								</li>
								<li>
									<b>text</b> - <i>string</i> - Текст
								</li>
							</ul>
						</li>
						<li>
							<b>working_state</b> - <i>0 or 1</i> - Статус работы. <b>1</b> - работает; <b>0</b> - неработает. Если <b>0</b> в поле <code>work_start_time</code> отправляется время открытия ресторана 
						</li>
						<li>
							<b>work_start_time</b> - <i>unixtime</i> - Время открытия ресторана. Если ресторан работает во время запроса возвращается <b>0</b>. Если в ресторане не имеется данных о времени работы, тогда возвращается <b>-1</b>
						</li>
						<li>
							<b>image</b> - <i>link</i> - Ссылка на изображении ресторана
						</li>
						<li>
							<b>kitchens</b> - <i>string</i> - Список кухни.
						</li>
						<li>
							<b>pay_types</b> - <i>object</i> - Типы оплат. Содержит: 
							<ul>
								<li>
									<b>online</b> - <i>object</i> - Онлайн оплата. Содержит: 
									<ul>
										<li>
											<b>name</b> - <i>string</i> - Название 
										</li>
										<li>
											<b>items</b> - <i>array</i> - список тип онлайн оплаты. Каждый элемент содержит:
											<ul>
												<li>
													<b>name</b> - <i>string</i> - Название 
												</li>
												<li>
													<b>image</b> - <i>link</i> - Ссылка на изображение 
												</li>
											</ul>
										</li>
									</ul>
								</li>
								<li>
									<b>offline</b> - <i>array</i> - Оффлайн оплата. Каждый элемент является названием типа оплаты
								</li>
							</ul>
						</li>
						<li>
							<b>stars</b> - <i>float</i> - Звезды (рейтинг).
						</li>
						<li>
							<b>min_order_sum</b> - <i>object</i> - Объект минимальной суммы. Содержит:
							<ul>
								<li>
									<b>label</b> - <i>string</i> - Метка
								</li>
								<li>
									<b>amount</b> - <i>int</i> - Минимальная сумма
								</li>
								<li>
									<b>text</b> - <i>string</i> - Текст суммы
								</li>
							</ul>
						</li>
						<li>
							<b>delivery</b> - <i>object</i> - Стоимость доставки. Содержит:
							<ul>
								<li>
									<b>label</b> - <i>string</i> - Метка
								</li>
								<li>
									<b>amount</b> - <i>int</i> - Стоимость
								</li>
								<li>
									<b>text</b> - <i>string</i> - Текст стоимости
								</li>
							</ul>
						</li>
						<li>
							<b>delivery_time</b> - <i>object</i> - Время доставки. Содержит:
							<ul>
								<li>
									<b>label</b> - <i>string</i> - Метка
								</li>
								<li>
									<b>amount</b> - <i>int</i> - время в секундах
								</li>
								<li>
									<b>text</b> - <i>string</i> - Текст времени доставки
								</li>
							</ul>
						</li>
					</ul>
				</li>
			</ul>

		</div>
		
		<h4>Пример</h4>
		<div class="data-item">
			<b>Запрос:</b>
	<pre>
	POST {baseUrl}/restaurants/       HTTP/1.1
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
			"allCount": 3,
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
					"name": "Ресторан 1",
					"flags": [
						{
							"type": "promotion",
							"text": "Promotion"
						}
					],
					"working_state": 0,
					"work_start_time": 1508731200,
					"image": "http://foodexpress.spg.uz/images/none.png",
					"kitchens": "Русская, Европейская, Кавказская",
					"pay_types": {
						"online": {
							"name": "Онлайн оплата",
							"items": [
								{
									"name": "Виза",
									"image": ""
								},
								{
									"name": "Мастеркарт",
									"image": ""
								}
							]
						},
						"offline": [
							"Оплата наличными",
							"Оплата картой курьеру"
						]
					},
					"stars": 3,
					"min_order_sum": {
						"label": "Заказ от",
						"amount": 3000,
						"text": "3000 КЗТ"
					},
					"delivery": {
						"label": "Доставка",
						"amount": 500,
						"text": "500 КЗТ/км"
					},
					"delivery_time": {
						"label": "Время",
						"amount": 3600,
						"text": "1 час"
					}
				},
				{
					"id": 2,
					"name": "Ресторан 2",
					"flags": [
						{
							"type": "promotion",
							"text": "Promotion"
						}
					],
					"working_state": 0,
					"work_start_time": 1508472000,
					"image": "http://foodexpress.spg.uz/images/none.png",
					"kitchens": "",
					"pay_types": {
						"online": {
							"name": "Онлайн оплата",
							"items": []
						},
						"offline": []
					},
					"stars": 4,
					"min_order_sum": {
						"label": "Заказ от",
						"amount": 3000,
						"text": "КЗТ"
					},
					"delivery": {
						"label": "Доставка",
						"amount": 500,
						"text": "500 КЗТ/км"
					},
					"delivery_time": {
						"label": "Время",
						"amount": 3600,
						"text": "1 час"
					}
				},
				{
					"id": 3,
					"name": "Ресторан 3",
					"flags": [],
					"working_state": 0,
					"work_start_time": 1508472000,
					"image": "http://foodexpress.spg.uz/images/none.png",
					"kitchens": "",
					"pay_types": {
						"online": {
							"name": "Онлайн оплата",
							"items": []
						},
						"offline": []
					},
					"stars": 0,
					"min_order_sum": {
						"label": "Заказ от",
						"amount": 3000,
						"text": "КЗТ"
					},
					"delivery": {
						"label": "Доставка",
						"amount": 500,
						"text": "500 КЗТ/км"
					},
					"delivery_time": {
						"label": "Время",
						"amount": 3600,
						"text": "1 час"
					}
				}
			]
		},
		"req_id": 36
	}
	</pre>

		</div>
	</div>
	
</section>