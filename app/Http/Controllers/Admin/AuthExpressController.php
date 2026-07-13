<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthExpressController extends Controller
{
    // Muestra la pantalla de Login
    public function mostrarLogin()
    {
        // Pasamos las categorías por si el layout general las sigue requiriendo en el menú
        $categorias = Categoria::all();
        return view('admin.auth.login', compact('categorias'));
    }

    // Procesa el intento de entrada
    public function conectar(Request $request)
    {
        $credenciales = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Intentamos autenticar con las credenciales provistas
        if (Auth::attempt($credenciales)) {
            $request->session()->regenerate(); // Seguridad contra ataques de fijación de sesión

            return redirect()->intended('/admin/libros');
        }

        // Si falla, volvemos atrás con un mensaje de error explícito
        return back()->withErrors([
            'email' => 'Las credenciales ingresadas no coinciden con nuestros registros de administración.',
        ])->onlyInput('email');
    }

    // Cierra la sesión segura
    public function salir(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}