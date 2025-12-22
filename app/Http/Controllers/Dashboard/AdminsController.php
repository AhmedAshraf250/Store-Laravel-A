<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\Request;

class AdminsController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Admin::class, 'admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admins = Admin::with('roles')->paginate();
        return view('dashboard.admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.admins.create', [
            'roles' => Role::all(),
            'admin' => new Admin()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'roles' => 'required|array|min:1',
        ]);

        $admin = Admin::create($request->only('name', 'email'));
        $admin->roles()->attach($request->roles);

        return redirect()->route('dashboard.admins.index')->with('success', 'Admin created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin)
    {
        $roles = Role::all();
        $admin->load('roles'); // load()  → Lazy Eager Loading (Use on already fetched Model/Collection) || with()  → Eager Loading (Use in Query Builder - BEFORE fetching)
        // $admin_roles = $admin->roles()->pluck('id')->toArray();
        return view('dashboard.admins.edit', [
            'admin' => $admin,
            'roles' => $roles,
            // 'admin_roles' => $admin_roles
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $idvc
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'roles' => 'required|array|min:1',
        ]);

        $admin->update($request->only('name', 'email'));
        $admin->roles()->sync($request->roles);

        return redirect()->route('dashboard.admins.index')->with('success', 'Admin created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Admin::destroy($id);
        return redirect()->route('dashboard.admins.index')->with('success', 'Admin deleted successfully');
    }
}
