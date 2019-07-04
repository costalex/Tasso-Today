<?php

namespace App\Policies;

use App\User;
use App\PanierHistorique;
use Illuminate\Auth\Access\HandlesAuthorization;

class PanierHistoriquePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the panierHistorique.
     *
     * @param  \App\User  $user
     * @param  \App\PanierHistorique  $panierHistorique
     * @return mixed
     */
    public function view(User $user, PanierHistorique $panierHistorique)
    {
        //
    }

    /**
     * Determine whether the user can create panierHistoriques.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the panierHistorique.
     *
     * @param  \App\User  $user
     * @param  \App\PanierHistorique  $panierHistorique
     * @return mixed
     */
    public function update(User $user, PanierHistorique $panierHistorique)
    {
        //
    }

    /**
     * Determine whether the user can delete the panierHistorique.
     *
     * @param  \App\User  $user
     * @param  \App\PanierHistorique  $panierHistorique
     * @return mixed
     */
    public function delete(User $user, PanierHistorique $panierHistorique)
    {
        //
    }
}
