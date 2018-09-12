<section id="section_template" class="section">
	<br/>
	<br/>
	<h3 class="alert-success">Template</h3>
	<div class="section-body">
		<p></p>
		<div class="data-item">
			<b>url:</b> <code>{baseUrl}/</code>

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
				<li>
					<b>category_id</b> - <i>int</i>  - <u>required</u> - ID категории
				</li>
			</ul>

		</div>
		<h4>Ответ:</h4>
		<div class="data-item">
			<b>Данные:</b> 
			
			<ul class="data-ul">
				<li>
					<b>regions</b> - <i>array</i> - Массив объектов. Каждый объект содержит данные отдельных регионов
					<ul>
						<li>
							<b>id</b> - <i>int</i> - ID регионв в системе FoodExppress
						</li>
						<li>
							<b>name</b> - <i>string</i> - Название региона
						</li>
						<li>
							<b>map_data</b> - <i>object</i> - Данные на карте:
							<ul>
								<li>
									<b>pos</b> - <i>array</i> - координаты в виде массива  [широта, долгота]
								</li>
								<li>
									<b>zoom</b> - <i>int</i> - Масштаб на карте
								</li>
							</ul>
						</li>
						<li>
							<b>is_default</b> - <i>1 or 0</i> - Регион по умолчанию
						</li>
					</ul>
					
				</li>
			</ul>

		</div>
		
		<h4>Пример</h4>
		<div class="data-item">
			<b>Запрос:</b>
	<pre>
	GET {baseUrl}/regions/ HTTP/1.1
	Content-Type: text/json; charset=UTF-8

	{
		"params":{

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
			
		},
		"req_id": 28
	}
	</pre>

		</div>
	</div>
	
</section>