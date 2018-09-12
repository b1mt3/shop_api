<section id="section_restore_confirm" class="section">
	<br/>
	<br/>
	<h3 class="alert-success">Восстановление</h3>
	<div class="section-body">
		
		<p>Восстановление производится подтверждением телефонного номера путъем <b>отправки кода на номер</b>(1) и <b>подтверждение с помошью отправленного кода</b>(2)</p>
		
		<div class="data-item">
			<b>url:</b> <code>{baseUrl}/auth/restore/</code> - запрос отправки кода для подтверждения <br/>
			<b>url:</b> <code>{baseUrl}/auth/restoreconfirm/</code> - подтверждение

		</div>
		
	</div>
</section>
<section id="section_restore" class="section">
	<br/>
	<br/>
	<h3 class="alert-success">Отправка запроса на подтверждения(отправка кода)</h3>
	<div class="section-body">
		<p>При успешном запросе отправляется СМС с кодом для подтверждения номера. Можно отправить запрос повторно только после 2 минуты</p>
		<div class="data-item">
			<b>url:</b> <code>{baseUrl}/auth/restore/</code>

		</div>
		<h4>Запрос:</h4>
		<div class="data-item">
			<b>Параметры:</b> 
			<ul class="data-ul">
				<li>
					<b>phone</b> - <i>string</i> - <u>required</u> - Номер телефона.
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
					<b>token</b> - <i>string</i> - Временный токен;
				</li>
			</ul>
		</div>
		
		<h4>Пример</h4>
		<div class="data-item">
			<b>Запрос:</b>
	<pre>
	POST {baseUrl}/restore/       HTTP/1.1
	Content-Type: text/json; charset=UTF-8

	{
		"params":{
			"phone": 79859859893,
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
			"token": "96f109657ff2be257da4a443ad2fcc32"
		},
		"req_id": 33
	}
	</pre>

		</div>
	</div>
</section>

<section id="section_restorecheck" class="section">
	<br/>
	<br/>
	<h3 class="alert-success">Проверка кода</h3>
	<div class="section-body">
		
		<div class="data-item">
			<b>url:</b> <code>{baseUrl}/auth/restorecheck/</code>

		</div>
		<h4>Запрос:</h4>
		<div class="data-item">
			<b>Параметры:</b> 
			<ul class="data-ul">
				<li>
					<b>token</b> - <i>string</i>  - <u>required</u>  - Временный токен, полученный предыдушим запросом;
				</li>
				<li>
					<b>code</b> - <i>int</i>  - <u>required</u>  - Код отправленный серез СМС.
				</li>
			</ul>
		</div>
		
		<h4>Ответ:</h4>
		<div class="data-item">
			<b>Данные:</b> 
			<ul class="data-ul">
				<li>
					<b>state</b> - <i>int</i> - Статус. Если <code>1</code> - код правильный;
				</li>
				
			</ul>
		</div>
		
		<h4>Пример</h4>
		<div class="data-item">
			<b>Запрос:</b>
	<pre>
	GET {baseUrl}/auth/restorecheck/       HTTP/1.1
	Content-Type: text/json; charset=UTF-8

	{
		"params":{
			"token": "96f109657ff2be257da4a443ad2fcc32",
			"code": 666666
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
			"state": 1
		},
		"req_id": 34
	}
	</pre>

		</div>
	</div>
</section>

<section id="section_restoreconf" class="section">
	<br/>
	<br/>
	<h3 class="alert-success">Подтверждение номера и сохранение пароля</h3>
	<div class="section-body">
		
		<div class="data-item">
			<b>url:</b> <code>{baseUrl}/auth/restoreconfirm/</code>

		</div>
		<h4>Запрос:</h4>
		<div class="data-item">
			<b>Параметры:</b> 
			<ul class="data-ul">
				<li>
					<b>token</b> - <i>string</i>  - <u>required</u>  - Временный токен, полученный предыдушим запросом;
				</li>
				<li>
					<b>password</b> - <i>string</i>  - <u>required</u>  - Новый пароль;
				</li>
				<li>
					<b>code</b> - <i>int</i>  - <u>required</u>  - Код отправленный серез СМС.
				</li>
			</ul>
		</div>
		
		<h4>Ответ:</h4>
		<div class="data-item">
			<b>Данные:</b> 
			<ul class="data-ul">
				<li>
					<b>auth_token</b> - <i>string</i> - Токен авторизации;
				</li>
				<li>
					<b>profile</b> - <i>object</i> - Объект профиля пользователя:
					<ul>
						<li>
							<b>id</b> - <i>int</i> - ID пользователя
						</li>
						<li>
							<b>phone</b> - <i>string</i> - Номер телефона:
						</li>
						<li>
							<b>email</b> - <i>string</i> - Электронная почта
						</li>
						<li>
							<b>name</b> - <i>string</i> - Имя
						</li>
					</ul>
				</li>
			</ul>
		</div>
		
		<h4>Пример</h4>
		<div class="data-item">
			<b>Запрос:</b>
	<pre>
	GET {baseUrl}/auth/restoreconfirm/       HTTP/1.1
	Content-Type: text/json; charset=UTF-8

	{
		"params":{
			"token": "96f109657ff2be257da4a443ad2fcc32",
			"password": "123456",
			"code": 666666
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
			"auth_token": "afc9c37a2e328ccd77551df2d727e7c3",
			"profile": {
				"id": 7,
				"phone": "79859859894",
				"email": "some@example.com",
				"name": "Some Name"
			}
		},
		"req_id": 34
	}
	</pre>

		</div>
	</div>
</section>