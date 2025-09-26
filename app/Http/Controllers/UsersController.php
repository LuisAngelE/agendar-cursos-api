<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::all();

        return response()->json([
            'success' => true,
            'data' => $users,
        ], 200);
    }

    public function indexFisicas()
    {
        $fisicas = User::where('type_person', 4)->get();

        return response()->json([
            'success' => true,
            'data' => $fisicas,
        ], 200);
    }

    public function indexMorales()
    {
        $morales = User::where('type_person', 5)->get();

        return response()->json([
            'success' => true,
            'data' => $morales,
        ], 200);
    }

    public function instructores()
    {
        $instructores = User::where('type_user', 2)->get();

        return response()->json([
            'success' => true,
            'data' => $instructores,
        ], 200);
    }

    public function storeFisica(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'birth_date' => 'required|date',
                'curp' => 'required|string|max:18|unique:users,curp',
                'rfc' => 'required|string|max:13|unique:users,rfc',
                'email' => 'required|string|email|max:255|unique:users,email',
                'phone' => 'required|string|max:20',
                'type_user' => 'required|integer|in:1,2,3',
                'password' => 'required|string|min:8|confirmed',
                'collaborator_number' => 'nullable|string|max:10',
            ], [
                'name.required' => 'El nombre es obligatorio.',
                'name.max' => 'El nombre no puede tener más de 255 caracteres.',
                'last_name.required' => 'El apellido es obligatorio.',
                'last_name.max' => 'El apellido no puede tener más de 255 caracteres.',
                'birth_date.required' => 'La fecha de nacimiento es obligatoria.',
                'birth_date.date' => 'La fecha de nacimiento no es válida.',
                'curp.required' => 'La CURP es obligatoria.',
                'curp.max' => 'La CURP no puede tener más de 18 caracteres.',
                'curp.unique' => 'La CURP ya está registrada.',
                'rfc.required' => 'El RFC es obligatorio.',
                'rfc.max' => 'El RFC no puede tener más de 13 caracteres.',
                'rfc.unique' => 'El RFC ya está registrado.',
                'email.required' => 'El correo electrónico es obligatorio.',
                'email.email' => 'El correo electrónico debe ser válido.',
                'email.max' => 'El correo electrónico no puede superar los 255 caracteres.',
                'email.unique' => 'El correo electrónico ya está registrado.',
                'phone.required' => 'El teléfono es obligatorio.',
                'phone.max' => 'El teléfono no puede superar los 20 caracteres.',
                'type_user.required' => 'El tipo de usuario es obligatorio.',
                'type_user.in' => 'El tipo de usuario seleccionado no es válido.',
                'password.required' => 'La contraseña es obligatoria.',
                'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
                'password.confirmed' => 'La confirmación de la contraseña no coincide.',
                'collaborator_number.max' => 'El número de colaborador no puede superar los 10 caracteres.',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $data = $request->only([
                'name',
                'last_name',
                'birth_date',
                'curp',
                'rfc',
                'email',
                'phone',
                'type_user',
                'collaborator_number',
            ]);

            $data['password'] = Hash::make($request->password);
            $data['type_person'] = User::Fisica;

            $user = User::create($data);

            return response()->json([
                'message' => 'Usuario creado exitosamente',
                'data' => $user,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Creación fallida',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function storeMorales(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'razon_social'        => 'required|string|max:255',
                'rfc'                 => 'required|string|max:13|unique:users,rfc',
                'representante_legal' => 'required|string|max:255',
                'domicilio_fiscal'    => 'required|string|max:255',
                'email'               => 'required|string|email|max:255|unique:users,email',
                'phone'               => 'required|string|max:20',
                'type_user'           => 'required|integer|in:1,2,3',
                'password'            => 'required|string|min:8|confirmed',
                'collaborator_number' => 'nullable|string|max:10',
            ], [
                'razon_social.required' => 'La razón social es obligatoria.',
                'razon_social.max' => 'La razón social no puede superar los 255 caracteres.',
                'rfc.required' => 'El RFC es obligatorio.',
                'rfc.max' => 'El RFC no puede superar los 13 caracteres.',
                'rfc.unique' => 'El RFC ya está registrado.',
                'representante_legal.required' => 'El representante legal es obligatorio.',
                'representante_legal.max' => 'El nombre del representante legal no puede superar los 255 caracteres.',
                'domicilio_fiscal.required' => 'El domicilio fiscal es obligatorio.',
                'domicilio_fiscal.max' => 'El domicilio fiscal no puede superar los 255 caracteres.',
                'email.required' => 'El correo electrónico es obligatorio.',
                'email.email' => 'El correo electrónico debe ser válido.',
                'email.max' => 'El correo electrónico no puede superar los 255 caracteres.',
                'email.unique' => 'El correo electrónico ya está registrado.',
                'phone.required' => 'El teléfono es obligatorio.',
                'phone.max' => 'El teléfono no puede superar los 20 caracteres.',
                'type_user.required' => 'El tipo de usuario es obligatorio.',
                'type_user.in' => 'El tipo de usuario seleccionado no es válido.',
                'password.required' => 'La contraseña es obligatoria.',
                'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
                'password.confirmed' => 'La confirmación de la contraseña no coincide.',
                'collaborator_number.max' => 'El número de colaborador no puede superar los 10 caracteres.',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $data = $request->only([
                'razon_social',
                'rfc',
                'representante_legal',
                'domicilio_fiscal',
                'email',
                'phone',
                'type_user',
                'collaborator_number',
            ]);

            $data['password'] = Hash::make($request->password);
            $data['type_person'] = User::Moral;

            $user = User::create($data);

            return response()->json([
                'message' => 'Usuario creado exitosamente',
                'data' => $user,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Creación fallida',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        return response()->json($user);
    }

    public function updateFisica(Request $request, string $id)
    {
        try {
            $user = User::find($id);

            if (!$user || $user->type_person !== User::Fisica) {
                return response()->json(['error' => 'Usuario no encontrado o no es persona física'], 404);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'last_name' => 'sometimes|required|string|max:255',
                'birth_date' => 'sometimes|required|date',
                'curp' => 'sometimes|required|string|max:18|unique:users,curp,' . $id,
                'rfc' => 'sometimes|required|string|max:13|unique:users,rfc,' . $id,
                'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
                'phone' => 'sometimes|required|string|max:20',
                'type_user' => 'sometimes|required|integer|in:1,2,3',
                'password' => 'nullable|string|min:8|confirmed',
                'collaborator_number' => 'nullable|string|max:10',
            ], [
                'name.required' => 'El nombre es obligatorio.',
                'name.max' => 'El nombre no puede superar los 255 caracteres.',
                'last_name.required' => 'El apellido es obligatorio.',
                'last_name.max' => 'El apellido no puede superar los 255 caracteres.',
                'birth_date.required' => 'La fecha de nacimiento es obligatoria.',
                'birth_date.date' => 'La fecha de nacimiento no es válida.',
                'curp.required' => 'La CURP es obligatoria.',
                'curp.max' => 'La CURP no puede superar los 18 caracteres.',
                'curp.unique' => 'La CURP ya está registrada.',
                'rfc.required' => 'El RFC es obligatorio.',
                'rfc.max' => 'El RFC no puede superar los 13 caracteres.',
                'rfc.unique' => 'El RFC ya está registrado.',
                'email.required' => 'El correo electrónico es obligatorio.',
                'email.email' => 'El correo electrónico debe ser válido.',
                'email.max' => 'El correo electrónico no puede superar los 255 caracteres.',
                'email.unique' => 'El correo electrónico ya está registrado.',
                'phone.required' => 'El teléfono es obligatorio.',
                'phone.max' => 'El teléfono no puede superar los 20 caracteres.',
                'type_user.required' => 'El tipo de usuario es obligatorio.',
                'type_user.in' => 'El tipo de usuario seleccionado no es válido.',
                'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
                'password.confirmed' => 'La confirmación de la contraseña no coincide.',
                'collaborator_number.max' => 'El número de colaborador no puede superar los 10 caracteres.',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $data = $request->only([
                'name',
                'last_name',
                'birth_date',
                'curp',
                'rfc',
                'email',
                'phone',
                'type_user',
                'collaborator_number',
            ]);

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $data['type_person'] = User::Fisica;

            $user->update($data);

            return response()->json([
                'message' => 'Persona física actualizada exitosamente',
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Actualización fallida',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateMorales(Request $request, string $id)
    {
        try {
            $user = User::find($id);

            if (!$user || $user->type_person !== User::Moral) {
                return response()->json([
                    'error' => 'Usuario no encontrado o no es persona moral',
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'razon_social'        => 'sometimes|required|string|max:255',
                'rfc'                 => 'sometimes|required|string|max:13|unique:users,rfc,' . $id,
                'representante_legal' => 'sometimes|required|string|max:255',
                'domicilio_fiscal'    => 'sometimes|required|string|max:255',
                'email'               => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
                'phone'               => 'sometimes|required|string|max:20',
                'type_user'           => 'sometimes|required|integer|in:1,2,3',
                'password'            => 'nullable|string|min:8|confirmed',
                'collaborator_number' => 'nullable|string|max:10',
            ], [
                'razon_social.required' => 'La razón social es obligatoria.',
                'razon_social.max' => 'La razón social no puede superar los 255 caracteres.',
                'rfc.required' => 'El RFC es obligatorio.',
                'rfc.max' => 'El RFC no puede superar los 13 caracteres.',
                'rfc.unique' => 'El RFC ya está registrado.',
                'representante_legal.required' => 'El representante legal es obligatorio.',
                'representante_legal.max' => 'El nombre del representante legal no puede superar los 255 caracteres.',
                'domicilio_fiscal.required' => 'El domicilio fiscal es obligatorio.',
                'domicilio_fiscal.max' => 'El domicilio fiscal no puede superar los 255 caracteres.',
                'email.required' => 'El correo electrónico es obligatorio.',
                'email.email' => 'El correo electrónico debe ser válido.',
                'email.max' => 'El correo electrónico no puede superar los 255 caracteres.',
                'email.unique' => 'El correo electrónico ya está registrado.',
                'phone.required' => 'El teléfono es obligatorio.',
                'phone.max' => 'El teléfono no puede superar los 20 caracteres.',
                'type_user.required' => 'El tipo de usuario es obligatorio.',
                'type_user.in' => 'El tipo de usuario seleccionado no es válido.',
                'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
                'password.confirmed' => 'La confirmación de la contraseña no coincide.',
                'collaborator_number.max' => 'El número de colaborador no puede superar los 10 caracteres.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 422);
            }

            $data = $request->only([
                'razon_social',
                'rfc',
                'representante_legal',
                'domicilio_fiscal',
                'email',
                'phone',
                'type_user',
                'collaborator_number',
            ]);

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $data['type_person'] = User::Moral;

            $user->update($data);

            return response()->json([
                'message' => 'Persona moral actualizada exitosamente',
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Actualización fallida',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }

            $user->delete();

            return response()->json([
                'message' => 'Usuario eliminado exitosamente',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Eliminación fallida',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
