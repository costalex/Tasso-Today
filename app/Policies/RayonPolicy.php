<?php

namespace App\Policies;

use App\User;
use App\Rayon;
use Illuminate\Auth\Access\HandlesAuthorization;

class RayonPolicy
{
    use HandlesAuthorization;

    //vois plus tard si besoin d'une securitée renforcée en plus de getAbonnementLimitationFor
	//avec la recherche par user_id et les aborts
}
