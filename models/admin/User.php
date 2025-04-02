<?php

namespace app\models\admin;

use Yii;
use PDO;
use app\extensions\XModel;

class User extends XModel
{
    public $Id;
    public $currPassword;
    public $newPassword;

    public function rules()
	{
        return [
            [['currPassword', 'newPassword'], 'required', 'on' => 'change-password'],
            [['currPassword'], 'checkCurrPassword', 'on' => ['change-password']],
        ];
    }

    public function checkCurrPassword($attribute, $params)
	{
		$password = $this->currPassword;
		
		$sql = "
			SELECT
				u.password
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
		$st->bindParam(':userUid', $this->userUid);
		$st->bindParam(':businessId', $this->businessId);
        // $this->dd($st->getRawSql());
		$data = $st->queryOne();
		
		if (!password_verify($password, $data['password']))
		{
            $this->addError($attribute, 'Current Password is wrong');
		}
	}

	public function UpdateLastActivity()
	{
		$action = $this->currentController . ' : ' . $this->currentAction;

		$sql = "
			SELECT
				* 
			FROM 
				sp_user_update_last_activity
				(
					in_id 			=> :id,
					in_activity 	=> :activity
				);
		";
		
		$st = $this->db->createCommand($sql);
		$st->bindParam(':id', $this->userUid);
		$st->bindParam(':activity', $action);
		// $this->dd($st->getRawSql());
		$st->queryOne();

		return true;
	}

    public function changePassword()
	{
		$password = password_hash($this->newPassword, PASSWORD_DEFAULT);

		$sql = "
			SELECT
				* 
			FROM 
				sp_user_change_password
				(
                    in_id	        => :id,
					in_password	    => :newPassword,
					in_user_uid	    => :userUid,
                    in_owner_id		=> :businessId
				);
		";
		
		$st = $this->db->createCommand($sql);
		
		$st->bindParam(':id', $this->Id);
		$st->bindParam(':businessId', $this->businessId);
		$st->bindParam(':newPassword', $password);
		$st->bindParam(':userUid', $this->userUid);
        // $this->dd($st->getRawSql());
		$result = $st->queryOne();

		return [
			'errNum' => $result['out_num'],
			'errStr' => $result['out_str']
		];
	}
}