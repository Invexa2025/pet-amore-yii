<?php

namespace app\controllers\sa;
use app\extensions\XController;
use app\models\sa\GlobalVariable;
use Yii;

class GlobalVariablesController extends XController
{
    public function actionIndex()
    {
		$gv = new GlobalVariable();
        $gv->db = $this->setDb();
        $businessApplicationList = $gv->getBusinessApplicationList();
        
        $gv->sort = ['var_name'];
		$gv->order = ['ASC'];
		$gvList = $gv->getGvList(true);

        return $this->render('global-variables', [
			'businessApplicationList' => $this->jsonEncode($businessApplicationList, 1),
            'gvList' => $this->jsonEncode($gvList, 1),
        ]);
    }
    
    public function actionGetGvList()
	{
		$gv = new GlobalVariable();
		$gv->db = $this->setDb();
		$gv->search = $this->getParam('search');
		$this->setPaginationParam($gv);

		$result = $gv->getGvList(true);

		return $this->jsonEncode($result);
	}

	public function actionGetGvDetailByVarName()
	{
		$gv = new GlobalVariable();
		$gv->db = $this->setDb();
		$gv->gvVarName = $this->getParam('gvVarName');
		$gv->scenario = 'get-gv-detail-by-var-name';

		if ($gv->validate())
		{
			$data = $gv->getGvDetailByVarName();

			return $this->jsonEncode($data);
		}
		else
		{
			return $this->rulesValidation($gv->errors);
		}
	}

	public function actionInsertGv()
	{
		$gv = new GlobalVariable();
        $gv->db = $this->setDb();
		$gv->gvVarName = $this->getParam('gvVarName');
        $gv->gvVarDesc = $this->getParam('gvVarDesc');
        $gv->gvVarValue = $this->getParam('gvVarValue');
		$gv->gvVarNumber = $this->getParam('gvVarNumber') ? $this->getParam('gvVarNumber') : null;
        $gv->gvVarGroup = $this->getParam('gvVarGroup');
		$gv->scenario = 'insert-gv';

		if ($gv->validate())
		{
			$this->beginTx();
			$data = $gv->insertGv();
			$this->validateBasicTx($data);

			return $this->jsonEncode($data);
		}
		else
		{
			return $this->rulesValidation($gv->errors);
		}
	}

	public function actionUpdateGv()
	{
		$gv = new GlobalVariable();
        $gv->db = $this->setDb();
		$gv->gvVarName = $this->getParam('gvVarName');
        $gv->gvVarDesc = $this->getParam('gvVarDesc');
        $gv->gvVarValue = $this->getParam('gvVarValue');
		$gv->gvVarNumber = $this->getParam('gvVarNumber') !== '' ? $this->getParam('gvVarNumber') : null;
        $gv->gvVarGroup = $this->getParam('gvVarGroup');
		$gv->scenario = 'update-gv';

		if ($gv->validate())
		{
			$this->beginTx();
			$data = $gv->updateGv();
			$this->validateBasicTx($data);

			return $this->jsonEncode($data);
		}
		else
		{
			return $this->rulesValidation($gv->errors);
		}
	}
}
