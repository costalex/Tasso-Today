<?php

namespace App\Policies;

use App\User;
use App\FamilleProduit;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class FamilleProduitPolicy
{
    use HandlesAuthorization;

	public function before(User $user, $ability)
	{
		if(Auth::check() && $user->isAdmin())
			return true;
	}

	/**
     * Determine whether the user can view the familleProduit.
     *
     * @param  \App\User  $user
     * @param  \App\FamilleProduit  $familleProduit
     * @return mixed
     */
    public function view(User $user)
    {
		return true;
    }
}
