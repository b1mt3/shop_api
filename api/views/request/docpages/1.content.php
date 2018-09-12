<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<style>
	.section {
		margin: 15px 0;
		border-top: 1px solid #cccccc;
	}
	h2.alert-info {
		padding: 15px;
	}
	h3.alert-success {
		padding: 10px;
		margin-bottom: 20px;
	}
	.example {
		padding: 20px;
		display: block;
	}
	.data-item {
		margin-bottom: 25px;
		padding-left: 15px;
		line-height: 2em;
	}
	.data-item li {
		margin-bottom: 8px;;
	}
	.section-body {
		padding-left: 25px;
	}
	.section-body .h4 {
		margin-bottom: 15px;
	}
	.data-ul {
		background: #fafafa;
		padding-top: 15px;
		padding-bottom: 15px;
	}
	.data-ul b {
		font-family: Menlo, Monaco, Consolas, "Courier New", monospace;
		color: #c7254e;
	}
	.data-ul i {
		font-family: Menlo, Monaco, Consolas, "Courier New", monospace;
		color: #31708f;
	}
	pre {
		tab-size: 4; 
		line-height: 20px;
	}
	.contents-ul {
		background: #f3f6f9;
		padding-top: 20px;
		padding-bottom: 20px;
	}
	.contents-ul li {
		line-height: 1.6em;
		font-size: 20px;
	}
	h4 {
		background: #e6f9ff;
		padding: 7px;
	}
	.data-ul u {
		color: blue;
		text-decoration: none;
	}
	.data-ul u:before {
		content: '(';
	}
	.data-ul u:after {
		content: ')';
	}
</style>
<h1>Документация</h1>
<br/>
<h3 id="content">Содержание</h3>
<ul class="contents-ul">
	<li class="h4"><a href="#section_concept">Общая концепция</a></li>
	<li class="h4">
		<a href="#section_endpoints">Эндпоинты</a>
		<ul>
			<li>
				<a href="#section_regions">Регионы</a>
			</li>
			<li>
				<a href="#section_filter">Фильтр</a>
			</li>
			<li>
				<a href="#section_restaurants">Список ресторанов</a>
			</li>
			<li>
				<a href="#section_restaurantview">Просмотр ресторана</a>
			</li>
			<li>
				<a href="#section_menu">Меню</a>
			</li>
			<li>
				<a href="#section_reg_auth">Регистрация</a>
				<ul>
					<li>
						<a href="#section_register">Отправка запроса на подтверждения(отправка кода)</a>
					</li>
					<li>
						<a href="#section_auth">Подтверждение номера</a>
					</li>
					<li>
						<a href="#section_resendcode">Переотправка кода</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="#section_auth_pass">Авторизация (паролем)</a>
				<ul>
					<li>
						<a href="#section_temptoken">Получение временного токена</a>
					</li>
					<li>
						<a href="#section_auth_pass_confirm">Полученияе токена авторизации</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="#section_restore_confirm">Восстановление</a>
				<ul>
					<li>
						<a href="#section_restore">Отправка запроса на подтверждения(отправка кода)</a>
					</li>
					<li>
						<a href="#section_restorecheck">Проверка кода</a>
					</li>
					<li>
						<a href="#section_restoreconf">Подтверждение номера и сохранение пароля</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="#section_promotions">Акции</a>
			</li>
			<li>
				<a href="#section_reviews">Отзывы о ресторане</a>
			</li>
			<li>
				<a href="#section_orders">Заказы</a>
				<ul>
					<li>
						<a href="#section_order_config">Получить конфигурацию</a>
					</li>
					<li>
						<a href="#section_order_create_delivery">Запрос на создание заказа(доставка)</a>
					</li>
					<li>
						<a href="#section_order_create_pickup">Запрос на создание заказа(самовывоз)</a>
					</li>
					<li>
						<a href="#section_order_confirm">Подтверждение заказа</a>
					</li>
					<li>
						<a href="#section_order_history">История заказов</a>
					</li>
					<li>
						<a href="#section_order_detail">Заказ - детально</a>
					</li>
					<li>
						<a href="#section_order_add_review">Добавить отзыв к заказу</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="#section_profile">Профиль</a>
				<ul>
					<li>
						<a href="#section_profile_update">Обновить данные профиля</a>
					</li>
					<li>
						<a href="#section_profile_addresses">Адреса</a>
					</li>
					<li>
						<a href="#section_profile_address_add">Добавить адрес</a>
					</li>
					<li>
						<a href="#section_profile_address_update">Изменить адрес</a>
					</li>
					<li>
						<a href="#section_profile_address_remove">Удалить адрес</a>
					</li>
					<li>
						<a href="#section_changepass">Смена пароля</a>
					</li>
					
				</ul>
			</li>
			<li>
				<a href="#section_search_my_addresses">Поиск</a>
				<ul>
					<li>
						<a href="#section_search_my_addresses">Поиск: мои адреса</a>
					</li>
					<li>
						<a href="#section_search_my_addresses_history">Поиск: история адресов</a>
					</li>
					<li>
						<a href="#section_search_filters">Поиск: фильтр в разделе поиска</a>
					</li>
					<li>
						<a href="#section_search_preset_results">Поиск: резултаты поиска по фильтру</a>
					</li>
					<li>
						<a href="#section_search_results">Резултаты поиска по ключевой фразе</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="#section_settings">Настройки</a>
				<ul>
					<li>
						<a href="#section_settings_update">Изменить настроек</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="#section_support">Отображение контактов</a>
			</li>
			<li>
				<a href="#section_payment">Оплата</a>
				<ul>
					<li>
						<a href="#section_payment_state">Проверка оплаты</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="#section_push">Пуш уведомления</a>
			</li>
		</ul>
	</li>
</ul>
<br/>