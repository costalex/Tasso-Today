<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TypeActivitePolicy
{
    use HandlesAuthorization;

	public function before(User $user, $ability)
	{
		if($user->isAdmin())
			return true;
	}

	/**
	 * Retour forcé
	 * @param User $user
	 * @return bool
	 */
	public function view(User $user)
	{
		return true;
	}
}
