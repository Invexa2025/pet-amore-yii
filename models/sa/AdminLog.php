<?php

namespace app\models\sa;

use Yii;
use PDO;
use app\extensions\XModel;

class AdminLog extends XModel
{
	public $adminHistoryId;

    public function rules()
	{
		return [
			[['adminHistoryId'], 'required', 'on' => ['get-admin-log-detail-by-id']]
        ];
    }

    public function getAdminLogList($isPagination)
    {
		$where = '';
		$params = [];

		if ($this->search) {	
			if (!is_array($this->search)) {
				$params[':search'] = '%' . strtoupper($this->search) . '%';
				$where .= "AND ah.action LIKE :search\n";
			} else {
				[
					$searchAction, 
                    $searchStartDateTime,
                    $searchEndDateTime,
				] = array_pad($this->search, 4, null);
				
				if (!empty($searchAction)) {
					$params[':action'] = '%' . strtoupper($searchAction) . '%';
					$where .= "AND ah.action LIKE :action\n";
				}

                if (!empty($searchStartDateTime)) {
					$params[':start_datetime'] = $searchStartDateTime;
					$where .= "AND ah.create_time at time zone :officeTimezone >= TO_TIMESTAMP(:start_datetime, 'DD/MM/YYYY HH24:MI')\n";
				}
				
				if (!empty($searchEndDateTime)) {
					$params[':end_datetime'] = $searchEndDateTime;
					$where .= "AND ah.create_time at time zone :officeTimezone <= TO_TIMESTAMP(:end_datetime, 'DD/MM/YYYY HH24:MI')\n";
				}
			}
		}

        $sql = "
			SELECT
				ah.id,
				ah.action,
                fn_get_user_id(ah.create_by, 1) action_by,
                ah.action_to,
                ah.old_value,
                ah.new_value,
                TO_CHAR(ah.create_time at time zone :officeTimezone, :datetimeDisplayDefault) action_time
			FROM
				admin_history ah
			WHERE
				ah.status = 1
                AND ah.owner_id = :businessId
				$where
		";

		$st = $this->db->createCommand($sql);
		$this->bindParams($st, $params ?? []);
        $st->bindParam(':businessId', $this->businessId);
        $st->bindParam(':officeTimezone', $this->officeTimezone);
        $st->bindParam(':datetimeDisplayDefault', Yii::$app->params['datetimeDisplayDefault']);

		// $this->dd($st->getRawSql());
		if ($isPagination)
		{
			$result = $this->pagination($st->getRawSql());
		}
		else
		{
			$result = $st->queryAll();
		}

		return $result;
    }

	public function getAdminLogDetailById()
	{
		$sql = "
			SELECT
				ah.id,
				ah.action,
                fn_get_user_id(ah.create_by, 1) action_by,
                ah.action_to,
                ah.old_value,
                ah.new_value,
                TO_CHAR(ah.create_time at time zone :officeTimezone, :datetimeDisplayDefault) action_time
			FROM
				admin_history ah
			WHERE
				ah.status = 1
                AND ah.owner_id = :businessId
                AND ah.id = :adminHistoryId
		";

		$st = $this->db->createCommand($sql);
        $st->bindParam(':adminHistoryId', $this->adminHistoryId);
        $st->bindParam(':businessId', $this->businessId);
        $st->bindParam(':officeTimezone', $this->officeTimezone);
        $st->bindParam(':datetimeDisplayDefault', Yii::$app->params['datetimeDisplayDefault']);
		// $this->dd($st->getRawSql());
		$result = $st->queryOne();

		return $result;
	}

    public function getActionList()
    {
        $sql = "
			SELECT DISTINCT
				ah.action
			FROM
				admin_history ah
            ORDER BY
                ah.action ASC
		";

		$st = $this->db->createCommand($sql);
		// $this->dd($st->getRawSql());
		$result = $st->queryAll();

		return $result;
    }
}