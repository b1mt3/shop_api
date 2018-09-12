<section id="section_auth_pass" class="section">
	<br/>
	<br/>
	<h3 class="alert-success">Авторизация (паролем)</h3>
	<div class="section-body">
		<p>Шаги для авторизации паролем:</p>
		<p>1. Отправляется контактный телефон(<code>phone</code>) и в ответе возврашаем временный <code>token</code></p>
		<p>2. Генерируется хеш используя <code>token</code> и отправляется на сервер и в ответе получаем <code>auth_token</code></p>
		<p>3. Полученный <code>auth_token</code> отправляется при каждом запросе</p>	
		
		<div class="data-item">
			<b>url:</b> <code>{baseUrl}/auth/temptoken/</code> - получение временного токена<br/>
			<b>url:</b> <code>{baseUrl}/auth/token/</code> - подтверждение

		</div>

	</div>
</section>

<section id="section_temptoken" class="section">
	<br/>
	<br/>
	<h3 class="alert-success">Получение временного токена</h3>
	<div class="section-body">
		
		<div class="data-item">
			<b>url:</b> <code>{baseUrl}/auth/temptoken/</code>

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
	POST {baseUrl}/auth/temptoken/       HTTP/1.1
	Content-Type: text/json; charset=UTF-8

	{
		"params":{
			"phone": "79859859892",
			"app_data":{
				"device_id":"sfsdjhJJHkjhdf",
				"device_token":"sfsdjh...JJHkjhdf",
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
			"token": "e2e60edffa2d6d6901c04f0cebabe31f"
		},
		"req_id": 61
	}
	</pre>

		</div>
	</div>
</section>

<section id="section_auth_pass_confirm" class="section">
	<br/>
	<br/>
	<h3 class="alert-success">Получение токена авторизации</h3>
	<div class="section-body">
		<p>Генерирование HASH пароля с использованием временного токена</p>
		
		1. хешируется пароль в md5:<br/> 
		<code>hash1=md5(password)</code><br/>
		
		2. полученный hash1 хешируется с помошь hash_hmac(256) с использованием токена: <br/>
		<code>pass_hash=hash_hmac('sha256', hash1 , token)</code><br/>

		в java используется альтернатива  hash_hmac функции.				

		Пример результатов:	<br/>							
		<code>password = "test"	</code><br/>							
		<code>token="7a0b99ba403dc9bdc92b0bfc97601d64"</code>	<br/>							
		<code>pass_hash="d41c64980b3b3e7cf175dfb70da5abdb1b380b5c50f3a7b82db4618a2660969f"</code>								
		<br/>
		<br/>
		<div class="data-item">
			<b>url:</b> <code>{baseUrl}/auth/token/</code>

		</div>
		<h4>Запрос:</h4>
		<div class="data-item">
			<b>Параметры:</b> 
			<ul class="data-ul">
				<li>
					<b>token</b> - <i>string</i>  - <u>required</u>  - Временный токен, полученный предыдушим запросом;
				</li>
				<li>
					<b>pass_hash</b> - <i>string</i>  - <u>required</u>  - Сгенерированный хеш.
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
	GET {baseUrl}/auth/token/       HTTP/1.1
	Content-Type: text/json; charset=UTF-8

	{
		"params":{
			"token": "e2e60edffa2d6d6901c04f0cebabe31f",
			"pass_hash":"36454d40dd800b95b8c8dd3d164792ea344f61b7a7ad9183a4068e90eddb2176"
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
			"auth_token": "12275c5ba161f7db8b12bf306f29e1d5",
			"profile": {
				"id": 6,
				"phone": "79859859892",
				"email": "some@example.com",
				"name": "Some Name"
			}
		},
		"req_id": 63
	}
	</pre>

		</div>
	</div>
</section>