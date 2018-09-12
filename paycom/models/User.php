<?php

namespace frontend\modules\paycom\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $phone Контактный номер
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email Электронная почта
 * @property string $create_time Дата добавления
 * @property string $update_time Дата обновления
 * @property int $role Роль
 * @property int $is_active Активный
 * @property string $fio ФИО
 * @property int $test_mode В тестовом режиме
 * @property string $pass_md5
 * @property int $email_confirmed Email подтвержден
 * @property string $last_visit Последний визит
 * @property string $added_source Источник регистрации
 * @property string $user_name Имя
 * @property string $user_surname Фамилия
 * @property string $gender Пол
 * @property string $birth_date Дата рождения
 *
 * @property Address[] $addresses
 * @property App[] $apps
 * @property Auth[] $auths
 * @property Card[] $cards
 * @property History[] $histories
 * @property Manager[] $managers
 * @property Order[] $orders
 * @property Userset[] $usersets
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phone', 'auth_key', 'password_hash'], 'required'],
            [['create_time', 'update_time', 'last_visit', 'birth_date'], 'safe'],
            [['role', 'is_active', 'test_mode', 'email_confirmed'], 'integer'],
            [['added_source', 'gender'], 'string'],
            [['phone', 'password_hash', 'password_reset_token', 'email', 'pass_md5', 'user_name', 'user_surname'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['fio'], 'string', 'max' => 45],
            [['phone'], 'unique'],
            [['password_reset_token'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone' => 'Контактный номер',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Электронная почта',
            'create_time' => 'Дата добавления',
            'update_time' => 'Дата обновления',
            'role' => 'Роль',
            'is_active' => 'Активный',
            'fio' => 'ФИО',
            'test_mode' => 'В тестовом режиме',
            'pass_md5' => 'Pass Md5',
            'email_confirmed' => 'Email подтвержден',
            'last_visit' => 'Последний визит',
            'added_source' => 'Источник регистрации',
            'user_name' => 'Имя',
            'user_surname' => 'Фамилия',
            'gender' => 'Пол',
            'birth_date' => 'Дата рождения',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddresses()
    {
        return $this->hasMany(Address::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApps()
    {
        return $this->hasMany(App::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuths()
    {
        return $this->hasMany(Auth::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCards()
    {
        return $this->hasMany(Card::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistories()
    {
        return $this->hasMany(History::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManagers()
    {
        return $this->hasMany(Manager::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsersets()
    {
        return $this->hasMany(Userset::className(), ['user_id' => 'id']);
    }
}
