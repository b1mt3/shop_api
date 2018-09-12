<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
	$this->title="FoodExpress developer page";
?>

<h1 class="text-info">Страница разработчика</h1>
<br/>
<br/>
<h2 class="text-info">Статус сервиса</h2>
<p>
	<?php if(Yii::$app->controller->serviceMode=='test') echo 'Сервис работает в <b>ТЕСТОВОМ</b> режиме. В этом режиме сервис доступен только для запросов которые в "<b>header</b>" имеется поле <i><code>mode</code></i> со значением <i><code>test</code></i>'; ?>
	<?php if(Yii::$app->controller->serviceMode=='enabled') echo 'Сервис работает в <b>РАБОЧИМ</b> режиме. '; ?>
</p>
<br/>
<hr/>
<h2 class="text-info">Статус дебага</h2>
<p><?php if(Yii::$app->controller->debugStatus) echo '<span class="h4 text-success">Включен</span>'; else  echo '<span class="h4 text-danger">Отключен</span>'; ?></p>
<hr/>
<p class="h3 text-info">Полезные ссылки:</p>
<ul>
	<li><a target="_blank" href="https://docs.google.com/drawings/d/1F9FkWAqZuZ0E7m5aHkvEOznMILL8baeV84kdz1SmufA/edit">Схема ролей</a></li>
</ul>

<hr/>

