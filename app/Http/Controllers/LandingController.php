<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LandingController extends Controller
{
    
    public function showLandingPage()
    {
        return view('landing');
    }

    public function registerTenant(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'domain' => 'required|string|unique:domains,domain', // Verifica que el dominio no exista
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Crear el tenant
        $tenant = Tenant::create([
            'id' => uniqid(), // ID único para el tenant 
            'tenancy_data' => [
                'name' => $request->name,
            ],
        ]);

        // Asociar el dominio o subdominio al tenant
        $tenant->domains()->create([
            'domain' => $request->domain . '.kaelytechnology.test', // Cambia según la lógica deseada
        ]);

     
        return redirect()->route('landing.page')->with('success', 'Tenant registrado con éxito.');
    }
}
