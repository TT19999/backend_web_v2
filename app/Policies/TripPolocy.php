<?php

namespace App\Policies;

use App\Models\Trip;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TripPolocy
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
     * @param  \App\Models\Trips  $trips
     * @return mixed
     */
    public function view(User $user, Trip $trips)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create_trip');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Trips  $trips
     * @return mixed
     */
    public function update(User $user, Trip $trip)
    {
        return (($user->hasPermission('restore_trip') || ($user->id == $trip->user_id)));
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Trips  $trips
     * @return mixed
     */
    public function delete(User $user, Trip $trip)
    {
        return (($user->hasPermission('delete_trip') || ($user->id == $trip->user_id)));
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Trips  $trips
     * @return mixed
     */
    public function restore(User $user, Trip $trip)
    {
        return (($user->hasPermission('restore_trip') || ($user->id == $trips->user_id)));
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Trips  $trips
     * @return mixed
     */
    public function forceDelete(User $user, Trip $trip)
    {
        //
    }
}
