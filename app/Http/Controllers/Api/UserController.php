<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\FaceModel;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class UserController extends Controller
{
    public function addLocation(Request $request)
    {
        $user_id = auth()->user()->id;

        $user = User::find($user_id);
        if (!$user->employee->location) {
            $location = Location::create([
                'location_name' => $request->location_name,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'is_office' => 0,
                'company_id' => $user->employee->company_id,
            ]);

            $user->employee->update(['location_id' => $location->id]);

            $respon = [
                'error' => false,
                'status_code' => 200,
                'message' => 'Lokasi berhasil ditambah',
                'redirect' => 'HomeScreen'
            ];
            return response()->json($respon, 200);
        }

        $user->employee->location->update([
            'location_name' => $request->location_name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'is_office' => 0,
            'company_id' => $user->employee->company_id,
        ]);

        $respon = [
            'error' => false,
            'status_code' => 200,
            'message' => 'Lokasi Berhasil Diupdate',
            'redirect' => 'HomeScreen'
        ];
        return response()->json($respon, 200);
    }

    public function getUserInfo(Request $request)
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);

        $respon = [
            'error' => false,
            'status_code' => 200,
            'data' => new UserResource($user)
        ];
        return response()->json($respon, 200);
    }

    public function uploadFace(Request $request)
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);


        FaceModel::create([
            'face_key' => $request->key,
            'face_value' => $request->value,
            'employe_id' => $user->employee->id
        ]);

        $respon = [
            'error' => false,
            'status_code' => 200,
        ];
        return response()->json($respon, 200);
    }

    public function getFace()
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        $faces = FaceModel::where('employe_id', $user->employee->id)->first();

        if ($faces) {
            $respon = [
                'key' =>  $faces->face_key,
                'value' =>  $faces->face_value,
            ];
            return response()->json($respon, 200);
        }

        $respon = [
            'key' =>  "",
            'value' =>  "{}",
        ];
        return response()->json($respon, 200);
    }

    public function updatePassword(Request $request)
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);

        if (Hash::check($request->oldPassword, $user->password)) {
            $user->update(['password' => Hash::make($request->newPassword)]);
            $respon = [
                'error' => false,
                'status_code' => 200,
                'message' => 'Kata sandi berhasil diperbarui'
            ];
            return response()->json($respon, 200);
        }

        $respon = [
            'error' => false,
            'status_code' => 401,
            'message' => 'Kata sandi sebelumnya tidak sesuai'
        ];
        return response()->json($respon, 401);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        $respon = [
            'error' => false,
            'status_code' => 200,
            'message' => 'Logout Berhasil'
        ];
        return response()->json($respon, 200);
    }

    public function updateProfile(Request $request)
    {
        $user_id = auth()->user()->id;

        $user = User::find($user_id);
        if (!$request->hasFile('foto_karyawan')) {
            return response()->json(['upload_file_not_found'], 400);
        }
        $file = $request->file('foto_karyawan');
        if (!$file->isValid()) {
            return response()->json(['invalid_file_upload'], 400);
        }
        $file = $request->foto_karyawan->store('foto_karyawan', 'public');

        $user->update(['profile_photo_path' => $file]);
        $respon = [
            'error' => false,
            'status_code' => 200,
            'message' => 'Profile berhasil diubah',
            'data' => [
                'foto_karyawan' => asset('storage/' . $file),
            ]

        ];
        return response()->json($respon, 200);
    }
}
