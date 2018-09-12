<section id="section_profile_address_add" class="section">
	<br/>
	<br/>
	<h3 class="alert-success">Добавить адрес</h3>
	<div class="section-body">
		<p></p>
		<div class="data-item">
			<b>url:</b> <code>{baseUrl}/profile/address/add</code>

		</div>
		
		<h4>Запрос:</h4>
		<div class="data-item">
			<b>Параметры:</b> 
			<ul class="data-ul">
				<li>
					<b>address</b> - <i>object</i> - <u>required</u> - Объект адреса. Содержит
					<ul>
						<li>
							<b>name</b> - <i>string</i> - <u>required</u> - Название
						</li>
						<li>
							<b>location</b> - <i>object</i> - <u>required</u> - Объект геолокации. Содержит:
							<ul>
								<li>
									<b>lat</b> - <i>float</i> - <u>required</u> - Широта на карте
								</li>
								<li>
									<b>lon</b> - <i>float</i> - <u>required</u> - Долгота не карте
								</li>
							</ul>
						</li>
						<li>
							<b>city</b> - <i>string</i> - <u>optional</u> - Город
						</li>
						<li>
							<b>district</b> - <i>string</i> - <u>optional</u> - Район
						</li>
						<li>
							<b>street</b> - <i>string</i> - <u>optional</u> - Улица
						</li>
						<li>
							<b>house</b> - <i>string</i> - <u>optional</u> - Дом
						</li>
						<li>
							<b>room</b> - <i>string</i> - <u>optional</u> - Квартира
						</li>
						<li>
							<b>floor</b> - <i>string</i> - <u>optional</u> - Этаж
						</li>
						<li>
							<b>entrance</b> - <i>string</i> - <u>optional</u> - Подъезд
						</li>
						<li>
							<b>doorphone_code</b> - <i>string</i> - <u>optional</u> - Код домофона
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
					<b>id</b> - <i>int</i> - ID адреса
				</li>
				<li>
					<b>name</b> - <i>string</i> - Название
				</li>
				<li>
					<b>location</b> - <i>object</i> - объект геолокации. Содержит:
					<ul>
						<li>
							<b>lat</b> - <i>float</i> - Широта на карте
						</li>
						<li>
							<b>lon</b> - <i>float</i> - Долгота на каоте
						</li>
					</ul>
				</li>
				<li>
					другие данные отправленные пользователем
				</li>
			</ul>

		</div>
		
		<h4>Пример</h4>
		<div class="data-item">
			<b>Запрос:</b>
	<pre>
	GET {baseUrl}/profile/address/add         HTTP/1.1
	Content-Type: text/json; charset=UTF-8
	Auth: {auth_token}

	{
		"params":{
			"address":{
					"entrance": "",
			"city": "Almata",
					"location": {
			  "lat": 43.241214752197,
			  "lon": 76.887275695801
			},
			"doorphone_code": "",
					"name":"",
			"street": "улица Карасу",
			"house": "",
			"floor": ""

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
			"address": {
				"id": 73,
				"name": "",
				"location": {
					"lat": 43.241214752197,
					"lon": 76.887275695801
				},
				"city": "Almata",
				"district": "",
				"street": "улица Карасу",
				"house": "",
				"room": "",
				"floor": "",
				"entrance": "",
				"doorphone_code": "",
				"formatted_address": ""
			}
		},
		"req_id": 11880
	}
	</pre>

		</div>
	</div>	
	
</section>