<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy extends ModelPolicy
{
    use HandlesAuthorization;
}
