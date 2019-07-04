<?php

namespace App\Policies;

use App\User;
use App\CodePromoStat;
use Illuminate\Auth\Access\HandlesAuthorization;

class CodePromoStatPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the codePromoStat.
     *
     * @param  \App\User  $user
     * @param  \App\CodePromoStat  $codePromoStat
     * @return mixed
     */
    public function view(User $user, CodePromoStat $codePromoStat)
    {
        //
    }

    /**
     * Determine whether the user can create codePromoStats.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the codePromoStat.
     *
     * @param  \App\User  $user
     * @param  \App\CodePromoStat  $codePromoStat
     * @return mixed
     */
    public function update(User $user, CodePromoStat $codePromoStat)
    {
        //
    }

    /**
     * Determine whether the user can delete the codePromoStat.
     *
     * @param  \App\User  $user
     * @param  \App\CodePromoStat  $codePromoStat
     * @return mixed
     */
    public function delete(User $user, CodePromoStat $codePromoStat)
    {
        //
    }
}
