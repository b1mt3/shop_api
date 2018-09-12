<section id="section_restaurantview" class="section">
	<br/>
	<br/>
	<h3 class="alert-success">Просмотр ресторана</h3>
	<div class="section-body">
		<p></p>
		<div class="data-item">
			<b>url:</b> <code>{baseUrl}/restaurants/get/</code>

		</div>
		<h4>Запрос:</h4>
		<div class="data-item">
			<b>Параметры:</b> 
			<ul class="data-ul">
				<li>
					<b>id</b> - <i>int</i> - <u>required</u> - id ресторана
				</li>
			</ul>

		</div>
		<h4>Ответ:</h4>
		<div class="data-item">
			<b>Данные:</b> 
			
			<ul class="data-ul">
				<li>
					<b>restaurant</b> - <i>object</i> - ресторан
					<ul>
						<li>
							<b>id</b> - <i>int</i> - id ресторана
						</li>
						<li>
							<b>name</b> - <i>string</i> - Название ресторана
						</li>
						<li>
							<b>flags</b> - <i>array</i> - Флаги ("новый" или "акция"). Каждый элемент массива содержит:
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
							<b>back_image</b> - <i>link</i> - Ссылка на изображении фона ресторана.
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
<!--						<li>
							<b>reviews</b> - <i>object</i> - Отзывы. Содержит:
							<ul>
								<li>
									<b>label</b> - <i>string</i> - Метка
								</li>
								<li>
									<b>count</b> - <i>int</i> - Количество отзывов
								</li>
							</ul>
						</li>-->
						<li>
							<b>promotion</b> - <i>object</i> - Акция:
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
									<b>discount_amount</b> - <i>int</i> - значение дисконта
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
									<b>restaurant_id</b> - <i>int</i> - ID ресторана
								</li>
								<li>
									<b>info_link</b> - <i>link</i> - Ссылка на Webview
								</li>
<!--								<li>
									<b>restaurant</b> - <i>object</i> - Объект ресторана:
									<ul>
										<li>
											<b>id</b> - <i>int</i> - ID ресторана
										</li>
										<li>
											<b>name</b> - <i>string</i> - Название
										</li>
										<li>
											<b>image</b> - <i>string</i> - Ссылка на изображение
										</li>
									</ul>
								</li>-->
							</ul>
						</li>
						<li>
							<b>menu</b> - <i>array</i> - Список категорий. Каждый элкмкнт является объектом катекорий:
							<ul>
								<li>
									<b>id</b> - <i>int</i> - ID категории. Если <b>0</b>, означает популярные позиции меню
								</li>
								<li>
									<b>name</b> - <i>string</i> - Название
								</li>
								<li>
									<b>submenu</b> - <i>array</i> - Список подкатегорий. Каждый элемент является объектом подкатегорий:
									<ul>
										<li>
											<b>id</b> - <i>int</i> - ID подкатегорий. 
										</li>
										<li>
											<b>name</b> - <i>string</i> - Название
										</li>
									</ul>
								</li>
							</ul>
						</li>
						<li>
							<b>info</b> - <i>object</i> - Информации:
							<ul>
								<li>
									<b>kitchens</b> - <i>string</i> - Кухня
								</li>
								<li>
									<b>working_hours</b> - <i>array</i> - Список времен работы по дням недели. Каждый элемент массива является объктом:
									<ul>
										<li>
											<b>day</b> - <i>string</i> - день недели
										</li>
										<li>
											<b>time</b> - <i>string</i> - Текст времени
										</li>
									</ul>
								</li>
								<li>
									<b>address</b> - <i>string</i> - Текст адреса
								</li>
								<li>
									<b>about</b> - <i>string</i> - О нас
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
	POST {baseUrl}/restaurants/get/    HTTP/1.1
	Content-Type: text/json; charset=UTF-8

	{
		"params":{
			"id":1
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
			"restaurant": {
				"id": 1,
				"name": "Ресторан 1",
				"flags": {
					"type": "promotion",
					"text": "Promotion"
				},
				"working_state": 0,
				"work_start_time": 1509422400,
				"image": "http://foodexpress.spg.uz/images/none.png",
				"back_image": "",
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
				},
				"promotion": {
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
				"menu": [
					{
						"id": 0,
						"name": "Популярный",
						"submenu": []
					},
					{
						"id": 1,
						"name": "Роллы",
						"submenu": [
							{
								"id": 5,
								"name": "Острые"
							},
							{
								"id": 6,
								"name": "Запеченные"
							}
						]
					}
				],
				"info": {
					"kitchens": "Русская, Европейская, Кавказская",
					"working_hours": [
						{
							"day": "Понедельник",
							"time": "09:00-23:00"
						},
						{
							"day": "Вторник",
							"time": "09:00-23:00"
						},
						{
							"day": "Среда",
							"time": "09:00-11:53"
						}
					],
					"address": "Sea sapientem ocurreret laboramus te",
					"about": "Sea sapientem ocurreret laboramus te, pri in adhuc bonorum verterem. Dicit noster mei et, ut paulo habemus adipisci nam, eos summo dicam patrioque in. Ex vel populo deterruisset, ex facer debet eum. Ut nam brute justo inimicus, ea modus laboramus qui. Quodsi consulatu ne vim, quis salutandi te vix. No pro nisl regione, duis adhuc ignota est ex. An virtute incorrupte sed, lorem solet ne per."
				}
			}
		},
		"req_id": 864
	}
	</pre>

		</div>
	</div>
	
</section>