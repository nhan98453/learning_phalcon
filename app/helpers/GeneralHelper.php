<?php

namespace CoreAPI\Helpers;

use Firebase\JWT\JWT;
use Phalcon\Mvc\User\Component;
use Phalcon\Di;

class GeneralHelper extends Component{
	public static function checkAuthorization()
    {
       
        $token = null;

        $headers = getallheaders();
        if (isset($headers['Authorization'])) {
            $token = stripos($headers['Authorization'], 'Bearer ') === false ? 'Bearer ' . $headers['Authorization'] : $headers['Authorization'];

			list($jwt) = sscanf($token, 'Bearer %s');
			
            if ($jwt) {
                try {
                    //decode the jwt using the key from config
                    $token = JWT::decode($jwt, SECRET_KEY_JWT, ['HS256']);
                    return $token;
                } catch (\Exception $e) {
                   
                    header('HTTP/1.0 401 Unauthorized');
                    echo "Wrong token";
                    exit();
                }
            }
        } else {
             header('HTTP/1.0 501 Unauthorized');
			 echo '{"error": "Please login"}';
			exit();
        }
    }

	public static function checkPermission($modelName, $action)
	{
		$roles = self::checkAuthorization()->permission;
		print_r(json_decode($roles));exit();
		if (!isset($roles->$modelName)) {
			self::thrownError("You don't have permission to access: " . $modelName);
		}

		$acl = bindec($roles->$modelName);
		$result = ($acl & ACL_PERMISSION[$action]) === ACL_PERMISSION[$action];

		if ($result == true)
			return true;
		else
			self::thrownError('Access denied: ' . $modelName . ' - ' . $action);
	}



	public static function thrownError($message){
		die ($message);
	}

}