<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>

<?php
	require '1.content.php';
	require '2.concept.php';
	require '3.endpoints.php';
	require '4.regions.php'; //Регионы
	require '5.filter.php'; //Фильтр
	require '6.restaurants.php'; //Список ресторанов
	require '6.restaurantview.php'; //Просмотр ресторана
	require '6.menu.php'; //Меню
	require '7.reg_auth.php'; //Регистрация и авторизация (отправкой СМС)
	require '7.resendcode.php'; //Переотправка смс
	require '8.auth_pass.php'; //Авторизация (паролем)
	require '8.1.restore.php'; //Восстановление
	require '9.promotions.php'; //Акции
	require '10.reviews.php'; //Отзывы о ресторане
	require '11.orders.php'; //заказы
	require '11.order_config.php'; //Конфигурации
	require '11.order_create_delivery.php'; //Запрос на создание заказа(доставка)
	require '11.order_create_pickup.php'; //Запрос на создание заказа(самовывоз)
	require '11.order_confirm.php'; //Запрос на создание заказа(самовывоз)
	require '11.orders_history.php'; //История заказов
	require '11.order_detail.php'; //заказ - детально
	require '11.order_add_review.php'; //Добавить отзыв к заказу
	require '12.profile.php'; //Профиль
	require '12.profile_update.php'; //Обновить данные профиля
	require '12.profile_addresses.php'; //Адреса
	require '12.profile_address_add.php'; //Добавить адрес
	require '12.profile_address_update.php'; //Обновить адрес
	require '12.profile_address_remove.php'; //Удалить адрес
	require '12.profile_changepass.php'; //Смена пароля
	require '13.search_my_addresses.php'; //Мои адреса в разделе поиска
	require '13.search_my_addresses_history.php'; //Мои адреса в разделе поиска (истории)
	require '13.search_filters.php'; //Фильтр в разделе поиска
	require '13.search_preset_results.php'; //Резултаты поиска по фильтру
	require '13.search_results.php'; //Резултаты поиска по ключевой фразе
	require '14.settings.php'; //Настройки
	require '14.settings_update.php'; //Изменить настроек
	require '15.support.php'; //Отображение контактов
	require '16.payment.php'; //Оплата
	require '16.paystate.php'; //Оплата
	require '17.push.php'; //Push

?>