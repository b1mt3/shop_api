<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\Restaurant as BaseRestaurant;

/**
 * Description of Restaurant
 *
 * @author Firdaus
 */
class Restaurant extends BaseRestaurant {
	
	public function getRestaurantObject() {
		return [
			'id'=>  $this->id,
			'name'=>  Yii::$app->gFunctions->translate($this, 'name', Yii::$app->controller->language),
			'flags'=>  $this->returnFlags(),
			'working_state'=>  $this->workingState(),
			'work_start_time'=>  $this->workStart(),
			'image'=>  $this->getImagePath(),
			'kitchens'=>  $this->getKitchenText(),
			'pay_types'=>  $this->getPayTypeList(),
			'stars'=>  (int)$this->stars,
			'min_order_sum'=>  [
				'label'=>Yii::t('app', 'Order from'),
				'amount'=>$this->min_order_sum,
				'text'=>Yii::$app->gFunctions->translate($this, 'min_order_sum_text', Yii::$app->controller->language),
			],
			'delivery'=>  [
				'label'=>Yii::t('app', 'Delivery'),
				'amount'=>$this->delivery_price,
				'text'=>Yii::$app->gFunctions->translate($this, 'delivery_price', Yii::$app->controller->language),
			],
			'delivery_time'=>  [
				'label'=>Yii::t('app', 'Delivery time'),
				'amount'=>$this->delivery_time,
				'text'=>Yii::$app->gFunctions->translate($this, 'delivery_time_text', Yii::$app->controller->language),
			],
		];
	}
	
	public static function getFilter(){
		$criteria=[
			'alias'=>'criteria',
			'name'=>\Yii::t('app', 'Criteria'),
			'type'=>'checkbox',
			'items'=>[
				['val'=>'has_promotion', 'name'=>  Yii::t('app', 'With promotion'), 'checked'=>0],
				['val'=>'free_delivery', 'name'=>  Yii::t('app', 'Free delivery'), 'checked'=>0],
				['val'=>'card_online', 'name'=>  Yii::t('app', 'Payment by card online'), 'checked'=>0],
				['val'=>'card_courier', 'name'=>  Yii::t('app', 'Payment by card to the courier'), 'checked'=>0],
				['val'=>'is_working', 'name'=>  Yii::t('app', 'Is working now'), 'checked'=>0],
			]
		];
		
		//categories
		$category=[
			'alias'=>'categories',
			'name'=>Yii::t('app', 'Dishes'),
			'type'=>'checkbox',
			'items'=>[]
		];
		
		$catModels=  Rescat::find()->where(['is_active'=>1])->orderBy('position ASC')->all();
		
		foreach ($catModels as $item) {
			$category['items'][]=[
				'val'=>(string)$item->id,
				'name'=>Yii::$app->gFunctions->translate($item, 'name', Yii::$app->controller->language),
				'checked'=>0,
			];
		}
		
		$kitchen=[
			'alias'=>'kitchens',
			'name'=>\Yii::t('app', 'Kitchens'),
			'type'=>'checkbox',
			'items'=>[]
		];
		$kitchensModels=  Rescat::find()->where(['is_active'=>1])->orderBy('position ASC')->all();
		
		foreach ($kitchensModels as $item) {
			$kitchen['items'][]=[
				'val'=>(string)$item->id,
				'name'=>Yii::$app->gFunctions->translate($item, 'name', Yii::$app->controller->language),
				'checked'=>0,
			];
		}
		
		$sort=[
			'alias'=>'sort',
			'name'=>\Yii::t('app', 'Sort'),
			'type'=>'radio',
			'items'=>[
				['val'=>'default', 'name'=>  Yii::t('app', 'Default'), 'checked'=>1],
				['val'=>'popular', 'name'=>  Yii::t('app', 'Popular'), 'checked'=>0],
				['val'=>'rate', 'name'=>  Yii::t('app', 'Rate'), 'checked'=>0],
			]
		];
		
		$min_price=[
			'alias'=>'min_price',
			'name'=>Yii::t('app', 'Prices'),
			'desc'=>Yii::t('app', 'Order amount up to'),
			'type'=>'slider',
			'val'=>10000,
			'min_val'=>0,
			'max_val'=>100000,
			'step'=>1000,
		];
		
		
		
		$filter=[];
		if(count($sort['items'])>0) {
			$filter[]=$sort;
		}
		$filter[]=$min_price;
		if(count($criteria['items'])>0) {
			$filter[]=$criteria;
		}
		if(count($category['items'])>0) {
			$filter[]=$category;
		}
		if(count($kitchen['items'])>0) {
			$filter[]=$kitchen;
		}
		
		return $filter;
	}
	
	public static function getFilterFromModel($id){
		$filterModel=  \common\models\Filter::findOne($id);
		$crData=  json_decode($filterModel->criteria, true);
		if($crData===NULL) {
			$crData=[];
		}
		
		$criteria=[
			'alias'=>'criteria',
			'name'=>\Yii::t('app', 'Criteria'),
			'type'=>'checkbox',
			'items'=>[
				['val'=>'has_promotion', 'name'=>  Yii::t('app', 'With promotion'), 'checked'=>(in_array('has_promotion', $crData))?1:0],
				['val'=>'free_delivery', 'name'=>  Yii::t('app', 'Free delivery'), 'checked'=>(in_array('free_delivery', $crData))?1:0],
				['val'=>'card_online', 'name'=>  Yii::t('app', 'Payment by card online'), 'checked'=>(in_array('card_online', $crData))?1:0],
				['val'=>'card_courier', 'name'=>  Yii::t('app', 'Payment by card to the courier'), 'checked'=>(in_array('card_courier', $crData))?1:0],
				['val'=>'is_working', 'name'=>  Yii::t('app', 'Is working now'), 'checked'=>(in_array('is_working', $crData))?1:0],
			]
		];
		
		//categories
		$category=[
			'alias'=>'categories',
			'name'=>Yii::t('app', 'Dishes'),
			'type'=>'checkbox',
			'items'=>[]
		];
		$ctData=[];
		foreach ($filterModel->rescats as $item) {
			$ctData[]=$item->id;
		}
		
		$catModels=  Rescat::find()->where(['is_active'=>1])->orderBy('position ASC')->all();
		
		foreach ($catModels as $item) {
			$category['items'][]=[
				'val'=>(string)$item->id,
				'name'=>Yii::$app->gFunctions->translate($item, 'name', Yii::$app->controller->language),
				'checked'=>(in_array($item->id, $ctData))?1:0,
			];
		}
		
		$ktchData=[];
		foreach ($filterModel->kitchens as $item) {
			$ktchData[]=$item->id;
		}
		$kitchen=[
			'alias'=>'kitchens',
			'name'=>\Yii::t('app', 'Kitchens'),
			'type'=>'checkbox',
			'items'=>[]
		];
		$kitchensModels=  Rescat::find()->where(['is_active'=>1])->orderBy('position ASC')->all();
		
		foreach ($kitchensModels as $item) {
			$kitchen['items'][]=[
				'val'=>(string)$item->id,
				'name'=>Yii::$app->gFunctions->translate($item, 'name', Yii::$app->controller->language),
				'checked'=>(in_array($item->id, $ktchData))?1:0,
			];
		}
		
		$sort=[
			'alias'=>'sort',
			'name'=>\Yii::t('app', 'Sort'),
			'type'=>'radio',
			'items'=>[
				['val'=>'default', 'name'=>  Yii::t('app', 'Default'), 'checked'=>($filterModel->sort=='default')?1:0],
				['val'=>'popular', 'name'=>  Yii::t('app', 'Popular'), 'checked'=>($filterModel->sort=='popular')?1:0],
				['val'=>'rate', 'name'=>  Yii::t('app', 'Rate'), 'checked'=>($filterModel->sort=='rate')?1:0],
			]
		];
		
		$min_price=[
			'alias'=>'min_price',
			'name'=>Yii::t('app', 'Prices'),
			'desc'=>Yii::t('app', 'Order amount up to'),
			'type'=>'slider',
			'val'=>(int)$filterModel->min_price,
			'min_val'=>0,
			'max_val'=>100000,
			'step'=>1000,
		];
		
		
		
		$filter=[];
		if(count($sort['items'])>0) {
			$filter[]=$sort;
		}
		$filter[]=$min_price;
		if(count($criteria['items'])>0) {
			$filter[]=$criteria;
		}
		if(count($category['items'])>0) {
			$filter[]=$category;
		}
		if(count($kitchen['items'])>0) {
			$filter[]=$kitchen;
		}
		
		return $filter;
	}
	
	
	public function getMenuItems() {
		$menu=[
			[
				'id'=>0,
				'name'=>Yii::t('app', 'Popular'),
				'submenu'=>[]
			]
		];
		
		$foodCategories=  FoodCategory::find()->innerJoin('food', 'food.food_category_id=food_category.id')->innerJoin('restaurant_has_food', 'restaurant_has_food.food_id=food.id')->where(['food_category.is_active'=>1, 'food_category.is_deleted'=>0, 'food.is_active'=>1, 'food.is_deleted'=>0, 'restaurant_has_food.restaurant_id'=>  $this->id])->distinct()->all();
		$catIds=[0];
		foreach ($foodCategories as $item) {
			if($item->level==0) {
				$catIds[]=$item->id;
			} elseif($item->level==1) {
				if($item->parent_id) {
					if(!in_array($item->parent_id, $catIds)) {
						$catIds[]=$item->parent_id;
					}
				}
				$catIds[]=$item->id;
			}
		}
		
		$foodCategories=FoodCategory::find()->where(['id'=>$catIds, 'is_active'=>1, 'is_deleted'=>0])->orderBy('position ASC')->all();
		foreach ($foodCategories as $item) {
			if($item->level==0) {
				$subMenus=[];
				foreach ($foodCategories as $subitem) {
					if($subitem->level==1 && $subitem->parent_id==$item->id) {
						$subMenus[]=[
							'id'=>$subitem->id,
							'name'=>Yii::$app->gFunctions->translate($subitem, 'name', Yii::$app->controller->language),
						];
					}
				}
				$menu[]=[
					'id'=>$item->id,
					'name'=>Yii::$app->gFunctions->translate($item, 'name', Yii::$app->controller->language),
					'submenu'=>$subMenus
				];
				
			}
		}
		return $menu;
	}
	
	public function getImagePath() {
		if($this->app_image && file_exists(Yii::getAlias('@images').'/restaurant/app/'.$this->app_image)) {
			return \yii\helpers\Url::home(true).'images/restaurant/app/'.$this->app_image;
		} else {
			return \yii\helpers\Url::home(true).'images/appimages/logo.png';
		}
	}
	
	public function getBackImagePath() {
		if($this->app_back_image && file_exists(Yii::getAlias('@images').'/restaurant/appback/'.$this->app_back_image)) {
			return \yii\helpers\Url::home(true).'images/restaurant/appback/'.$this->app_back_image;
		} else {
			return \yii\helpers\Url::home(true).'images/appimages/bg_back.png';
		}
	}
	
	public function getKitchenText() {
		$kitchens=  $this->kitchens;
		$kitchensText='';
		foreach ($kitchens as $item) {
			$kitchensText.=Yii::$app->gFunctions->translate($item, 'name', Yii::$app->controller->language).', ';
		}
		$kitchensText=  trim($kitchensText);
		$kitchensText= trim($kitchensText, ',');
		return $kitchensText;
	}
	
	public function getPayTypeList() {
		$payTypes=  $this->payTypes;
		$pt=[];
		$onlineType=[
			'name'=>Yii::t('app', 'Online pay'),
			'items'=>[]
		];
		$offlineType=[];
		foreach ($payTypes as $item) {
			if($item->method=='online') {
				$onlineType['items'][]=[
					'name'=>Yii::$app->gFunctions->translate($item, 'name', Yii::$app->controller->language),
					'image'=>$item->getImagePath(),
				];
			}
			if($item->method=='offline') {
				$offlineType[]=Yii::$app->gFunctions->translate($item, 'name', Yii::$app->controller->language);
			}
		}
		return [
			'online'=>$onlineType,
			'offline'=>$offlineType,
		];
	}
	
	public function getPayTypesData() {
		$payTypes=  $this->payTypes;
		$pt=[];
		$onlineType=[
			'name'=>Yii::t('app', 'Online pay'),
			'items'=>[]
		];
		$offlineType=[
			'name'=>Yii::t('app', 'Offline pay'),
			'items'=>[]
		];
		foreach ($payTypes as $item) {
			if($item->method=='online') {
				$onlineType['items'][]=[
					'id'=>$item->id,
					'name'=>Yii::$app->gFunctions->translate($item, 'name', Yii::$app->controller->language),
					'image'=>$item->getImagePath(),
				];
			}
			if($item->method=='offline') {
				$offlineType['items'][]=[
					'id'=>$item->id,
					'name'=>Yii::$app->gFunctions->translate($item, 'name', Yii::$app->controller->language),
					'image'=>$item->getImagePath(),
				];
			}
		}
		return [
			'online'=>$onlineType,
			'offline'=>$offlineType,
		];
	}
	
	public function getPaytypesList() {
		$payTypes=  $this->payTypes;
		$payTypesList=[];
		foreach ($payTypes as $item) {
			$payTypesList[]=[
				'id'=>$item->id,
				'name'=>Yii::$app->gFunctions->translate($item, 'name', Yii::$app->controller->language),
				'type'=>$item->method,
				'isCash'=>$item->is_cash,
			];
		}
		return [
			'delivery'=>$payTypesList,
			'pickup'=>$payTypesList,
		];
	}
	
	public function  getPromotionData(){
		$promotion=  $this->activePromotion;
		if($promotion) {
			return $promotion->returnObjectData();
		} else {
			return (object)[];
		}
	}
	
	public function workingHours() {
		$workArr=[];
		if($this->monday_work_start && $this->monday_work_end) {
			$workArr[]=[
				'day'=>Yii::t('app', 'Monday'),
				'time'=>Yii::$app->gFunctions->secondsToTime($this->monday_work_start).'-'.Yii::$app->gFunctions->secondsToTime($this->monday_work_end),
			];
		}
		if($this->tuesday_work_start && $this->tuesday_work_end) {
			$workArr[]=[
				'day'=>Yii::t('app', 'Tuesday'),
				'time'=>Yii::$app->gFunctions->secondsToTime($this->tuesday_work_start).'-'.Yii::$app->gFunctions->secondsToTime($this->tuesday_work_end),
			];
		}
		if($this->wednesday_work_start && $this->wednesday_work_end) {
			$workArr[]=[
				'day'=>Yii::t('app', 'Wednesday'),
				'time'=>Yii::$app->gFunctions->secondsToTime($this->wednesday_work_start).'-'.Yii::$app->gFunctions->secondsToTime($this->wednesday_work_end),
			];
		}
		if($this->thursday_work_start && $this->thursday_work_end) {
			$workArr[]=[
				'day'=>Yii::t('app', 'Thursday'),
				'time'=>Yii::$app->gFunctions->secondsToTime($this->thursday_work_start).'-'.Yii::$app->gFunctions->secondsToTime($this->thursday_work_end),
			];
		}
		if($this->friday_work_start && $this->friday_work_end) {
			$workArr[]=[
				'day'=>Yii::t('app', 'Friday'),
				'time'=>Yii::$app->gFunctions->secondsToTime($this->friday_work_start).'-'.Yii::$app->gFunctions->secondsToTime($this->friday_work_end),
			];
		}
		if($this->saturday_work_start && $this->saturday_work_end) {
			$workArr[]=[
				'day'=>Yii::t('app', 'Saturday'),
				'time'=>Yii::$app->gFunctions->secondsToTime($this->saturday_work_start).'-'.Yii::$app->gFunctions->secondsToTime($this->saturday_work_end),
			];
		}
		if($this->sunday_work_start && $this->sunday_work_end) {
			$workArr[]=[
				'day'=>Yii::t('app', 'Sunday'),
				'time'=>Yii::$app->gFunctions->secondsToTime($this->sunday_work_start).'-'.Yii::$app->gFunctions->secondsToTime($this->sunday_work_end),
			];
		}
		return $workArr;
	}


	public function returnFlags() {
		$flags=[];
		$promotion=  $this->activePromotion;
		if($promotion) {
			$flags=[
				'type'=>'promotion',
				'text'=>Yii::t('app', 'Promotion'),
			];
		} else {
			$currentTime=time();
			$createdTime=  strtotime($this->create_time);
			if(Yii::$app->controller->newFlagPeriod>($currentTime-$createdTime))  {
				$flags=[
					'type'=>'new',
					'text'=>Yii::t('app', 'New'),
				];
			}
		}
		return (object)$flags;
	}
	
	public function workingState($time=0) {
		$currentTimestamp=time();
		$addTime=  Yii::$app->params['readyPeriod'];
//		echo $time; 
//		echo '<br/>';
//		echo $currentTimestamp; 
//		
//		exit;
		if($time) {
			if($currentTimestamp>$time) {
				return 0;
			}
		}
		
		$day=  strtolower(date('l'));
		$stVar=$day.'_work_start';
		$endVar=$day.'_work_end';
		if($time) {
			$currTime=  $time - strtotime("today");
		} else {
			$currTime=  $currentTimestamp - strtotime("today");
		}
		$startTime=$this->$stVar;
		$endTime=$this->$endVar;
		if($startTime<=$currTime && $endTime>$currTime && $startTime!=null && $endTime!==null) {
			return 1;
		} else {
			return 0;
		}
	}
	
	public function todaysWorkTime($inSeconds=true) {
		$day=  strtolower(date('l'));
		$stVar=$day.'_work_start';
		$endVar=$day.'_work_end';
		if($inSeconds) {
			return ['start'=>(int)$this->$stVar, 'end'=>(int)$this->$endVar];
		} else {
			return [
				'day'=>Yii::t('app', date('l')),
				'time'=>Yii::$app->gFunctions->secondsToTime($this->$stVar).'-'.Yii::$app->gFunctions->secondsToTime($this->$endVar)
//				'time'=>Yii::$app->gFunctions->secondsToTime($this->$stVar).'-'.Yii::$app->gFunctions->secondsToTime($this->$endVar)
				];
		}
	}
	
	public function workStart() {
		if($this->workingState()) {
			return 0;
		} else {
			$day=  strtolower(date('l'));
			$dayToAdd=0;
			$workArr=[];
			if($this->monday_work_start===null) {
				$workArr['monday']=['state'=>0];
			} else {
				$workArr['monday']=['state'=>1, 'starts'=>$this->monday_work_start];
			}
			
			if($this->tuesday_work_start===null) {
				$workArr['tuesday']=['state'=>0];
			} else {
				$workArr['tuesday']=['state'=>1, 'starts'=>$this->tuesday_work_start];
			}
			
			if($this->wednesday_work_start===null) {
				$workArr['wednesday']=['state'=>0];
			} else {
				$workArr['wednesday']=['state'=>1, 'starts'=>$this->wednesday_work_start];
			}
			
			if($this->thursday_work_start===null) {
				$workArr['thursday']=['state'=>0];
			} else {
				$workArr['thursday']=['state'=>1, 'starts'=>$this->thursday_work_start];
			}
			
			if($this->friday_work_start===null) {
				$workArr['friday']=['state'=>0];
			} else {
				$workArr['friday']=['state'=>1, 'starts'=>$this->friday_work_start];
			}
			
			if($this->saturday_work_start===null) {
				$workArr['saturday']=['state'=>0];
			} else {
				$workArr['saturday']=['state'=>1, 'starts'=>$this->saturday_work_start];
			}
			
			if($this->sunday_work_start===null) {
				$workArr['sunday']=['state'=>0];
			} else {
				$workArr['sunday']=['state'=>1, 'starts'=>$this->sunday_work_start];
			}
			$foundState=false;
			foreach ($workArr as $key=>$item) {
				if($key==$day) {
					$foundState=true;
				}
				if($foundState) {
					if($item['state']){
						return strtotime('+'.$dayToAdd.' day', strtotime('today'))+$item['starts'];
					}
					$dayToAdd++;
				}
			}
			
			foreach ($workArr as $key=>$item) {
				if($foundState) {
					if($item['state']){
						return strtotime('+'.$dayToAdd.' day', strtotime('today'))+$item['starts'];
					}
					$dayToAdd++;
				}
			}
			
			return -1;
		}
	}


	/**
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveries()
    {
        return $this->hasMany(Delivery::className(), ['restaurant_id' => 'id'])->where(['is_active'=>1])->orderBy('position ASC');
    }
	
    public function getDelivery()
    {
        return $this->hasOne(Delivery::className(), ['restaurant_id' => 'id'])->where(['is_active'=>1])->orderBy('position ASC');
    }

    public function reexecStars() {
		$starsSum=0;
		$count=0;
		foreach ($this->reviews as $item) {
			if($item->is_moderated==1) {
				$starsSum=$starsSum+(int)$item->stars;
				$count++;
			}
		}
		if($count>0) {
			$this->stars=(float)($starsSum/$count);
		} else {
			$this->stars=(float)0;
		}
		$this->save(false);
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(Region::className(), ['id' => 'region_id']);
    }

    

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantHasFoods()
    {
        return $this->hasMany(RestaurantHasFood::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFoods()
    {
        return $this->hasMany(Food::className(), ['id' => 'food_id'])->viaTable('restaurant_has_food', ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantHasFoodCategories()
    {
        return $this->hasMany(RestaurantHasFoodCategory::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFoodCategories()
    {
        return $this->hasMany(FoodCategory::className(), ['id' => 'food_category_id'])->viaTable('restaurant_has_food_category', ['restaurant_id' => 'id'])->where(['is_active'=>1, 'is_deleted'=>0])->orderBy('position ASC');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantHasKitchens()
    {
        return $this->hasMany(RestaurantHasKitchen::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKitchens()
    {
        return $this->hasMany(Kitchen::className(), ['id' => 'kitchen_id'])->viaTable('restaurant_has_kitchen', ['restaurant_id' => 'id'])->where(['is_active'=>1])->orderBy('position ASC');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantHasPayTypes()
    {
        return $this->hasMany(RestaurantHasPayType::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayTypes()
    {
        return $this->hasMany(PayType::className(), ['id' => 'pay_type_id'])->viaTable('restaurant_has_pay_type', ['restaurant_id' => 'id'])->where(['is_active'=>1])->orderBy('position ASC');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantHasPromotions()
    {
        return $this->hasMany(RestaurantHasPromotion::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromotions()
    {
        return $this->hasMany(Promotion::className(), ['restaurant_id' => 'id'])->where(['is_active'=>1])->orderBy('position ASC');
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getPromotions0()
    {
        return $this->hasMany(Promotion::className(), ['id' => 'promotion_id'])->viaTable('restaurant_has_promotion', ['restaurant_id' => 'id']);
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getActivePromotion()
    {
		$currentTime=  date('Y-m-d H:i:s');
        return $this->hasOne(Promotion::className(), ['id' => 'promotion_id'])->viaTable('restaurant_has_promotion', ['restaurant_id' => 'id'])->where(['is_active'=>1])->orderBy('position ASC')->where(['is_deleted'=>0, 'is_active'=>1])->andWhere('start_time<="'.$currentTime.'" AND end_time>"'.$currentTime.'"')->andWhere(['region_id'=>  $this->region_id])->orderBy('create_time DESC');
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantHasRegions()
    {
        return $this->hasMany(RestaurantHasRegion::className(), ['restaurant_id' => 'id']);
    }

    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivePromotion2()
    {
		$currentTime=  date('Y-m-d H:i:s');
        return $this->hasOne(Promotion::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantHasRescats()
    {
        return $this->hasMany(RestaurantHasRescat::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRescats()
    {
        return $this->hasMany(Rescat::className(), ['id' => 'rescat_id'])->viaTable('restaurant_has_rescat', ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantHasUsers()
    {
        return $this->hasMany(RestaurantHasUser::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('restaurant_has_user', ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(Review::className(), ['restaurant_id' => 'id'])->where(['is_moderated'=>1])->orderBy('create_time DESC');
    }
}
