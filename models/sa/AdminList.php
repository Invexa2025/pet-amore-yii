<?php

namespace app\models\sa;

use Yii;
use PDO;
use app\extensions\XModel;
use app\common\components\Helper;

class AdminList extends XModel
{
	public $Id;
	public $userId;
	public $password;
	public $firstName;
	public $lastName;
	public $gender;
	public $birthdate;
	public $email;
	public $phone;
	public $group;
	public $office;
	public $status;
	
    public function rules()
	{
		return [
			[['userId', 'firstName', 'lastName', 'group', 'office'], 'required', 'on' => ['insert-admin']],
			[['Id', 'firstName', 'lastName', 'group', 'office'], 'required', 'on' => ['update-admin']],
			[['Id'], 'required', 'on' => ['get-admin-detail-by-id']],
			[['Id', 'status'], 'required', 'on' => ['update-status-admin']],
        ];
    }

    public function getAdminList($isPagination)
    {
		$where = '';
		$params = [];

		if ($this->search) {	
			if (!is_array($this->search)) {
				$params[':search'] = '%' . strtoupper($this->search) . '%';
				$where .= "AND (u.first_name || ' ' || u.last_name) LIKE :search\n";
			} else {
				[
					$searchName, 
					$searchGender,
                    $searchLastAction,
					$searchGroup,
					$searchOffice,
				] = array_pad($this->search, 5, null);
				
				if (!empty($searchName)) {
					$params[':name'] = '%' . strtoupper($searchName) . '%';
					$where .= "AND (u.first_name || ' ' || u.last_name) LIKE :name\n";
				}

				if (!empty($searchGender)) {
					$params[':gender'] = strtoupper($searchGender);
					$where .= "AND u.gender = :gender\n";
				}

                if (!empty($searchLastAction)) {
					$params[':last_action'] = $searchLastAction;
					$where .= "AND u.last_activity  at time zone :officeTimezone BETWEEN TO_DATE(:last_action, 'DD/MM/YYYY') AND TO_DATE(:last_action, 'DD/MM/YYYY') + INTERVAL '1 DAY'\n";
				}

				if (!empty($searchGroup)) {
					$params[':group_id'] = $searchGroup;
					$where .= "AND u.group_id && STRING_TO_ARRAY(:group_id, ',')::INT[]";
				}							

				if (!empty($searchOffice)) {
					$params[':office_id'] = $searchOffice;
					$where .= "AND u.office_id = :office_id\n";
				}
			}
		}

        $sql = "
			SELECT
				u.id,
				u.user_id,
				u.first_name,
				u.last_name,
				u.first_name || ' ' || u.last_name full_name,
				u.office_id,
				fn_get_office_name(u.office_id) office_name,
				u.email,
				u.phone,
				u.status,
				TO_CHAR(u.create_time  at time zone :officeTimezone, :fullDatetimeDisplayDefault) create_time,
				TO_CHAR(u.last_activity  at time zone :officeTimezone, :fullDatetimeDisplayDefault) last_action
			FROM
				users u
			WHERE
				u.status > 0
				AND u.business_id = :businessId
				AND u.role_type NOT IN ('SSA')
				$where
		";

		$st = $this->db->createCommand($sql);
		$this->bindParams($st, $params ?? []);
		$st->bindParam(':businessId', $this->businessId);
		$st->bindParam(':officeTimezone', $this->officeTimezone);
		$st->bindParam(':fullDatetimeDisplayDefault', Yii::$app->params['fullDatetimeDisplayDefault']);

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

	public function getAdminDetailById()
	{
		$result = [];

		$sql = "
			SELECT
				u.id,
				u.user_id,
				u.first_name,
				u.last_name,
				u.gender,
				u.first_name || ' ' || u.last_name full_name,
				TO_CHAR(u.birthdate, 'DD/MM/YYYY') birthdate,
				u.office_id,
				u.group_id,
				fn_get_office_name(u.office_id) office_name,
				u.email,
				u.phone,
				u.status,
				TO_CHAR(u.create_time  at time zone :officeTimezone, :fullDatetimeDisplayDefault) create_time,
				TO_CHAR(u.last_activity  at time zone :officeTimezone, :fullDatetimeDisplayDefault) last_action
			FROM
				users u
			WHERE
				u.status > 0
				AND u.business_id = :businessId
				AND u.id = :id
		";

		$st = $this->db->createCommand($sql);
		$st->bindParam(':id', $this->Id);
		$st->bindParam(':businessId', $this->businessId);
		$st->bindParam(':officeTimezone', $this->officeTimezone);
		$st->bindParam(':fullDatetimeDisplayDefault', Yii::$app->params['fullDatetimeDisplayDefault']);
		// $this->dd($st->getRawSql());
		$result = $st->queryOne();

		return $result;
	}

    public function insertAdmin()
	{	
		$randomPassword = Helper::generateRandomPassword(10);
		$password = password_hash($randomPassword, PASSWORD_DEFAULT);
		
		$sql = "
			SELECT
				* 
			FROM 
				sp_user_admin_insert
				(
					in_user_id			=> :userId,
					in_password 		=> :password,
					in_first_name		=> :firstName,
					in_last_name		=> :lastName,
					in_gender			=> :gender,
					in_birthdate		=> TO_DATE(:birthdate, 'DD/MM/YYYY'),
					in_email			=> :email,
					in_phone			=> :phone,
					in_group			=> :group,
					in_office			=> :office,
                    in_create_by        => :userUid,
                    in_owner_id         => :businessId
				);
		";

		$st = $this->db->createCommand($sql);
		$st->bindParam(':userId', $this->userId);
		$st->bindParam(':password', $password);
		$st->bindParam(':firstName', $this->firstName);
		$st->bindParam(':lastName', $this->lastName);
		$st->bindParam(':gender', $this->gender);
		$st->bindParam(':birthdate', $this->birthdate);
		$st->bindParam(':email', $this->email);
		$st->bindParam(':phone', $this->phone);
		$st->bindParam(':group', $this->group);
		$st->bindParam(':office', $this->office);
		$st->bindParam(':userUid', $this->userUid);
		$st->bindParam(':businessId', $this->businessId);
		// $this->dd($st->getRawSql());
		$result = $st->queryOne();

		return [
			'errNum' => $result['out_num'],
			'errStr' => $result['out_str']
		];
	}

	public function updateAdmin()
	{
		$sql = "
			SELECT
				* 
			FROM 
				sp_user_admin_update
				(
					in_id 				=> :Id,
					in_first_name		=> :firstName,
					in_last_name		=> :lastName,
					in_gender			=> :gender,
					in_birthdate		=> TO_DATE(:birthdate, 'DD/MM/YYYY'),
					in_email			=> :email,
					in_phone			=> :phone,
					in_group			=> :group,
					in_office			=> :office,
                    in_create_by        => :userUid,
                    in_owner_id         => :businessId
				);
		";

		$st = $this->db->createCommand($sql);
		$st->bindParam(':Id', $this->Id);
		$st->bindParam(':firstName', $this->firstName);
		$st->bindParam(':lastName', $this->lastName);
		$st->bindParam(':gender', $this->gender);
		$st->bindParam(':birthdate', $this->birthdate);
		$st->bindParam(':email', $this->email);
		$st->bindParam(':phone', $this->phone);
		$st->bindParam(':group', $this->group);
		$st->bindParam(':office', $this->office);
		$st->bindParam(':userUid', $this->userUid);
		$st->bindParam(':businessId', $this->businessId);
		// $this->dd($st->getRawSql());
		$result = $st->queryOne();

		return [
			'errNum' => $result['out_num'],
			'errStr' => $result['out_str']
		];
	}

	public function updateStatusAdmin()
	{
		$sql = "
			SELECT
				* 
			FROM 
				sp_user_status_update
				(
					in_id 			=> :Id,
					in_status 		=> :status,
					in_create_by 	=> :userUid,
					in_owner_id 	=> :businessId
				);
		";

		$st = $this->db->createCommand($sql);
		$st->bindParam(':Id', $this->Id);
		$st->bindParam(':status', $this->status);
		$st->bindParam(':userUid', $this->userUid);
		$st->bindParam(':businessId', $this->businessId);
		// $this->dd($st->getRawSql());
		$result = $st->queryOne();

		return [
			'errNum' => $result['out_num'],
			'errStr' => $result['out_str']
		];
	}
}