<?php

namespace App\Policies;

use App\User;
use App\Couleur;
use Illuminate\Auth\Access\HandlesAuthorization;

class CouleurPolicy
{
    use HandlesAuthorization;

	/**
     * Determine whether the user can view the couleur.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function view(User $user)
    {
		return true;
    }
}
