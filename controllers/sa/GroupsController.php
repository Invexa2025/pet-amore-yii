<?php

namespace app\controllers\sa;
use app\extensions\XController;
use app\models\sa\Groups;
use Yii;

class GroupsController extends XController
{
    public function actionIndex()
    {
		$g = new Groups();
        $g->db = $this->setDb();
		$appList = $g->getAllApp();
		
		$g->sort = ['name'];
		$g->order = ['ASC'];
		$groupList = $g->getGroupList(true);

		$officeUtc 			= $this->getSession('OFFICE_UTC');
		$officeUtc 			= floatval($officeUtc) > 0 ? '+' . $officeUtc : $officeUtc;
    
        return $this->render('groups', [
			'officeUtc' => $officeUtc,
			'appList' => $this->jsonEncode($appList, 1),
            'groupList' => $this->jsonEncode($groupList, 1),
        ]);
    }
    
    public function actionGetGroupList()
	{
		$g = new Groups();
		$g->db = $this->setDb();
		$g->search = $this->getParam('search');
		$this->setPaginationParam($g);

		$result = $g->getGroupList(true);

		return $this->jsonEncode($result);
	}

	public function actionGetGroupDetailById()
	{
		$g = new Groups();
		$g->db = $this->setDb();
		$g->groupId = $this->getParam('groupId');
		$g->scenario = 'get-group-detail-by-id';

		if ($g->validate())
		{
			$data = $g->getGroupDetailById();

			return $this->jsonEncode($data);
		}
		else
		{
			return $this->rulesValidation($g->errors);
		}
	}

	public function actionInsertGroup()
	{
		$g = new Groups();
        $g->db = $this->setDb();
		$g->groupName = $this->getParam('groupName');
		$g->groupDesc = $this->getParam('groupDesc');
        $g->groupApp = $this->getParam('groupApp');
		$g->groupRoleType = "SA";
		$g->scenario = 'insert-group';

		if ($g->validate())
		{
			$this->beginTx();
			$data = $g->insertGroup();
			$this->validateBasicTx($data);

			return $this->jsonEncode($data);
		}
		else
		{
			return $this->rulesValidation($g->errors);
		}
	}

	public function actionUpdateGroup()
	{
		$g = new Groups();
        $g->db = $this->setDb();
		$g->groupId = $this->getParam('groupId');
		$g->groupName = $this->getParam('groupName');
		$g->groupDesc = $this->getParam('groupDesc');
        $g->groupApp = $this->getParam('groupApp');
		$g->groupRoleType = "SA";
		$g->scenario = 'update-group';

		if ($g->validate())
		{
			$this->beginTx();
			$data = $g->updateGroup();
			$this->validateBasicTx($data);

			return $this->jsonEncode($data);
		}
		else
		{
			return $this->rulesValidation($g->errors);
		}
	}

	public function actionDeleteGroup()
	{
		$g = new Groups();
        $g->db = $this->setDb();
		$g->groupId = $this->getParam('groupId');
		$g->scenario = 'delete-group';

		if ($g->validate())
		{
			$this->beginTx();
			$data = $g->deleteGroup();
			$this->validateBasicTx($data);

			return $this->jsonEncode($data);
		}
		else
		{
			return $this->rulesValidation($g->errors);
		}
	}
}
