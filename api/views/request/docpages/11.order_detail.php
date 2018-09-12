<section id="section_order_detail" class="section">
	<br/>
	<br/>
	<h3 class="alert-success">Заказ - детально</h3>
	<div class="section-body">
		<p></p>
		<div class="data-item">
			<b>url:</b> <code>{baseUrl}/order</code>

		</div>
		
		<h4>Запрос:</h4>
		<div class="data-item">
			<b>Параметры:</b> 
			<ul class="data-ul">
				<li>
					<b>order_id</b> - <i>int</i> - <u>required</u> - ID заказа
					
				</li>
			</ul>
		</div>

	
		<h4>Ответ:</h4>
		<div class="data-item">
			<b>Данные:</b> 
			
			<ul class="data-ul">
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
					<b>leave_comment_is_available</b> - <i>boolean</i> - статус разрешения оставления отзыва
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
				<li>
					<b>products</b> - <i>array</i> - Список содержания заказов. Каждый элемент массива является объектом блюда:
					<ul>
						<li>
							<b>id</b> - <i>int</i> - ID блюда. 
						</li>
						<li>
							<b>name</b> - <i>string</i> - название блюда. 
						</li>
						<li>
							<b>image</b> - <i>link</i> - изображение блюда. 
						</li>
						<li>
							<b>price</b> - <i>int</i> - стоимость блюда. 
						</li>
						<li>
							<b>price_text</b> - <i>string</i> - тексть стоимости блюда. 
						</li>
						<li>
							<b>count</b> - <i>int</i> - количество в корзине. 
						</li>
						<li>
							<b>additives</b> - <i>array</i> - Список добавок.  Каждый элемент массива является объектом добавки:
							<ul>
								<li>
									<b>id</b> - <i>int</i> - ID добавки. 									
								</li>
								<li>
									<b>name</b> - <i>string</i> - название добавки.									
								</li>
								<li>
									<b>price</b> - <i>int</i> - стоимость добавки.
								</li>
								<li>
									<b>price_text</b> - <i>string</i> - текст стоимости добавки.
								</li>
							</ul>
						</li>
						<li>
							<b>cook_conditions</b> - <i>array</i> - Услови пригатовления блюды:
							<ul>
								<li>
									<b>id</b> - <i>int</i> - ID условии. 									
								</li>
								<li>
									<b>name</b> - <i>string</i> - название условии.									
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
	GET {baseUrl}/order         HTTP/1.1
	Content-Type: text/json; charset=UTF-8
	Auth: {auth_token}

	{"params":{
		"order_id":44
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
			"id": 43,
			"status": "processing",
			"date": 1511354285,
			"total_price": 58271,
			"total_price_text": "58271 КЗТ",
			"leave_comment_is_available": true,
			"restaurant": {
				"id": 4,
				"name": "Дессерт1",
				"image": "http://foodexpress.spg.uz/images/restaurant/app/6ec45165ec870728b9aad74c4cb23ebe.jpg"
			},
			"products": [
				{
					"id": 7,
					"name": "ЧИКЕН ФИЛЕ",
					"image": "http://foodexpress.spg.uz/images/food/app/950-102-chicken-fillet.png",
					"price": 4000,
					"price_text": "4000 КЗТ",
					"count": 11,
					"additives": [],
					"cook_conditions": [
						{
							"id": 3,
							"name": "Условие 3"
						}
					]
				}
			]
		},
		"req_id": 12432
	}
	</pre>

		</div>
	</div>	
	
</section>