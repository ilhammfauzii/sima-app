<?php

namespace App\Policies;

use App\Models\User;
use App\Models\FileEnkripsi;

class FileEnkripsiPolicy
{

    public function isUploader(User $user, FileEnkripsi $file)
    {
        return $file->diupload_oleh === $user->id;
    }

    public function isReceiver(User $user, FileEnkripsi $file)
    {
        if (!$file->penerima) {
            return false;
        }

        foreach ($file->penerima as $p) {
            if (($p['user_id'] ?? null) == $user->id) {
                return true;
            }
        }

        return false;
    }

    public function download(User $user, FileEnkripsi $file)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $this->isUploader($user,$file)
            || $this->isReceiver($user,$file);
    }

    public function downloadEncrypted(User $user, FileEnkripsi $file)
    {
        return $user->isSuperAdmin();
    }

    public function decrypt(User $user, FileEnkripsi $file)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $this->download($user,$file);
    }

    public function delete(User $user, FileEnkripsi $file)
    {
        return $user->isSuperAdmin();
    }

    public function updateKadaluarsa(User $user, FileEnkripsi $file)
    {
        return $user->isSuperAdmin();
    }

}