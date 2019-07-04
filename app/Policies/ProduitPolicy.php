<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class ProduitPolicy
{
    use HandlesAuthorization;

	public function before(User $user, $ability)
	{
		if(Auth::check() && $user->isAdmin())
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
}
