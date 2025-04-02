<?php

namespace app\controllers\sa;
use app\extensions\XController;
use app\models\sa\Country;
use app\models\sa\City;
use app\models\sa\Offices;
use Yii;

class OfficesController extends XController
{
    public function actionIndex()
    {
		$country = new Country();
		$country->db = $this->setDb();
		$countryList = $country->getCountryList(false, ['name'], ['ASC']);
		
		$city = new City();
		$city->db = $this->setDb();
		$cityList = $city->getCityList(false, ['name'], ['ASC']);

		$o = new Offices();
        $o->db = $this->setDb();
		$o->sort = ['name'];
		$o->order = ['ASC'];
		$officeList = $o->getOfficeList(true);
    
        return $this->render('offices', [
			'countryList' 		=> $this->jsonEncode($countryList, 1),
			'cityList' 			=> $this->jsonEncode($cityList, 1),
			'officeList' 		=> $this->jsonEncode($officeList, 1),
        ]);
    }
    
    public function actionGetOfficeList()
	{
		$o = new Offices();
		$o->db = $this->setDb();
		$o->search = $this->getParam('search');
		$this->setPaginationParam($o);

		$result = $o->getOfficeList(true);

		return $this->jsonEncode($result);
	}

	public function actionGetOfficeDetailById()
	{
		$o = new Offices();
		$o->db = $this->setDb();
		$o->officeId = $this->getParam('officeId');
		$o->scenario = 'get-office-detail-by-id';

		if ($o->validate())
		{
			$data = $o->getOfficeDetailById();

			return $this->jsonEncode($data);
		}
		else
		{
			return $this->rulesValidation($o->errors);
		}
	}

	public function actionInsertOffice()
	{
		$o = new Offices();
        $o->db = $this->setDb();
		$o->officeCode = $this->getParam('officeCode');
		$o->officeName = $this->getParam('officeName');
		$o->countryCode = $this->getParam('countryCode');
		$o->cityCode = $this->getParam('cityCode');
		$o->address = $this->getParam('address');
		$o->phone = $this->getParam('phone');
		$o->fax = $this->getParam('fax');
		$o->scenario = 'insert-office';

		if ($o->validate())
		{
			$this->beginTx();
			$data = $o->insertOffice();
			$this->validateBasicTx($data);

			return $this->jsonEncode($data);
		}
		else
		{
			return $this->rulesValidation($o->errors);
		}
	}

	public function actionUpdateOffice()
	{
		$o = new Offices();
        $o->db = $this->setDb();
		$o->officeId = $this->getParam('officeId');
		$o->officeCode = $this->getParam('officeCode');
		$o->officeName = $this->getParam('officeName');
		$o->countryCode = $this->getParam('countryCode');
		$o->cityCode = $this->getParam('cityCode');
		$o->address = $this->getParam('address');
		$o->phone = $this->getParam('phone');
		$o->fax = $this->getParam('fax');
		$o->scenario = 'update-office';

		if ($o->validate())
		{
			$this->beginTx();
			$data = $o->updateOffice();
			$this->validateBasicTx($data);

			return $this->jsonEncode($data);
		}
		else
		{
			return $this->rulesValidation($o->errors);
		}
	}
}
