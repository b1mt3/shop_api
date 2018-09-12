<section id="section_order_config" class="section">
	<br/>
	<br/>
	<h3 class="alert-success">Получить конфигурацию</h3>
	<div class="section-body">
		<p></p>
		<div class="data-item">
			<b>url:</b> <code>{baseUrl}/order/config</code>

		</div>
		<h4>Запрос:</h4>
		<div class="data-item">
			<b>Параметры:</b> 
			<ul class="data-ul">
				<li>
					<b>restaurant_id</b> - <i>int</i>  - <u>required</u> - ID ресторана
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
					</ul>
					
				</li>
			</ul>

		</div>
		<h4>Ответ:</h4>
		<div class="data-item">
			<b>Данные:</b> 
			
			<ul class="data-ul">
				<li>
					<b>delivery_price</b> - <i>int</i> - Стоимость доставки (в КЗТ)
				</li>
				<li>
					<b>delivery_price_text</b> - <i>string</i> - Текст стоимости доставки
				</li>
				<li>
					<b>distance</b> - <i>object</i> - расстояние ближайщего ресторана
					<ul>
						<li>
							<b>text</b> - <i>string</i> - Текст расстоянии
						</li>
						<li>
							<b>value</b> - <i>int</i> - Значение расстоянии (в метрах)
						</li>
					</ul>
				</li>
				<li>
					<b>pay_types</b> - <i>object</i> - Типы оплат. Содержит: 
					<ul>
						<li>
							<b>delivery</b> - <i>array</i> - Список оплат для доставки. Каждый элемент содержит: 
							<ul>
								<li>
									<b>id</b> - <i>int</i> - ID типа оплаты 
								</li>
								<li>
									<b>name</b> - <i>string</i> - Название 
								</li>
								<li>
									<b>type</b> - <i>string</i> - метод оплаты. Возможные значения <code>online</code> (онлайн оплата) и <code>offline</code> (оффлайн оплата) 
								</li>
								<li>
									<b>isCash</b> - <i>0 or 1</i> - имеется оплата кеш
								</li>
							</ul>
						</li>
						<li>
							<b>pickup</b> - <i>array</i> - Список оплат для самовывоза. Каждый элемент содержит: 
							<ul>
								<li>
									<b>id</b> - <i>int</i> - ID типа оплаты 
								</li>
								<li>
									<b>name</b> - <i>string</i> - Название 
								</li>
								<li>
									<b>type</b> - <i>string</i> - метод оплаты. Возможные значения <code>online</code> (онлайн оплата) и <code>offline</code> (оффлайн оплата) 
								</li>
								<li>
									<b>isCash</b> - <i>0 or 1</i> - имеется оплата кеш
								</li>
							</ul>
						</li>
						
					</ul>
				</li>
				<li>
					<b>restaurant</b> - <i>object</i> - Ближайший филиал ресторана
					<ul>
						<li>
							<b>id</b> - <i>int</i> - ID ресторана
						</li>
						<li>
							<b>working_hours_in_seconds</b> - <i>object</i> - Время работы:
							<ul>
								<li>
									<b>start</b> - <i>int</i> - Время начало работы в секундах с начало дня
								</li>
								<li>
									<b>end</b> - <i>int</i> - Время окончания работы в секундах с начало дня
								</li>
							</ul>
						</li>
						<li>
							<b>min_order_sum_val</b> - <i>int</i> - Минимальная сумма заказа
						</li>
					</ul>
				</li>
				<li>
					<b>has_pickup</b> - <i>0 or 1</i> - имеется самовывоз
				</li>
				<li>
					<b>filials</b> - <i>array</i> - Филиалы ресторана (список):
					<ul>
						<li>
							<b>id</b> - <i>int</i> - ID ресторана
						</li>
						<li>
							<b>name</b> - <i>string</i> - Название ресторана
						</li>
						<li>
							<b>working_hours</b> - <i>object</i> - Время работы
							<ul>
								<li>
									<b>day</b> - <i>string</i> - Название
								</li>
								<li>
									<b>time</b> - <i>string</i> - время
								</li>
							</ul>
						</li>
						<li>
							<b>address</b> - <i>string</i> - Текст адреса
						</li>
					</ul>
				</li>
			</ul>

		</div>
		
		<h4>Пример</h4>
		<div class="data-item">
			<b>Запрос:</b>
	<pre>
	POST {baseUrl}/order/config      HTTP/1.1
	Content-Type: text/json; charset=UTF-8
	{
	"params":{
			"restaurant_id": 1,
			"gloc":{
				"coord":{
					"lon":76.945019,
					"lat":43.361843
				}
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
			"delivery_price": 14271,
			"delivery_price_text": "14271 КЗТ",
			"distance": {
				"text": "11,9 км",
				"value": 11893
			},
			"pay_types": {
				"delivery": [
					{
						"id": 1,
						"name": "Виза",
						"type": "online",
						"isCash": 0
					},
					{
						"id": 2,
						"name": "Мастеркарт",
						"type": "online",
						"isCash": 0
					},
					{
						"id": 3,
						"name": "Оплата наличными",
						"type": "offline",
						"isCash": 0
					},
					{
						"id": 4,
						"name": "Оплата картой курьеру",
						"type": "offline",
						"isCash": 0
					}
				],
				"pickup": [
					{
						"id": 1,
						"name": "Виза",
						"type": "online",
						"isCash": 0
					},
					{
						"id": 2,
						"name": "Мастеркарт",
						"type": "online",
						"isCash": 0
					},
					{
						"id": 3,
						"name": "Оплата наличными",
						"type": "offline",
						"isCash": 0
					},
					{
						"id": 4,
						"name": "Оплата картой курьеру",
						"type": "offline",
						"isCash": 0
					}
				]
			},
			"restaurant": {
				"id": 4,
				"working_hours_in_seconds": {
					"start": 0,
					"end": 73800
				},
				"min_order_sum_val": 5000
			},
			"has_pickup": 1,
			"filials": [
				{
					"id": 1,
					"name": "Ресторан 1",
					"working_hours": {
						"day": "Вторник",
						"time": "07:00-22:00"
					},
					"address": "Sea sapientem ocurreret laboramus te"
				},
				{
					"id": 4,
					"name": "Ресторан 4",
					"working_hours": {
						"day": "Вторник",
						"time": "07:00-22:00"
					},
					"address": "Адрес 4"
				}
			]
		},
		"req_id": 1156
	}
	</pre>

		</div>
	</div>
	
</section>