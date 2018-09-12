<?php

namespace frontend\modules\paycom\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property int $user_id Пользователь
 * @property string $status Статус
 * @property int $is_paid Оплачен
 * @property string $create_time Дата добавления
 * @property string $update_time Дата обновления
 * @property string $user_comment Комментарие от пользователя
 * @property string $canceled_comment Причина отмены
 * @property string $phone Телефон
 * @property string $user_name Имя пользователя
 * @property int $price Стоимость
 * @property int $discount_price Скидка
 * @property int $delivery_price Стоимость доставки
 * @property string $other_data Другие данные
 * @property string $map_data Данные геолокации
 * @property int $is_new Новый
 * @property string $address_data Данные адреса
 * @property int $test_mode В тестовом режиме
 * @property int $admin_id Администратор
 * @property int $number_confirmed Номер подтвержден
 * @property double $delivery_distance Дальность доставки
 * @property string $delivery_method Метод доставки
 * @property string $ready_time Время заказа
 * @property string $source Источник заказа
 * @property int $manager_id Менеджер
 * @property string $paytoken Токен для оплаты
 * @property int $address_id Адрес
 * @property int $pay_type_id Способ оплаты
 * @property int $card_id Платиковая карта
 *
 * @property History[] $histories
 * @property Address $address
 * @property Card $card
 * @property Manager $manager
 * @property PayType $payType
 * @property User $user
 * @property OrderProduct[] $orderProducts
 * @property PaymentResponse[] $paymentResponses
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'is_paid', 'price', 'discount_price', 'delivery_price', 'is_new', 'test_mode', 'admin_id', 'number_confirmed', 'manager_id', 'address_id', 'pay_type_id', 'card_id'], 'integer'],
            [['status', 'user_comment', 'canceled_comment', 'other_data', 'map_data', 'address_data', 'delivery_method', 'source'], 'string'],
            [['create_time', 'update_time', 'ready_time'], 'safe'],
            [['delivery_distance'], 'number'],
            [['phone', 'user_name', 'paytoken'], 'string', 'max' => 255],
            [['address_id'], 'exist', 'skipOnError' => true, 'targetClass' => Address::className(), 'targetAttribute' => ['address_id' => 'id']],
            [['card_id'], 'exist', 'skipOnError' => true, 'targetClass' => Card::className(), 'targetAttribute' => ['card_id' => 'id']],
            [['manager_id'], 'exist', 'skipOnError' => true, 'targetClass' => Manager::className(), 'targetAttribute' => ['manager_id' => 'id']],
            [['pay_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => PayType::className(), 'targetAttribute' => ['pay_type_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'status' => 'Статус',
            'is_paid' => 'Оплачен',
            'create_time' => 'Дата добавления',
            'update_time' => 'Дата обновления',
            'user_comment' => 'Комментарие от пользователя',
            'canceled_comment' => 'Причина отмены',
            'phone' => 'Телефон',
            'user_name' => 'Имя пользователя',
            'price' => 'Стоимость',
            'discount_price' => 'Скидка',
            'delivery_price' => 'Стоимость доставки',
            'other_data' => 'Другие данные',
            'map_data' => 'Данные геолокации',
            'is_new' => 'Новый',
            'address_data' => 'Данные адреса',
            'test_mode' => 'В тестовом режиме',
            'admin_id' => 'Администратор',
            'number_confirmed' => 'Номер подтвержден',
            'delivery_distance' => 'Дальность доставки',
            'delivery_method' => 'Метод доставки',
            'ready_time' => 'Время заказа',
            'source' => 'Источник заказа',
            'manager_id' => 'Менеджер',
            'paytoken' => 'Токен для оплаты',
            'address_id' => 'Адрес',
            'pay_type_id' => 'Способ оплаты',
            'card_id' => 'Платиковая карта',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistories()
    {
        return $this->hasMany(History::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddress()
    {
        return $this->hasOne(Address::className(), ['id' => 'address_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCard()
    {
        return $this->hasOne(Card::className(), ['id' => 'card_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManager()
    {
        return $this->hasOne(Manager::className(), ['id' => 'manager_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayType()
    {
        return $this->hasOne(PayType::className(), ['id' => 'pay_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderProducts()
    {
        return $this->hasMany(OrderProduct::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentResponses()
    {
        return $this->hasMany(PaymentResponse::className(), ['order_id' => 'id']);
    }
}
