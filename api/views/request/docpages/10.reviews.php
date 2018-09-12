<section id="section_reviews" class="section">
	<br/>
	<br/>
	<h3 class="alert-success">Отзывы о ресторане</h3>
	<div class="section-body">
		<p></p>
		<div class="data-item">
			<b>url:</b> <code>{baseUrl}/reviews</code>

		</div>
		<h4>Запрос:</h4>
		<div class="data-item">
			<b>Параметры:</b> 
			<ul class="data-ul">
				<li>
					<b>page</b> - <i>int</i> - <u>optional</u> - Страница
				</li>
				<li>
					<b>restaurant_id</b> - <i>int</i>  - <u>required</u> - ID ресторана
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
					<b>list</b> - <i>array</i> - Список отывов. Каждый элемент является объектом отзыва:
					<ul>
						<li>
							<b>id</b> - <i>int</i> - ID акции
						</li>
						<li>
							<b>user_name</b> - <i>string</i> - Имя пользователя
						</li>
						<li>
							<b>text</b> - <i>string</i> - Комментарие
						</li>
						<li>
							<b>stars</b> - <i>int</i> - рейтинг
						</li>
						<li>
							<b>date</b> - <i>unixtime</i> - Дата отзыва
						</li>
						<li>
							<b>date_text</b> - <i>string</i> - Дата отзыва
						</li>
					</ul>
					
				</li>
			</ul>

		</div>
		
		<h4>Пример</h4>
		<div class="data-item">
			<b>Запрос:</b>
	<pre>
	POST {baseUrl}/reviews/    HTTP/1.1
	Content-Type: text/json; charset=UTF-8

	{
		"params":{
			"page": 1,
			"restaurant_id":1
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
			"page": 1,
			"pageSize": 10,
			"allCount": 2,
			"list": [
				{
					"id": 2,
					"user_name": "Some Name",
					"text": "Sea sapientem ocurreret laboramus te, pri in adhuc bonorum verterem. Dicit noster mei et, ut paulo habemus adipisci nam, eos summo dicam patrioque in. Ex vel populo deterruisset, ex facer debet eum. Ut nam brute justo inimicus, ea modus laboramus qui. Quodsi consulatu ne vim, quis salutandi te vix. No pro nisl regione, duis adhuc ignota est ex. An virtute incorrupte sed, lorem solet ne per.",
					"stars": 2,
					"date": 1507811495,
					"date_text": "12 октября 2017 г."
				},
				{
					"id": 3,
					"user_name": "Some Name",
					"text": "Sea sapientem ocurreret laboramus te, pri in adhuc bonorum verterem. Dicit noster mei et, ut paulo habemus adipisci nam, eos summo dicam patrioque in. Ex vel populo deterruisset, ex facer debet eum. Ut nam brute justo inimicus, ea modus laboramus qui. Quodsi consulatu ne vim, quis salutandi te vix. No pro nisl regione, duis adhuc ignota est ex. An virtute incorrupte sed, lorem solet ne per.",
					"stars": 2,
					"date": 1507811495,
					"date_text": "12 октября 2017 г."
				}
			]
		},
		"req_id": 238
	}
	</pre>

		</div>
	</div>
	
</section>