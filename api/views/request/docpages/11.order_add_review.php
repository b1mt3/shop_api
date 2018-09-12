<section id="section_order_add_review" class="section">
	<br/>
	<br/>
	<h3 class="alert-success">Добавить отзыв к заказу</h3>
	<div class="section-body">
		<p></p>
		<div class="data-item">
			<b>url:</b> <code>{baseUrl}/order/add/review</code>

		</div>
		
		<h4>Запрос:</h4>
		<div class="data-item">
			<b>Параметры:</b> 
			<ul class="data-ul">
				<li>
					<b>order_id</b> - <i>int</i> - <u>required</u> - ID заказа
				</li>
				<li>
					<b>comment</b> - <i>object</i> - <u>required</u> - Объект комментарии. Содержит:
					<ul>
						<li>
							<b>text</b> - <i>string</i> - <u>required</u> - Текст комментарий		
						</li>
						<li>
							<b>stars</b> - <i>int</i> - <u>required</u> - рейтинг
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
					<b>review_id</b> - <i>int</i> - ID комментарий.
				</li>
			</ul>

		</div>
		
		<h4>Пример</h4>
		<div class="data-item">
			<b>Запрос:</b>
	<pre>
	GET {baseUrl}/order/add/review         HTTP/1.1
	Content-Type: text/json; charset=UTF-8
	Auth: {auth_token}

	{"params":{
		"order_id":44,
		"comment":{
			"text":"some text",
			"stars":5
		}
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
			"review_id": 34
		},
		"req_id": 12435
	}
	</pre>

		</div>
	</div>	
	
</section>