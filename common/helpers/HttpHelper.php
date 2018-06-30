<?php
/**
 ** Author: Boris Matic
 * Date: 12/25/2015 4:06 PM
 * Email: boris.matic.1991@gmail.com
 */

namespace common\helpers;

class HttpHelper
{
    const STATUS_CODE_CREATED = 201;
    const STATUS_CODE_DELETED = 204;
    const HTTP_NOT_MODIFIED = 304;

    const HTTP_BAD_REQUEST = 400;

    const HTTP_OK = 200;
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_PAYMENT_REQUIRED = 402;
    const HTTP_FORBIDDEN = 403;
    const HTTP_NOT_FOUND = 404;
    const HTTP_METHOD_NOT_ALLOWED = 405;
    const HTTP_NOT_ACCEPTABLE = 406;
    const HTTP_PROXY_AUTHENTICATION_REQUIRED = 407;
    const HTTP_REQUEST_TIMEOUT = 408;
    const HTTP_CONFLICT = 409;
    const HTTP_GONE = 410;
    const HTTP_LENGTH_REQUIRED = 411;
    const HTTP_PRECONDITION_FAILED = 412;
    const HTTP_REQUEST_ENTITY_TOO_LARGE = 413;
    const HTTP_REQUEST_URI_TOO_LONG = 414;
    const HTTP_UNSUPPORTED_MEDIA_TYPE = 415;
    const HTTP_REQUESTED_RANGE_NOT_SATISFIABLE = 416;
    const HTTP_EXPECTATION_FAILED = 417;
    const HTTP_DATA_VALIDATION_FAILED = 422;

    public static function sanitizeUrl($url)
    {
        return strpos($url, 'http') === 0 ? trim($url, '/') . '/' : rtim($url, '/') . '/';
    }
}