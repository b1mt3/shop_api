<section id="section_filter" class="section">
	<br/>
	<br/>
	<h3 class="alert-success">Фильтр</h3>
	<div class="section-body">
		<p>Возврашает фильтр. Для фильтрации списка ресторанов отапрвляется этот фильтр в объекте параметров (<code>params</code>) изменив статуса выбора (<code>checked</code>) или значение слайдера (<code>val</code>)</p>
		<div class="data-item">
			<b>url:</b> <code>{baseUrl}/restaurants/filter/</code>

		</div>
		<h4>Запрос:</h4>
		<div class="data-item">
			<b>Параметры:</b> <code>(без параметров)</code>

		</div>
		<h4>Ответ:</h4>
		<div class="data-item">
			<b>Данные:</b> 
			
			<ul class="data-ul">
				<li>
					<b>filter</b> - <i>array</i> - Массив объектов. Каждый объект содержит данные отдельных фильтров:
					<ul>
						<li>
							<b>alias</b> - <i>string</i> - Название поле для отправки
						</li>
						<li>
							<b>name</b> - <i>string</i> - Название фильтра
						</li>
						<li>
							<b>type</b> - <i>string</i> - Тип фильтра. Возможные значения: <code>radio</code>, <code>checkbox</code>, <code>slider</code>
						</li>
						<li>
							<b>items</b> - <i>array</i> - Массив инпутов (присутствует при типе фильтра <code>radio</code> или <code>checkbox</code>). Каждый инпут содержит:
							<ul>
								<li>
									<b>val</b> - <i>string || int</i> - значение инпута
								</li>
								<li>
									<b>name</b> - <i>string</i> - Название инпута
								</li>
								<li>
									<b>checked</b> - <i>0 || 1</i> - Статус выбора
								</li>
							</ul>
						</li>
						<li>
							<b>min_val</b> - <i>int</i> - Минимальное значение слайдера (присутствует при типе фильтра <code>slider</code>)
						</li>
						<li>
							<b>max_val</b> - <i>int</i> - Максимальное  значение слайдера (присутствует при типе фильтра <code>slider</code>)
						</li>
						<li>
							<b>val</b> - <i>int</i> - Значение слайдера (присутствует при типе фильтра <code>slider</code>)
						</li>
						<li>
							<b>desc</b> - <i>string</i> - Описание поле (присутствует при типе фильтра <code>slider</code>)
						</li>
						<li>
							<b>step</b> - <i>int</i> - Шаг прокрутки слайдера
						</li>
					</ul>
					
				</li>
			</ul>

		</div>
		
		<h4>Пример</h4>
		<div class="data-item">
			<b>Запрос:</b>
	<pre>
	POST {baseUrl}/restaurants/filter/     HTTP/1.1
	Content-Type: text/json; charset=UTF-8

	{
		"params":{

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
		"filter": [
			{
				"alias": "sort",
				"name": "Сортировка",
				"type": "radio",
				"items": [
					{
						"val": "default",
						"name": "По умолчанию",
						"checked": 1
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
				"val": 10000,
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
						"val": 1,
						"name": "Пироги",
						"checked": 0
					},
					{
						"val": 2,
						"name": "Фаст-фуд",
						"checked": 0
					},
					{
						"val": 3,
						"name": "Пицца",
						"checked": 0
					},
					{
						"val": 4,
						"name": "Суши",
						"checked": 0
					}
				]
			},
			{
				"alias": "kitchens",
				"name": "Кухнии",
				"type": "checkbox",
				"items": [
					{
						"val": 1,
						"name": "Пироги",
						"checked": 0
					},
					{
						"val": 2,
						"name": "Фаст-фуд",
						"checked": 0
					},
					{
						"val": 3,
						"name": "Пицца",
						"checked": 0
					},
					{
						"val": 4,
						"name": "Суши",
						"checked": 0
					}
				]
			}
		]
	},
	"req_id": 188
}
	</pre>

		</div>
	</div>
	
</section>