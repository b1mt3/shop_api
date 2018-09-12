<section id="section_payment" class="section">
	<br/>
	<br/>
	<h3 class="alert-success">Оплата</h3>
	<div class="section-body">
		<p>Для оплаты необходимо сформировать <b><i>payUrl</i></b> и открыть этот <b><i>payUrl</i></b> в <i>webview</i>. В <i>webview</i> пользователь перенаправляется на сайт системы оплаты. Заполняется необходимые поля и оплачивается. В странице успешной (/неуспешной) оплаты имеется ссылка возврата. При клике на ссылок:</p>
		<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>- (1) при успешной оплате </b> - пользователь перенаправляется на <b><i>successUrl</i></b>.</p>
		<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>- (2) при неуспешной оплате </b> - пользователь перенаправляется на <b><i>failUrl</i></b>.</p>
		<p>А в случае закрытия <i>webview</i>  необходимо проверить возможность оплаты для этого заказа  в разделе <a href="#section_payment_state">Проверка оплаты</a>. Если там возвращается <code>1</code> в ответе, тогда необходимо повторить оплату</p>
		<h3>Формирование ссылок <b><i>payUrl</i></b>, <b><i>successUrl</i></b> и <b><i>failUrl</i></b>:</h3>
		<p><code><b><i>payUrl</i></b> = {siteUrl}/{lang}/pay/{paytoken}</code> - пример: <i>http://foodexpress.spg.uz/ru/pay/sdjfhksdf65s4dfsdfJHsfd5sdf</i> </p>
		<p><code><b><i>successUrl</i></b> = {siteUrl}/{lang}/pay/success</code></p>
		<p><code><b><i>failUrl</i></b> = {siteUrl}/{lang}/pay/fail</code></p>
		<p>где:</p>
		<p><code>{siteUrl}</code> - ссылка для рабочего сайта</p>
		<p><code>{lang}</code> - язык, возможные значения - <code>en</code> и <code>ru</code></p>
		<p><code>{paytoken}</code> - токен полученный при создании заказа</p>
		
	</div>
	
</section>