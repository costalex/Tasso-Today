<?php

namespace App\Policies;

use App\User;
use App\Groupe;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the groupe.
     *
     * @param  \App\User  $user
     * @param  \App\Groupe  $groupe
     * @return mixed
     */
    public function view(User $user, Groupe $groupe)
    {
        //
    }

    /**
     * Determine whether the user can create groupes.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the groupe.
     *
     * @param  \App\User  $user
     * @param  \App\Groupe  $groupe
     * @return mixed
     */
    public function update(User $user, Groupe $groupe)
    {
        //
    }

    /**
     * Determine whether the user can delete the groupe.
     *
     * @param  \App\User  $user
     * @param  \App\Groupe  $groupe
     * @return mixed
     */
    public function delete(User $user, Groupe $groupe)
    {
        //
    }
}
