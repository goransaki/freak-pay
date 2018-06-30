<?php
/**
 * CorsFilter.php
 *
 ** Author: Goran Sarenac
 * Date: 05-Jan-16
 * Time: 22:26
 */

namespace api\extensions;


use yii\filters\Cors;

class CorsFilter extends Cors
{
    public $allowedHeaders = [
        'X-Pagination-Current-Page',
        'X-Pagination-Page-Count',
        'X-Pagination-Per-Page',
        'X-Pagination-Total-Count'
    ];

    public function init()
    {
        $this->cors['Access-Control-Expose-Headers'] =
            array_merge($this->cors['Access-Control-Expose-Headers'], $this->allowedHeaders);
    }
}