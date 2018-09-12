<section id="section_profile" class="section">
	<br/>
	<br/>
	<h3 class="alert-success">Профиль</h3>
	
	<div class="section-body">
		<p></p>
		<div class="data-item">
			<b>url:</b> <code>{baseUrl}/profile</code>

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
	GET {baseUrl}/profile/       HTTP/1.1
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
			"id": 13,
			"phone": "77777777",
			"email": "",
			"name": "Some Name"
		},
		"req_id": 506
	}
	</pre>

		</div>
	</div>
	
</section>