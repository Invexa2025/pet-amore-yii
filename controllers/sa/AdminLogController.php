<?php

namespace app\controllers\sa;
use app\extensions\XController;
use app\models\sa\AdminLog;
use Yii;

class AdminLogController extends XController
{
    public function actionIndex()
    {
		$adminLog = new AdminLog();
        $adminLog->db = $this->setDb();
        $actionList = $adminLog->getActionList();

        $adminLog->sort = ['id'];
		$adminLog->order = ['DESC'];
		$adminLog->search = ['', date('d/m/Y H:i', strtotime('-1 hour')), date('d/m/Y H:i')];
		$adminLogList = $adminLog->getAdminLogList(true);
		
		$officeUtc 			= $this->getSession('OFFICE_UTC');
		$officeUtc 			= floatval($officeUtc) > 0 ? '+' . $officeUtc : $officeUtc;

        return $this->render('admin-log', [
			'officeUtc' => $officeUtc,
			'actionList' => $this->jsonEncode($actionList, 1),
			'adminLogList' => $this->jsonEncode($adminLogList, 1),
        ]);
    }
    
    public function actionGetAdminLogList()
	{
		$adminLog = new AdminLog();
		$adminLog->db = $this->setDb();
		$adminLog->search = $this->getParam('search');
		$this->setPaginationParam($adminLog);

		$result = $adminLog->getAdminLogList(true);

		return $this->jsonEncode($result);
	}

	public function actionGetAdminLogDetailById()
	{
		$adminLog = new AdminLog();
		$adminLog->db = $this->setDb();
		$adminLog->adminHistoryId = $this->getParam('adminHistoryId');
		$adminLog->scenario = 'get-admin-log-detail-by-id';

		if ($adminLog->validate())
		{
			$data = $adminLog->getAdminLogDetailById();

			return $this->jsonEncode($data);
		}
		else
		{
			return $this->rulesValidation($adminLog->errors);
		}
	}
}
