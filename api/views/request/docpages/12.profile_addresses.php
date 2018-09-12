<section id="section_profile_addresses" class="section">
	<br/>
	<br/>
	<h3 class="alert-success">Адреса</h3>
	<div class="section-body">
		<p></p>
		<div class="data-item">
			<b>url:</b> <code>{baseUrl}/profile/addresses</code>

		</div>
		
		<h4>Запрос:</h4>
		<div class="data-item">
			<b>Параметры:</b> 
			<ul class="data-ul">
				<li>
					<b>page</b> - <i>int</i> - <u>optional</u> - Номер страницы при пагинации. По умолчанию 1. <span class="text-muded"></span>
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
					<b>addresses</b> - <i>array</i> - Список адресов. Каждый элемент является объектом адреса:
					<ul>
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
				</li>
			</ul>

		</div>
		
		<h4>Пример</h4>
		<div class="data-item">
			<b>Запрос:</b>
	<pre>
	GET {baseUrl}/profile/addresses        HTTP/1.1
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
					"id": 4,
					"name": "Work",
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