<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\User;
use App\Models\FileEnkripsi;
use App\Policies\FileEnkripsiPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        FileEnkripsi::class => FileEnkripsiPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('view-user-management', function (User $user) {
            return in_array($user->role?->nama_roles, ['Super Admin', 'Admin']);
        });

        Gate::define('manage-users', function (User $user) {
            return $user->role?->nama_roles === 'Super Admin';
        });

        Gate::define('view-pengeluaran', function (User $user) {
            return in_array($user->role?->nama_roles, ['Super Admin', 'Admin', 'User']);
        });

        Gate::define('create-pengeluaran', function (User $user) {
        return in_array($user->role?->nama_roles, ['Super Admin', 'Admin', 'User']);
        });

        Gate::define('manage-pengeluaran', function (User $user) {
            return in_array($user->role?->nama_roles, ['Super Admin', 'Admin']);
        });

        Gate::define('edit-pengeluaran', function (User $user) {
            return $user->role?->nama_roles === 'Super Admin';
        });

        
        Gate::define('delete-pengeluaran', function (User $user) {
            return $user->role?->nama_roles === 'Super Admin';
        });

        Gate::define('view-barang', function (User $user) {
            return in_array($user->role?->nama_roles, ['Super Admin', 'Admin', 'User']);
        });

        Gate::define('manage-barang', function (User $user) {
            return in_array($user->role?->nama_roles, ['Super Admin', 'Admin']);
        });

        Gate::define('edit-barang-full', function (User $user) {
            return $user->role?->nama_roles === 'Super Admin';
        });

        Gate::define('edit-barang-terbatas', function (User $user) {
            return $user->role?->nama_roles === 'Admin';
        });

        Gate::define('delete-barang', function (User $user) {
            return $user->role?->nama_roles === 'Super Admin';
        });

        Gate::define('manage-master', function (User $user) {
            return in_array($user->role?->nama_roles, ['Super Admin', 'Admin']);
        });

        Gate::define('manage-sla', function (User $user) {
            $role = $user->role?->nama_roles; 
            $dept = strtoupper(trim($user->departemen?->data_master ?? '')); 
            $jabatan = strtoupper(trim($user->jabatan ?? ''));

            if ($role === 'Super Admin') {
                return true;
            }

            if ($role === 'Admin') {

                $isEngineeringDept = ($dept === 'ENGINEERING');
                $isGMDept = ($dept === 'GENERAL MANAGER');

                $isEngineerLeader = in_array($jabatan, ['HEAD OF ENGINEER', 'ASSISTANCE HEAD OF ENGINEER']);
                $isGMLeader = ($jabatan === 'GENERAL MANAGER');

                return ($isEngineeringDept && $isEngineerLeader) || ($isGMDept && $isGMLeader);
            }

            return false;
        });

        Gate::define('delete-sla', function (User $user) {
            return $user->role?->nama_roles === 'Super Admin';
        });

        Gate::define('manage-customer', function (User $user) {
            return in_array($user->role?->nama_roles, ['Super Admin', 'Admin'])
                || $user->departemen?->data_master === 'Sales & marketing';
        });

        Gate::define('delete-customer', function (User $user) {
            return $user->role?->nama_roles === 'Super Admin';
        });
    }
}