<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\RoleAbility;
use Illuminate\Http\Request;

class RolesController extends Controller
{

    public function __construct()
    {
        // instead of {$this->authorize('methodName', Role::class)} inside each method of this controller
        $this->authorizeResource(
            Role::class,
            // 'role', // Route::get('/admin/dashboard/roles/{role}', [RolesController::class, 'show'])
            // [
            //     'except' => ['index', 'show'],
            //     'only' => ['edit', 'update', 'destroy'],
            // ]
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $roles = Role::paginate();
        return view('dashboard.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.roles.create', ['roles' => new Role()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->post());
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'abilities' => 'required|array|min:1',
        ]);

        $role = Role::createWithAbilities($request);

        return redirect()->route('dashboard.roles.index')->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        // $role = Role::findOrFail($id);
        // $this->authorize('view', $role);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        // $role = $role->load('abilities');
        $role_abilities = $role->abilities()->pluck('type', 'ability')->toArray(); // pluck('type', 'ability') to get array like ['categories.view' => 'allow', .... ]

        // dd($role);
        return view('dashboard.roles.edit', compact('role', 'role_abilities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $role->updateWithAbilities($request);

        return redirect()->route('dashboard.roles.index')->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Role::destroy($id);
        // already (cascade) delete in 'role_abilities' table via foreign key in the migration file
        return redirect()->route('dashboard.roles.index')->with('success', 'Role deleted successfully.');
    }
}
