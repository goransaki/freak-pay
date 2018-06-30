<?php
/**
 * Created by IntelliJ IDEA.
 * User: jasminsuljic
 * Date: 30/06/2018
 * Time: 16:33
 */

namespace api\modules\v1\service;

interface EnrollmentService
{
    /**
     *
     * Return ['status' => 'enrolled | not_enrolled', '?user_id']
     *
     * @param $id
     * @param $type
     * @return mixed
     */
    public function enrollmentStatus($id, $type);

}