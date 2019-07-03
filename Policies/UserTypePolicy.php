<?php

namespace App\Policies;

use App\User;
use App\UserType;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserTypePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the userType.
     *
     * @param  \App\User  $user
     * @param  \App\UserType  $userType
     * @return mixed
     */
    public function view(User $user, UserType $userType)
    {
        //
    }

    /**
     * Determine whether the user can create userTypes.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the userType.
     *
     * @param  \App\User  $user
     * @param  \App\UserType  $userType
     * @return mixed
     */
    public function update(User $user, UserType $userType)
    {
        //
    }

    /**
     * Determine whether the user can delete the userType.
     *
     * @param  \App\User  $user
     * @param  \App\UserType  $userType
     * @return mixed
     */
    public function delete(User $user, UserType $userType)
    {
        //
    }
}
