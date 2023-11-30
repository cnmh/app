<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\PermissionRepository;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

use App\Exports\ExportPermission;
use App\Import\importPermissions;
use App\Imports\ImportPermissions as ImportsImportPermissions;
use Illuminate\Support\Facades\Route;


class PermissionController extends AppBaseController
{
    /** @var PermissionRepository $permissionRepository*/
    private $permissionRepository;

    public function __construct(PermissionRepository $permissionRepo)
    {
        $this->permissionRepository = $permissionRepo;
    }

    /**
     * Display a listing of the Permission.
     */
    public function index(Request $request)
    {
        
        $query = $request->input('query');
        $permissions = $this->permissionRepository->paginate($query);
       
        if ($request->ajax()) {
            return view('permissions.table')
                ->with('permissions', $permissions);
        }

        return view('permissions.index')
            ->with('permissions', $permissions);
    }

    /**
     * Show the form for creating a new Permission.
     */
    public function create()
    {
        return view('permissions.create');
    }

    /**
     * Store a newly created Permission in storage.
     */
    public function store(CreatePermissionRequest $request)
    {
        $input = $request->all();

        $permission = $this->permissionRepository->create($input);

        Flash::success(__('messages.saved', ['model' => __('models/permissions.singular')]));

        return redirect(route('permissions.index'));
    }

    /**
     * Display the specified Permission.
     */
    public function show($id)
    {
        $permission = $this->permissionRepository->find($id);

        if (empty($permission)) {
            Flash::error(__('models/permissions.singular').' '.__('messages.not_found'));

            return redirect(route('permissions.index'));
        }

        return view('permissions.show')->with('permission', $permission);
    }

    /**
     * Show the form for editing the specified Permission.
     */
    public function edit($id)
    {
        $permission = $this->permissionRepository->find($id);

        if (empty($permission)) {
            Flash::error(__('models/permissions.singular').' '.__('messages.not_found'));

            return redirect(route('permissions.index'));
        }

        return view('permissions.edit')->with('permission', $permission);
    }

    /**
     * Update the specified Permission in storage.
     */
    public function update($id, UpdatePermissionRequest $request)
    {
        $permission = $this->permissionRepository->find($id);

        if (empty($permission)) {
            Flash::error(__('models/permissions.singular').' '.__('messages.not_found'));

            return redirect(route('permissions.index'));
        }

        $permission = $this->permissionRepository->update($request->all(), $id);

        Flash::success(__('messages.updated', ['model' => __('models/permissions.singular')]));

        return redirect(route('permissions.index'));
    }

    /**
     * Remove the specified Permission from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $permission = $this->permissionRepository->find($id);

        if (empty($permission)) {
            Flash::error(__('models/permissions.singular').' '.__('messages.not_found'));

            return redirect(route('permissions.index'));
        }

        $this->permissionRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/permissions.singular')]));

        return redirect(route('permissions.index'));
    }

    /**
     * Export the Permission data to an Excel file.
     *
     * @return BinaryFileResponse
    */

    public function export()
    {
        return Excel::download(new ExportPermission, 'permissions.xlsx');
    }

    // TODO: Code refactoring
    public function addPermissionsAuto()
    {
        $successMessage = __('messages.saved', ['model' => __('models/permissions.singular')]);
        $controllers = $this->getControllerNames();

        $permissions = [];

        foreach ($controllers as $controller) {
            $permission = str_replace(['Controller', '@'], ['', '-'], $controller);
            $permission = implode('-', array_reverse(explode('-', $permission)));
            $permissions[] = $permission;
        }

        foreach ($permissions as $permission) {
            $this->createPermissionsForController($permission);
        }

        flash($successMessage)->success()->important();
        return redirect()->back();
    }

    private function getControllerNames()
    {
        $controllerNames = [];

        foreach (Route::getRoutes()->getRoutes() as $route) {
            $action = $route->getAction();

            if (array_key_exists('controller', $action)) {
                $fullControllerName = $action['controller'];

                if (strpos($fullControllerName, 'App\Http\Controllers\\') === 0 && strpos($fullControllerName, 'App\Http\Controllers\Auth\\') !== 0) {
                    $controllerNames[] = str_replace('App\Http\Controllers\\', '', $fullControllerName);
                }
            }
        }

        return $controllerNames;
    }

    private function createPermissionsForController($permission)
    {
        if (!Permission::where('name', $permission)->exists()) {
            Permission::create(['name' => $permission]);
        }
    }

    public function showRolePermission($id)
    {
        
        $user = User::findOrFail($id);

        $userRole = $user->roles;

        $userPermissions = $user->permissions()->pluck('id')->toArray(); 
        $roles = Role::pluck('name','id');
        
        $permissions = Permission::pluck('name', 'id');

        return view('gestion_permissions.create', compact('userRole', 'roles', 'permissions', 'userPermissions', 'user'));
    }

    public function assignRolePermission(Request $request)
    {

        $user = User::where('name', $request->input('user'))->first();
        $removeAssignRoles = false;
        $removeAssignPermissions = false;

        if ($user) {
            $role = Role::find($request->input('role'));
            $permissions = Permission::find($request->input('permissions', []));

            if ($role) {
                $user->assignRole($role);
            } else {
                $removeAssignRoles = $user->syncRoles([]);
            }

            if ($permissions) {
                $user->syncPermissions($permissions);
            } else {
                $removeAssignPermissions = $user->syncPermissions([]);
            }

            if ($removeAssignRoles) {
                return back()->with('success', 'Rôle supprimé avec succès.');
            }elseif($removeAssignPermissions){
                return back()->with('success', 'Autorisation supprimée avec succès.');
            }elseif($removeAssignRoles && $removeAssignPermissions){
                return back()->with('success', 'Rôle et autorisation supprimés avec succès.');
            }

            return back()->with('success', 'Rôle et autorisation attribués avec succès.');
        } else {
            return back()>with('error', 'Utilisateur non trouvé.');
        }
    }

    
    
    

    // public function import(Request $request)
    // {
    //     Excel::import(new ImportPermissions, $request->file('file')->store('files'));
    //     return redirect()->back();
    // }
}
