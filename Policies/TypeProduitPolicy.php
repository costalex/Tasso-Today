<?php

namespace App\Policies;

use App\User;
use App\TypeProduit;
use Illuminate\Auth\Access\HandlesAuthorization;

class TypeProduitPolicy
{
    use HandlesAuthorization;

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
