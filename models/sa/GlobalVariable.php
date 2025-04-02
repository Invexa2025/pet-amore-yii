<?php

namespace app\models\sa;

use Yii;
use PDO;
use app\extensions\XModel;

class GlobalVariable extends XModel
{
	public $gvVarName;
	public $gvVarDesc;
    public $gvVarValue;
    public $gvVarNumber;
    public $gvVarGroup;

    public function rules()
	{
		return [
			[['gvVarName', 'gvVarDesc', 'gvVarGroup'], 'required', 'on' => ['insert-gv', 'update-gv']],
			[['gvVarName'], 'required', 'on' => ['get-gv-detail-by-var-name']]
        ];
    }

    public function getGvList($isPagination)
    {
		$where = '';
		$params = [];

		if ($this->search) {	
			if (!is_array($this->search)) {
				$params[':search'] = '%' . strtolower($this->search) . '%';
				$where .= "AND gv.var_name LIKE :search\n";
			} else {
				[
					$searchGvVarName, 
                    $searchGvVarDesc,
					$searchGvVarGroup, 
				] = array_pad($this->search, 3, null);
				
				if (!empty($searchGvVarName)) {
					$params[':var_name'] = '%' . strtolower($searchGvVarName) . '%';
					$where .= "AND gv.var_name LIKE :var_name\n";
				}

                if (!empty($searchGvVarDesc)) {
					$params[':var_desc'] = '%' . $searchGvVarDesc . '%';
					$where .= "AND gv.var_desc ILIKE :var_desc\n";
				}
				
				if (!empty($searchGvVarGroup)) {
					$params[':var_group'] = strtoupper($searchGvVarGroup);
					$where .= "AND gv.var_group LIKE :var_group\n";
				}
			}
		}

        $sql = "
			SELECT
				LOWER(gv.var_name) var_name,
				gv.var_desc,
				gv.var_value,
                gv.var_number,
                gv.var_group
			FROM
				global_variables_desc gv
			WHERE
				gv.status = 1
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

    public function getBusinessApplicationList()
    {
        $sql = "
			SELECT
				ba.code,
                ba.name
			FROM
				business_applications ba
			WHERE
				ba.status = 1
		";

		$st = $this->db->createCommand($sql);
		// $this->dd($st->getRawSql());
		$result = $st->queryAll();

		return $result;
    }

	public function getGvDetailByVarName()
	{
		$sql = "
			SELECT
				LOWER(gv.var_name) var_name,
				gv.var_desc,
				gv.var_desc,
                gv.var_value,
                gv.var_number,
                gv.var_group
			FROM
				global_variables_desc gv
			WHERE
				gv.status = 1
                AND gv.var_name = :varName
		";

		$st = $this->db->createCommand($sql);
		$st->bindParam(':varName', $this->gvVarName);
		// $this->dd($st->getRawSql());
		$result = $st->queryOne();

		return $result;
	}

    public function insertGv()
	{	
		$sql = "
			SELECT
				* 
			FROM 
				sp_global_variables_desc_insert
				(
					in_var_name		     => :gvVarName,
                    in_var_desc		     => :gvVarDesc,
                    in_var_value         => :gvVarValue,
                    in_var_number        => :gvVarNumber,
                    in_var_group         => :gvVarGroup,
                    in_create_by         => :userUid,
                    in_owner_id          => :businessId
				);
		";

		$st = $this->db->createCommand($sql);
		$st->bindParam(':gvVarName', $this->gvVarName);
		$st->bindParam(':gvVarDesc', $this->gvVarDesc);
		$st->bindParam(':gvVarValue', $this->gvVarValue);
		$st->bindParam(':gvVarNumber', $this->gvVarNumber);
        $st->bindParam(':gvVarGroup', $this->gvVarGroup);
		$st->bindParam(':userUid', $this->userUid);
		$st->bindParam(':businessId', $this->businessId);
		// $this->dd($st->getRawSql());
		$result = $st->queryOne();

		return [
			'errNum' => $result['out_num'],
			'errStr' => $result['out_str']
		];
	}

	public function updateGv()
	{
		$sql = "
			SELECT
				* 
			FROM 
				sp_global_variables_desc_update
				(
					in_var_name		     => :gvVarName,
                    in_var_desc		     => :gvVarDesc,
                    in_var_value         => :gvVarValue,
                    in_var_number        => :gvVarNumber,
                    in_var_group         => :gvVarGroup,
                    in_create_by         => :userUid,
                    in_owner_id          => :businessId
				);
		";

		$st = $this->db->createCommand($sql);
		$st->bindParam(':gvVarName', $this->gvVarName);
		$st->bindParam(':gvVarDesc', $this->gvVarDesc);
		$st->bindParam(':gvVarValue', $this->gvVarValue);
		$st->bindParam(':gvVarNumber', $this->gvVarNumber);
        $st->bindParam(':gvVarGroup', $this->gvVarGroup);
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