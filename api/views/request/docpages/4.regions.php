<section id="section_regions" class="section">
	<br/>
	<br/>
	<h3 class="alert-success">Регионы</h3>
	<div class="section-body">
		<p>Возврашает доступные регионы</p>
		<div class="data-item">
			<b>url:</b> <code>{baseUrl}/regions/</code>

		</div>
		<h4>Запрос:</h4>
		<div class="data-item">
			<b>Параметры:</b> <code>(без параметров)</code>

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
							<b>gloc</b> - <i>object</i> - Объект геолокации. Содержит:
							<ul>
								<li>
									<b>coord</b> - <i>object</i> - Объект координат:
									<ul>
										<li>
											<b>lon</b> - <i>float</i> - долгота на карте
										</li>
										<li>
											<b>lat</b> - <i>float</i> - широта на карте
										</li>
									</ul>
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
	POST {baseUrl}/regions/      HTTP/1.1
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
			"regions": [
				{
					"id": 1,
					"name": "Almaty",
					"map_data": {
						"coord": {
							"lon": 76.888121,
							"lat": 43.243933
						},
						"zoom": 11
					},
					"is_default": 1
				},
				{
					"id": 2,
					"name": "Astana",
					"map_data": {
						"coord": {
							"lon": 71.430564,
							"lat": 51.128422
						},
						"zoom": 11
					},
					"is_default": 0
				}
			]
		},
		"req_id": 32
	}
	</pre>

		</div>
	</div>
	
</section>