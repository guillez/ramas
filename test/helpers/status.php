<?php
namespace Kolla\Test\helpers;

class status {
    /**
     * @var OK The request was fulfilled
     * @static OK = 200
     */
    static $OK = 200;
    /**
     * @var CREATED
     * @static CREATED = 201
     */
    static $CREATED = 201;
    /**
     * @var NO_CONTENT 
     * @static NO_CONTENT = 204
     */
    static $NO_CONTENT = 204;
     /**
     * @var BAD_REQUEST
     * @static BAD_REQUEST = 400
     */
    static $BAD_REQUEST = 400;
     /**
     * @var NOT_FOUND
     * @static NOT_FOUND = 404
     */
    static $NOT_FOUND = 404;
      /**
     * @var ERROR_SERVER
     * @static ERROR_SERVER = 500
     */
    static $ERROR_SERVER = 500;
}
