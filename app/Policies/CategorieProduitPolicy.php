<?php

namespace App\Policies;

use App\User;
use App\CategorieProduit;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class CategorieProduitPolicy
{
    use HandlesAuthorization;

	/**
	 * Determine whether the user can view the CategorieProduit.
	 * @param User $user
	 * @return bool
	 */
	public function view(User $user)
	{
		return true;
	}
}
