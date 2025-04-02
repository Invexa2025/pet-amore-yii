<?php

namespace app\models\sa;

use Yii;
use PDO;
use app\extensions\XModel;

class Groups extends XModel
{
	public $groupId;
	public $groupName;
    public $groupDesc;
	public $groupApp;
    public $groupRoleType;

    public function rules()
	{
		return [
			[['groupName', 'groupDesc', 'groupApp', 'groupRoleType'], 'required', 'on' => ['insert-group']],
			[['groupId', 'groupName', 'groupDesc', 'groupApp', 'groupRoleType'], 'required', 'on' => ['update-group']],
			[['groupId'], 'required', 'on' => ['get-group-detail-by-id', 'delete-group']]
        ];
    }

    public function getGroupList($isPagination, $orderColumn = [], $sortDirection = [])
    {
		$where = '';
		$params = [];

		if ($this->search) {	
			if (!is_array($this->search)) {
				$params[':search'] = '%' . strtoupper($this->search) . '%';
				$where .= "AND g.name LIKE :search\n";
			} else {
				[
					$searchGroupName, 
                    $searchGroupDesc,
				] = array_pad($this->search, 2, null);
				
				if (!empty($searchGroupName)) {
					$params[':name'] = '%' . strtoupper($searchGroupName) . '%';
					$where .= "AND g.name LIKE :name\n";
				}

                if (!empty($searchGroupDesc)) {
					$params[':description'] = '%' . $searchGroupDesc . '%';
					$where .= "AND g.description ILIKE :description\n";
				}
			}
		}

		if (!is_array($orderColumn)) {
			$orderColumn = [$orderColumn];
		}
	
		if (!is_array($sortDirection)) {
			$sortDirection = [$sortDirection];
		}
	
		$orderClauses = [];
		
		foreach ($orderColumn as $index => $col) {
			$direction = strtoupper($sortDirection[$index] ?? 'ASC');
			$orderClauses[] = "$col $direction";
		}
	
		$orderBy = !empty($orderClauses) ? "ORDER BY " . implode(", ", $orderClauses) : "";	

        $sql = "
			SELECT
				g.id,
				g.name,
				g.description,
				g.role_type,
				TO_CHAR(g.create_time at time zone :officeTimezone, :datetimeDisplayDefault) create_time,
				fn_get_user_id(g.create_by, 1) create_by,
				TO_CHAR(g.update_time at time zone :officeTimezone, :datetimeDisplayDefault) update_time,
				fn_get_user_id(g.update_by, 1) update_by
			FROM
				groups g
			WHERE
				g.status = 1
				AND g.owner_id = :businessId
				$where
			$orderBy
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

	public function getGroupDetailById()
	{
		$result = [];

		$sql = "
			SELECT
				g.id,
				g.name,
				g.description
			FROM
				groups g
			WHERE
				g.id = :groupId
				AND g.owner_id = :businessId
				AND g.status = 1
		";
		$st = $this->db->createCommand($sql);
		$st->bindParam(':groupId', $this->groupId);
		$st->bindParam(':businessId', $this->businessId);
		$result['group'] = $st->queryOne();

		$sql = "
			SELECT
				ga.app_code
			FROM
				group_apps ga
			WHERE
				ga.group_id = :groupId
				AND ga.status = 1
		";
		$st = $this->db->createCommand($sql);
		$st->bindParam(':groupId', $this->groupId);
		$result['group_apps'] = $st->queryAll();

		return $result;
	}

    public function insertGroup()
	{	
		$sql = "
			SELECT
				* 
			FROM 
				sp_group_insert
				(
					in_group_name 		=> :groupName,
					in_group_desc 		=> :groupDesc,
					in_role_type 		=> :groupRoleType,
                    in_create_by        => :userUid,
                    in_owner_id         => :businessId
				);
		";

		$st = $this->db->createCommand($sql);
		$st->bindParam(':groupName', $this->groupName);
		$st->bindParam(':groupDesc', $this->groupDesc);
		$st->bindParam(':groupRoleType', $this->groupRoleType);
		$st->bindParam(':userUid', $this->userUid);
		$st->bindParam(':businessId', $this->businessId);
		// $this->dd($st->getRawSql());
		$result = $st->queryOne();

		if ($result['out_num'] == 0 && !empty($this->groupApp))
		{
			$groupId = $result['out_group_id'];

			$sql = "
				SELECT
					* 
				FROM 
					sp_group_apps_insert
					(
					  	in_group_id		=> :groupId,
					  	in_apps			=> :groupApp,
						in_create_by 	=> :userUid,
						in_owner_Id 	=> :businessId
					);
			";

			$st = $this->db->createCommand($sql);
			$st->bindParam(':groupId' , $groupId);
			$st->bindParam(':groupApp' , $this->groupApp);
			$st->bindParam(':userUid', $this->userUid);
			$st->bindParam(':businessId', $this->businessId);
			// $this->dd($st->getRawSql());
			$result = $st->queryOne();
		}

		return [
			'errNum' => $result['out_num'],
			'errStr' => $result['out_str']
		];
	}

	public function updateGroup()
	{
		$sql = "
			SELECT
				* 
			FROM 
				sp_group_update
				(
					in_group_name 		=> :groupName,
					in_group_desc 		=> :groupDesc,
					in_role_type 		=> :groupRoleType,
                    in_create_by        => :userUid,
                    in_owner_id         => :businessId
				);
		";

		$st = $this->db->createCommand($sql);
		$st->bindParam(':groupName', $this->groupName);
		$st->bindParam(':groupDesc', $this->groupDesc);
		$st->bindParam(':groupRoleType', $this->groupRoleType);
		$st->bindParam(':userUid', $this->userUid);
		$st->bindParam(':businessId', $this->businessId);
		// $this->dd($st->getRawSql());
		$result = $st->queryOne();

		return [
			'errNum' => $result['out_num'],
			'errStr' => $result['out_str']
		];
	}

	public function deleteGroup()
	{
		$sql = "
			SELECT
				* 
			FROM 
				sp_group_delete
				(
					in_group_id 	=> :groupId,
					in_create_by 	=> :userUid,
					in_owner_id 	=> :businessId
				);
		";

		$st = $this->db->createCommand($sql);
		$st->bindParam(':groupId', $this->groupId);
		$st->bindParam(':userUid', $this->userUid);
		$st->bindParam(':businessId', $this->businessId);
		$result = $st->queryOne();

		return [
			'errNum' => $result['out_num'],
			'errStr' => $result['out_str']
		];
	}

	public function getAllApp()
	{
		$sql = "
			SELECT
				a.code app_code,
				a.parent_code,
				a.name,
				a.url,
				a.is_menu
			FROM
				apps a
			WHERE
				a.status = 1
			ORDER BY
				a.app_order
		";

		$st = $this->db->createCommand($sql);
		$result = $st->queryAll();

		return $result;
	}
}