<?php

namespace App\Policies;

use App\User;
use App\CodePromo;
use Illuminate\Auth\Access\HandlesAuthorization;

class CodePromoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the codePromo.
     *
     * @param  \App\User  $user
     * @param  \App\CodePromo  $codePromo
     * @return mixed
     */
    public function view(User $user, CodePromo $codePromo)
    {
        //
    }

    /**
     * Determine whether the user can create codePromos.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the codePromo.
     *
     * @param  \App\User  $user
     * @param  \App\CodePromo  $codePromo
     * @return mixed
     */
    public function update(User $user, CodePromo $codePromo)
    {
        //
    }

    /**
     * Determine whether the user can delete the codePromo.
     *
     * @param  \App\User  $user
     * @param  \App\CodePromo  $codePromo
     * @return mixed
     */
    public function delete(User $user, CodePromo $codePromo)
    {
        //
    }
}
