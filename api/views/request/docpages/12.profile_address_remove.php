<section id="section_profile_address_remove" class="section">
	<br/>
	<br/>
	<h3 class="alert-success">Удалить адрес</h3>
	<div class="section-body">
		<p></p>
		<div class="data-item">
			<b>url:</b> <code>{baseUrl}/profile/address/remove</code>

		</div>
		
		<h4>Запрос:</h4>
		<div class="data-item">
			<b>Параметры:</b> 
			<ul class="data-ul">
				<li>
					<b>address_id</b> - <i>int</i> - <u>required</u> - ID адреса
				</li>
			</ul>
		</div>

	
		<h4>Ответ:</h4>
		<div class="data-item">
			<b>Данные:</b> 
			
			<ul class="data-ul">
				
			</ul>

		</div>
		
		<h4>Пример</h4>
		<div class="data-item">
			<b>Запрос:</b>
	<pre>
	GET {baseUrl}/profile/address/remove         HTTP/1.1
	Content-Type: text/json; charset=UTF-8
	Auth: {auth_token}

	{
		"params":{
			"address_id":72
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
		"data": {},
		"req_id": 11897
	}
	</pre>

		</div>
	</div>	
	
</section>