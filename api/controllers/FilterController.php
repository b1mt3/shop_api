<?php

namespace frontend\modules\api\controllers;

use Yii;
use frontend\modules\api\models\Brand;
use frontend\modules\api\models\Size;
use frontend\modules\api\models\Color;
use frontend\modules\api\models\User;
use frontend\modules\api\models\Category;
use frontend\modules\api\models\Age;
use frontend\modules\api\models\Product;

class FilterController extends MainController
{
	public function actionIndex() {
        $params = $this->params;
		if (isset($params['category_id'])) {
			$categoryId=(int)$params['category_id'];
		} else {
			$categoryId=0;
		}

		$pageSize=2;


		if(isset($params['filters']) && is_array($params['filters']) && count($params['filters'])>0) {
			$filters=$params['filters'];
		} else {
			$filters= $this->filterModel();
		}
		$sortType='';
		if(isset($params['sort']) && is_array($params['sort']) && count($params['sort'])>0) {
			$sort=$params['sort'];
			$sortSelected=false;
			foreach ($params['sort'] as $item) {
				if($item['checked']==1) {
					$sortType=$item['type'];
					$sortSelected=true;
					break;
				}
			}
			if($sortSelected==false) {
				$sortType='new';
			}
		} else {
			$sort= [
				['type'=>'new','name'=> \Yii::t('app', 'Newest'), 'checked'=>1],
				['type'=>'expensive','name'=> \Yii::t('app', 'Most expensives'), 'checked'=>0],
				['type'=>'cheap','name'=> \Yii::t('app', 'Cheapest'), 'checked'=>0],
				['type'=>'popular','name'=> \Yii::t('app', 'Popular'), 'checked'=>0],
			];
			$sortType='new';
		}

		$returnData=['page_size'=>$pageSize, 'sort'=>$sort, 'filters'=>$filters];


		$priceCondition='';
		$saleText='';
		$sizesList=[];
		$colorsList=[];

		$condition=['category.is_active'=>1, 'category.is_deleted'=>0, 'category.parent_id'=>$categoryId];
		$query= \frontend\modules\api\models\Category::find()->with(['products', 'products.productItems', 'products.productItems.size', 'products.productItems.color', 'products.productItems.storageHasProductItems', 'products.productItems.storageHasProductItems.storage', 'products.brand', 'products.productHasAges', 'products.productHasAges.age']);

		$productTableJoined=false;
		foreach ($filters as $filterItem) {
			//check sale
			if($filterItem['alias']=='sale') {
				if(isset($filterItem['items'][0])) {
					if($filterItem['items'][0]['checked']==1) {
						$saleText='product.sale_percentage>0';
					}
				}
			}
			//check brands
			if($filterItem['alias']=='brand') {
				$brandsList=[];
				foreach ($filterItem['items'] as $brandItem) {
					if($brandItem['checked']==1) {
						$brandsList[]=$brandItem['id'];
					}
				}
				if($brandsList) {
					$condition['product.brand_id']=$brandsList;
				}
			}

			//check producer
			if($filterItem['alias']=='producer') {
				$producersList=[];
				foreach ($filterItem['items'] as $producerItem) {
					if($producerItem['checked']==1) {
						$producersList[]=$producerItem['id'];
					}
				}
				if($producersList) {
					$condition['product.producer_id']=$producersList;
				}
			}

			//check size
			if($filterItem['alias']=='size') {
				$sizesList=[];
				foreach ($filterItem['items'] as $sizeItem) {
					if($sizeItem['checked']==1) {
						$sizesList[]=$sizeItem['id'];
					}
				}
				if($sizesList) {
					if($productTableJoined==false) {
						$query->innerJoin('product', 'product.category_id=category.id')->innerJoin('product_item', 'product_item.product_id=product.id');
						$productTableJoined=true;

					}
					$condition['product_item.size_id']=$sizesList;
				}
			}

			//check colors
			if($filterItem['alias']=='color') {
				$colorsList=[];
				foreach ($filterItem['items'] as $colorItem) {
					if($colorItem['checked']==1) {
						$colorsList[]=$colorItem['id'];
					}
				}
				if($colorsList) {
					if($productTableJoined==false) {
						$query->innerJoin('product', 'product.category_id=category.id')->innerJoin('product_item', 'product_item.product_id=product.id');
						$productTableJoined=true;

					}
					$condition['product_item.color_id']=$colorsList;
				}
			}

			//check age
			if($filterItem['alias']=='age') {
				$agesList=[];
				foreach ($filterItem['items'] as $ageItem) {
					if($ageItem['checked']==1) {
						$agesList[]=$ageItem['id'];
					}
				}
				if($agesList) {
					$query->innerJoin('product_has_age', 'product_has_age.product_id=product.id');
					$condition['product_has_age.age_id']=$agesList;
				}
			}

			//check price
			if($filterItem['alias']=='price') {
				$minPrice=(int)$filterItem['price_from'];
				$maxPrice=(int)$filterItem['price_to'];
				$priceCondition='product_item.price>='.$minPrice.' AND '.'product_item.price<='.$maxPrice;
			}
		}
		$query->where($condition);
		if($priceCondition) {
			if($productTableJoined==false) {
				$query->innerJoin('product', 'product.category_id=category.id')->innerJoin('product_item', 'product_item.product_id=product.id');
				$productTableJoined=true;

			}
			$query->andWhere($priceCondition);
		}
		if($saleText) {
			if($productTableJoined==false) {
				$query->innerJoin('product', 'product.category_id=category.id')->innerJoin('product_item', 'product_item.product_id=product.id');
				$productTableJoined=true;

			}
			$query->andWhere($saleText);
		}

		$categpryModels=$query->orderBy('category.position ASC')->distinct(true)->all();

		$dataList=[];

		foreach ($categpryModels as $categoryItem) {
			$productsList=[];
			$productModels=$categoryItem->products;
			if($productModels) {

				foreach ($productModels as $childProductItem) {
					$addState=false;
					if($sizesList && $colorsList) {
						foreach ($childProductItem->productItems as $childProductItemColorSize) {
							if(in_array($childProductItemColorSize->color_id, $colorsList) && in_array($childProductItemColorSize->size_id, $sizesList)) {
								$addState=true;
								break;
							}
						}
					} elseif ($sizesList) {
						foreach ($childProductItem->productItems as $childProductItemColorSize) {
							if(in_array($childProductItemColorSize->size_id, $sizesList)) {
								$addState=true;
								break;
							}
						}
					} elseif ($colorsList) {
						foreach ($childProductItem->productItems as $childProductItemColorSize) {
							if(in_array($childProductItemColorSize->color_id, $colorsList) ) {
								$addState=true;
								break;
							}
						}
					} else {
						$addState=true;
					}
					if($addState) {
						$productsList[]=[
							'toSend'=>[
								'id'=>$childProductItem->id,
								'name'=>\Yii::$app->gFunctions->translate($childProductItem, 'name'),
								'priceText'=>$childProductItem->getPrice(),
								'image'=>$childProductItem->ImagePath(),
								'brand'=>($childProductItem->brand)?\Yii::$app->gFunctions->translate($childProductItem->brand, 'name'):'',
							],
							'create_time'=>strtotime($childProductItem->create_time),
							'price'=>$childProductItem->price,
							'min_price'=>$childProductItem->min_price,
							'max_price'=>$childProductItem->max_price,
							'order_count'=>$childProductItem->order_count,
						];
					}
				}
				$sortOrderArr=[];
				$allPrCount=count($productsList);
				foreach ($productsList as $key=>$prodListItem) {
					if($sortType=='new') {
						$sortOrderArr[$key]=$prodListItem['create_time'];
					}
					if($sortType=='expensive') {
						$sortOrderArr[$key]=$prodListItem['max_price'];
					}
					if($sortType=='cheap') {
						$sortOrderArr[$key]=$prodListItem['min_price'];
					}
					if($sortType=='popular') {
						$sortOrderArr[$key]=$prodListItem['order_count'];
					}
				}
				if(in_array($sortType, ['new', 'popular', 'expensive'])) {
					$sortText=SORT_DESC;
				} else {
					$sortText=SORT_ASC;
				}
				array_multisort($sortOrderArr, $sortText, $productsList);

				$productListToAdd=[];
				foreach ($productsList as $key=>$sordedItem) {
					$productListToAdd[]=$sordedItem['toSend'];
					if($key+1==$pageSize) {
						break;
					}
				}

				$dataList[]=[
					'id'=>$categoryItem->id,
					'name'=> \Yii::$app->gFunctions->translate($categoryItem, 'name'),
					'itemsCount'=>$allPrCount,
					'items'=>$productListToAdd,
				];
			}

		}
		$returnData['categoryList']=$dataList;


		return $this->returnResponse(200, $returnData);

	}

	public function actionPaginate() {
		 $params = $this->params;
		if (isset($params['category_id'])) {
			$categoryId=(int)$params['category_id'];
		} else {
			$categoryId=0;
		}

		if (isset($params['page'])&&((int)$params['page']>0)) {
			$page=(int)$params['page'];
		} else {
			$page=1;
		}

		$pageSize=2;


		if(isset($params['filters']) && is_array($params['filters']) && count($params['filters'])>0) {
			$filters=$params['filters'];
		} else {
			$filters= $this->filterModel();
		}
		$sortType='';
		if(isset($params['sort']) && is_array($params['sort']) && count($params['sort'])>0) {
			$sort=$params['sort'];
			$sortSelected=false;
			foreach ($params['sort'] as $item) {
				if($item['checked']==1) {
					$sortType=$item['type'];
					$sortSelected=true;
					break;
				}
			}
			if($sortSelected==false) {
				$sortType='new';
			}
		} else {
			$sort= [
				['type'=>'new','name'=> \Yii::t('app', 'Newest'), 'checked'=>1],
				['type'=>'expensive','name'=> \Yii::t('app', 'Most expensives'), 'checked'=>0],
				['type'=>'cheap','name'=> \Yii::t('app', 'Cheapest'), 'checked'=>0],
				['type'=>'popular','name'=> \Yii::t('app', 'Popular'), 'checked'=>0],
			];
			$sortType='new';
		}

		$returnData=['page_size'=>$pageSize, 'sort'=>$sort, 'filters'=>$filters];

		$condition=['product.is_active'=>1, 'product.is_deleted'=>0, 'product.category_id'=>$categoryId];
		$query= \frontend\modules\api\models\Product::find()->with(['productItems', 'productItems.size', 'productItems.color', 'brand', 'productHasAges']);


		foreach ($filters as $filterItem) {
			//check sale
			if($filterItem['alias']=='sale') {
				if(isset($filterItem['items'][0])) {
					if($filterItem['items'][0]['checked']==1) {
						$saleText='product.sale_percentage>0';
					}
				}
			}
			//check brands
			if($filterItem['alias']=='brand') {
				$brandsList=[];
				foreach ($filterItem['items'] as $brandItem) {
					if($brandItem['checked']==1) {
						$brandsList[]=$brandItem['id'];
					}
				}
				if($brandsList) {
					$condition['product.brand_id']=$brandsList;
				}
			}

			//check producer
			if($filterItem['alias']=='producer') {
				$producersList=[];
				foreach ($filterItem['items'] as $producerItem) {
					if($producerItem['checked']==1) {
						$producersList[]=$producerItem['id'];
					}
				}
				if($producersList) {
					$condition['product.producer_id']=$producersList;
				}
			}


			//check size
			if($filterItem['alias']=='size') {
				$sizesList=[];
				foreach ($filterItem['items'] as $sizeItem) {
					if($sizeItem['checked']==1) {
						$sizesList[]=$sizeItem['id'];
					}
				}
				if($sizesList) {
					if($productTableJoined==false) {
						$query->innerJoin('product_item', 'product_item.product_id=product.id');
						$productTableJoined=true;

					}
					$condition['product_item.size_id']=$sizesList;
				}
			}

			//check colors
			if($filterItem['alias']=='color') {
				$colorsList=[];
				foreach ($filterItem['items'] as $colorItem) {
					if($colorItem['checked']==1) {
						$colorsList[]=$colorItem['id'];
					}
				}
				if($colorsList) {
					if($productTableJoined==false) {
						$query->innerJoin('product_item', 'product_item.product_id=product.id');
						$productTableJoined=true;

					}
					$condition['product_item.color_id']=$colorsList;
				}
			}

			//check age
			if($filterItem['alias']=='age') {
				$agesList=[];
				foreach ($filterItem['items'] as $ageItem) {
					if($ageItem['checked']==1) {
						$agesList[]=$ageItem['id'];
					}
				}
				if($agesList) {
					$query->innerJoin('product_has_age', 'product_has_age.product_id=product.id');
					$condition['product_has_age.age_id']=$agesList;
				}
			}

			//check price
			if($filterItem['alias']=='price') {
				$minPrice=(int)$filterItem['price_from'];
				$maxPrice=(int)$filterItem['price_to'];
				$priceCondition='product.min_price>='.$minPrice.' AND '.'product.max_price<='.$maxPrice;
			}
		}
		$query->where($condition);

		if($priceCondition) {
			if($productTableJoined==false) {
				$query->innerJoin('product_item', 'product_item.product_id=product.id');
				$productTableJoined=true;

			}
			$query->andWhere($priceCondition);
		}
		if($saleText) {
			if($productTableJoined==false) {
				$query->innerJoin('product_item', 'product_item.product_id=product.id');
				$productTableJoined=true;

			}
			$query->andWhere($saleText);
		}

		if($sortType=='new') {
			$query->orderBy('product.create_time DESC');
		}
		if($sortType=='expensive') {
			$query->orderBy('product.max_price DESC');
		}
		if($sortType=='cheap') {
			$query->orderBy('product.min_price ASC');
		}
		if($sortType=='popular') {
			$query->orderBy('product.order_count DESC');
		}

		$offsetCount=0;
		if($page>1) {
			$offsetCount=($page-1)*$pageSize;
		}
		$query->distinct(true);
		$allCount=$query->count();

		if($offsetCount) {
			$query->offset($offsetCount);
		}
		$query->limit($pageSize);

		$productModels=$query->all();

		$dataList=[];

		foreach ($productModels as $item) {
			$dataList[]=[
				'id'=>$item->id,
				'name'=>\Yii::$app->gFunctions->translate($item, 'name'),
				'priceText'=>$item->getPrice(),
				'image'=>$item->ImagePath(),
				'brand'=>($item->brand)?\Yii::$app->gFunctions->translate($item->brand, 'name'):'',
			];
		}

		$returnData['itemsCount']=(int)$allCount;
		$returnData['page']=$page;
		$returnData['productList']=$dataList;

		return $this->returnResponse(200, $returnData);
	}

	public function filterModel() {
		$filter = [];
        $brand_filter = [];
		$producer_filter=[];
        $size_filter = [];
        $age_filter = [];
        $colour_filter = [];
		$language= Yii::$app->language;
		if($language=='uz') {
			$langExt='uz';
		} else {
			$langExt='ru';
		}

		$filter[]=[
			'name'=>Yii::t('app', 'Sale'),
			'type' => 'checkbox',
            'items' => [
				[
					'id' => 1,
					'name' => Yii::t('app', 'Sale'),
					'checked' => 0
				]
			],
			'checked'=>0,
            'alias' => 'sale',
		];

        $brandQuery = Brand :: find()->where(['is_deleted'=>0])->orderBy('name_'.$langExt.' ASC')->all();
        foreach($brandQuery as $item)
        {
            $brand_filter[] = [
                'id' => $item->id,
                'name' => Yii::$app->gFunctions->translate($item, 'name', $this->language),
                'checked' => 0
            ];
        }
        $brands = [
            'name' => Yii::t('app', 'Brands'),
            'type' => 'checkbox',
            'items' => $brand_filter,
            'alias' => 'brand',
        ];
        $filter [] = $brands;


        $producerQuery = Brand :: find()->where(['is_deleted'=>0])->orderBy('name_'.$langExt.' ASC')->all();
        foreach($producerQuery as $item)
        {
            $producer_filter[] = [
                'id' => $item->id,
                'name' => Yii::$app->gFunctions->translate($item, 'name', $this->language),
                'checked' => 0
            ];
        }
        $producers = [
            'name' => Yii::t('app', 'Producers'),
            'type' => 'checkbox',
            'items' => $producer_filter,
            'alias' => 'producer',
        ];
        $filter [] = $producers;

        $sizeQuery = Size :: find()->where(['is_deleted'=>0])->orderBy('position ASC')->all();
        foreach($sizeQuery as $item)
        {
            $size_filter[] = [
                'id' => $item->id,
                'name' => Yii::$app->gFunctions->translate($item, 'name', $this->language),
                'checked' => 0
            ];
        }
        $sizes = [
            'name' => Yii::t('app', 'Sizes'),
            'type' => 'checkbox',
            'items' => $size_filter,
            'alias' => 'size',
        ];
        $filter[] = $sizes;

        $colourQuery = Color :: find()->all();
        foreach($colourQuery as $item)
        {
            $colour_filter[] = [
                'id' => $item->id,
                'name' => Yii::$app->gFunctions->translate($item, 'name', $this->language),
                'hex' => $item->hex,
                'hex2' => $item->hex2,
                'borderHex' => $item->border_hex,
                'checked' => 0
            ];
        }
        $colourGroups = [
            'name' => Yii::t('app', 'Colors'),
            'type' => 'colours',
            'items' => $colour_filter,
            'alias' => 'color',
        ];
        $filter[] = $colourGroups;


        $ageQuery = Age :: find()->where(['is_deleted'=>0])->orderBy('name_'.$langExt.' ASC')->all();
        foreach ($ageQuery as $key => $value) {
            $age_filter[] = [
                'id' => $value['id'],
                'name' => Yii::$app->gFunctions->translate($value, 'name', $this->language),
                'checked' => 0
            ];
        }
        $ageGroups = [
            'name' => Yii::t('app', 'Ages'),
            'type' => 'checkbox',
            'items' => $age_filter,
            'alias' => 'age',
        ];
        $filter[] = $ageGroups;



        $price_filter = [
            'name' => Yii::t('app', 'Price'),
            'type' => 'slider',
            'min_val' => 0,
            'max_val' => 10000000,
            'step' => 1000,
            'price_from' => 0,
            'price_to' => 10000000,
            'alias' => 'price'
        ];
        $filter [] = $price_filter;
        return $filter;
	}
}
