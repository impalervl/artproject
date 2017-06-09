<?php

namespace App\Policies;

use App\User;
use App\Picture;
use Illuminate\Auth\Access\HandlesAuthorization;

class PicturePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the picture.
     *
     * @param  \App\User  $user
     * @param  \App\Picture  $picture
     * @return mixed
     */
    public function view(User $user, Picture $picture)
    {
        //
    }

    /**
     * Determine whether the user can create pictures.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if(($user->role == 'artist')&&($user->max_uploads > $user->uploads)){
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the picture.
     *
     * @param  \App\User  $user
     * @param  \App\Picture  $picture
     * @return mixed
     */
    public function update(User $user, Picture $picture)
    {
        return $user->id === $picture->user_id;
    }

    /**
     * Determine whether the user can delete the picture.
     *
     * @param  \App\User  $user
     * @param  \App\Picture  $picture
     * @return mixed
     */
    public function delete(User $user, Picture $picture)
    {
        return $user->id === $picture->user_id;
    }

    /**
     * Determine whether the user can delete the picture.
     *
     * @param  \App\User  $user
     * @return mixed
     */

    public function addToWatchlist(User $user)
    {
        if (($user->role == 'byer') || ($user->role == 'artist')) {

            return true;
        }

        return false;
    }

}
