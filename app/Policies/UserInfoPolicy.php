<?php

namespace App\Policies;

use App\Models\User;
use App\Models\User_info;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserInfoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User_info  $userInfo
     * @return mixed
     */
    public function view(User $user, User_info $userInfo)
    {
        return ($user && ($user->id == $userInfo->user_id) || ($user->hasPermission('view_info')));
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User_info  $userInfo
     * @return mixed
     */
    public function update(User $user)
    {
        return ( $user && ($user->hasRole('admin')));
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User_info  $userInfo
     * @return mixed
     */
    public function delete(User $user, User_info $userInfo)
    {
        return ($user && ($user->hasPermission('delete_info')));
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User_info  $userInfo
     * @return mixed
     */
    public function restore(User $user, User_info $userInfo)
    {
        return ($user && ($user->id == $userInfo->user_id) || ($user->hasPermission('restore_info')));
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User_info  $userInfo
     * @return mixed
     */
    public function forceDelete(User $user, User_info $userInfo)
    {
        //
    }
}
