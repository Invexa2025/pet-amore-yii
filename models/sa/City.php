<?php

namespace app\models\sa;

use Yii;
use PDO;
use app\extensions\XModel;

class City extends XModel
{
	public $cityCode;
	public $cityName;
    public $cityCountryCode;
    public $cityTimezone;

    public function rules()
	{
		return [
			[['cityCode', 'cityName', 'cityCountryCode', 'cityTimezone'], 'required', 'on' => ['insert-city', 'update-city']],
			[['cityCode'], 'required', 'on' => ['get-city-detail-by-code']]
        ];
    }

    public function getCityList($isPagination, $orderColumn = [], $sortDirection = [])
    {
		$where = '';
		$params = [];

		if ($this->search) {	
			if (!is_array($this->search)) {
				$params[':search'] = '%' . strtoupper($this->search) . '%';
				$where .= "AND c.code LIKE :search\n";
			} else {
				[
					$searchCityCode, 
                    $searchCityName,
					$searchCityCountryCode, 
					$searchCityTimezone
				] = array_pad($this->search, 4, null);
				
				if (!empty($searchCityCode)) {
					$params[':code'] = '%' . strtoupper($searchCityCode) . '%';
					$where .= "AND c.code LIKE :code\n";
				}

                if (!empty($searchCityName)) {
					$params[':name'] = '%' . strtoupper($searchCityName) . '%';
					$where .= "AND c.name LIKE :name\n";
				}
				
				if (!empty($searchCityCountryCode)) {
					$params[':country_code'] = strtoupper($searchCityCountryCode);
					$where .= "AND c.country_code LIKE :country_code\n";
				}

                if (!empty($searchCityTimezone)) {
                    $params[':timezone'] = $searchCityTimezone;
                    $where .= "AND c.timezone LIKE :timezone\n";
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
				c.code,
				c.name,
				c.country_code,
				c.timezone
			FROM
				cities c
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

	public function getTimezoneList()
	{
		$sql = "
			SELECT
				name,
				abbrev,
				to_char(utc_offset,'hh24.mm') utc,
				is_dst
			FROM 
				pg_timezone_names
			WHERE
				name NOT LIKE ('%posix%')
			ORDER BY 
				utc_offset
		";
		
		$st = $this->db->createCommand($sql);
		$result = $st->queryAll();

		return $result;
	}

	public function getCityDetailByCode()
	{
		$sql = "
			SELECT
				c.code,
				c.name,
				c.country_code,
				c.timezone
			FROM
				cities c
			WHERE
				c.status = 1
				AND c.code = :cityCode
		";

		$st = $this->db->createCommand($sql);
		$st->bindParam(':cityCode', $this->cityCode);
		// $this->dd($st->getRawSql());
		$result = $st->queryOne();

		return $result;
	}

    public function insertCity()
	{	
		$sql = "
			SELECT
				* 
			FROM 
				sp_city_insert
				(
					in_code			    => :cityCode,
					in_name		        => :cityName,
                    in_country_code     => :cityCountryCode,
					in_timezone       	=> :cityTimezone,
					in_create_by	    => :userUid,
					in_owner_id		    => :businessId
				);
		";

		$st = $this->db->createCommand($sql);
		$st->bindParam(':cityCode', $this->cityCode);
		$st->bindParam(':cityName', $this->cityName);
		$st->bindParam(':cityCountryCode', $this->cityCountryCode);
		$st->bindParam(':cityTimezone', $this->cityTimezone);
		$st->bindParam(':userUid', $this->userUid);
		$st->bindParam(':businessId', $this->businessId);
		// $this->dd($st->getRawSql());
		$result = $st->queryOne();

		return [
			'errNum' => $result['out_num'],
			'errStr' => $result['out_str']
		];
	}

	public function updateCity()
	{
		$sql = "
			SELECT
				* 
			FROM 
				sp_city_update
				(
					in_code			    => :cityCode,
					in_name		        => :cityName,
                    in_country_code     => :cityCountryCode,
					in_timezone       	=> :cityTimezone,
					in_create_by	    => :userUid,
					in_owner_id		    => :businessId
				);
		";

		$st = $this->db->createCommand($sql);
		$st->bindParam(':cityCode', $this->cityCode);
		$st->bindParam(':cityName', $this->cityName);
		$st->bindParam(':cityCountryCode', $this->cityCountryCode);
		$st->bindParam(':cityTimezone', $this->cityTimezone);
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