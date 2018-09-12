<?php

namespace frontend\modules\cashbox\models;

use Yii;
use frontend\models\User as BaseUser;

/**
 * Description of Restaurant
 *
 * @author Firdaus
 */
class User extends BaseUser {

	public function getProfileData() {
		return [
			'id'=>  $this->id,
			'phone'=>  (string)$this->phone,
			'email'=>  (string)$this->email,
			'name'=>  $this->fio,
		];
	}


	public function deleteAuths() {
		foreach ($this->auths as $item) {
			$item->delete();
		}
	}

	public function deleteOtherApps() {
		foreach ($this->apps as $item) {
			$item->delete();
		}
	}

	/**
     * @return \yii\db\ActiveQuery
     */
    public function getUserset()
    {
        return $this->hasOne(Userset::className(), ['user_id' => 'id']);
    }

	public function addUserSet() {
		$newUserSet=new Userset;
		$newUserSet->sms_notification=1;
		$newUserSet->push_notification=1;
		$newUserSet->email_newsletters=0;
		$newUserSet->default_language="ru";
		$newUserSet->user_id=  $this->id;
		$newUserSet->save();
		$newUserSet->refresh();
		return $newUserSet;
	}
}
