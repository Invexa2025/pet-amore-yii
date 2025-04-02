<?php

namespace app\controllers\sa;
use app\extensions\XController;
use app\models\sa\Groups;
use app\models\sa\Offices;
use app\models\sa\AdminList;
use Yii;

class AdminListController extends XController
{
    public function actionIndex()
    {
		$g = new Groups();
		$g->db = $this->setDb();
		$g->sort = ['name'];
		$g->order = ['ASC'];
		$groupList = $g->getGroupList(false);

		$o = new Offices();
		$o->db = $this->setDb();
		$o->sort = ['name'];
		$o->order = ['ASC'];
		$officeList = $o->getOfficeList(false);

		$adminList = new AdminList();
        $adminList->db = $this->setDb();
		$adminList->sort = ['first_name'];
		$adminList->order = ['ASC'];
		$adminList = $adminList->getAdminList(true);

		$officeUtc 			= $this->getSession('OFFICE_UTC');
		$officeUtc 			= floatval($officeUtc) > 0 ? '+' . $officeUtc : $officeUtc;
    
        return $this->render('admin-list', [
			'officeUtc' 		=> $officeUtc,
			'groupList' 		=> $this->jsonEncode($groupList, 1),
			'officeList' 		=> $this->jsonEncode($officeList, 1),
			'adminList' 		=> $this->jsonEncode($adminList, 1)
        ]);
    }
    
    public function actionGetAdminList()
	{
		$adminList = new AdminList();
		$adminList->db = $this->setDb();
		$adminList->search = $this->getParam('search');
		$this->setPaginationParam($adminList);

		$result = $adminList->getAdminList(true);

		return $this->jsonEncode($result);
	}

	public function actionGetAdminDetailById()
	{
		$adminList = new AdminList();
		$adminList->db = $this->setDb();
		$adminList->Id = $this->getParam('userId');
		$adminList->scenario = 'get-admin-detail-by-id';

		if ($adminList->validate())
		{
			$data = $adminList->getAdminDetailById();

			return $this->jsonEncode($data);
		}
		else
		{
			return $this->rulesValidation($adminList->errors);
		}
	}

	public function actionInsertAdmin()
	{
		$adminList = new AdminList();
        $adminList->db = $this->setDb();
		$adminList->userId 		= $this->getParam('userId');
		$adminList->firstName 	= $this->getParam('firstName');
		$adminList->lastName 	= $this->getParam('lastName');
		$adminList->gender 		= $this->getParam('gender');
		$adminList->birthdate 	= $this->getParam('birthdate');
		$adminList->email 		= $this->getParam('email');
		$adminList->phone 		= $this->getParam('phone');
		$adminList->group 		= $this->getParam('group');
		$adminList->office 		= $this->getParam('office');
		$adminList->scenario 	= 'insert-admin';

		if ($adminList->validate())
		{
			$this->beginTx();
			$data = $adminList->insertAdmin();
			$this->validateBasicTx($data);

			return $this->jsonEncode($data);
		}
		else
		{
			return $this->rulesValidation($adminList->errors);
		}
	}

	public function actionUpdateStatusAdmin()
	{
		$adminList = new AdminList();
        $adminList->db = $this->setDb();
		$adminList->Id 			= $this->getParam('id');
		$adminList->status 		= $this->getParam('status');
		$adminList->scenario 	= 'update-status-admin';

		if ($adminList->validate())
		{
			$this->beginTx();
			$data = $adminList->updateStatusAdmin();
			$this->validateBasicTx($data);

			return $this->jsonEncode($data);
		}
		else
		{
			return $this->rulesValidation($adminList->errors);
		}
	}
}
