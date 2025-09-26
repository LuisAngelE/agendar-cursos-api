<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\Contraseña;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'nullable|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'phone' => 'required|string|max:20',
                'type_person' => 'required|integer|in:4,5',
                'birth_date' => 'nullable|date',
                'curp' => 'nullable|string|max:18|unique:users,curp,',
                'rfc' => 'required|string|max:13|unique:users,rfc,',
                'razon_social' => 'nullable|string|max:255',
                'representante_legal' => 'nullable|string|max:255',
                'domicilio_fiscal' => 'nullable|string|max:255',
            ], [
                'name.max' => 'El nombre no puede tener más de 255 caracteres.',
                'last_name.max' => 'El apellido no puede tener más de 255 caracteres.',
                'email.required' => 'El correo electrónico es obligatorio.',
                'email.email' => 'El correo electrónico debe ser una dirección válida.',
                'email.unique' => 'Este correo electrónico ya está registrado.',
                'password.required' => 'La contraseña es obligatoria.',
                'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
                'password.confirmed' => 'La confirmación de la contraseña no coincide.',
                'phone.required' => 'El teléfono es obligatorio.',
                'phone.max' => 'El teléfono no puede tener más de 20 caracteres.',
                'type_person.required' => 'Debes seleccionar un tipo de persona.',
                'type_person.in' => 'El tipo de persona seleccionado no es válido.',
                'curp.unique' => 'La CURP ya está registrada.',
                'rfc.required' => 'El RFC es obligatorio.',
                'rfc.unique' => 'El RFC ya está registrado.',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $data = $request->all();
            $data['password'] = Hash::make($request->password);
            $data['type_user'] = User::Student;

            $user = User::create($data);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Usuario registrado exitosamente',
                'user' => $user,
                'token' => $token
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Registro fallido',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string',
            ], [
                'email.required' => 'El correo electrónico es obligatorio.',
                'email.email' => 'El correo electrónico debe ser una dirección válida.',
                'password.required' => 'La contraseña es obligatoria.',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json(['error' => 'El usuario no existe.'], 404);
            }

            if (!Hash::check($request->password, $user->password)) {
                return response()->json(['error' => 'Contraseña incorrecta.'], 401);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Inicio de sesión exitoso',
                'user' => $user,
                'token' => $token
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error de inicio de sesión',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function me(Request $request)
    {
        try {
            $user = $request->user()->load('imageProfile');

            if (!$user->imageProfile) {
                $user->imageProfile = (object)[
                    'url' => asset('images/default.png')
                ];
            }

            return response()->json([
                'message' => 'Usuario autenticado',
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener el usuario',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'current_password' => 'required|string',
                'password' => 'required|string|min:8|confirmed',
            ], [
                'current_password.required' => 'La contraseña actual es obligatoria.',
                'password.required' => 'La nueva contraseña es obligatoria.',
                'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
                'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $user = $request->user();

            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'error' => 'La contraseña actual es incorrecta.'
                ], 401);
            }

            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json([
                'message' => 'Contraseña actualizada exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al actualizar la contraseña',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            $user = $request->user();

            $validator = Validator::make($request->all(), [
                'name' => 'nullable|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'phone' => 'required|string|max:20',
                'type_person' => 'required|integer|in:4,5',
                'birth_date' => 'nullable|date',
                'curp' => 'nullable|string|max:18|unique:users,curp,' . $user->id,
                'rfc' => 'required|string|max:13|unique:users,rfc,' . $user->id,
                'razon_social' => 'nullable|string|max:255',
                'representante_legal' => 'nullable|string|max:255',
                'domicilio_fiscal' => 'nullable|string|max:255',
                'collaborator_number' => 'nullable|string|max:10',
            ], [
                'email.required' => 'El correo electrónico es obligatorio.',
                'email.email' => 'El correo electrónico debe ser una dirección válida.',
                'email.unique' => 'Este correo electrónico ya está registrado.',
                'phone.required' => 'El teléfono es obligatorio.',
                'phone.max' => 'El teléfono no puede tener más de 20 caracteres.',
                'type_person.required' => 'Debes seleccionar un tipo de persona.',
                'type_person.in' => 'El tipo de persona seleccionado no es válido.',
                'curp.unique' => 'La CURP ya está registrada.',
                'rfc.required' => 'El RFC es obligatorio.',
                'rfc.unique' => 'El RFC ya está registrado.',
                'collaborator_number.max' => 'El número de colaborador no puede superar los 10 caracteres.',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $user->update($request->only([
                'name',
                'last_name',
                'email',
                'phone',
                'type_person',
                'birth_date',
                'curp',
                'rfc',
                'razon_social',
                'representante_legal',
                'domicilio_fiscal',
                'collaborator_number',
            ]));

            return response()->json([
                'message' => 'Perfil actualizado correctamente',
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al actualizar el perfil',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function profileImageUpdate(Request $request)
    {
        try {
            $user = $request->user();

            $validator = Validator::make($request->all(), [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ], [
                'image.required' => 'La imagen es obligatoria.',
                'image.image' => 'El archivo debe ser una imagen.',
                'image.mimes' => 'La imagen debe ser jpeg, png, jpg o gif.',
                'image.max' => 'La imagen no debe pesar más de 2MB.',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('profile', $filename, 'public');

            $url = asset('storage/' . $path);

            if ($user->imageProfile) {
                $oldPath = str_replace(asset('storage') . '/', '', $user->imageProfile->url);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }

                $user->imageProfile->update(['url' => $url]);
            } else {
                $user->imageProfile()->create(['url' => $url]);
            }

            return response()->json([
                'message' => 'Imagen de perfil actualizada correctamente',
                'image'   => ['url' => $url],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al actualizar la imagen de perfil',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function forgotPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
            ], [
                'email.required' => 'El correo electrónico es obligatorio.',
                'email.email' => 'El correo electrónico debe ser una dirección válida.',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'error' => 'El correo electrónico no está registrado en el sistema.'
                ], 404);
            }

            $randomPassword = Str::random(8);
            $user->password = Hash::make($randomPassword);
            $user->save();

            $url = url('http://localhost:3000/');

            if ($request->has('preview') && $request->preview == true) {
                return view('mail.contraseña', compact('randomPassword', 'user', 'url'));
            }

            try {
                Mail::to($user->email)->send(new Contraseña($randomPassword, $user, $url));
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'No se pudo enviar el correo',
                    'message' => $e->getMessage()
                ], 500);
            }

            return response()->json([
                'message' => 'Se ha enviado un correo electrónico con la nueva contraseña.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error en el proceso de recuperación de contraseña',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
