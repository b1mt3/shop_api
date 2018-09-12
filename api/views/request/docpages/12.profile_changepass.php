<section id="section_changepass" class="section">
	<br/>
	<br/>
	<h3 class="alert-success">Смена пароля</h3>
	<div class="section-body">
		<p></p>
		<div class="data-item">
			<b>url:</b> <code>{baseUrl}/profile/changepass</code>

		</div>
		<h4>Запрос:</h4>
		<div class="data-item">
			<b>Параметры:</b> 
			<ul class="data-ul">
				<li>
					<b>password</b> - <i>string</i> - <u>required</u> - Текущий пароль
				</li>
				<li>
					<b>new_password</b> - <i>string</i>  - <u>required</u> - Новый пароль
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
	GET {baseUrl}/profile/changepass     HTTP/1.1
	Content-Type: text/json; charset=UTF-8

	{"params":{
		"password":"111111",
		"new_password":"123456"
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
			"id": 34,
			"phone": "79859859895",
			"email": "",
			"name": null
		},
		"req_id": 716
	}
	</pre>

		</div>
	</div>
	
</section>