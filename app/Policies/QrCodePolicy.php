<?php

namespace App\Policies;

use App\QrCode;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class QrCodePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any app qr codes.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the app qr code.
     *
     * @param  \App\User  $user
     * @param  \App\QrCode  $qrcode
     * @return mixed
     */
    public function view(User $user, QrCode $qrcode)
    {
        //
    }

    /**
     * Determine whether the user can create app qr codes.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the app qr code.
     *
     * @param  \App\User  $user
     * @param  \App\QrCode  $qrcode
     * @return mixed
     */
    public function update(User $user, QrCode $qrcode)
    {
        //
    }

    /**
     * Determine whether the user can delete the app qr code.
     *
     * @param  \App\User  $user
     * @param  \App\QrCode  $qrcode
     * @return mixed
     */
    public function delete(User $user, QrCode $qrcode)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the app qr code.
     *
     * @param  \App\User  $user
     * @param  \App\QrCode  $qrcode
     * @return mixed
     */
    public function restore(User $user, QrCode $qrcode)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the app qr code.
     *
     * @param  \App\User  $user
     * @param  \App\QrCode  $qrcode
     * @return mixed
     */
    public function forceDelete(User $user, QrCode $qrcode)
    {
        return false;
    }
}
