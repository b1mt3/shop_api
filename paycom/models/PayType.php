<?php

namespace frontend\modules\paycom\models;

use Yii;

/**
 * This is the model class for table "pay_type".
 *
 * @property int $id
 * @property string $name_ru Название (RU)
 * @property string $name_uz Название (UZ)
 * @property string $alias
 * @property int $is_online Онлайн
 *
 * @property Order[] $orders
 */
class PayType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pay_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_ru', 'name_uz'], 'required'],
            [['alias'], 'string'],
            [['is_online'], 'integer'],
            [['name_ru'], 'string', 'max' => 255],
            [['name_uz'], 'string', 'max' => 225],
            [['alias'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name_ru' => 'Название (RU)',
            'name_uz' => 'Название (UZ)',
            'alias' => 'Alias',
            'is_online' => 'Онлайн',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['pay_type_id' => 'id']);
    }
}
