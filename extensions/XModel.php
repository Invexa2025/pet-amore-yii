<?php
namespace app\extensions;

use Yii;
use PDO;
use yii\base\Model;

class XModel extends Model
{
	protected $db;
	protected $st;
	public $limit = 10,
		$offset = 0,
		$search,
		$sort,
		$order;

	public $userUid;
	public $businessId;
	public $officeId;
	public $roleType;
	public $officeTimezone;
	public $currentController;
	public $currentAction;
	
	public function init()
	{
		if (isset(Yii::$app->session))
		{
			$session = Yii::$app->session;
			$this->userUid 			= $session['USER_UID'];
			$this->officeId			= $session['OFFICE_ID'];
			$this->businessId 		= $session['BUSINESS_ID'];
			$this->roleType 		= $session['ROLE_TYPE'];
			$this->officeTimezone 	= $session['OFFICE_TIMEZONE'];
		}
	}

	public function bindParam($param)
	{
		$this->st->bindValues($param);
	}
	
	public function bindParams($command, array $params)
	{
		foreach ($params as $name => $value) {
			$command->bindValue($name, $value);
		}
	}

	public function pagination($rawSql = '')
	{
		try 
		{
			if (!empty($this->sort))
			{
				$rawSql .= 'ORDER BY ';

				if (is_array($this->sort) && is_array($this->order))
				{
					for ($i = 0; $i < sizeof($this->sort); $i++)
					{
						$rawSql .= $this->sort[$i] . ' ' . $this->order[$i];

						if ($i != sizeof($this->sort) - 1 && sizeof($this->sort) > 1)
						{
							$rawSql .= ',';
						}
					}
				}
				else
				{
					if (substr($this->sort, -5) == '_date')
					{
						$this->sort = substr_replace($this->sort, '_date::date', -5); 
					}

					$rawSql .= "
						{$this->sort} {$this->order}
					";
				}
			}

			$sql = "
				SELECT
					ROW_NUMBER() OVER() rnum, 
					pgr.*
				FROM 
					(
						$rawSql
					) pgr
				OFFSET {$this->offset} LIMIT {$this->limit}
			";
			$st = $this->db->createCommand($sql);
			// $this->dd($st->getRawSql());
			$resultQuery = $st->queryAll();

			$sql = "
				SELECT
					COUNT(1) total_row
				FROM
					({$rawSql}) pgr
			";
			$st = $this->db->createCommand($sql);
			$totalRow = $st->queryScalar();
		}
		catch (exception $e)
		{
			return [
				'errNum' => 101,
				'errStr' => $e->getMessage(),
			];
		}

		return [
			'errStr' 	=> 'Success',
			'errNum' 	=> 0,
			'rows' 		=> $resultQuery,
			'total' 	=> $totalRow
		];
	}

	public function setSession($data, $sessionName = '')
	{
		$session = Yii::$app->session;
		$session->open();

		if ((array) $data === $data)
		{
			if ($sessionName == '')
			{
				foreach ($data as $key => $value)
				{
					$session[$key] = $value;
				}
			}
			else
			{
				$session[$sessionName] = $data;
			}
		}
		else
		{
			$session[$sessionName] = $data;
		}

		$session->close();

		return 0;
	}

	public function deleteSession($param)
	{
		$session = Yii::$app->session;
		$session->open();

		for ($i = 0 ; $i < sizeof($param) ; $i++)
		{
			$session->remove($param[$i]);
		}

		$session->close();

		return 0;
	}

	public function getSession($sessionName)
	{
		$session = Yii::$app->session;
		$session->open();

		return $session[$sessionName];
	}

	public function setDb($db)
	{
		$this->db = $db;
	}

	private function ObjectToArrayRecursive($data)
	{
		return Json::decode(Json::encode($data));
	}

	public function addAliasing($fieldName, $aliasingName)
	{
		if (is_array($this->sort))
		{
			$idx = array_search($fieldName, $this->sort);

			if ($idx !== null)
			{
				$this->sort[$idx] = $aliasingName . '.' . $this->sort[$idx];
			}
		}
		else
		{
			if ($this->sort == $fieldName)
			{
				$this->sort = $aliasingName . '.' . $this->sort;
			} 
		}
	}

	/**
     * Replacing placeholder which we want to replace with array
     *
     * Run this function after calling getBindStringSQL
     *
     * @param string $attributeName , The attribute itself must be an array
     * @param array $arrayValue , Array containing the value we want to bind
     * @param string $sql , SQL we want to replace with the new placeholders
     *
     * @return boolean, indicating the binding success
     */
    public function replaceSQLArrayPlaceholder($attributeNameBind, $arrayValue, &$sql)
    {
        $attributeBind = [];
        $attributeBindString = '';

        if( !empty($arrayValue) ) {
            foreach( $arrayValue as $index => $attribute ) {
                $attributeBind[] = "$attributeNameBind$index";
            }
        }

        $attributeBindString = implode(',', $attributeBind);

        if( $attributeNameBind[0] == ':' ) {
            $attributeNameBind = substr($attributeNameBind, 1);
        }

        $sql = preg_replace('/:\b' . $attributeNameBind . '\b/', $attributeBindString, $sql);

        return true;
    }

	/**
     * Binding array values one by one to the prepared statement
     * 
     * Run this function after calling getBindStringSQL
     * 
     * @param string $attributeName , The attribute itself must be an array
     * @param array $arrayValue , Array containing the value we want to bind
     * @param PDOStatement $st , PDOStatement reference
     * 
     * @return boolean, indicating the binding success
     */
    public function bindArraySQL($attributeNameBind, $arrayValue, $st)
    {
        if( !empty($arrayValue) ) {
            foreach( $arrayValue as $index => $value ) {            
                $st->bindParam( "$attributeNameBind$index", $arrayValue[$index] );
            }
        }

        return true;
    }

	public function dd($data)
	{
		echo '<pre>';
		var_dump($data);
		echo '</pre>';
		die();
	}
}
