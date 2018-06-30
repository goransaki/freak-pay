<?php
/**
 * Created by PhpStorm.
 * User: Boris Matic
 * Date: 2/6/2016
 * Time: 1:24 AM
 */

namespace common\components\authmanager;

use common\helpers\EnvHelper;
use Yii;
use yii\web\Request;

class AuthTokenProvider
{
    /** @var Request */
    protected $_request;

    public function __construct($request = null)
    {
        $this->_request = $request !== null ? $request : Yii::$app->getRequest();
        $this->_authToken = $this->getTokenFromRequest();
    }

    public function getAuthToken()
    {
        return $this->_authToken;
    }

    public function getRefreshToken()
    {
        $cookie = $this->_request->getCookies();
        return $cookie->has(EnvHelper::getTokenKey()) ? $cookie->get(EnvHelper::getTokenKey())->value['refresh_token'] : null;
    }

    protected function getTokenFromRequest()
    {
        $token = $this->getTokenFromHeader();

        if (empty($token)) {
            $token = $this->getTokenFromParams();
        }

        if (empty($token)) {
            $token = $this->getTokenFromCookie();
        }

        return $token;
    }

    protected function getTokenFromHeader()
    {
        $authHeader = $this->_request->isConsoleRequest ? null : $this->_request->getHeaders()->get('Authorization');
        if ($authHeader !== null && preg_match('/^Bearer\s+(.*?)$/', $authHeader, $matches)) {
            return $matches[1];
        }

        return null;
    }

    protected function getTokenFromParams()
    {
        return $this->_request->get('access-token', null);
    }

    protected function getTokenFromCookie()
    {
        $cookie = $this->_request->getCookies();

        return $cookie->has(EnvHelper::getTokenKey()) ? $cookie->get(EnvHelper::getTokenKey())->value['access_token'] : null;
    }
}