<?php

namespace App\Policies;

use App\User;
use App\Etagere;
use Illuminate\Auth\Access\HandlesAuthorization;

class EtagerePolicy
{
    use HandlesAuthorization;

	/**
	 * Autorisation forcée
	 * @param User $user
	 * @return bool
	 */
	public function view(User $user)
	{
		return true;
	}
}
