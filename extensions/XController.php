<?php
namespace app\extensions;

use Yii;
use yii\web\Controller;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\admin\User;

class XController extends Controller
{
    public $enableCsrfValidation;
    public $startTime;
    protected $db;
    protected $tr;

    public function init()
    {
		$this->startTime = microtime(true);
        $dbConfig = $this->getDbConfig();
        $this->db = Yii::$app->$dbConfig;

        Yii::$app->session['LANGUAGE'] = 'en';

        parent::init();
    }

    public function beforeAction($action)
    {
        $sessionTimeout = isset($_ENV["SESSION_TIMEOUT"]) ? $_ENV["SESSION_TIMEOUT"] : 3600;
        $currentController = Yii::$app->controller;

        $lastActivity = Yii::$app->session->get('LAST_ACTIVITY');

        if ($currentController->id != 'login' || $currentController->id != 'reload-session')
        {
            // session timeout
            if (isset($lastActivity) && (time() - $this->getSession('LAST_ACTIVITY') > $sessionTimeout))
            {
                if (isset(Yii::$app->session))
                {
                    $this->destroyAllSession();
                }
            }
            else
            {
                Yii::$app->session->set('LAST_ACTIVITY', time());

                $user = new User();
                $user->Id = $this->getSession('USER_UID');
                $user->currentController = $currentController->id;
                $user->currentAction = $currentController->action->id;
                $user->setDb($this->db);
                $user->updateLastActivity();
            }
        }

        // if not login yet
        if (!isset(Yii::$app->session['USER_UID']))
        {
            if ($currentController->id != 'login' && $currentController->id != 'reload-session')
            {        
                if (Yii::$app->request->isAjax == false)
                {
                    header('Location: '. Yii::$app->urlManager->createUrl('login'));
                    exit;
                }
                else
                {
                    $this->redirectUrl('login');
                }
            }
        }
        else
        {
            if ($currentController->id == 'login' && $currentController->id == 'reload-session')
            {
                $this->redirectUrl('dashboard');
            }
            else
            {
                $this->checkActionPrivilege();
            }
        }

        return parent::beforeAction($action);
    }

    public function redirectUrl($param)
    {
        $param = (array) $param;
        $url = Yii::$app->getUrlManager()->createUrl($param);
        
        return $this->redirect($url);
    }

    public function jsonEncode($data, $useTime = false)
    {
		if (!$useTime)
		{
			if (is_array($data))
			{
				if (array_key_exists('errStr', $data))
				{
					if (!is_array($data['errStr']))
					{
						$data['errStr'] = Yii::t('app', $data['errStr']);
					}
				}
			}

			$endtime = microtime(true);
			$data['processtime'] = sprintf('%0.3f', $endtime - $this->startTime);
		}

        return Json::encode($data);
    }

    public function rulesValidation($errors)
    {
        $object = [];
        $object['errNum'] = 1;
        $object['errStr'] = $errors;

        return $this->jsonEncode($object);
    }

    public function createUrl($param, $scheme = false)
    {
        return Url::toRoute($param, $scheme);
    }

    protected function setPaginationParam($model)
	{
		if ($model)
		{
			$model->sort 	= $this->getParam('sort');
			$model->order 	= $this->getParam('order');
			$model->offset 	= $this->getParam('offset');
			$model->limit 	= $this->getParam('limit');
		}
	}

    public function checkTags($val)
    {
        if ($val !== null)
        {
            $val = strtolower($val);

            if (strpos($val, '<') !== false || strpos($val, '>') !== false)
            {
                return 1;
            }
        }

        return 0;
    }

    public function checkArrayRecursive($paramValue, $flag)
    {
        if (!is_array($paramValue) && $this->checkTags($paramValue) && $flag == 1)
        {
            die(print('No HTML/Script injection allowed!'));
        }
        else if (is_array($paramValue))
        {
            foreach ($paramValue as $data)
            {
                $this->checkArrayRecursive($data, $flag);
            }
        }

        return;
    }

    /**
     * Returns GET or POST parameter with given name
     * If key not exist it will return false
     * @param  string $paramKey
     * @return mixed the value
     */
    //~ return the parameter value whether it is POST or GET method
    public function getParam($paramKey, $flag = 1)
    {
        $paramValue = '';

        if (Yii::$app->request->isGet)
        {
            $paramValue = Yii::$app->request->getQueryParam($paramKey);
        }
        else if (Yii::$app->request->isPost)
        {
            $paramValue = Yii::$app->request->getBodyParam($paramKey);
        }

        $this->checkArrayRecursive($paramValue, $flag);

        return $paramValue;
    }

	public function getParams()
	{
		if (Yii::$app->request->isGet)
		{
			$param = Yii::$app->request->get();
		}
		elseif (Yii::$app->request->isPost)
		{
			$param = Yii::$app->request->post();
		}

		return $param;
	}

    public function getSession($name)
    {
        $session = Yii::$app->session;
        $value = $session->get($name);
        $session->close();

        return $value;
    }

    public function getSessions()
	{
		$this->dd($_SESSION);
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
	}

    public function destroyAllSession()
    {
        $session = Yii::$app->session;
        $session->open();
        $session->destroy();
        $session->close();
    }

    public function checkCompanyRegistration()
    {
        $request = Yii::$app->request;
    }

    public function checkPrivilege($appCode)
    {
        if (isset(Yii::$app->session['APPS']))
        {
            $arrayOfPrivileges = $this->getSession('APPS');

            $hasPrivilege = in_array($appCode, array_column($arrayOfPrivileges, 'code'));

            return $hasPrivilege;
        }
        else
        {
            $this->redirectUrl('dashboard');
        }
    }

    protected function checkActionPrivilege()
    {
        $currentController  = Yii::$app->controller;
        $privilege          = $this->getSession('APPS');
        $privilegeAll       = $this->getSession('ALL_APPS');

        $isMenu = 0;

        if ($currentController->action->id != 'index')
        {
            $controllerAction = $currentController->id . '/' . $currentController->action->id;
        }
        else
        {
            $controllerAction = $currentController->id;
        }

        $privilegeExist = 0;
        $individualPrivilegeExist = 0;
        $chkParentCode = '';

        for ($i = 0; $i < sizeof($privilegeAll); $i++)
        {
            if ($privilegeAll[$i]['url'] == $controllerAction)
            {
                $isMenu = $privilegeAll[$i]['is_menu'];
                $chkParentCode = $privilegeAll[$i]['parent_code'];

                $privilegeExist = 1;
                
                for ($j = 0; $j < sizeof($privilege); $j++)
                {
                    if ($privilege[$j]['app_code'] == $privilegeAll[$i]['app_code'])
                    {
                        $individualPrivilegeExist = 1;
                        break;
                    }
                }
            }
        }

        if ($privilegeExist == 1 && $individualPrivilegeExist == 0)
        {
            throw new \yii\web\HttpException(403);

			return false;
        }

        return true;
    }
    
    public function getDbConfig()
    {
        $db = 'db';

        return $db;
    }

    public function setDb($dbc = '')
    {
        $dbConfig = 'db';
        $db = empty($dbc) ? $dbConfig : $dbc;
        $this->db = Yii::$app->$db;
        
        return $this->db;
    }

    public function beginTx()
    {
        $this->tr = $this->db->beginTransaction();
    }

    public function commitTx()
    {
        $this->tr->commit();
    }

    public function rollbackTx()
    {
        $this->tr->rollback();
    }

    public function validateBasicTx($data, $status = 1)
    {
        if ($data['errNum'] == 0)
        {
            if ($status == 0)
            {
                return true;
            }
            else if ($status == 1)
            {
                $this->commitTx();
            }
        }
        else if ($data['errNum'] != 0)
        {
            $this->rollbackTx();
        }
    }

	/**
	 * Wrapper function for getting cookie value
	 *
	 * @param $name, string, name of the cookie
	 *
	 * @return any | null
	 *
	 **/
	public function getCookie($name)
	{
		$cookies = Yii::$app->request->cookies;

		return $cookies->getValue($name, null);
	}

	/**
	 * Wrapper function for setting cookie
	 *
	 * @param $name, string, name of the cookie
	 * @param $value, string, value of the cookie
	 * @param $expire, integer, expire time of the cookie in seconds
	 *
	 **/
	public function setCookie($name, $value, $expire = 0)
	{
		$cookies = Yii::$app->response->cookies;

		$cookies->add(new \yii\web\Cookie([
		   'name' => $name,
		   'value' => $value,
		   'expire' => time() + $expire
		]));
	}

	/**
	 * Wrapper function for removing cookie
	 *
	 * @param $name, string, name of the cookie
	 *
	 **/
	public function removeCookie($name)
	{
		$cookies = Yii::$app->response->cookies;

		$cookies->remove($name);
	}

    public function dd($data)
	{
		echo '<pre>';
		var_dump($data);
		die();
		echo '</pre>';
	}
}
