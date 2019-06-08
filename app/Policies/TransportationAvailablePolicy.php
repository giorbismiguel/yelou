<?php

namespace App\Policies;

use App\User;
use App\TransportationAvailable;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransportationAvailablePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the transportation available.
     *
     * @param  \App\User                    $user
     * @param  \App\TransportationAvailable $transportationAvailable
     * @return mixed
     */
    public function view(User $user, TransportationAvailable $transportationAvailable)
    {
        return $user->id === $transportationAvailable->user_id;
    }

    /**
     * Determine whether the user can create transportation availables.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function create(User $user)
    {

    }

    /**
     * Determine whether the user can update the transportation available.
     *
     * @param  \App\User                    $user
     * @param  \App\TransportationAvailable $transportationAvailable
     * @return mixed
     */
    public function update(User $user, TransportationAvailable $transportationAvailable)
    {
        return $user->id === $transportationAvailable->user_id;
    }

    /**
     * Determine whether the user can delete the transportation available.
     *
     * @param  \App\User                    $user
     * @param  \App\TransportationAvailable $transportationAvailable
     * @return mixed
     */
    public function delete(User $user, TransportationAvailable $transportationAvailable)
    {
        return $user->id === $transportationAvailable->user_id;
    }

    /**
     * Determine whether the user can restore the transportation available.
     *
     * @param  \App\User                    $user
     * @param  \App\TransportationAvailable $transportationAvailable
     * @return mixed
     */
    public function restore(User $user, TransportationAvailable $transportationAvailable)
    {
        return $user->id === $transportationAvailable->user_id;
    }

    /**
     * Determine whether the user can permanently delete the transportation available.
     *
     * @param  \App\User                    $user
     * @param  \App\TransportationAvailable $transportationAvailable
     * @return mixed
     */
    public function forceDelete(User $user, TransportationAvailable $transportationAvailable)
    {
        return $user->id === $transportationAvailable->user_id;
    }
}
