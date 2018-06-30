<?php
/**
 ** Author: Boris Matic
 * Date: 1/18/2016 12:10 AM
 * Email: boris.matic.1991@gmail.com
 */

namespace common\components\authmanager;

use Yii;
use yii\base\Object;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use yii\helpers\ArrayHelper;
use yii\rbac\Assignment;
use yii\rbac\Item;
use yii\rbac\ManagerInterface;
use yii\base\NotSupportedException;
use yii\rbac\Permission;
use yii\rbac\Role;
use yii\web\HttpException;
use yii\helpers\Json;

class ApiManager extends Object implements ManagerInterface
{
    const APPLICATION = 'scoutal';

    private $_assignments;
    private $_itemChildren;
    private $_items;

    public function init()
    {
        parent::init();

        try {
            $request = \Yii::$app->getRequest();

            $tokenProvider = new AuthTokenProvider($request);
            $client = new Client(['base_uri' => Yii::$app->params['adminPortalUrl']]);

            $response = $client->get('oauth/db-manager', [
                'query' => [
                    'access_token' => $tokenProvider->getAuthToken(),
                    'app' => self::APPLICATION
                ]
            ]);

            $responseBody = Json::decode($response->getBody(), true);

            $this->_assignments = $responseBody['auth_assignment'];
            $this->_itemChildren = $responseBody['auth_item_child'];
            $this->_items = $responseBody['auth_item'];
        } catch (ClientException $e) {
            $response = $e->getResponse();
            throw new HttpException($response->getStatusCode(), $response->getReasonPhrase());
        }
    }

    public function getAuthAssignments()
    {
        return $this->_assignments;
    }

    public function getAuthItemChildren()
    {
        return $this->_itemChildren;
    }

    public function getAuthItems()
    {
        return $this->_items;
    }

    public function getPermissions()
    {
        return $this->getItems(Item::TYPE_PERMISSION);
    }

    public function getPermission($name)
    {
        $item = $this->getItem($name);
        return $item instanceof Item && $item->type == Item::TYPE_PERMISSION ? $item : null;
    }

    public function getRoles()
    {
        return $this->getItems(Item::TYPE_ROLE);
    }

    public function getRole($name)
    {
        $item = $this->getItem($name);
        return $item instanceof Item && $item->type == Item::TYPE_ROLE ? $item : null;
    }

    public function checkAccess($userId, $permissionName, $params = [])
    {
        $assignments = $this->getAssignments($userId);
        return $this->checkAccessRecursive($userId, $permissionName, $params, $assignments);
    }

    public function getRolesByUser($userId)
    {
        if (empty($userId)) {
            return [];
        }

        $allRoleItems = [];
        foreach ($this->getAuthItems() as $item) {
            if ($item['type'] == Item::TYPE_ROLE) {
                $allRoleItems[$item['name']] = $item;
            }
        }

        $roles = [];
        foreach ($this->getAuthAssignments() as $item) {
            if ($item['user_id'] == $userId && isset($allRoleItems[$item['item_name']])) {
                $role = $allRoleItems[$item['item_name']];
                $roles[$item['item_name']] = $this->populateItem($role);
            }
        }

        return $roles;
    }

    public function getPermissionsByRole($roleName)
    {
        $childrenList = $this->getChildrenList();
        $result = [];
        $this->getChildrenRecursive($roleName, $childrenList, $result);

        if (empty($result)) {
            return [];
        }

        $permissions = [];
        $resultKeys = array_keys($result);
        foreach ($this->getAuthItems() as $item) {
            if ($item['type'] == Item::TYPE_PERMISSION && in_array($item['name'], $resultKeys)) {
                $permissions[$item['name']] = $this->populateItem($item);
            }
        }

        return $permissions;
    }

    public function getPermissionsByUser($userId)
    {
        if (empty($userId)) {
            return [];
        }

        $childrenList = $this->getChildrenList();
        $result = [];
        foreach ($this->getAssignmentsByUserId($userId) as $item) {
            $this->getChildrenRecursive($item['item_name'], $childrenList, $result);
        }

        if (empty($result)) {
            return [];
        }

        $permissions = [];
        $resultKeys = array_keys($result);
        foreach ($this->getAuthItems() as $item) {
            if ($item['type'] == Item::TYPE_PERMISSION && in_array($item['name'], $resultKeys)) {
                $permissions[$item['name']] = $this->populateItem($item);
            }
        }

        return $permissions;
    }

    public function getRule($name)
    {
        return null;
    }

    public function getRules()
    {
        return null;
    }

    public function getAssignment($roleName, $userId)
    {
        if (empty($userId)) {
            return null;
        }

        foreach ($this->getAuthAssignments() as $assignment) {
            if ($assignment['user_id'] == $userId && $assignment['item_name'] == $roleName) {
                return new Assignment([
                    'userId' => $assignment['user_id'],
                    'roleName' => $assignment['item_name'],
                    'createdAt' => $assignment['created_at'],
                ]);
            }
        }

        return null;
    }

    public function getAssignments($userId)
    {
        if (empty($userId)) {
            return [];
        }

        $assignments = [];
        foreach ($this->getAssignmentsByUserId($userId) as $item) {
            $assignments[$item['item_name']] = new Assignment([
                'userId' => $item['user_id'],
                'roleName' => $item['item_name'],
                'createdAt' => $item['created_at'],
            ]);
        }

        return $assignments;
    }

    public function hasChild($parent, $child)
    {
        foreach ($this->getAuthAssignments() as $assignment) {
            if ($assignment['parent'] == $parent && $assignment['child'] == $child) {
                return true;
            }
        }
        return false;
    }

    public function getChildren($name)
    {
        $children = [];
        $allItems = ArrayHelper::index($this->getAuthItems(), 'name');
        foreach ($this->getAuthItemChildren() as $itemChild) {
            if ($itemChild['parent'] == $name) {
                $childName = $itemChild['child'];
                $children[$childName] = $this->populateItem($allItems[$childName]);
            }
        }

        return $children;
    }

    protected function getItems($type)
    {
        $items = [];
        foreach ($this->getAuthItems() as $item) {
            if ($item['type'] == $type) {
                $items[$item['name']] = $this->populateItem($item);
            }
        }

        return $items;
    }

    protected function populateItem($row)
    {
        $class = $row['type'] == Item::TYPE_PERMISSION ? Permission::className() : Role::className();

        if (!isset($row['data']) || ($data = @unserialize($row['data'])) === false) {
            $data = null;
        }

        return new $class([
            'name' => $row['name'],
            'type' => $row['type'],
            'description' => $row['description'],
            'ruleName' => $row['rule_name'],
            'data' => $data,
            'createdAt' => $row['created_at'],
            'updatedAt' => $row['updated_at'],
        ]);
    }

    protected function getItem($name)
    {
        if (empty($name)) {
            return null;
        }

        foreach ($this->getAuthItems() as $item) {
            if ($item['name'] == $name) {
                if (!isset($item['data']) || ($data = @unserialize($item['data'])) === false) {
                    $item['data'] = null;
                }

                return $this->populateItem($item);
            }
        }

        return null;
    }

    protected function getChildrenList()
    {
        $parents = [];
        foreach ($this->getAuthItemChildren() as $row) {
            $parents[$row['parent']][] = $row['child'];
        }
        return $parents;
    }

    protected function getChildrenRecursive($name, $childrenList, &$result)
    {
        if (isset($childrenList[$name])) {
            foreach ($childrenList[$name] as $child) {
                $result[$child] = true;
                $this->getChildrenRecursive($child, $childrenList, $result);
            }
        }
    }

    private function getAssignmentsByUserId($userId)
    {
        $items = [];
        foreach ($this->getAuthAssignments() as $assignment) {
            if ($assignment['user_id'] == $userId) {
                $items[] = $assignment;
            }
        }
        return $items;
    }

    protected function checkAccessRecursive($user, $itemName, $params, $assignments)
    {
        if (($item = $this->getItem($itemName)) === null) {
            return false;
        }

        Yii::trace($item instanceof Role ? "Checking role: $itemName" : "Checking permission: $itemName", __METHOD__);

        if (isset($assignments[$itemName])) {
            return true;
        }

        foreach ($this->getParentsByChild($itemName) as $parent) {
            if ($this->checkAccessRecursive($user, $parent, $params, $assignments)) {
                return true;
            }
        }

        return false;
    }

    private function getParentsByChild($child)
    {
        $parents = [];
        foreach ($this->getAuthItemChildren() as $item) {
            if ($item['child'] == $child) {
                $parents[] = $item['parent'];
            }
        }
        return $parents;
    }

    public function getUserIdsByRole($roleName)
    {
        throw new NotSupportedException('"getUserIdsByRole" is not implemented.');
    }

    public function createRole($name)
    {
        throw new NotSupportedException('"createRole" is not implemented.');
    }

    public function createPermission($name)
    {
        throw new NotSupportedException('"createPermission" is not implemented.');
    }

    public function add($object)
    {
        throw new NotSupportedException('"add" is not implemented.');
    }

    public function remove($object)
    {
        throw new NotSupportedException('"remove" is not implemented.');
    }

    public function update($name, $object)
    {
        throw new NotSupportedException('"update" is not implemented.');
    }

    public function addChild($parent, $child)
    {
        throw new NotSupportedException('"addChild" is not implemented.');
    }

    public function removeChild($parent, $child)
    {
        throw new NotSupportedException('"removeChild" is not implemented.');
    }

    public function removeChildren($parent)
    {
        throw new NotSupportedException('"removeChildren" is not implemented.');
    }

    public function assign($role, $userId)
    {
        throw new NotSupportedException('"assign" is not implemented.');
    }

    public function revoke($role, $userId)
    {
        throw new NotSupportedException('"revoke" is not implemented.');
    }

    public function revokeAll($userId)
    {
        throw new NotSupportedException('"revokeAll" is not implemented.');
    }

    public function removeAll()
    {
        throw new NotSupportedException('"removeAll" is not implemented.');
    }

    public function removeAllPermissions()
    {
        throw new NotSupportedException('"removeAllPermissions" is not implemented.');
    }

    public function removeAllRoles()
    {
        throw new NotSupportedException('"removeAllRoles" is not implemented.');
    }

    public function removeAllRules()
    {
        throw new NotSupportedException('"removeAllRules" is not implemented.');
    }

    public function removeAllAssignments()
    {
        throw new NotSupportedException('"removeAllAssignments" is not implemented.');
    }

    /**
     * Returns child roles of the role specified. Depth isn't limited.
     * @param string $roleName name of the role to file child roles for
     * @return Role[] Child roles. The array is indexed by the role names.
     * First element is an instance of the parent Role itself.
     * @throws \yii\base\InvalidParamException if Role was not found that are getting by $roleName
     * @since 2.0.10
     */
    public function getChildRoles($roleName)
    {
        // TODO: Implement getChildRoles() method.
    }

    /**
     * Checks the possibility of adding a child to parent
     * @param Item $parent the parent item
     * @param Item $child the child item to be added to the hierarchy
     * @return bool possibility of adding
     *
     * @since 2.0.8
     */
    public function canAddChild($parent, $child)
    {
        // TODO: Implement canAddChild() method.
    }
}