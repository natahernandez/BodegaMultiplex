<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminUserController extends Controller
{
    /**
     * Display a listing of admin users.
     */
    public function index(Request $request)
    {
        $this->setSimplePage('Administradores', 'Gestiona los usuarios administradores del sistema');
        
        $query = User::where('role', 1); // Solo administradores

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('name')->paginate(15)->appends($request->query());

        // Estadísticas
        $stats = [
            'total_admins' => User::where('role', 1)->count(),
            'total_clients' => User::where('role', 2)->count(),
            'total_users' => User::count(),
            'recent_admins' => User::where('role', 1)->where('created_at', '>=', now()->subDays(30))->count(),
        ];

        return view('pages.admin-users.index', compact('users', 'stats'));
    }

    /**
     * Show the form for creating a new admin user.
     */
    public function create()
    {
        $this->setSimplePage('Nuevo Administrador', 'Crear un nuevo usuario administrador');
        
        return view('pages.admin-users.create');
    }

    /**
     * Store a newly created admin user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 1, // Administrador
            'email_verified_at' => now(), // Auto-verificado
        ]);

        return redirect()->route('admin-users.index')->with('success', 'Administrador creado exitosamente.');
    }

    /**
     * Display the specified admin user.
     */
    public function show(User $adminUser)
    {
        // Verificar que es administrador
        if (!$adminUser->isAdmin()) {
            abort(404);
        }

        $this->setSimplePage('Detalles del Administrador', 'Información del usuario administrador');
        
        return view('pages.admin-users.show', compact('adminUser'));
    }

    /**
     * Show the form for editing the specified admin user.
     */
    public function edit(User $adminUser)
    {
        // Verificar que es administrador
        if (!$adminUser->isAdmin()) {
            abort(404);
        }

        $this->setSimplePage('Editar Administrador', 'Modificar información del administrador');
        
        return view('pages.admin-users.edit', compact('adminUser'));
    }

    /**
     * Update the specified admin user in storage.
     */
    public function update(Request $request, User $adminUser)
    {
        // Verificar que es administrador
        if (!$adminUser->isAdmin()) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $adminUser->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $adminUser->name = $validated['name'];
        $adminUser->email = $validated['email'];
        
        if (!empty($validated['password'])) {
            $adminUser->password = Hash::make($validated['password']);
        }
        
        $adminUser->save();

        return redirect()->route('admin-users.index')->with('success', 'Administrador actualizado exitosamente.');
    }

    /**
     * Remove the specified admin user from storage.
     */
    public function destroy(User $adminUser)
    {
        // Verificar que es administrador
        if (!$adminUser->isAdmin()) {
            abort(404);
        }

        // No permitir eliminar el último administrador
        if (User::where('role', 1)->count() <= 1) {
            return redirect()->route('admin-users.index')->with('error', 'No se puede eliminar el último administrador del sistema.');
        }

        // No permitir auto-eliminación
        if ($adminUser->id === auth()->id()) {
            return redirect()->route('admin-users.index')->with('error', 'No puedes eliminarte a ti mismo.');
        }

        $adminUser->delete();

        return redirect()->route('admin-users.index')->with('success', 'Administrador eliminado exitosamente.');
    }

    /**
     * Set page metadata
     */
    protected function setSimplePage($title, $description = '')
    {
        view()->share('pageTitle', $title);
        view()->share('pageDescription', $description);
    }
}