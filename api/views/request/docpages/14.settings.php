<section id="section_settings" class="section">
	<br/>
	<br/>
	<h3 class="alert-success">Натройки</h3>
	
	<div class="section-body">
		<p></p>
		<div class="data-item">
			<b>url:</b> <code>{baseUrl}/settings</code>

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
					<b>sms_notification</b> - <i>0 or 1</i> - Уведомление через СМС
				</li>
				<li>
					<b>push_notification</b> - <i>0 or 1</i> - Уведомление через ПУШ
				</li>
				<li>
					<b>email_newsletters</b> - <i>0 or 1</i> - Уведомление через email
				</li>
				<li>
					<b>default_language</b> - <i>string</i> - Язык по умолчанию
				</li>
			</ul>

		</div>
		
		<h4>Пример</h4>
		<div class="data-item">
			<b>Запрос:</b>
	<pre>
	GET {baseUrl}/settings/       HTTP/1.1
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
			"sms_notification": 1,
			"push_notification": 1,
			"email_newsletters": 0,
			"default_language": "ru"
		},
		"req_id": 551
	}
	</pre>

		</div>
	</div>
	
</section>