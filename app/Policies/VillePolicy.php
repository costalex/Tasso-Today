<?php

namespace App\Policies;

use App\User;
use App\Ville;
use Illuminate\Auth\Access\HandlesAuthorization;

class VillePolicy
{
    use HandlesAuthorization;

	public function view(User $user)
	{
		return true;
	}
}
