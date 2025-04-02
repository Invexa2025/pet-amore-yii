<?php

namespace app\models\sa;

use Yii;
use PDO;
use app\extensions\XModel;

class Currency extends XModel
{
	public $ccyCode;
	public $ccyName;
	public $ccyNumericCode;

    public function rules()
	{
		return [
			[['ccyCode', 'ccyName'], 'required', 'on' => ['insert-ccy', 'update-ccy']],
			[['ccyCode'], 'required', 'on' => ['get-currency-detail-by-code']]
        ];
    }

    public function getCurrencyList($isPagination)
    {
		$where = '';
		$params = [];

		if ($this->search) {	
			if (!is_array($this->search)) {
				$params[':search'] = '%' . strtoupper($this->search) . '%';
				$where .= "AND c.code LIKE :search\n";
			} else {
				[
					$searchCcyCode, 
					$searchCcyName, 
					$searchCcyNumericCode
				] = array_pad($this->search, 3, null);
				
				if (!empty($searchCcyCode)) {
					$params[':code'] = '%' . strtoupper($searchCcyCode) . '%';
					$where .= "AND c.code LIKE :code\n";
				}
				
				if (!empty($searchCcyName)) {
					$params[':name'] = '%' . strtoupper($searchCcyName) . '%';
					$where .= "AND c.name LIKE :name\n";
				}
				
				if (!empty($searchCcyNumericCode)) {
					$params[':numericCode'] = '%' . ($searchCcyNumericCode) . '%';
					$where .= "AND c.numeric_code LIKE :numericCode\n";
				}
			}
		}

        $sql = "
			SELECT
				c.code,
				c.name,
				COALESCE(NULLIF(c.numeric_code, ''), '-') numeric_code
			FROM
				currencies c
			WHERE
				c.status = 1
				$where
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

	public function getCurrencyDetailByCode()
	{
		$sql = "
			SELECT
				c.code,
				c.name,
				COALESCE(NULLIF(c.numeric_code, ''), '-') numeric_code
			FROM
				currencies c
			WHERE
				c.status = 1
				AND c.code = :ccyCode
		";

		$st = $this->db->createCommand($sql);
		$st->bindParam(':ccyCode', $this->ccyCode);
		// $this->dd($st->getRawSql());
		$result = $st->queryOne();

		return $result;
	}

    public function insertCurrency()
	{	
		$sql = "
			SELECT
				* 
			FROM 
				sp_currency_insert
				(
					in_code			=> :ccyCode,
					in_name			=> :ccyName,
					in_numeric_code => :ccyNumericCode,
					in_create_by	=> :userUid,
					in_owner_id		=> :businessId
				);
		";

		$st = $this->db->createCommand($sql);
		$st->bindParam(':ccyCode', $this->ccyCode);
		$st->bindParam(':ccyName', $this->ccyName);
		$st->bindParam(':ccyNumericCode', $this->ccyNumericCode);
		$st->bindParam(':userUid', $this->userUid);
		$st->bindParam(':businessId', $this->businessId);
		// $this->dd($st->getRawSql());
		$result = $st->queryOne();

		return [
			'errNum' => $result['out_num'],
			'errStr' => $result['out_str']
		];
	}

	public function updateCurrency()
	{
		$sql = "
			SELECT
				* 
			FROM 
				sp_currency_update
				(
					in_code			=> :ccyCode,
					in_name			=> :ccyName,
					in_numeric_code => :ccyNumericCode,
					in_create_by	=> :userUid,
					in_owner_id		=> :businessId
				);
		";

		$st = $this->db->createCommand($sql);
		$st->bindParam(':ccyCode', $this->ccyCode);
		$st->bindParam(':ccyName', $this->ccyName);
		$st->bindParam(':ccyNumericCode', $this->ccyNumericCode);
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