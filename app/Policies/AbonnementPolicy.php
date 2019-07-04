<?php

namespace App\Policies;

use App\User;
use App\Abonnement;
use Illuminate\Auth\Access\HandlesAuthorization;

class AbonnementPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the abonnement.
     *
     * @param  \App\User  $user
     * @param  \App\Abonnement  $abonnement
     * @return mixed
     */
    public function view(User $user, Abonnement $abonnement)
    {
        //
    }

    /**
     * Determine whether the user can create abonnements.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the abonnement.
     *
     * @param  \App\User  $user
     * @param  \App\Abonnement  $abonnement
     * @return mixed
     */
    public function update(User $user, Abonnement $abonnement)
    {
        //
    }

    /**
     * Determine whether the user can delete the abonnement.
     *
     * @param  \App\User  $user
     * @param  \App\Abonnement  $abonnement
     * @return mixed
     */
    public function delete(User $user, Abonnement $abonnement)
    {
        //
    }
}
