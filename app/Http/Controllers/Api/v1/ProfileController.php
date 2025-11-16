<?php

namespace App\Http\Controllers\Api\v1;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        return ResponseFormatter::success(new UserResource($user), 'Profil data berhasil diambil');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return new UserResource($user);
    }

    public function update(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return ResponseFormatter::error('Unauthenticated', 401);
        }

        $validator = Validator::make($request->all(), [
            'name'              => 'nullable|string|max:255',
            'status'            => 'nullable|string|max:100',
            'email'             => 'nullable|email|unique:users,email,' . $user->id,
            'profile_photo_url' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'delete_photo'      => 'nullable|in:true,false,1,0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            // Update user table
            if ($request->filled('email')) {
                $user->email = $request->input('email');
            }
            if ($request->filled('name')) {
                $user->name = $request->input('name');
            }
            if ($request->filled('status')) {
                $user->status = $request->input('status');
            }

            // Handle profile photo deletion
            $deletePhoto = $request->input('delete_photo');
            if ($deletePhoto === true || $deletePhoto === 'true' || $deletePhoto === '1' || $deletePhoto === 1) {
                // Delete photo from storage if exists
                if ($user->profile_photo_path) {
                    Storage::disk('public')->delete($user->profile_photo_path);
                }
                // Clear photo path from database
                $user->profile_photo_path = null;
            }
            // Handle profile photo upload
            elseif ($request->hasFile('profile_photo_url')) {
                // Delete old photo if exists
                if ($user->profile_photo_path) {
                    Storage::disk('public')->delete($user->profile_photo_path);
                }

                $file = $request->file('profile_photo_url');
                $path = $file->store('profile-photos', 'public');
                $user->profile_photo_path = $path;
            }

            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui',
                'data'    => new UserResource($user->fresh()),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui profil',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
