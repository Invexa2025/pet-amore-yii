<?php

namespace app\controllers\sa;
use app\extensions\XController;
use app\models\sa\Currency;
use app\models\sa\Country;
use Yii;

class CountryController extends XController
{
    public function actionIndex()
    {
        $ccy = new Currency();
        $ccy->db = $this->setDb();
        $ccy->sort = ['code'];
		$ccy->order = ['ASC'];
        $ccyList = $ccy->getCurrencyList(false);

        $c = new Country();
        $c->db = $this->setDb();
        $c->sort = ['code_2'];
		$c->order = ['ASC'];
        $countryList = $c->getCountryList(true);

        return $this->render('country', [
            'ccyList' => $this->jsonEncode($ccyList, 1),
            'countryList' => $this->jsonEncode($countryList, 1),
        ]);
    }
    
    public function actionGetCountryList()
	{
		$c = new Country();
		$c->db = $this->setDb();
		$c->search = $this->getParam('search');
		$this->setPaginationParam($c);

		$result = $c->getCountryList(true);

		return $this->jsonEncode($result);
	}

	public function actionGetCountryDetailByCode()
	{
		$c = new Country();
		$c->db = $this->setDb();
		$c->countryCode2 = $this->getParam('countryCode2');
		$c->scenario = 'get-country-detail-by-code';

		if ($c->validate())
		{
			$data = $c->getCountryDetailByCode();

			return $this->jsonEncode($data);
		}
		else
		{
			return $this->rulesValidation($c->errors);
		}
	}

	public function actionInsertCountry()
	{
		$c = new Country();
        $c->db = $this->setDb();
		$c->countryCode2 = $this->getParam('countryCode2');
        $c->countryCode3 = $this->getParam('countryCode3');
        $c->countryName = $this->getParam('countryName');
		$c->ccyCode = $this->getParam('ccyCode');
		$c->countryPhoneCode = $this->getParam('countryPhoneCode');
		$c->scenario = 'insert-country';

		if ($c->validate())
		{
			$this->beginTx();
			$data = $c->insertCountry();
			$this->validateBasicTx($data);

			return $this->jsonEncode($data);
		}
		else
		{
			return $this->rulesValidation($c->errors);
		}
	}

	public function actionUpdateCountry()
	{
		$c = new Country();
        $c->db = $this->setDb();
		$c->countryCode2 = $this->getParam('countryCode2');
        $c->countryCode3 = $this->getParam('countryCode3');
        $c->countryName = $this->getParam('countryName');
		$c->ccyCode = $this->getParam('ccyCode');
		$c->countryPhoneCode = $this->getParam('countryPhoneCode');
		$c->scenario = 'update-country';

		if ($c->validate())
		{
			$this->beginTx();
			$data = $c->updateCountry();
			$this->validateBasicTx($data);

			return $this->jsonEncode($data);
		}
		else
		{
			return $this->rulesValidation($c->errors);
		}
	}
}
