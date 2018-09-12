<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_type".
 *
 * @property int $id
 * @property string $name_ru Наименование типа товара [RU]
 * @property string $name_uz Наименование типа товара [UZ]
 * @property string $url_key ЮРЛ ключь
 * @property int $is_active Активный
 * @property int $is_deleted Удален ли из общего доступа
 * @property string $create_time Время и дата создания записи
 * @property string $update_time Время и дата последнего именения записи
 * @property string $icon Иконка
 * @property int $position Позиция в списке
 *
 * @property CategoryFilter[] $categoryFilters
 * @property Product[] $products
 * @property ProductTypeHasCategory[] $productTypeHasCategories
 * @property Category[] $categories
 */
class ProductType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name_ru', 'name_uz', 'url_key'], 'required'],
            [['id', 'position'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['name_ru', 'name_uz', 'url_key', 'icon'], 'string', 'max' => 255],
            [['is_active', 'is_deleted'], 'string', 'max' => 4],
            [['id'], 'unique'],
            [['url_key'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name_ru' => 'Name Ru',
            'name_uz' => 'Name Uz',
            'url_key' => 'Url Key',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'icon' => 'Icon',
            'position' => 'Position',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryFilters()
    {
        return $this->hasMany(CategoryFilter::className(), ['product_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['product_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductTypeHasCategories()
    {
        return $this->hasMany(ProductTypeHasCategory::className(), ['product_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['id' => 'category_id'])->viaTable('product_type_has_category', ['product_type_id' => 'id']);
    }
}
