<?php

namespace App\Policies;

use App\User;
use App\Entreprise;
use Illuminate\Auth\Access\HandlesAuthorization;

class EntreprisePolicy
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
	 * Verification si l'utilisateur courant est proprietaire de ce a quoi il veut acceder
	 * @param User $user
	 * @param User $model
	 * @return bool
	 */
	public function proprietary(User $user, Entreprise $model)
	{
		return $user->id == $model->user_id;
	}
}
