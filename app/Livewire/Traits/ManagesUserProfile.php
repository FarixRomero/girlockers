<?php

namespace App\Livewire\Traits;

use App\Models\User;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\WithFileUploads;

trait ManagesUserProfile
{
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public $avatar;
    public ?string $existingAvatar = null;
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Initialize profile fields from authenticated user
     */
    public function mountProfile(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->existingAvatar = $user->avatar;
    }

    /**
     * Update user profile information
     */
    public function updateProfile(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'avatar' => ['nullable', 'image', 'max:10240'], // 10MB max
        ]);

        // Handle avatar upload
        if ($this->avatar) {
            $fileUploadService = app(FileUploadService::class);
            $avatarPath = $fileUploadService->uploadImage(
                $this->avatar,
                'users/avatars',
                $this->existingAvatar
            );
            $validated['avatar'] = $avatarPath;
            $this->existingAvatar = $avatarPath;
            $this->reset('avatar');
        }

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        session()->flash('profile-updated', '¡Perfil actualizado exitosamente!');
        $this->dispatch('profile-saved');
    }

    /**
     * Update user password
     */
    public function updatePassword(): void
    {
        $validated = $this->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        session()->flash('password-updated', '¡Contraseña actualizada exitosamente!');
        $this->dispatch('password-saved');
    }
}
