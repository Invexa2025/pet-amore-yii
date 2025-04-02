<?php

namespace app\controllers\sa;
use app\extensions\XController;
use app\models\sa\Currency;
use Yii;

class CurrencyController extends XController
{
    public function actionIndex()
    {
        $c = new Currency();
        $c->db = $this->setDb();
        $c->sort = ['code'];
		$c->order = ['ASC'];
        $ccyList = $c->getCurrencyList(true);

        return $this->render('currency', [
            'ccyList' => $this->jsonEncode($ccyList, 1),
        ]);
    }
    
    public function actionGetCurrencyList()
	{
		$c = new Currency();
		$c->db = $this->setDb();
		$c->search = $this->getParam('search');
		$this->setPaginationParam($c);

		$result = $c->getCurrencyList(true);

		return $this->jsonEncode($result);
	}

	public function actionGetCurrencyDetailByCode()
	{
		$c = new Currency();
		$c->db = $this->setDb();
		$c->ccyCode = $this->getParam('ccyCode');
		$c->scenario = 'get-currency-detail-by-code';

		if ($c->validate())
		{
			$data = $c->getCurrencyDetailByCode();

			return $this->jsonEncode($data);
		}
		else
		{
			return $this->rulesValidation($c->errors);
		}
	}

	public function actionInsertCurrency()
	{
		$c = new Currency();
        $c->db = $this->setDb();
		$c->ccyCode = $this->getParam('ccyCode');
		$c->ccyName = $this->getParam('ccyName');
		$c->ccyNumericCode = $this->getParam('ccyNumericCode');
		$c->scenario = 'insert-ccy';

		if ($c->validate())
		{
			$this->beginTx();
			$data = $c->insertCurrency();
			$this->validateBasicTx($data);

			return $this->jsonEncode($data);
		}
		else
		{
			return $this->rulesValidation($c->errors);
		}
	}

	public function actionUpdateCurrency()
	{
		$c = new Currency();
        $c->db = $this->setDb();
		$c->ccyCode = $this->getParam('ccyCode');
		$c->ccyName = $this->getParam('ccyName');
		$c->ccyNumericCode = $this->getParam('ccyNumericCode');
		$c->scenario = 'update-ccy';

		if ($c->validate())
		{
			$this->beginTx();
			$data = $c->updateCurrency();
			$this->validateBasicTx($data);

			return $this->jsonEncode($data);
		}
		else
		{
			return $this->rulesValidation($c->errors);
		}
	}
}
