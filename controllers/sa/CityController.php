<?php

namespace app\controllers\sa;
use app\extensions\XController;
use app\models\sa\Country;
use app\models\sa\City;
use Yii;

class CityController extends XController
{
    public function actionIndex()
    {
		$c = new Country();
        $c->db = $this->setDb();
        $c->sort = ['code_2'];
		$c->order = ['ASC'];
		$countryList = $c->getCountryList(false);

        $ct = new City();
        $ct->db = $this->setDb();
        $ct->sort = ['code'];
		$ct->order = ['ASC'];
        $cityList = $ct->getCityList(true);
		$timezoneList = $ct->getTimezoneList();

        return $this->render('city', [
			'countryList' => $this->jsonEncode($countryList, 1),
			'timezoneList' => $this->jsonEncode($timezoneList, 1),
            'cityList' => $this->jsonEncode($cityList, 1),
        ]);
    }
    
    public function actionGetCityList()
	{
		$c = new City();
		$c->db = $this->setDb();
		$c->search = $this->getParam('search');
		$this->setPaginationParam($c);

		$result = $c->getCityList(true);

		return $this->jsonEncode($result);
	}

	public function actionGetCityDetailByCode()
	{
		$c = new City();
		$c->db = $this->setDb();
		$c->cityCode = $this->getParam('cityCode');
		$c->scenario = 'get-city-detail-by-code';

		if ($c->validate())
		{
			$data = $c->getCityDetailByCode();

			return $this->jsonEncode($data);
		}
		else
		{
			return $this->rulesValidation($c->errors);
		}
	}

	public function actionInsertCity()
	{
		$c = new City();
        $c->db = $this->setDb();
		$c->cityCode = $this->getParam('cityCode');
        $c->cityName = $this->getParam('cityName');
        $c->cityCountryCode = $this->getParam('cityCountryCode');
		$c->cityTimezone = $this->getParam('cityTimezone');
		$c->scenario = 'insert-city';

		if ($c->validate())
		{
			$this->beginTx();
			$data = $c->insertCity();
			$this->validateBasicTx($data);

			return $this->jsonEncode($data);
		}
		else
		{
			return $this->rulesValidation($c->errors);
		}
	}

	public function actionUpdateCity()
	{
		$c = new City();
        $c->db = $this->setDb();
		$c->cityCode = $this->getParam('cityCode');
        $c->cityName = $this->getParam('cityName');
        $c->cityCountryCode = $this->getParam('cityCountryCode');
		$c->cityTimezone = $this->getParam('cityTimezone');
		$c->scenario = 'update-city';

		if ($c->validate())
		{
			$this->beginTx();
			$data = $c->updateCity();
			$this->validateBasicTx($data);

			return $this->jsonEncode($data);
		}
		else
		{
			return $this->rulesValidation($c->errors);
		}
	}
}
