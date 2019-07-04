<?php

namespace App\Policies;

use App\User;
use App\TypeEntreprise;
use Illuminate\Auth\Access\HandlesAuthorization;

class TypeEntreprisePolicy
{
    use HandlesAuthorization;

	public function before(User $user, $ability)
	{
		if($user->isAdmin())
			return true;
	}

	public function view(User $user)
	{
		return true;
	}
}
