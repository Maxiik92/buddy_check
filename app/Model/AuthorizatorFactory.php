<?php
declare(strict_types=1);
namespace App\Model;

use App\Core\Factory\UtilityFactory;
use Nette\Caching\Cache;
use Nette\Caching\Storages\FileStorage;
use Nette\Security\Authorizator;
use Nette\Security\Permission;
use stdClass;

class AuthorizatorFactory
{
  private Permission $permission;
  private Cache $cache;


  public function __construct(
    private RoleModel $roleModel,
    private AclPermissionModel $aclPermissionModel,
    private AclResourceModel $aclResourceModel,
    private UtilityFactory $utilityFactory,
  ) {
    $this->permission = new Permission;
    $storage = new FileStorage(__DIR__ . '/../../temp/cache');
    $this->cache = new Cache($storage, 'authorizator');
  }

  public static function create(
    RoleModel $roleModel,
    AclPermissionModel $aclPermissionModel,
    AclResourceModel $aclResourceModel,
    UtilityFactory $utilityFactory,
  ): Permission {
    return (
      new AuthorizatorFactory(
        $roleModel,
        $aclPermissionModel,
        $aclResourceModel,
        $utilityFactory,
      )
    )->getPermission();
  }

  protected function getPermission(): Permission
  {
    $this->addRoles();
    $this->addResources();
    $this->addPermissions();
    return $this->permission;
  }

  private function addRoles(): void
  {
    $roles = $this->getActiveRoles();
    if (!empty($roles)) {
      foreach ($roles as $role) {
        $this->permission->addRole($role->name, $role->parent_name);
      }
    }
  }

  private function addResources(): void
  {
    $resources = $this->getActiveResources();
    if (!empty($resources)) {
      foreach ($resources as $resource) {
        $this->permission->addResource($resource->name);
      }
    }
  }

  private function addPermissions(): void
  {
    $permissions = $this->getPermissions();
    $this->permission->deny('guest');
    foreach ($permissions as $permission) {
      $this->permission->allow($permission->role, $permission->resource, $permission->action);
    }
    $this->permission->allow('admin', Authorizator::All, Authorizator::All);
  }

  private function getActiveRoles(): stdClass
  {
    $key = 'authorizatorRoles';
    return $this->cache->load($key, function (&$dependencies) {
      $dependencies[Cache::EXPIRE] = '1 day';
      $data = $this->roleModel->getTable()
        ->select('role.id, role.name, p.name AS parent_name')
        ->alias('p', 'role.p_id = p.id')
        ->fetchAll();

      return $this->utilityFactory->createObjectFromMultipleResults($data) ?? null;
    });
  }

  private function getActiveResources(): stdClass
  {
    $key = 'authorizatorActiveResources';
    return $this->cache->load($key, function (&$dependencies) {
      $data = $this->aclResourceModel->getTable()->where("active", 1)->fetchAll();
      return $this->utilityFactory->createObjectFromMultipleResults($data) ?? null;
    });
  }

  private function getPermissions(): stdClass
  {
    $key = "authorizatorPermissions";
    return $this->cache->load($key, function (&$dependencies) {
      $data = $this->aclPermissionModel->getTable()
        ->select("role.name AS role, resource.name AS resource, action.name AS action")
        ->fetchAll();

      return $this->utilityFactory->createObjectFromMultipleResults($data) ?? null;
    });
  }
}