<section id="section_support" class="section">
	<br/>
	<br/>
	<h3 class="alert-success">Отображение контактов</h3>
	
	<div class="section-body">
		<p></p>
		<div class="data-item">
			<b>url:</b> <code>{baseUrl}/support</code>

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
					<b>phone</b> - <i>string</i> - Контактный номер
				</li>
				<li>
					<b>email</b> - <i>string</i> - Электронная почта
				</li>
			</ul>

		</div>
		
		<h4>Пример</h4>
		<div class="data-item">
			<b>Запрос:</b>
	<pre>
	GET {baseUrl}/support/       HTTP/1.1
	Content-Type: text/json; charset=UTF-8

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
			"phone": "+7777777777",
			"email": "support@foodexpress.kz"
		},
		"req_id": 503
	}
	</pre>

		</div>
	</div>
	
</section>