<section id="section_payment_state" class="section">
	<br/>
	<br/>
	<h3 class="alert-success">Проверка оплаты</h3>
	<div class="section-body">
		<p></p>
		<div class="data-item">
			<b>url:</b> <code>{baseUrl}/order/paystate</code>

		</div>
		<h4>Запрос:</h4>
		<div class="data-item">
			<b>Параметры:</b> 
			<ul class="data-ul">
				<li>
					<b>paytoken</b> - <i>string</i> - <u>required</u> - Токен оплаты полученный при создании заказа
				</li>
			</ul>

		</div>
		<h4>Ответ:</h4>
		<div class="data-item">
			<b>Данные:</b> 
			
			<ul class="data-ul">
				<li>
					<b>state</b> - <i>0 or 1 or 2</i> - статус возможности оплаты для заданного заказа. 0 - невозможно оплатыть, 1 - готов к оплате, 2 - заказ уше оплачен
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
			"paytoken": "hgdf5HGhsdf525sdfbjhsKJdfg"
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
			"state": 0
		},
		"req_id": 31434
	}
	</pre>

		</div>
	</div>
	
</section>