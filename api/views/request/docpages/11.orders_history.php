<section id="section_order_history" class="section">
	<br/>
	<br/>
	<h3 class="alert-success">История заказов</h3>
	<div class="section-body">
		<p></p>
		<div class="data-item">
			<b>url:</b> <code>{baseUrl}/orders</code>

		</div>
		
		<h4>Запрос:</h4>
		<div class="data-item">
			<b>Параметры:</b> 
			<ul class="data-ul">
				<li>
					<b>page</b> - <i>int</i> - <u>optional</u> - история заказов
					
				</li>
			</ul>
		</div>

	
		<h4>Ответ:</h4>
		<div class="data-item">
			<b>Данные:</b> 
			
			<ul class="data-ul">
				<li>
					<b>page</b> - <i>int</i> - Номер страницы при пагинации.
				</li>
				<li>
					<b>pageSize</b> - <i>int</i> - Количество объектов в странице.
				</li>
				<li>
					<b>allCount</b> - <i>int</i> - Количество всех найденных объектов.
				</li>
				<li>
					<b>orders</b> - <i>array</i> - Список заказов. Каждый элемент массива является объектом заказа:
					<ul>
						<li>
							<b>id</b> - <i>int</i> - ID заказа. 
						</li>
						<li>
							<b>status</b> - <i>string</i> - статус заказа.  Воможные значения <code>canceled</code>-отменен;  <code>processing</code>-принят;  <code>shipping</code>-отправлен;   <code>delivered</code>-доставлен;
						</li>
						<li>
							<b>date</b> - <i>unix timestamp</i> - дата заказа
						</li>
						<li>
							<b>total_price</b> - <i>int</i> - стоимость заказа
						</li>
						<li>
							<b>total_price_text</b> - <i>string</i> - текст стоимости заказа
						</li>
						<li>
							<b>restaurant</b> - <i>object</i> - объект ресторана:
							<ul>
								<li>
									<b>id</b> - <i>int</i> - ID ресторана.
								</li>
								<li>
									<b>name</b> - <i>string</i> - название ресторана.
								</li>
								<li>
									<b>image</b> - <i>link</i> - ссылка на изображенеи.
								</li>
							</ul>
						</li>
					</ul>
				</li>
			</ul>

		</div>
		
		<h4>Пример</h4>
		<div class="data-item">
			<b>Запрос:</b>
	<pre>
	GET {baseUrl}/orders         HTTP/1.1
	Content-Type: text/json; charset=UTF-8
	Auth: {auth_token}

	{"params":{
		"page":1
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
			"page": 1,
			"pageSize": 10,
			"allCount": 5,
			"orders": [
				{
					"id": 44,
					"status": "processing",
					"date": 1511354437,
					"total_price": 58271,
					"total_price_text": "58271 KZT",
					"restaurant": {
						"id": 4,
						"name": "Дессерт1",
						"image": "http://foodexpress.spg.uz/images/restaurant/app/6ec45165ec870728b9aad74c4cb23ebe.jpg"
					}
				},
				{
					"id": 43,
					"status": "processing",
					"date": 1511354285,
					"total_price": 58271,
					"total_price_text": "58271 KZT",
					"restaurant": {
						"id": 4,
						"name": "Дессерт1",
						"image": "http://foodexpress.spg.uz/images/restaurant/app/6ec45165ec870728b9aad74c4cb23ebe.jpg"
					}
				},
				{
					"id": 42,
					"status": "processing",
					"date": 1511354182,
					"total_price": 58271,
					"total_price_text": "58271 KZT",
					"restaurant": {
						"id": 4,
						"name": "Дессерт1",
						"image": "http://foodexpress.spg.uz/images/restaurant/app/6ec45165ec870728b9aad74c4cb23ebe.jpg"
					}
				},
				{
					"id": 41,
					"status": "processing",
					"date": 1511354163,
					"total_price": 58271,
					"total_price_text": "58271 KZT",
					"restaurant": {
						"id": 4,
						"name": "Дессерт1",
						"image": "http://foodexpress.spg.uz/images/restaurant/app/6ec45165ec870728b9aad74c4cb23ebe.jpg"
					}
				},
				{
					"id": 40,
					"status": "processing",
					"date": 1511354019,
					"total_price": 58271,
					"total_price_text": "58271 KZT",
					"restaurant": {
						"id": 4,
						"name": "Дессерт1",
						"image": "http://foodexpress.spg.uz/images/restaurant/app/6ec45165ec870728b9aad74c4cb23ebe.jpg"
					}
				}
			]
		},
		"req_id": 12407
	}
	</pre>

		</div>
	</div>	
	
</section>