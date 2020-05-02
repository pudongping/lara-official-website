<?php
/**
 * 角色相关
 *
 * Created by PhpStorm.
 * User: Alex
 * Date: 2020/2/13
 * Time: 20:33
 */

namespace App\Repositories\Auth;

use App\Repositories\BaseRepository;
use App\Models\Auth\Role;
use App\Models\Auth\Permission;
use App\Exceptions\ApiException;
use App\Support\Code;
use App\Models\Setting\Menu;

class RolesRepository extends BaseRepository
{

    protected $model;
    protected $menu;
    protected $permission;

    public function __construct(
        Role $role,
        Menu $menu,
        Permission $permission
    ) {
        $this->model = $role;
        $this->menu = $menu;
        $this->permission = $permission;
    }

    /**
     * 权限列表
     *
     * @param $request
     * @return mixed
     */
    public function getList($request)
    {
        $search = $request->input('s');
        $model = $this->model->where(function ($query) use ($search) {
            if (!empty($search)) {
                $query->orWhere('name', 'like', '%' . $search . '%');
                $query->orWhere('cn_name', 'like', '%' . $search . '%');
            }
        });

        if (false !== ($between = $this->searchTime($request))) {
            $model = $model->whereBetween('created_at', $between);
        }

        $model = $model->with('permissions')->currentGuard();

        return $this->usePage($model);
    }

    /**
     * 添加新角色数据处理
     *
     * @param $request
     * @return mixed
     */
    public function storage($request)
    {
        $extra = $request->extra;
        // 验证菜单 id 和权限 id 的有效性
        $extra = $this->validateMenuPermission($extra);

        $input = $request->only('name', 'cn_name');
        $input['guard_name'] = config('api.default_guard_name');
        $input['extra'] = json_encode($extra, 320);
        $role = $this->store($input);

        // 当前选中的所有权限 id
//        $permissionsId = $request->permissions;
//        if ($permissionsId) {
//            $permissions = Permission::whereIn('id', $permissionsId)->get();
//            // 将多个权限同步赋予到一个角色
//            $role->syncPermissions($permissions);
//        }

        return $role;
    }

    public function edit($request)
    {
        $role = $request->role->toArray();
        $role['extra'] = $this->prepareMenuPerData($role['extra']);
        return $role;
    }

    /**
     *  编辑角色数据提交
     *
     * @param $request
     * @return mixed
     */
    public function modify($request)
    {
        $input = $request->only('name', 'cn_name');
        $role = $this->update($request->role, $input);

        // 先删除用户所有的权限
        \DB::table('role_has_permissions')->where('role_id', $request->role)->delete();
        // 手动重置缓存
        \Artisan::call('cache:forget spatie.permission.cache');

        // 当前选中的所有权限 id
        $permissionsId = $request->permissions;
        if ($permissionsId) {
            $permissions = Permission::whereIn('id', $permissionsId)->get();
            // 将多个权限同步赋予到一个角色
            $role->syncPermissions($permissions);
        }

        return $role;
    }

    /**
     * 删除角色
     *
     * @param $role
     * @return array
     */
    public function destroy($role)
    {
        if (in_array($role->name, Role::DEFAULT_ROLES)) {
            Code::setCode(Code::ERR_PARAMS, '默认角色不允许删除');
            return false;
        }
        $role->delete();
    }

    /**
     * 批量删除角色
     *
     * @param $request
     * @throws ApiException
     */
    public function massDestroy($request)
    {
        $roles = $this->model->select('name')->whereIn('id', $request->ids)->get()->pluck('name')->toArray();
        foreach ($roles as $role) {
            if (in_array($role, Role::DEFAULT_ROLES)) {
                throw new ApiException(Code::ERR_PARAMS, [], '包含默认角色，不允许删除');
            }
        }
        $this->model->whereIn('id', $request->ids)->delete();
    }

    /**
     * 验证角色有效性
     *
     * @param array $roles 需要验证的角色数组
     * @return array  合法的角色数组
     */
    public function validateRoles($roles): array
    {
        $allowRoles = [];
        if (! empty($roles) && is_array($roles)) {
            // 判断角色有效性
            $rolesInDatabase = $this->model->currentGuard()->pluck('name')->toArray();
            // 合法的角色数组
            $allowRoles = array_intersect($roles, $rolesInDatabase);
        }
        return $allowRoles;
    }

    /**
     * 检验菜单 id 和权限 id 的有效性
     *
     * @param $extra
     * @return array|bool
     */
    public function validateMenuPermission($extra)
    {
//        {
//            "name": "roles22",
//	        "cn_name": "角色22",
//	        "extra": {
//                "page": [{"menu_id": 1, "permission": [1, 2]},{"menu_id": 7, "permission": [3]}],
//		        "special": [4, 5, 7]
//	        }
//        }
        if (empty($extra)) return false;
        $menuIdAndPer = $this->menu->select('id', 'permission')->status()->get()->toArray();
        if (empty($menuIdAndPer)) return false;
        $allPermission = $this->permission->select('id', 'name')->get()->pluck('id', 'name')->toArray();
        if (empty($allPermission)) return false;
        $menuPermission = [];
        $menuIdAndPerId = [];  // 菜单 id 为 key，菜单相对应的权限 id 为值的数组
        foreach ($menuIdAndPer as $k => $itemMenu) {
            $menuPermission[$k]['menu_id'] = $itemMenu['id'];  // 菜单 id
            $menuPermission[$k]['permission'] = [];
            $menuIdAndPerId[$itemMenu['id']] = [];
            if (! empty($itemMenu['permission']) && is_array($itemMenu['permission'])) {
                foreach ($itemMenu['permission'] as $kk => $vv) {
                    $menuPermission[$k]['permission'][$kk]['permission_id'] = $allPermission[$vv] ?? 0;  // 权限 id
                    $menuPermission[$k]['permission'][$kk]['permission_name'] = $vv ?? '';  // 权限名称
                    $menuIdAndPerId[$itemMenu['id']][] = $allPermission[$vv] ?? 0;  // 权限 id
                }
            }
        }

        // 判断权限 id 的有效性
        $special = [];
        if (isset($extra['special']) && !empty($extra['special']) && is_array($extra['special'])) {
            foreach ($extra['special'] as $vvv) {
                // 判断提交的特殊权限 id 是否在数据库中的权限数组中
                if (in_array($vvv, array_values($allPermission))) {
                    $special[] = $vvv;
                }
            }
        }

        $page = [];
        if (isset($extra['page']) && ! empty($extra['page']) && is_array($extra['page'])) {
            foreach ($extra['page'] as $kkkk => $vvvv) {
                // 判断提交的菜单 id 是否合法
                if (in_array($vvvv['menu_id'], array_column($menuIdAndPer, 'id'))) {
                    $page[$kkkk]['menu_id'] = $vvvv['menu_id'];  // 菜单 id
                    if (isset($vvvv['permission']) && !empty($vvvv['permission']) && is_array($vvvv['permission'])) {
                        foreach ($vvvv['permission'] as $kkkkk => $vvvvv) {
                            if (in_array($vvvvv, $menuIdAndPerId[$vvvv['menu_id']])) {
                                $page[$kkkk]['permission'][$kkkkk] = $vvvvv;
                            }
                        }
                    }
                }
            }
        }

        return ['page' => $page, 'special' => $special];
    }

    /**
     * 将菜单数据和权限数据拼接到角色信息中
     *
     * @param $extra  菜单 id 和权限 id json 字符串
     * @return array|void
     */
    public function prepareMenuPerData($extra)
    {
        if (empty($extra)) return;
        $extra = json_decode($extra, true);
        $menus = $this->menu->get()->toArray();
        $menuData = [];
        foreach ($menus as $menu) {
            $menuData[$menu['id']] = $menu;
        }
        $permissionData = [];
        $permissions = $this->permission->get()->toArray();
        foreach ($permissions as $permission) {
            $permissionData[$permission['id']] = $permission;
        }

        $page = [];
        if (isset($extra['page']) && ! empty($extra['page']) && is_array($extra['page'])) {
            foreach ($extra['page'] as $k => $v) {
                if (isset($v['menu_id'])) {
                    $page[$k]['menu'] = $menuData[$v['menu_id']] ?? '';
                }
                if (isset($v['permission']) && ! empty($v['permission']) && is_array($v['permission'])) {
                    foreach ($v['permission'] as $kk => $vv) {
                        $page[$k]['permission'][$kk] = $permissionData[$vv] ?? '';
                    }
                }
            }
        }

        $special = [];
        if (isset($extra['special']) && ! empty($extra['special']) && is_array($extra['special'])) {
            foreach ($extra['special'] as $kkk => $vvv) {
                $special[$kkk] = $permissionData[$vvv] ?? '';
            }
        }

        return ['page' => $page, 'special' => $special];
    }

}
