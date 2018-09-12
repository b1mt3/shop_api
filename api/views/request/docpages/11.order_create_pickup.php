<section id="section_order_create_pickup" class="section">
	<br/>
	<br/>
	<h3 class="alert-success">Запрос на создание заказа(самовывоз)</h3>
	<div class="section-body">
		<p></p>
		<div class="data-item">
			<b>url:</b> <code>{baseUrl}/order/create/pickup</code>

		</div>
		<h4>Запрос:</h4>
		<div class="data-item">
			<b>Параметры:</b> 
			<ul class="data-ul">
				<li>
					<b>restaurant_id</b> - <i>int</i>  - <u>required</u> - ID ресторана (выбранного филиала)
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
	POST {baseUrl}/order/create/pickup/    HTTP/1.1
	Content-Type: text/json; charset=UTF-8

	{
		"params":{
			"restaurant_id": 1,
			"user_name":"Firdaus",
			"phone":998935405520,
			"payment_type":1,
			"date":"21:00",
			"person_count":3,
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
			"token": "e2c25cc3cc1b7366b1e34c3a971cf6aa",
			"order_id": 24,
			"paytoken": "65406ba01e7ac30bb81fce0978ff8d25"
		},
		"req_id": 463
	}
	</pre>

		</div>
	</div>
	
</section>