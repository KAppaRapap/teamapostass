<?php

namespace App\Policies;

use App\Models\Draw;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DrawPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Draw  $draw
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Draw $draw)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        // Only admins can create draws
        return $user->is_admin;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Draw  $draw
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Draw $draw)
    {
        // Only admins can update draws
        return $user->is_admin;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Draw  $draw
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Draw $draw)
    {
        // Only admins can delete draws
        return $user->is_admin;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Draw  $draw
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Draw $draw)
    {
        // Only admins can restore draws
        return $user->is_admin;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Draw  $draw
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Draw $draw)
    {
        // Only admins can force delete draws
        return $user->is_admin;
    }
}
