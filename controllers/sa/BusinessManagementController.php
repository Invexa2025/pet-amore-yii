<?php

namespace app\controllers\sa;

use Yii;
use app\extensions\XController;
use app\models\sa\Country;
use app\models\sa\City;
use app\models\sa\Groups;
use app\models\sa\Offices;
use app\models\sa\BusinessManagement;

class BusinessManagementController extends XController
{
    public function actionIndex()
    {
        $country = new Country();
		$country->db = $this->setDb();
		$countryList = $country->getCountryList(false, ['name'], ['ASC']);
		
		$city = new City();
		$city->db = $this->setDb();
		$cityList = $city->getCityList(false, ['name'], ['ASC']);

        $g = new Groups();
		$g->db = $this->setDb();
		$groupList = $g->getGroupList(false, ['name'], ['ASC']);

        $o = new Offices();
		$o->db = $this->setDb();
		$officeList = $o->getOfficeList(false, ['name'], ['ASC']);

        $bm = new BusinessManagement();
		$bm->db = $this->SetDb();
        $bm->sort = ['b.name'];
		$bm->order = ['ASC'];
        $businessList = $bm->getBusinessList(true);

        return $this->render('business-management', [
            'countryList' 		=> $this->jsonEncode($countryList, 1),
			'cityList' 			=> $this->jsonEncode($cityList, 1),
            'groupList' 		=> $this->jsonEncode($groupList, 1),
            'officeList' 		=> $this->jsonEncode($officeList, 1),
            'businessList'      => $this->jsonEncode($businessList, 1)
        ]);
    }

    public function actionGetBusinessList()
    {
        $bm = new BusinessManagement();
		$bm->db = $this->setDb();
        $bm->search = $this->getParam('search');
        $this->setPaginationParam($bm);

		$result  = $bm->getBusinessList(true);

		return $this->jsonEncode($result);
    }

    public function actionGetBusinessDetailById()
    {
        $bm = new BusinessManagement();
		$bm->db = $this->setDb();
        $bm->Id = $this->getParam('businessId');
        $this->setPaginationParam($bm);

		$result  = $bm->getBusinessDetailById();

		return $this->jsonEncode($result);
    }

    public function insertBusiness()
    {

    }

    public function updateBusiness()
    {

    }

    public function updateGlobalVariables()
    {

    }

    public function updateBusinessApps()
    {

    }
}
