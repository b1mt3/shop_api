<section id="section_order_create_delivery" class="section">
	<br/>
	<br/>
	<h3 class="alert-success">Запрос на создание заказа(доставка)</h3>
	<div class="section-body">
		<p></p>
		<div class="data-item">
			<b>url:</b> <code>{baseUrl}/order/create/delivery</code>

		</div>
		<h4>Запрос:</h4>
		<div class="data-item">
			<b>Параметры:</b> 
			<ul class="data-ul">
				<li>
					<b>restaurant_id</b> - <i>int</i>  - <u>required</u> - ID ресторана
				</li>
				<li>
					<b>user_name</b> - <i>string</i>  - <u>required</u> - Имя пользователя
				</li>
				<li>
					<b>phone</b> - <i>string</i>  - <u>required</u> - Контактный телефон
				</li>
				<li>
					<b>promo_code</b> - <i>string</i>  - <u>optional</u> - Промокод
				</li>
				<li>
					<b>comment</b> - <i>string</i>  - <u>optional</u> - Комментарие от пользователя
				</li>
				<li>
					<b>address</b> - <i>object</i>  - <u>required</u> - Объект адреса
					<ul>
						<li>
							<b>name</b> - <i>string</i>  - <u>optional</u> - Название
						</li>
						<li>
							<b>location</b> - <i>object</i>  - <u>required</u> - геолокация:
							<ul>
								<li>
									<b>lon</b> - <i>float</i>  - <u>required</u> - долгота на карте
								</li>
								<li>
									<b>lat</b> - <i>float</i>  - <u>required</u> - широта на карте
								</li>
							</ul>
						</li>
						<li>
							<b>city</b> - <i>string</i>  - <u>optional</u> - Город
						</li>
						<li>
							<b>district</b> - <i>string</i>  - <u>optional</u> - Район
						</li>
						<li>
							<b>street</b> - <i>string</i>  - <u>optional</u> - улица
						</li>
						<li>
							<b>house</b> - <i>string</i>  - <u>optional</u> - дом
						</li>
						<li>
							<b>room</b> - <i>string</i>  - <u>optional</u> - квартира
						</li>
						<li>
							<b>floor</b> - <i>string</i>  - <u>optional</u> - этаж
						</li>
						<li>
							<b>entrance</b> - <i>string</i>  - <u>optional</u> - подъезд
						</li>
						<li>
							<b>doorphone_code</b> - <i>string</i>  - <u>optional</u> - код домофона
						</li>
					</ul>
				</li>
				<li>
					<b>payment_type</b> - <i>int</i>  - <u>required</u> - ID типа оплаты
				</li>
				<li>
					<b>date</b> - <i>string</i>  - <u>optional</u> - Время изготовления заказа типа "21:00"
				</li>
				<li>
					<b>person_count</b> - <i>int</i>  - <u>optional</u> - Количество человек
				</li>
				<li>
					<b>items</b> - <i>array</i>  - <u>required</u> - список блюд добавления. Каждый элемент массива является объектом:
					<ul>
						<li>
							<b>id</b> - <i>int</i>  - <u>required</u> - ID блюд
						</li>
						<li>
							<b>additives</b> - <i>array</i>  - <u>optional</u> - список ID добавки к блюдам
						</li>
						<li>
							<b>cook_condition</b> - <i>int</i>  - <u>optional</u> - ID способа изготовления
						</li>
						<li>
							<b>count</b> - <i>int</i>  - <u>required</u> - количество
						</li>
					</ul>
				</li>
				<li>
					<b>app_data</b> - <i>object</i> - <u>required</u> - Данные телефоного аппарата. Содержит:
					<ul>
						<li>
							<b>device_id</b> - <i>string</i> - <u>required</u> - Уникальный ID телефонного аппарата
						</li>
						<li>
							<b>device_token</b> - <i>string</i> - <u>optional</u> - Токен девайса (используется для отправки уведомления)
						</li>
						<li>
							<b>device_type</b> - <i>string</i> - <u>optional</u> - Тип операционной системы. Возможные значения: <code>ios</code> или <code>android</code>
						</li>
						<li>
							<b>ios_mode</b> - <i>string</i> - <u>optional</u> - Тип режима отправки ПУШ для <b>IOS</b>. Возможные значения: <code>sandbox</code> или <code>production</code>
						</li>
					</ul>
					
				</li>
				<li>
					<b>distance</b> - <i>object</i>- <u>required</u> - расстояние. Ноадо отправить объект полученный в составе конфигурации 
					<ul>
						<li>
							<b>text</b> - <i>string</i> - <u>required</u> - Текст расстоянии
						</li>
						<li>
							<b>value</b> - <i>int</i> - <u>required</u> - Значение расстоянии (в метрах)
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
					<b>sent_sms</b> - <i>0 or 1</i> - статус отправки СМС на номер. Поле присутствует при неавторизованном пользователе
				</li>
				<li>
					<b>token</b> - <i>string</i> - временный токен для подтверждения номера телефона. Поле присутствует при неавторизованном пользователе
				</li>
				<li>
					<b>order_id</b> - <i>int</i> - ID заказа
				</li>
				<li>
					<b>paytoken</b> - <i>string</i> - Токен для оплаты. Присутствует при выборе <b>online</b> типа оплаты
				</li>
			</ul>

		</div>
		
		<h4>Пример</h4>
		<div class="data-item">
			<b>Запрос:</b>
	<pre>
	POST {baseUrl}/order/create/delivery      HTTP/1.1
	Content-Type: text/json; charset=UTF-8
	{
		"params":{
			"restaurant_id": 1,
			"user_name":"Firdaus",
			"phone":998935405520,
			"date":"21:00",
			"person_count":3,
			"address":{
				"name": "Some name",
				"location":{
					"lon":76.945019,
					"lat":43.361843
				},
				"city": "Tashkent",
				"district": "",
				"street": "ул. Бурхона",
				"house": "2",
				"room": "1",
				"floor": 2,
				"entrance": 12,
				"doorphone_code": 2222
			},
			"payment_type":1,
			"items":[
				{
					"id":1,
					"additives":[],
					"cook_condition":[],
					"count":2
				},
				{
					"id":2,
					"additives":[],
					"cook_condition":[],
					"count":5
				},
				{
					"id":3,
					"additives":[2,4],
					"cook_condition":[],
					"count":2
				}
			],
			"app_data":{
				"device_id":"sfsdjhJJHkjhdf",
				"device_token":"sfsdjhJJHkjhdf",
				"device_type":"ios"
			},
			"distance": {
				"text": "11,9 км",
				"value": 11893
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
			"sent_sms": 1,
			"token": "4c206ba01e7ac30bb81fce0978ff8d62",
			"order_id": 24,
			"paytoken": "65406ba01e7ac30bb81fce0978ff8d25"
		},
		"req_id": 14
	}
	</pre>

		</div>
	</div>
	
</section>