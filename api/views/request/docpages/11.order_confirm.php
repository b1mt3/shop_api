<section id="section_order_confirm" class="section">
	<br/>
	<br/>
	<h3 class="alert-success">Подтверждение заказа</h3>
	<div class="section-body">
		<p></p>
		<div class="data-item">
			<b>url:</b> <code>{baseUrl}/order/confirm/</code>

		</div>
		
		<h4>Запрос:</h4>
		<div class="data-item">
			<b>Параметры:</b> 
			<ul class="data-ul">
				<li>
					<b>token</b> - <i>string</i> - <u>required</u> - Временный токен
				</li>
				<li>
					<b>code</b> - <i>int</i>  - <u>required</u> - Код отправленный через СМС
				</li>
			</ul>

		</div>
		<h4>Ответ:</h4>
		<div class="data-item">
			<b>Данные:</b> 
			
			<ul class="data-ul">
				<li>
					<b>auth_token</b> - <i>string</i> - Токен авторизации
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
				<li>
					<b>order_id</b> - <i>int</i> - ID заказа
				</li>
			</ul>

		</div>
		
		<h4>Пример</h4>
		<div class="data-item">
			<b>Запрос:</b>
	<pre>
	GET {baseUrl}//order/confirm/       HTTP/1.1
	Content-Type: text/json; charset=UTF-8

	{
		"params":{
			"token": "4c206ba01e7ac30bb81fce0978ff8d62",
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
			"auth_token": "208ee5bfba9f8769f9cc091671b4707e",
			"profile": {
				"id": 12,
				"phone": "998935405520",
				"email": null,
				"name": "Firdaus"
			},
			"order_id": 24
		},
		"req_id": 16
	}
	</pre>

		</div>
	</div>
	
</section>