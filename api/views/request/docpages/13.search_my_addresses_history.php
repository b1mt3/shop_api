<section id="section_search_my_addresses_history" class="section">
	<br/>
	<br/>
	<h3 class="alert-success">Поиск: история адресов</h3>
	
	<div class="section-body">
		<p></p>
		<div class="data-item">
			<b>url:</b> <code>{baseUrl}/search/address/history</code>

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
					<b>addresses</b> - <i>array</i> - Список адресов. Каждый элемент является объектом адреса:
					<ul>
						
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
				</li>
			</ul>

		</div>
		
		<h4>Пример</h4>
		<div class="data-item">
			<b>Запрос:</b>
	<pre>
	GET {baseUrl}/search/address/history        HTTP/1.1
	Content-Type: text/json; charset=UTF-8
	Auth: {auth_token}

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
			"page": 1,
			"pageSize": 10,
			"allCount": 1,
			"addresses": [
				{
					"location": {
						"lat": 25,
						"lon": 22
					},
					"city": "Almata",
					"district": "",
					"street": "ул. Бурхона",
					"house": "2",
					"room": "3",
					"floor": 1,
					"entrance": 2,
					"doorphone_code": 3333,
					"formatted_address": "Some address"
				}
			]
		},
		"req_id": 599
	}
	</pre>

		</div>
	</div>
	
</section>