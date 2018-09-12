<?php

namespace frontend\modules\manager\models;

use Yii;
use common\models\Producer as BaseProducer;

class Producer extends BaseProducer
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'producer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name_ru', 'name_uz'], 'required'],
            [['manager', 'other_data'], 'string'],
            [['create_time', 'update_time'], 'safe'],
            [['is_deleted'], 'integer'],
            [['name_ru', 'name_uz', 'address_ru', 'address_uz', 'phone', 'country_origin', 'link', 'image'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name_ru' => 'Name Ru',
            'name_uz' => 'Name Uz',
            'address_ru' => 'Address Ru',
            'address_uz' => 'Address Uz',
            'phone' => 'Phone',
            'manager' => 'Manager',
            'country_origin' => 'Country Origin',
            'other_data' => 'Other Data',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'is_deleted' => 'Is Deleted',
            'link' => 'Link',
            'image' => 'Image',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['producer_id' => 'id']);
    }
}
