<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<section id="section_concept" class="section">
	<h2 class="alert-info">Общая концепция</h2>
	
	<!-- #######  YAY, I AM THE SOURCE EDITOR! #########-->
	<h3>Разделы страницы разработчика</h3>
	<p>Имеются несколько разделов в страницы разработчика:</p>
	<ul>
	<li><a href="/api/request/index">Запросы</a></li>
	<li><a href="/api/request/statuses">Статусы</a></li>
	</ul>
	<p>Каждый сапрос АПИ сервера записываются при влюченном статусе дебага. Эти заприсы можно отслеживать в разделе&nbsp;<strong>Запросы </strong>в виде списка. Самые последние запросы в начале списка.&nbsp;</p>
	<p>При включенном дебаггере, для каждого ответа гапроса добавляется поле&nbsp;<em><strong>req_id. </strong></em>По значением этого поле можно найти запрос в списке запросов вводив их в поле <strong><em>ID </em></strong>колонки таблицы.</p>
	<p>В разделе <strong>Статусы</strong> приведен список статусов, возврашаемые с АПИ сервера.</p>
	<h3>Протокол</h3>
	<p>Сервис работает по протоколу&nbsp;<strong>REST.&nbsp;</strong>&nbsp;</p>
	<p>Базовый адрес АПИ (<b>baseUrl</b>): <a href="http://foodexpress.spg.uz/api/">http://foodexpress.spg.uz/api/</a>&nbsp;</p>
	<h4>Header запроса</h4>
	<p>Поля <strong>header:&nbsp;</strong></p>
	<table style="width: 100%;" class="table table-bordered table-striped" >
	<tbody>
	<tr>
	<th style="width: 152px;">Название полей</th>
	<th style="width: 252px;">Значение</th>
	<th style="width: 447px;">Примечание</th>
	</tr>
	<tr>
	<td style="width: 152px;">Content-Type</td>
	<td style="width: 252px;">application/json</td>
	<td style="width: 447px;">&nbsp;</td>
	</tr>
	<tr>
	<td style="width: 152px;">lang</td>
	<td style="width: 252px;"><strong>ru</strong> или <strong>en&nbsp;</strong></td>
	<td style="width: 447px;">Выбранный язык приложения. Если поле отсутствует или значение другое, тогда устанавливается язык <strong>ru&nbsp;</strong></td>
	</tr>
	<tr>
	<td style="width: 152px;">mode</td>
	<td style="width: 252px;"><strong>test</strong></td>
	<td style="width: 447px;">Режим работы приложения. Если отсутствует, считается что работает в релизе</td>
	</tr>
	<tr>
	<td style="width: 152px;">v</td>
	<td style="width: 252px;"><strong></strong></td>
	<td style="width: 447px;">версия приложения.</td>
	</tr>
	<tr>
	<td style="width: 152px;">Auth</td>
	<td style="width: 252px;">&nbsp;</td>
	<td style="width: 447px;">Токен авторизации</td>
	</tr>
	</tbody>
	</table>
	
	<h3>Данные запроса и ответа</h3>
	<p>В каждом запросе можно отправить JSON объект с данными <strong>params:</strong></p>
	<code class="example">{"params":{"order_id":2545}}</code>
	<br/>
	<p>А в ответ получаем тоже JSON объект:</p>
	<code class="example">
	{
		"status": 200,
		"error": "",
		"message": "",
		"data": {
			"order_id": 500
		},
		"req_id": 11
	}
	</code>
	<br/>
	<p>Описание полей:</p>
	<ul style="list-style-type: square;">
	<li>&nbsp;<em><strong>status</strong> </em>- статус ответа, значение 200 - успешный запрос, а другие значения означает ошибку. Всех статусов можно посмотреть в разделе <a href="/api/request/statuses">Статусы</a>.</li>
	<li><em><strong>error</strong> </em>- ошибка. Краткое описание. Пустое значение означает что запрос возврашен баз ошибки.</li>
	<li><em><strong>message</strong> </em>- Подробное описание ошибки.&nbsp;Пустое значение означает что запрос возврашен баз ошибки.</li>
	<li><em><strong>data</strong> </em>- объект содержаший данных ответа. При ошибки объект будет пустым</li>
	<li><em><strong>req_id</strong> </em>-&nbsp; ID записи запроса в дебаггере сервера. Пи отключении на сервере, поле будет отсутствовать</li>
	</ul>
</section>