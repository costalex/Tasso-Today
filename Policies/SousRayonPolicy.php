<?php

namespace App\Policies;

use App\User;
use App\SousRayon;
use Illuminate\Auth\Access\HandlesAuthorization;

class SousRayonPolicy
{
    use HandlesAuthorization;

	//vois plus tard si besoin d'une securitée renforcée en plus de getAbonnementLimitationFor
	//avec la recherche par user_id et les aborts
}
