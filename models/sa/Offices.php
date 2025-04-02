<?php

namespace app\models\sa;

use Yii;
use PDO;
use app\extensions\XModel;

class Offices extends XModel
{
	public $officeId;
	public $officeName;
	public $officeCode;
	public $countryCode;
	public $cityCode;
	public $address;
	public $phone;
	public $fax;

    public function rules()
	{
		return [
			[['officeName', 'officeCode', 'countryCode', 'cityCode'], 'required', 'on' => ['insert-office']],
			[['officeId', 'officeName', 'officeCode', 'countryCode', 'cityCode'], 'required', 'on' => ['update-office']],
			[['officeId'], 'required', 'on' => ['get-office-detail-by-id']]
        ];
    }

    public function getOfficeList($isPagination, $orderColumn = [], $sortDirection = [])
    {
		$where = '';
		$params = [];

		if ($this->search) {	
			if (!is_array($this->search)) {
				$params[':search'] = '%' . strtoupper($this->search) . '%';
				$where .= "AND o.name LIKE :search\n";
			} else {
				[
					$searchOfficeName, 
                    $searchOfficeCode,
				] = array_pad($this->search, 2, null);
				
				if (!empty($searchOfficeName)) {
					$params[':name'] = '%' . strtoupper($searchOfficeName) . '%';
					$where .= "AND o.name LIKE :name\n";
				}

                if (!empty($searchOfficeCode)) {
					$params[':code'] = '%' . $searchOfficeCode . '%';
					$where .= "AND o.code ILIKE :code\n";
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
				o.id,
				o.code,
				o.name,
				country.code_2 country_code,
				city.code city_code,
				city.name as city,
				city.timezone,
				country.ccy,
				a.address,
				o.phone,
				o.fax
			FROM
				offices o
				INNER JOIN addresses a ON
					a.id = o.address_id
					AND a.status = 1
					AND a.owner_id = o.owner_id
				INNER JOIN countries country ON
					country.code_2 = a.country_code
					AND country.status = 1
				INNER JOIN cities city ON
					city.code = a.city_code
					AND city.country_code = country.code_2
					AND city.status = 1
			WHERE
				o.status = 1
				AND o.owner_id = :businessId
				$where
			$orderBy
		";

		$st = $this->db->createCommand($sql);
		$this->bindParams($st, $params ?? []);
		$st->bindParam(':businessId', $this->businessId);

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

	public function getOfficeDetailById()
	{
		$result = [];

		$sql = "
			SELECT
				o.id,
				o.code,
				o.name,
				country.code_2 country_code,
				city.code city_code,
				city.name as city,
				city.timezone,
				country.ccy,
				a.address,
				o.phone,
				o.fax
			FROM
				offices o
				INNER JOIN addresses a ON
					a.id = o.address_id
					AND a.status = 1
					AND a.owner_id = o.owner_id
				INNER JOIN countries country ON
					country.code_2 = a.country_code
					AND country.status = 1
				INNER JOIN cities city ON
					city.code = a.city_code
					AND city.country_code = country.code_2
					AND city.status = 1
			WHERE
				o.id = :officeId
				AND o.status = 1
				AND o.owner_id = :businessId
		";

		$st = $this->db->createCommand($sql);
		$st->bindParam(':officeId', $this->officeId);
		$st->bindParam(':businessId', $this->businessId);
		$result = $st->queryOne();

		return $result;
	}

    public function insertOffice()
	{	
		$sql = "
			SELECT
				* 
			FROM 
				sp_office_insert
				(
					in_office_name 		=> :officeName,
					in_office_code 		=> :officeCode,
					in_country_code 	=> :countryCode,
					in_city_code 		=> :cityCode,
					in_address 			=> :address,
					in_phone 			=> :phone,
					in_fax 				=> :fax,
                    in_create_by        => :userUid,
                    in_owner_id         => :businessId
				);
		";

		$st = $this->db->createCommand($sql);
		$st->bindParam(':officeName', $this->officeName);
		$st->bindParam(':officeCode', $this->officeCode);
		$st->bindParam(':countryCode', $this->countryCode);
		$st->bindParam(':cityCode', $this->cityCode);
		$st->bindParam(':address', $this->address);
		$st->bindParam(':phone', $this->phone);
		$st->bindParam(':fax', $this->fax);
		$st->bindParam(':userUid', $this->userUid);
		$st->bindParam(':businessId', $this->businessId);
		// $this->dd($st->getRawSql());
		$result = $st->queryOne();

		return [
			'errNum' => $result['out_num'],
			'errStr' => $result['out_str']
		];
	}

	public function updateOffice()
	{
		$sql = "
			SELECT
				* 
			FROM 
				sp_office_update
				(
					in_office_id 		=> :officeId,
					in_office_name 		=> :officeName,
					in_office_code 		=> :officeCode,
					in_country_code 	=> :countryCode,
					in_city_code 		=> :cityCode,
					in_address 			=> :address,
					in_phone 			=> :phone,
					in_fax 				=> :fax,
                    in_create_by        => :userUid,
                    in_owner_id         => :businessId
				);
		";

		$st = $this->db->createCommand($sql);
		$st->bindParam(':officeId', $this->officeId);
		$st->bindParam(':officeName', $this->officeName);
		$st->bindParam(':officeCode', $this->officeCode);
		$st->bindParam(':countryCode', $this->countryCode);
		$st->bindParam(':cityCode', $this->cityCode);
		$st->bindParam(':address', $this->address);
		$st->bindParam(':phone', $this->phone);
		$st->bindParam(':fax', $this->fax);
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