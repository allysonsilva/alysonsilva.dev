<?php

namespace App\Support\Actions;

use App\Support\Contracts\ShouldActionInterface;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class LoggedUserAction implements ShouldActionInterface
{
    /**
     * Create a new Action instance.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     */
    public function __construct(protected AuthenticatableContract $loggedUser)
    {
    }
}
