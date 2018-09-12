
<p class="h1 text-success">Список статусов:</p>
<br/>
<table class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>
				Код
			</th>
			<th>
				Заголовок
			</th>
			<th>
				Описание
			</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$statuses=Yii::$app->controller->statuses;
			foreach($statuses as $key=>$item) :
		?>
		<tr>
			<td>
				<?php echo $key; ?>
			</td>
			<td>
				<b><i>RU</i></b>: <?php echo $item['ru']['title'] ?> <br/>
				<b><i>EN</i></b>: <?php echo $item['en']['title'] ?> 
			</td>
			<td>
				<b><i>RU</i></b>: <?php echo $item['ru']['message'] ?> <br/>
				<b><i>EN</i></b>: <?php echo $item['en']['message'] ?> 
			</td>
		</tr>
		<?php endforeach; ?>
		
	</tbody>
</table>
<hr/>