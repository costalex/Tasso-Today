<?php

namespace App\Policies;

use App\User;
use App\MarqueProduit;
use Illuminate\Auth\Access\HandlesAuthorization;

class MarqueProduitPolicy
{
    use HandlesAuthorization;

	public function before(User $user, $ability)
	{
		if($user->isAdmin())
			return true;
	}

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
	 * Determine whether the user can view the MarqueProduit.
	 * @param User $user
	 * @return bool
	 */
	public function view(User $user)
	{
		return false;
	}
}
