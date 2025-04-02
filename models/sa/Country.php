<?php

namespace app\models\sa;

use Yii;
use PDO;
use app\extensions\XModel;

class Country extends XModel
{
	public $countryCode2;
	public $countryCode3;
	public $countryName;
    public $ccyCode;
    public $countryPhoneCode;

    public function rules()
	{
		return [
			[['countryCode2', 'countryCode3', 'countryName', 'ccyCode'], 'required', 'on' => ['insert-country', 'update-country']],
			[['countryCode2'], 'required', 'on' => ['get-country-detail-by-code']]
        ];
    }

    public function getCountryList($isPagination, $orderColumn = [], $sortDirection = [])
    {
		$where = '';
		$params = [];

		if ($this->search) {	
			if (!is_array($this->search)) {
				$params[':search'] = '%' . strtoupper($this->search) . '%';
				$where .= "AND c.code_2 LIKE :search\n";
			} else {
				[
					$searchCountryCode2, 
                    $searchCountryCode3,
					$searchCountryName, 
                    $searchCountryCcy, 
					$searchCountryPhoneCode,
				] = array_pad($this->search, 5, null);
				
				if (!empty($searchCountryCode2)) {
					$params[':code_2'] = '%' . strtoupper($searchCountryCode2) . '%';
					$where .= "AND c.code_2 LIKE :code_2\n";
				}

                if (!empty($searchCountryCode3)) {
					$params[':code_3'] = '%' . strtoupper($searchCountryCode3) . '%';
					$where .= "AND c.code_3 LIKE :code_3\n";
				}
				
				if (!empty($searchCountryName)) {
					$params[':name'] = '%' . strtoupper($searchCountryName) . '%';
					$where .= "AND c.name LIKE :name\n";
				}

                if (!empty($searchCountryCcy)) {
                    $params[':ccy'] = strtoupper($searchCountryCcy);
                    $where .= "AND c.ccy LIKE :ccy\n";
                }
                
                if (!empty($searchCountryPhoneCode)) {
                    $params[':phoneCode'] = '%' . ($searchCountryPhoneCode) . '%';
                    $where .= "AND c.phone_code LIKE :phoneCode\n";
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
				c.code_2,
                c.code_3,
				c.name,
                c.ccy,
				c.phone_code
			FROM
				countries c
			WHERE
				c.status = 1
				$where
			$orderBy
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

	public function getCountryDetailByCode()
	{
		$sql = "
			SELECT
				c.code_2,
                c.code_3,
				c.name,
                c.ccy,
				c.phone_code
			FROM
				countries c
			WHERE
				c.status = 1
				AND c.code_2 = :countryCode2
		";

		$st = $this->db->createCommand($sql);
		$st->bindParam(':countryCode2', $this->countryCode2);
		// $this->dd($st->getRawSql());
		$result = $st->queryOne();

		return $result;
	}

    public function insertCountry()
	{	
		$sql = "
			SELECT
				* 
			FROM 
				sp_country_insert
				(
					in_code_2			=> :countryCode2,
                    in_code_3			=> :countryCode3,
					in_name		        => :countryName,
                    in_ccy_code         => :ccyCode,
					in_phone_code       => :countryPhoneCode,
					in_create_by	    => :userUid,
					in_owner_id		    => :businessId
				);
		";

		$st = $this->db->createCommand($sql);
		$st->bindParam(':countryCode2', $this->countryCode2);
		$st->bindParam(':countryCode3', $this->countryCode3);
		$st->bindParam(':countryName', $this->countryName);
        $st->bindParam(':ccyCode', $this->ccyCode);
        $st->bindParam(':countryPhoneCode', $this->countryPhoneCode);
		$st->bindParam(':userUid', $this->userUid);
		$st->bindParam(':businessId', $this->businessId);
		// $this->dd($st->getRawSql());
		$result = $st->queryOne();

		return [
			'errNum' => $result['out_num'],
			'errStr' => $result['out_str']
		];
	}

	public function updateCountry()
	{
		$sql = "
			SELECT
				* 
			FROM 
				sp_country_update
				(
					in_code_2			=> :countryCode2,
                    in_code_3			=> :countryCode3,
					in_name		        => :countryName,
                    in_ccy_code         => :ccyCode,
					in_phone_code       => :countryPhoneCode,
					in_create_by	    => :userUid,
					in_owner_id		    => :businessId
				);
		";

		$st = $this->db->createCommand($sql);
		$st->bindParam(':countryCode2', $this->countryCode2);
		$st->bindParam(':countryCode3', $this->countryCode3);
		$st->bindParam(':countryName', $this->countryName);
        $st->bindParam(':ccyCode', $this->ccyCode);
        $st->bindParam(':countryPhoneCode', $this->countryPhoneCode);
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