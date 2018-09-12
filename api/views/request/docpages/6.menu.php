<section id="section_menu" class="section">
	<br/>
	<br/>
	<h3 class="alert-success">Меню</h3>
	<div class="section-body">
		<p></p>
		<div class="data-item">
			<b>url:</b> <code>{baseUrl}/menu</code>

		</div>
		<h4>Запрос:</h4>
		<div class="data-item">
			<b>Параметры:</b> 
			
			<ul class="data-ul">
				<li>
					<b>page</b> - <i>int</i> - <u>optional</u> - Страница
				</li>
				<li>
					<b>restaurant_id</b> - <i>int</i>  - <u>required</u> - ID ресторана
				</li>
				<li>
					<b>menu_id</b> - <i>int</i>  - <u>required</u> - ID категории
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
					<b>list</b> - <i>array</i> - Массив блюд. Каждый элемент является объектом позиции меню:
					<ul>
						<li>
							<b>id</b> - <i>int</i> - ID позиции меню
						</li>
						<li>
							<b>name</b> - <i>string</i> - Название
						</li>
						<li>
							<b>image</b> - <i>link</i> - Ссылка на изображение
						</li>
						<li>
							<b>flags</b> - <i>array</i> - Флаги ("новый" или "акция"):
							<ul>
								<li>
									<b>type</b> - <i>string</i> - Тип. Возможные значения: <b>new</b> - Новый
								</li>
								<li>
									<b>text</b> - <i>string</i> - Текст
								</li>
							</ul>
						</li>
						<li>
							<b>price</b> - <i>int</i> - Стоимость
						</li>
						<li>
							<b>price_text</b> - <i>string</i> - Текст стоимости
						</li>
						<li>
							<b>short_description</b> - <i>string</i> - Краткое описание
						</li>
						<li>
							<b>description</b> - <i>string</i> - Описание
						</li>
						<li>
							<b>additives</b> - <i>array</i> - Список добавки. Каждый элемент является добавком:
							<ul>
								<li>
									<b>id</b> - <i>int</i> - ID добавки
								</li>
								<li>
									<b>name</b> - <i>string</i> - название
								</li>
								<li>
									<b>price</b> - <i>int</i> - стоимость
								</li>
								<li>
									<b>price_text</b> - <i>string</i> - текст стоимости
								</li>
							</ul>
						</li>
						<li>
							<b>cook_conditions</b> - <i>array</i> - Условия приготовления. Каждый элемент является объектом условия:
							<ul>
								<li>
									<b>id</b> - <i>int</i> - ID добавки
								</li>
								<li>
									<b>name</b> - <i>string</i> - название
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
	POST {baseUrl}/menu/     HTTP/1.1
	Content-Type: text/json; charset=UTF-8

	{
		"params":{
			"page": 1,
			"restaurant_id":1,
			"menu_id":5
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
					"flags": {
						"type": "promotion",
						"text": "Promotion"
					},
					"working_state": 0,
					"work_start_time": 1509336000,
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
					"flags": {
						"type": "promotion",
						"text": "Promotion"
					},
					"working_state": 0,
					"work_start_time": 1509163200,
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
					"id": 3,
					"name": "Ресторан 3",
					"flags": {},
					"working_state": 0,
					"work_start_time": 1509163200,
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
				}
			]
		},
		"req_id": 683
	}
	</pre>

		</div>
	</div>
	
</section>