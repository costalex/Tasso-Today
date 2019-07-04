<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

	/**
	 * Verification du status admin obligatoire
	 * @param User $user
	 * @return bool
	 */
    public function isAdmin(User $user)
    {
    	return $user->isAdmin();
    }

	/**
	 * Verification si l'utilisateur courant est proprietaire de ce a quoi il veut acceder
	 * @param User $user
	 * @param User $model
	 * @return bool
	 */
    public function proprietary(User $user, User $model)
    {
	    return $user->id === $model->id;
    }
}
