<?php

namespace frontend\modules\paycom\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property int $id
 * @property string $name Название
 * @property int $user_id
 * @property string $create_time Дата добавления
 * @property string $update_time Дата обновления
 * @property string $text Текст адреса
 * @property int $is_default Адрес по умолчанию
 * @property int $district_id Район
 * @property string $street Улица
 * @property string $building Дом
 * @property string $apartment Квартира
 * @property string $zipcode Почтовый индекс
 * @property string $city Город
 * @property string $user_name Имя
 * @property string $user_surname Фамилия
 * @property string $user_phone Контактный телефон
 *
 * @property District $district
 * @property User $user
 * @property Order[] $orders
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'is_default', 'district_id'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['name', 'text', 'street', 'building', 'apartment', 'zipcode', 'city', 'user_name', 'user_surname', 'user_phone'], 'string', 'max' => 255],
            [['district_id'], 'exist', 'skipOnError' => true, 'targetClass' => District::className(), 'targetAttribute' => ['district_id' => 'id']],
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
            'name' => 'Название',
            'user_id' => 'User ID',
            'create_time' => 'Дата добавления',
            'update_time' => 'Дата обновления',
            'text' => 'Текст адреса',
            'is_default' => 'Адрес по умолчанию',
            'district_id' => 'Район',
            'street' => 'Улица',
            'building' => 'Дом',
            'apartment' => 'Квартира',
            'zipcode' => 'Почтовый индекс',
            'city' => 'Город',
            'user_name' => 'Имя',
            'user_surname' => 'Фамилия',
            'user_phone' => 'Контактный телефон',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistrict()
    {
        return $this->hasOne(District::className(), ['id' => 'district_id']);
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
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['address_id' => 'id']);
    }
}
