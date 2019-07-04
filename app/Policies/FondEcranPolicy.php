<?php

namespace App\Policies;

use App\User;
use App\FondEcran;
use Illuminate\Auth\Access\HandlesAuthorization;

class FondEcranPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the fondEcran.
     *
     * @param  \App\User  $user
     * @param  \App\FondEcran  $fondEcran
     * @return mixed
     */
    public function view(User $user, FondEcran $fondEcran)
    {
        //
    }

    /**
     * Determine whether the user can create fondEcrans.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the fondEcran.
     *
     * @param  \App\User  $user
     * @param  \App\FondEcran  $fondEcran
     * @return mixed
     */
    public function update(User $user, FondEcran $fondEcran)
    {
        //
    }

    /**
     * Determine whether the user can delete the fondEcran.
     *
     * @param  \App\User  $user
     * @param  \App\FondEcran  $fondEcran
     * @return mixed
     */
    public function delete(User $user, FondEcran $fondEcran)
    {
        //
    }
}
