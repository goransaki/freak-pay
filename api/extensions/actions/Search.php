<?php
/**
 *
 * Index.php
 *
 * Date: 01/04/14
 * Time: 20:57
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 */

namespace api\extensions\actions;

use Yii;
use yii\rest\Action;


class Search extends Action
{
    public $searchScenario = 'search';
    public $formName = null;
    public $scope = null;

    public function run()
    {
        ini_set('memory_limit', '1024M');
        $class = $this->modelClass;

        $searchModel = new $class();

        $params = Yii::$app->request->getQueryParams();
        $user = Yii::$app->user->getIdentity();

        if (is_callable($this->scope)) {
            $params = call_user_func_array($this->scope, [$searchModel, $user, $params]);
        }

        $searchModel->load($params, $this->formName);
        $results = $searchModel->search($params);

        return $results;
    }
} 