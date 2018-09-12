<section id="section_search_filters" class="section">
	<br/>
	<br/>
	<h3 class="alert-success">Поиск: фильтр в разделе поиска</h3>
	
	<div class="section-body">
		<p>Фильтр - изначально выбранный администратором, набор параметров фильтрации. В списке присутствует ID фильтра и название. Полученный ID используется для вывода списка ресторанов по выбранным параметрам</p>
		<div class="data-item">
			<b>url:</b> <code>{baseUrl}/search/filters</code>

		</div>
		
		<h4>Запрос:</h4>
		<div class="data-item">
			<b>Параметры:</b> 
			<p class="text-muded">(Без параметров)</p>
		</div>

	
		<h4>Ответ:</h4>
		<div class="data-item">
			<b>Данные:</b> 
			
			<ul class="data-ul">
				<li>
					<b>list</b> - <i>array</i> - Список фильтров. Каждый элемент массива является объектом фильтра со следуюшими данными
					<ul>
						<li>
							<b>id</b> - <i>int</i> - ID фильтра
						</li>
						<li>
							<b>name</b> - <i>string</i> - название фильтра
						</li>
						<li>
							<b>filter</b> - <i>object</i> - объект <a href="/ru/api/request/docs#section_filter">фильтра</a> с выбранными параметрами
						</li>
					</ul>
				</li>
			</ul>

		</div>
		
		<h4>Пример</h4>
		<div class="data-item">
			<b>Запрос:</b>
	<pre>
	GET {baseUrl}/search/filters        HTTP/1.1
	Content-Type: text/json; charset=UTF-8

	{"params":{

	}}
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
			"list": [
				{
					"id": 1,
					"name": "Бургеры",
					"filter": [
						{
							"alias": "sort",
							"name": "Сортировка",
							"type": "radio",
							"items": [
								{
									"val": "default",
									"name": "По умолчанию",
									"checked": 0
								},
								{
									"val": "popular",
									"name": "Популярный",
									"checked": 0
								},
								{
									"val": "rate",
									"name": "Рейтингу",
									"checked": 0
								}
							]
						},
						{
							"alias": "min_price",
							"name": "Prices",
							"desc": "Сумма заказа до",
							"type": "slider",
							"val": 15000,
							"min_val": 0,
							"max_val": 100000,
							"step": 1000
						},
						{
							"alias": "criteria",
							"name": "Критерии",
							"type": "checkbox",
							"items": [
								{
									"val": "has_promotion",
									"name": "С акциями",
									"checked": 0
								},
								{
									"val": "free_delivery",
									"name": "Бесплатная доставка",
									"checked": 0
								},
								{
									"val": "card_online",
									"name": "Оплата картой онлайн",
									"checked": 0
								},
								{
									"val": "card_courier",
									"name": "Оплата картой курьеру",
									"checked": 0
								},
								{
									"val": "is_working",
									"name": "Работает сейчас",
									"checked": 0
								}
							]
						},
						{
							"alias": "categories",
							"name": "Блюда",
							"type": "checkbox",
							"items": [
								{
									"val": "1",
									"name": "Пироги",
									"checked": 1
								},
								{
									"val": "2",
									"name": "Фаст-фуд",
									"checked": 0
								},
								{
									"val": "3",
									"name": "Шашлыки",
									"checked": 1
								},
								{
									"val": "4",
									"name": "Пицца",
									"checked": 1
								},
								{
									"val": "6",
									"name": "Суши",
									"checked": 0
								}
							]
						},
						{
							"alias": "kitchens",
							"name": "Кухня",
							"type": "checkbox",
							"items": [
								{
									"val": "1",
									"name": "Пироги",
									"checked": 1
								},
								{
									"val": "2",
									"name": "Фаст-фуд",
									"checked": 0
								},
								{
									"val": "3",
									"name": "Шашлыки",
									"checked": 1
								},
								{
									"val": "4",
									"name": "Пицца",
									"checked": 0
								},
								{
									"val": "6",
									"name": "Суши",
									"checked": 0
								}
							]
						}
					]
				},
				{
					"id": 2,
					"name": "Шашлыки",
					"filter": [
						{
							"alias": "sort",
							"name": "Сортировка",
							"type": "radio",
							"items": [
								{
									"val": "default",
									"name": "По умолчанию",
									"checked": 0
								},
								{
									"val": "popular",
									"name": "Популярный",
									"checked": 0
								},
								{
									"val": "rate",
									"name": "Рейтингу",
									"checked": 1
								}
							]
						},
						{
							"alias": "min_price",
							"name": "Prices",
							"desc": "Сумма заказа до",
							"type": "slider",
							"val": 5000,
							"min_val": 0,
							"max_val": 100000,
							"step": 1000
						},
						{
							"alias": "criteria",
							"name": "Критерии",
							"type": "checkbox",
							"items": [
								{
									"val": "has_promotion",
									"name": "С акциями",
									"checked": 0
								},
								{
									"val": "free_delivery",
									"name": "Бесплатная доставка",
									"checked": 0
								},
								{
									"val": "card_online",
									"name": "Оплата картой онлайн",
									"checked": 0
								},
								{
									"val": "card_courier",
									"name": "Оплата картой курьеру",
									"checked": 0
								},
								{
									"val": "is_working",
									"name": "Работает сейчас",
									"checked": 0
								}
							]
						},
						{
							"alias": "categories",
							"name": "Блюда",
							"type": "checkbox",
							"items": [
								{
									"val": "1",
									"name": "Пироги",
									"checked": 0
								},
								{
									"val": "2",
									"name": "Фаст-фуд",
									"checked": 0
								},
								{
									"val": "3",
									"name": "Шашлыки",
									"checked": 0
								},
								{
									"val": "4",
									"name": "Пицца",
									"checked": 1
								},
								{
									"val": "6",
									"name": "Суши",
									"checked": 0
								}
							]
						},
						{
							"alias": "kitchens",
							"name": "Кухня",
							"type": "checkbox",
							"items": [
								{
									"val": "1",
									"name": "Пироги",
									"checked": 1
								},
								{
									"val": "2",
									"name": "Фаст-фуд",
									"checked": 0
								},
								{
									"val": "3",
									"name": "Шашлыки",
									"checked": 0
								},
								{
									"val": "4",
									"name": "Пицца",
									"checked": 1
								},
								{
									"val": "6",
									"name": "Суши",
									"checked": 0
								}
							]
						}
					]
				},
				{
					"id": 3,
					"name": "Пироги",
					"filter": [
						{
							"alias": "sort",
							"name": "Сортировка",
							"type": "radio",
							"items": [
								{
									"val": "default",
									"name": "По умолчанию",
									"checked": 0
								},
								{
									"val": "popular",
									"name": "Популярный",
									"checked": 0
								},
								{
									"val": "rate",
									"name": "Рейтингу",
									"checked": 1
								}
							]
						},
						{
							"alias": "min_price",
							"name": "Prices",
							"desc": "Сумма заказа до",
							"type": "slider",
							"val": 12000,
							"min_val": 0,
							"max_val": 100000,
							"step": 1000
						},
						{
							"alias": "criteria",
							"name": "Критерии",
							"type": "checkbox",
							"items": [
								{
									"val": "has_promotion",
									"name": "С акциями",
									"checked": 0
								},
								{
									"val": "free_delivery",
									"name": "Бесплатная доставка",
									"checked": 0
								},
								{
									"val": "card_online",
									"name": "Оплата картой онлайн",
									"checked": 0
								},
								{
									"val": "card_courier",
									"name": "Оплата картой курьеру",
									"checked": 0
								},
								{
									"val": "is_working",
									"name": "Работает сейчас",
									"checked": 0
								}
							]
						},
						{
							"alias": "categories",
							"name": "Блюда",
							"type": "checkbox",
							"items": [
								{
									"val": "1",
									"name": "Пироги",
									"checked": 1
								},
								{
									"val": "2",
									"name": "Фаст-фуд",
									"checked": 0
								},
								{
									"val": "3",
									"name": "Шашлыки",
									"checked": 0
								},
								{
									"val": "4",
									"name": "Пицца",
									"checked": 0
								},
								{
									"val": "6",
									"name": "Суши",
									"checked": 0
								}
							]
						},
						{
							"alias": "kitchens",
							"name": "Кухня",
							"type": "checkbox",
							"items": [
								{
									"val": "1",
									"name": "Пироги",
									"checked": 0
								},
								{
									"val": "2",
									"name": "Фаст-фуд",
									"checked": 0
								},
								{
									"val": "3",
									"name": "Шашлыки",
									"checked": 0
								},
								{
									"val": "4",
									"name": "Пицца",
									"checked": 0
								},
								{
									"val": "6",
									"name": "Суши",
									"checked": 0
								}
							]
						}
					]
				}
			]
		},
		"req_id": 13515
	}
	</pre>

		</div>
	</div>
	
</section>