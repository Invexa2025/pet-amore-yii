<?php

namespace app\models\sa;

use Yii;
use PDO;
use app\extensions\XModel;

class BusinessManagement extends XModel
{
    public $Id;

    public function attributeLabels()
    {
        return [
            ['Id', 'required', 'on' => ['get-business-detail']]        
        ];
    }

    public function rules()
	{
		return [
			
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
				ab.address business_address,
				ab.country_code business_country_code,
				ab.city_code business_city_code,
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
				INNER JOIN addresses ab ON
					ab.id = b.address_id
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
		$st->bindParam(':Id', $this->Id);
		// $this->dd($st->getRawSql());
		$result = $st->queryOne();

		return $result;
    }

	public function insertBusiness()
	{

	}

	public function updateBusiness()
	{

	}
}