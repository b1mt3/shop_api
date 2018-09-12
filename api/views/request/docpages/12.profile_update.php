<section id="section_profile_update" class="section">
	<br/>
	<br/>
	<h3 class="alert-success">Обновить данные профиля</h3>
	
	<div class="section-body">
		
		<div class="data-item">
			<b>url:</b> <code>{baseUrl}/profile/update/</code>

		</div>
		
		<h4>Запрос:</h4>
		<div class="data-item">
			<b>Параметры:</b> 
			<ul class="data-ul">
				<li>
					<b>name</b> - <i>string</i> - <u>optional</u> - Имя пользователя
				</li>
				<li>
					<b>email</b> - <i>string</i> - <u>optional</u> - Электронная почта
				</li>
			</ul>
		</div>

	
		<h4>Ответ:</h4>
		<div class="data-item">
			<b>Данные:</b> 
			
			<ul class="data-ul">
				<li>
					<b>id</b> - <i>int</i> - ID пользователя
				</li>
				<li>
					<b>phone</b> - <i>string</i> - Номер пользователя
				</li>
				<li>
					<b>email</b> - <i>string</i> - Электронная почта пользователя
				</li>
				<li>
					<b>name</b> - <i>string</i> - Имя пользователя
				</li>
			</ul>

		</div>
		
		<h4>Пример</h4>
		<div class="data-item">
			<b>Запрос:</b>
	<pre>
	GET {baseUrl}/profile/update      HTTP/1.1
	Content-Type: text/json; charset=UTF-8
	Auth: {auth_token}

	{"params":{
		"name":"Some Name",
		"email":"some@mail.ru"
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
			"id": 13,
			"phone": "77777777",
			"email": "some@mail.ru",
			"name": "Some Name"
		},
		"req_id": 506
	}
	</pre>

		</div>
	</div>
	
</section>