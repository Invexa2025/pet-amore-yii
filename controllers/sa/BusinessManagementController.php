<?php

namespace app\controllers\sa;

use Yii;
use app\extensions\XController;
use app\models\sa\Country;
use app\models\sa\City;
use app\models\sa\Groups;
use app\models\sa\Offices;
use app\models\sa\BusinessManagement;
use app\models\sa\GlobalVariable;

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
        $appList = $g->getAllApp();

        $o = new Offices();
		$o->db = $this->setDb();
		$officeList = $o->getOfficeList(false, ['name'], ['ASC']);

        $bm = new BusinessManagement();
		$bm->db = $this->SetDb();
        $bm->sort = ['b.name'];
		$bm->order = ['ASC'];
        $businessList = $bm->getBusinessList(true);

        $gv = new GlobalVariable();
        $gv->db = $this->setDb();
        $globalVariableList = $gv->getGvList(0);

        return $this->render('business-management', [
            'countryList' 		    => $this->jsonEncode($countryList, 1),
			'cityList' 			    => $this->jsonEncode($cityList, 1),
            'groupList' 		    => $this->jsonEncode($groupList, 1),
            'appList'               => $this->jsonEncode($appList, 1),
            'globalVariableList'    => $this->jsonEncode($globalVariableList, 1),
            'officeList' 		    => $this->jsonEncode($officeList, 1),
            'businessList'          => $this->jsonEncode($businessList, 1)
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

    public function actionInsertBusiness()
    {
        $bm = new BusinessManagement();
        $bm->businessName = $this->getParam('businessName');
        $bm->businessDomain = $this->getParam('businessDomain');
        $bm->businessUserId = $this->getParam('businessUserId');
        $bm->businessUserFirstName = $this->getParam('businessUserFirstName');
        $bm->businessUserLastName = $this->getParam('businessUserLastName');
        $bm->businessUserGender = $this->getParam('businessUserGender');
        $bm->businessUserEmail = $this->getParam('businessUserEmail');
        $bm->businessUserBirthdate = $this->getParam('businessUserBirthdate');
        $bm->businessUserPhone = $this->getParam('businessUserPhone');
        $bm->businessOfficeCode = $this->getParam('businessOfficeCode');
        $bm->businessOfficeName = $this->getParam('businessOfficeName');
        $bm->businessOfficeCountry = $this->getParam('businessOfficeCountry');
        $bm->businessOfficeCity = $this->getParam('businessOfficeCity');
        $bm->businessOfficeAddress = $this->getParam('businessOfficeAddress');
        $bm->businessOfficePhone = $this->getParam('businessOfficePhone');
        $bm->businessOfficeFax = $this->getParam('businessOfficeFax');
        $bm->scenario = 'insert-business-management';

        if ($bm->validate())
        {
            $bm->db = $this->setDb();
            $this->beginTx();
            $result = $bm->insertBusiness();
            $this->validateBasicTx($result);

            return $this->jsonEncode($result);
        }

        return $this->rulesValidation($bm->errors);
    }

    public function actionUpdateBusiness()
    {
        $bm = new BusinessManagement();
        $bm->Id = $this->getParam('businessId');
        $bm->businessName = $this->getParam('businessName');
        $bm->businessDomain = $this->getParam('businessDomain');
        $bm->scenario = 'update-business-management';

        if ($bm->validate())
        {
            $bm->db = $this->setDb();
            $this->beginTx();
            $result = $bm->updateBusiness();
            $this->validateBasicTx($result);

            return $this->jsonEncode($result);
        }

        return $this->rulesValidation($bm->errors);
    }

    public function actionUpdateBusinessApps()
    {

    }

    public function actionUpdateGlobalVariables()
    {
        
    }
}
