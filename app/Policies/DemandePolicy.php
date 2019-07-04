<?php

namespace App\Policies;

use App\User;
use App\Demande;
use Illuminate\Auth\Access\HandlesAuthorization;

class DemandePolicy
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
}
