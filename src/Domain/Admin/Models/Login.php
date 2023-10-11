<?php

namespace Domain\Admin\Models;

use Filament\Pages\Auth\Login as AuthLogin;

class Login extends AuthLogin
{
    protected static string $view = 'Admin.Auth.login';
}
