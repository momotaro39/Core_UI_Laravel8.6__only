<?php

namespace App\Policies;

use App\Models\BandMembers;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BandMemberPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     * ユーザーのバンドIDとバンドメンバーのIDがあえば情報を見ることができる
     * 管理者は全バンドのバンドメンバーを閲覧できる
     * それ以外は 自分が所属するバンドのメンバーだけ閲覧できる
     * @param  \App\Models\User  $user
     * @param  \App\Models\BandMembers  $bandMembers
     * @return mixed
     */
    public function view(User $user, BandMembers $bandMembers)
    {
        return $user->band_id === $bandMembers->band_id || $user->isAdministrators();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BandMembers  $bandMembers
     * @return mixed
     */
    public function update(User $user, BandMembers $bandMembers)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BandMembers  $bandMembers
     * @return mixed
     */
    public function delete(User $user, BandMembers $bandMembers)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BandMembers  $bandMembers
     * @return mixed
     */
    public function restore(User $user, BandMembers $bandMembers)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BandMembers  $bandMembers
     * @return mixed
     */
    public function forceDelete(User $user, BandMembers $bandMembers)
    {
        //
    }
}
