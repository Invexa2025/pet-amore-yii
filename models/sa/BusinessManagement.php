<?php

namespace app\models\sa;

use Yii;
use PDO;
use app\extensions\XModel;

class BusinessManagement extends XModel
{
    public $businessId;

	// Business Detail
	public $businessName;
	public $businessDomain;

	// Business Admin
	public $businessAdminId; // business admin increment id
	public $businessUserId;
	public $businessUserFirstName;
	public $businessUserLastName;
	public $businessUserGender;
	public $businessUserBirthdate;
	public $businessUserEmail;
	public $businessUserPhone;

	// Business Office
	public $businessOfficeId;
	public $businessOfficeCode;
	public $businessOfficeName;
	public $businessOfficeCountry;
	public $businessOfficeCity;
	public $businessOfficeAddress;
	public $businessOfficePhone;
	public $businessOfficeFax;

	// Business App
	public $businessApps;

	// Global Variables
	public $globalVariables;

    public function rules()
	{
		return [
			// Get business detail
			['businessId', 'required', 'on' => ['get-business-detail']], 

			// Insert business management
			[['businessName', 'businessDomain', 'businessUserId', 'businessUserFirstName', 'businessUserLastName', 'businessUserGender', 'businessUserBirthdate', 'businessUserEmail', 'businessUserPhone', 'businessOfficeName', 'businessOfficeCode', 'businessOfficeCountry', 'businessOfficeCity', 'businessOfficeAddress', 'businessOfficePhone', 'businessOfficeFax'], 'required', 'on' => ['insert-business-management']],
			
			// Update business management
			[['businessId', 'businessName', 'businessDomain'], 'required', 'on' => ['update-business-management']],

			// Update business admin
			[['businessAdminId', 'businessUserFirstName', 'businessUserLastName', 'businessUserGender', 'businessUserBirthdate', 'businessUserEmail', 'businessUserPhone'], 'required', 'on' => ['update-business-admin']],

			// Update business office
			[['businessId', 'businessOfficeName', 'businessOfficeCode', 'businessOfficeCountry', 'businessOfficeCity', 'businessOfficeAddress', 'businessOfficePhone', 'businessOfficeFax'], 'required', 'on' => ['update-business-office']],
			
			// Update business global variables
			[['businessId', 'globalVariables'], 'required', 'on' => ['update-business-global-variables']],
		];
	}

    public function getBusinessList($isPagination)
    {
        $where = '';
		$params = [];

		if ($this->search) {	
			if (!is_array($this->search)) {
				$params[':search'] = '%' . strtoupper($this->search) . '%';
				$where .= "AND b.name LIKE :search\n";
			} else {
				[
					$searchName, 
                    $searchDomain,
					$searchAdminPhone,
					$searchAdminUserId,
					$searchAdminName,
				] = array_pad($this->search, 5, null);
				
				if (!empty($searchName)) {
					$params[':name'] = '%' . strtoupper($searchName) . '%';
					$where .= "AND b.name LIKE :name\n";
				}

                if (!empty($searchDomain)) {
					$params[':domain'] = '%' . strtoupper($searchDomain) . '%';
					$where .= "AND b.domain LIKE :domain\n";
				}

				if (!empty($searchAdminPhone)) {
					$params[':phone'] = $searchAdminPhone;
					$where .= "AND u.phone LIKE :phone\n";
				}

				if (!empty($searchAdminUserId)) {
					$params[':user_id'] = strtoupper($searchAdminUserId);
					$where .= "AND u.user_id LIKE :user_id\n";
				}

				if (!empty($searchAdminName)) {
					$params[':name'] = '%' . strtoupper($searchAdminName) . '%';
					$where .= "AND (u.first_name || ' ' || u.last_name) LIKE :name\n";
				}
			}
		}

        $sql = "
			SELECT
				b.id,
                b.name,
                b.domain,
                u.user_id,
                b.status,
                u.first_name || ' ' || u.last_name admin_name,
                u.phone admin_phone,
                u.email admin_email,
                o.name office_name,
                o.code office_code
			FROM
                businesses b
                INNER JOIN users u ON
                    u.id = b.admin_id
                INNER JOIN offices o ON
                    o.id = u.office_id
			WHERE
				b.status = 1
				$where
				--AND b.id NOT IN (SELECT u.business_id FROM users WHERE u.role_type = 'SSA')
		";

		$st = $this->db->createCommand($sql);
		$this->bindParams($st, $params ?? []);

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

    public function getBusinessDetailById()
    {
		$sql = "
			SELECT
				b.id business_id,
                b.name business_name,
                b.domain business_domain,
				u.id admin_id,
                u.user_id,
                b.status business_status,
				u.first_name,
				u.last_name,
				u.gender,
                u.first_name || ' ' || u.last_name admin_name,
				TO_CHAR(u.birthdate, 'DD/MM/YYYY') birthdate,
                u.phone admin_phone,
                u.email admin_email,
				o.id office_id,
                o.name office_name,
                o.code office_code,
				o.phone office_phone,
				o.fax office_fax,
				ao.address office_address,
				ao.country_code office_country_code,
				ao.city_code office_city_code
			FROM
                businesses b
                INNER JOIN users u ON
				u.id = b.admin_id
                INNER JOIN offices o ON
                    o.id = u.office_id
				INNER JOIN addresses ao ON
					ao.id = o.address_id
			WHERE
				b.id = :Id
				--AND b.id NOT IN (SELECT u.business_id FROM users WHERE u.role_type = 'SSA')
		";

		$st = $this->db->createCommand($sql);
		$st->bindParam(':Id', $this->businessId);
		// $this->dd($st->getRawSql());
		$result = $st->queryOne();

		return $result;
    }

	public function insertBusiness()
	{
		$sql = "
			SELECT
				*
			FROM
				sp_business_insert
				(
					in_business_name                => :businessName,
					in_business_domain              => :businessDomain,
					in_business_user_id             => :businessUserId,
					in_business_user_first_name     => :businessUserFirstName,
					in_business_user_last_name      => :businessUserLastName,
					in_business_user_gender         => :businessUserGender,
					in_business_user_birthdate      => TO_DATE(:businessUserBirthdate, 'DDMMYYYY'),
					in_business_user_email          => :businessUserEmail,
					in_business_user_phone          => :businessUserPhone,
					in_business_office_code         => :businessOfficeCode,
					in_business_office_name         => :businessOfficeName,
					in_business_office_address      => :businessOfficeAddress,
					in_business_office_country      => :businessOfficeCountry,
					in_business_office_city         => :businessOfficeCity,
					in_business_office_phone        => :businessOfficePhone,
					in_business_office_fax          => :businessOfficeFax,
					in_contact_id                  	=> :userUid
				)
		";

		$st = $this->db->createCommand($sql);
		$st->bindParam(':businessName', $this->businessName);
		$st->bindParam(':businessDomain', $this->businessDomain);
		$st->bindParam(':businessUserId', $this->businessUserId);
		$st->bindParam(':businessUserFirstName', $this->businessUserFirstName);
		$st->bindParam(':businessUserLastName', $this->businessUserLastName);
		$st->bindParam(':businessUserGender', $this->businessUserGender);
		$st->bindParam(':businessUserBirthdate', $this->businessUserBirthdate);
		$st->bindParam(':businessUserEmail', $this->businessUserEmail);
		$st->bindParam(':businessUserPhone', $this->businessUserPhone);
		$st->bindParam(':businessOfficeCode', $this->businessOfficeCode);
		$st->bindParam(':businessOfficeName', $this->businessOfficeName);
		$st->bindParam(':businessOfficeAddress', $this->businessOfficeAddress);
		$st->bindParam(':businessOfficeCountry', $this->businessOfficeCountry);
		$st->bindParam(':businessOfficeCity', $this->businessOfficeCity);
		$st->bindParam(':businessOfficePhone', $this->businessOfficePhone);
		$st->bindParam(':businessOfficeFax', $this->businessOfficeFax);
		$st->bindParam(':userUid', $this->userUid);
		$result = $st->queryOne();

		return [
			'errNum' => $result['out_num'], 
			'errStr' => $result['out_str']
		];
	}

	public function updateBusiness()
	{
		$sql = "
			SELECT 
				*
			FROM
				sp_business_update
				(
					in_business_id		=> :businessId,
					in_business_name	=> :businessName,
					in_business_domain	=> :businessDomain,
					in_contact_id		=> :userUid
				)
		";

		$st = $this->db->createCommand($sql);
		$st->bindParam(':businessId', $this->businessId);
		$st->bindParam(':businessName', $this->businessName);
		$st->bindParam(':businessDomain', $this->businessDomain);
		$st->bindParam(':userUid', $this->userUid);
		// $this->dd($st->getRawSql());
		$result = $st->queryOne();

		return [
			'errNum' => $result['out_num'], 
			'errStr' => $result['out_str']
		];
	}

	public function updateBusinessAdmin()
	{
		$sql = "
			SELECT
				*
			FROM
				sp_user_admin_update
				(
					in_id			=> :businessAdminId,
					in_first_name   => :businessUserFirstName,
					in_last_name    => :businessUserLastName,
					in_gender       => :businessUserGender,
					in_birthdate    => :businessUserBirthdate,
					in_email        => :businessUserEmail,
					in_phone        => :businessUserPhone,
					in_group		=> NULL,
					in_office		=> NULL,
					in_create_by	=> :userUid,
					in_owner_id		=> NULL
				)
		";

		$st = $this->db->createCommand($sql);
		$st->bindParam(':businessAdminId', $this->businessAdminId);
		$st->bindParam(':businessUserFirstName', $this->businessUserFirstName);
		$st->bindParam(':businessUserLastName', $this->businessUserLastName);
		$st->bindParam(':businessUserGender', $this->businessUserGender);
		$st->bindParam(':businessUserBirthdate', $this->businessUserBirthdate);
		$st->bindParam(':businessUserEmail', $this->businessUserEmail);
		$st->bindParam(':businessUserPhone', $this->businessUserPhone);
		$st->bindParam(':userUid', $this->userUid);
		$result = $st->queryOne();

		return [
			'errNum' => $result['out_num'], 
			'errStr' => $result['out_str']
		];
	}

	public function updateBusinessOffice()
	{
		$sql = "
			SELECT
				*
			FROM
				sp_office_update
				(
					in_office_id		=> :businessOfficeId, 			
					in_office_code      => :businessOfficeCode,
					in_office_name      => :businessOfficeName,
					in_country_code   	=> :businessOfficeCountry,
					in_city_code      	=> :businessOfficeCity,
					in_address   		=> :businessOfficeAddress,
					in_phone     		=> :businessOfficePhone,
					in_fax       		=> :businessOfficeFax,
					in_create_by		=> :userUid,
					in_owner_id			=> :businessId
				)
		";

		$st = $this->db->createCommand($sql);
		$st->bindParam(':businessId', $this->businessId);
		$st->bindParam(':businessOfficeId', $this->businessOfficeId);
		$st->bindParam(':businessOfficeCode', $this->businessOfficeCode);
		$st->bindParam(':businessOfficeName', $this->businessOfficeName);
		$st->bindParam(':businessOfficeAddress', $this->businessOfficeAddress);
		$st->bindParam(':businessOfficeCountry', $this->businessOfficeCountry);
		$st->bindParam(':businessOfficeCity', $this->businessOfficeCity);
		$st->bindParam(':businessOfficePhone', $this->businessOfficePhone);
		$st->bindParam(':businessOfficeFax', $this->businessOfficeFax);
		$st->bindParam(':userUid', $this->userUid);
		// $this->dd($st->getRawSql());
		$result = $st->queryOne();

		return [
			'errNum' => $result['out_num'], 
			'errStr' => $result['out_str']
		];
	}

	public function updateBusinessApps()
	{
		$sql = "
			SELECT 
				*
			FROM
				sp_update_business_apps
				(
				
				
				)
		";

		$st = $this->db->createCommand($sql);
		$st->bindParam(':businessApps', $this->businessApps);
		$result = $st->queryOne();

		return $result;
	}
	
	public function updateGlobalVariables()
	{
		$sql = "
			SELECT 
				*
			FROM
				sp_business_global_variables_insert
				(
					in_contact_id		=> :userUid,
					in_owner_id			=> :businessId,
					in_global_variables	=> :globalVariables
				)
		";

		$st = $this->db->createCommand($sql);
		$st->bindParam(':userUid', $this->userUid);
		$st->bindParam(':businessId', $this->businessId);
		$st->bindParam(':globalVariables', $this->globalVariables);
		$result = $st->queryOne();

		return [
			'errNum' => $result['out_num'],
			'errStr' => $result['out_str']
		];
	}
}