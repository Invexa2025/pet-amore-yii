<?php

namespace app\models;

use Yii;
use app\extensions\XModel;

class Login extends XModel
{
	public $userId;
    public $email;
    public $password;

    public function attributeLabels()
    {
        return [
            
        ];
    }

    public function rules()
	{
		return [
			[['userId', 'password'], 'required', 'on' => ['login']],
		];
	}

    public function tryLogin()
	{
		// Login Error Message List :
		// 1000001 - User input wrong username.
		// 1000002 - User input wrong password.
		// 1000003 - Domain not registered on database
		// 1000004 - User is locked.

		$userId = explode('@', strtoupper($this->userId));
        $password = $this->password;
        $ipAddr = \Yii::$app->request->userIp;
		$serverName = \Yii::$app->request->serverName;

		$sql = "
			SELECT
				b.id,
				b.name,
				b.domain
			FROM
				businesses b
			WHERE
				b.domain = :domain
				AND b.status = 1
		";

		$st = $this->db->createCommand($sql);
		$st->bindParam(':domain', $userId[1]);
        $result = $st->queryOne();

		if (!$result)
		{
			return [
				'errNum' => 1000003,
				'errStr' => Yii::t('app', 'Invalid login attempt, please contact your administrator if you find this unusual')
			];
		}


        $sql = "
			SELECT
				u.id AS user_uid,
				u.office_id,
				fn_get_office_code(u.office_id) office_code,
				fn_get_office_name(u.office_id) office_name,
				u.user_id,
                u.first_name,
                u.last_name,
                u.email,
				u.password,
				u.role_type,
				u.status,
				b.id AS business_id,
				b.name AS business_name
			FROM
				users u
				INNER JOIN businesses b ON
                    b.id = u.business_id
			WHERE
				u.user_id = UPPER(:userId)
				AND u.status > 0
		";

		$st = $this->db->createCommand($sql);
        $st->bindParam(':userId', $userId[0]);
		$data = $st->queryOne();

        if (empty($data))
		{
			return [
				'errNum' => 1000001,
				'errStr' => Yii::t('app', 'Invalid login attempt, please contact your administrator if you find this unusual')
			];
		}
		else if ($data['status'] == 2)
		{
			return [
				'errNum' => 1000004,
				'errStr' => Yii::t('app', 'This user is locked. Please contact your administrator')
			];
		}
        else if (password_verify($password, $data['password']) != true)
		{
			$sql = "
				SELECT
					* 
				FROM 
					sp_log_admin_access_insert
					(
						in_user_id 		=> :userUid,
						in_ip_address 	=> :ipAddress,
						in_activity 	=> 'Login Failed',
						in_owner_id 	=> :businessId,
						in_ref_code     => '1000002',
						in_type 		=> 'password'
					);
			";
			$st = $this->db->createCommand($sql);
			$st->bindParam(':userUid', $data['user_uid']);
			$st->bindParam(':ipAddress', $ipAddr);
			$st->bindParam(':businessId', $data['business_id']);
			$result = $st->queryOne();
			
            return [
				'errNum' => 1000002,
				'errStr' => Yii::t('app', 'Invalid login attempt, please contact your administrator if you find this unusual')
			];
        }

		$sql = "
			SELECT
				* 
			FROM 
				sp_log_admin_access_insert
				(
					in_user_id 		=> :userUid,
					in_ip_address 	=> :ipAddress,
					in_activity 	=> 'Login Success',
					in_owner_id 	=> :businessId
				);
		";
		$st = $this->db->createCommand($sql);
		$st->bindParam(':userUid', $data['user_uid']);
		$st->bindParam(':ipAddress', $ipAddr);
		$st->bindParam(':businessId', $data['business_id']);
		$result = $st->queryOne();

		$this->userUid = $data['user_uid'];
		$this->businessId = $data['business_id'];
		$this->roleType = $data['role_type'];

		$officeTimeZone = Yii::$app->params['defaultTimezone'];
		$officeUtc 		= Yii::$app->params['defaultUtc'];

		$sql = "
			WITH tz AS (
				SELECT * FROM pg_timezone_names
			)
			SELECT
				tz.name,
				tz.abbrev,
				(
					SPLIT_PART(tz.utc_offset::TEXT, ':', 1)::integer +
					SPLIT_PART(tz.utc_offset::TEXT, ':', 2)::integer / 60
				) utc_nbr,
				tz.utc_offset,
				tz.is_dst
			FROM
				offices o
				INNER JOIN addresses a ON
					a.id = o.address_id
				INNER JOIN cities c ON
					c.code = a.city_code
				INNER JOIN tz ON tz.name = c.timezone
			WHERE
				o.id  = :officeId
		";
		
		$st = $this->db->createCommand($sql);
		$st->bindParam(':officeId' , $data['office_id']);
		// $this->dd($st->getRawSql());
		$timezone = $st->queryOne();

		if (!is_bool($timezone))
		{
			$officeTimeZone = $timezone['name'];
			$officeUtc 		= $timezone['utc_nbr'];
		}
		
		$allPrivilege = $this->getAllApp();
		$privilege = $this->getPrivilege();

        $this->setSession([
			'USER_UID' 				=> $data['user_uid'],
			'USER_ID' 				=> $data['user_id'],
			'OFFICE_ID' 			=> $data['office_id'],
			'OFFICE_CODE' 			=> $data['office_code'],
			'OFFICE_NAME' 			=> $data['office_name'],
			'OFFICE_TIMEZONE' 		=> $officeTimeZone,
			'OFFICE_UTC' 			=> $officeUtc,
			'FIRST_NAME' 			=> $data['first_name'],
			'LAST_NAME' 			=> $data['last_name'],
			'ROLE_TYPE' 			=> $data['role_type'],
			'BUSINESS_ID' 			=> $data['business_id'],
			'BUSINESS_NAME' 		=> $data['business_name'],
			'EMAIL' 				=> $data['email'],
			'SERVER_TIMEZONE'   	=> Yii::$app->params['defaultTimezone'],
			'SERVER_UTC' 			=> Yii::$app->params['defaultUtc'],
			'IS_LOGIN' 				=> 1,
			'APPS' 					=> $privilege,
			'ALL_APPS'      		=> $allPrivilege,
		]);

        return [
            'errNum' => 0,
            'errStr' => Yii::t('app', 'Success')
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

	public function getPrivilege()
	{
		$privilege = [];
		$sql = '';

		if ($this->roleType == "SSA")
		{
			$sql = "
				SELECT
					a.code app_code,
					a.parent_code,
					a.name,
					a.url,
					a.is_menu,
					a.app_order
				FROM
					apps a
				WHERE
					a.status = 1
					AND a.code ILIKE 'ADMSA%'
				ORDER BY
					a.app_order
			";
		}
		else if ($this->roleType == "SA")
		{
			$sql = "
				SELECT DISTINCT
					a.code app_code,
					a.parent_code,
					a.name,
					a.url,
					a.is_menu,
					a.app_order
				FROM
					apps a
					INNER JOIN group_apps ga ON
						ga.app_code = a.code
						AND ga.status = 1
					INNER JOIN groups g ON
						g.id = ga.group_id
						AND g.status = 1
				WHERE
					a.status = 1
					AND ga.owner_id = :businessId
					AND g.id = ANY((SELECT u.group_id FROM users u WHERE u.id = :userUid AND u.status = 1)::INT[])
				ORDER BY
					a.app_order
			";
		}
		else if ($this->roleType == "BSA")
		{
			$sql = "
				SELECT DISTINCT
					a.code app_code,
					a.parent_code,
					a.name,
					a.url,
					a.is_menu,
					a.app_order
				FROM
					apps a
					INNER JOIN business_apps ba ON
						ba.app_code = a.code
				WHERE
					a.status = 1
					AND a.code NOT ILIKE 'ADMSA%'
					AND ba.owner_id = :businessId
				ORDER BY
					a.app_order
			";
		}
		else if ($this->roleType == "BU")
		{
			$sql = "
				SELECT DISTINCT
					a.code app_code,
					a.parent_code,
					COALESCE(fn_get_custom_app_name(a.code, ba.owner_id) name,
					a.url,
					a.is_menu,
					a.app_order
				FROM
					apps a
					INNER JOIN business_apps ba ON
						ba.app_code = a.code
						AND ba.status = 1
					INNER JOIN group_apps ga ON
						ga.app_code = a.code
						AND ga.status = 1
						AND ga.owner_id = ba.owner_id
					INNER JOIN groups g ON
						g.id = ga.group_id
						AND g.status = 1
				WHERE
					a.status = 1
					AND a.code NOT ILIKE 'ADMSA%'
					AND ba.owner_id = :businessId
					AND g.id = ANY((SELECT u.group_id FROM users u WHERE u.id = :userUid AND u.status = 1)::INT[])
				ORDER BY
					a.app_order
			";
		}
		else
		{
			return $privilege;
		}

		$st = $this->db->createCommand($sql);

		if ($this->roleType == "SA" || $this->roleType == "BSA")
		{
			$st->bindParam(':userUid', $this->userUid);
			$st->bindParam(':businessId', $this->businessId);
		}
		else if ($this->roleType == "CSA")
		{
			$st->bindParam(':userUid', $this->userUid);
			$st->bindParam(':businessId', $this->businessId);
		}

		// $this->dd($st->getRawSql());
		$privilege = $st->queryAll();

		for ($i = 0; $i < sizeof($privilege); $i++)
		{
			$privilege[$i]['name'] = Yii::t('app', $privilege[$i]['name']);
		}

		return $privilege;
	}

	public function reloadSession()
	{
		$userUid 	= $this->getSession('USER_UID');
		$businessId = $this->getSession('BUSINESS_ID');

		$sql = "
			SELECT
				u.id AS user_uid,
				u.office_id,
				fn_get_office_code(u.office_id) office_code,
				fn_get_office_name(u.office_id) office_name,
				u.user_id,
                u.first_name,
                u.last_name,
                u.email,
				u.password,
				u.role_type,
				b.id AS business_id,
				b.name AS business_name
			FROM
				users u
				INNER JOIN businesses b ON
                    b.id = u.business_id
			WHERE
				u.id = :userUid
				AND u.status > 0
				AND b.id = :businessId
		";

		$st = $this->db->createCommand($sql);
        $st->bindParam(':userUid', $userUid);
		$st->bindParam(':businessId', $businessId);
		$data = $st->queryOne();

		if (empty($data))
		{
			return [
				'errNum' => 1000001,
				'errStr' => Yii::t('app', 'Invalid login attempt, please contact your administrator if you find this unusual')
			];
		}

		$this->userUid = $data['user_uid'];
		$this->businessId = $data['business_id'];
		$this->roleType = $data['role_type'];

		$officeTimeZone = Yii::$app->params['defaultTimezone'];
		$officeUtc 		= Yii::$app->params['defaultUtc'];

		$sql = "
			WITH tz AS (
				SELECT * FROM pg_timezone_names
			)
			SELECT
				tz.name,
				tz.abbrev,
				(
					SPLIT_PART(tz.utc_offset::TEXT, ':', 1)::integer +
					SPLIT_PART(tz.utc_offset::TEXT, ':', 2)::integer / 60
				) utc_nbr,
				tz.utc_offset,
				tz.is_dst
			FROM
				offices o
				INNER JOIN addresses a ON
					a.id = o.address_id
				INNER JOIN cities c ON
					c.code = a.city_code
				INNER JOIN tz ON tz.name = c.timezone
			WHERE
				o.id  = :officeId
		";
		
		$st = $this->db->createCommand($sql);
		$st->bindParam(':officeId' , $data['office_id']);
		// $this->dd($st->getRawSql());
		$timezone = $st->queryOne();

		if (!is_bool($timezone))
		{
			$officeTimeZone = $timezone['name'];
			$officeUtc 		= $timezone['utc_nbr'];
		}

		$data['SERVER_TIMEZONE']    = Yii::$app->params['defaultTimezone'];
		$data['SERVER_UTC'] 	 	= Yii::$app->params['defaultUtc'];
		$data['OFFICE_TIMEZONE'] 	= $officeTimeZone;
		$data['OFFICE_UTC'] 		= $officeUtc;

		$allPrivilege = $this->getAllApp();
		$privilege = $this->getPrivilege();

		return [
			'errNum' 			=> 0,
			'errStr' 			=> '',
			'data' 				=> $data,
			'allPrivilege' 		=> $allPrivilege,
			'privilege' 		=> $privilege
		];
	}
}