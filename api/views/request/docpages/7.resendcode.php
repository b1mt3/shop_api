<section id="section_resendcode" class="section">
	<br/>
	<br/>
	<h3 class="alert-success">Переотправка кода</h3>
	<div class="section-body">
		<p></p>
		<div class="data-item">
			<b>url:</b> <code>{baseUrl}/auth/resend</code>

		</div>
		<h4>Запрос:</h4>
		<div class="data-item">
			<b>Параметры:</b> 
			<ul class="data-ul">
				<li>
					<b>token</b> - <i>string</i> - <u>required</u> - Временный токен
				</li>
			</ul>

		</div>
		<h4>Ответ:</h4>
		<div class="data-item">
			<b>Данные:</b> 
			
			<ul class="data-ul">
				<li>
					<b>sent</b> - <i>0 or 1</i> - Статус отправки
				</li>
			</ul>

		</div>
		
		<h4>Пример</h4>
		<div class="data-item">
			<b>Запрос:</b>
	<pre>
	POST {baseUrl}/auth/resend/          HTTP/1.1
	Content-Type: text/json; charset=UTF-8

	{
		"params":{
			"token":"879bddcf23f9f5499e696aa9a50bc331"
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
			"sent": 1
		},
		"req_id": 302
	}
	</pre>

		</div>
	</div>
	
</section>