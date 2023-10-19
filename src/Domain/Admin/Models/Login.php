<?php

namespace Domain\Admin\Models;

use Filament\Facades\Filament;
use Filament\Pages\Auth\Login as AuthLogin;
use Filament\Panel as FilamentPanel;


class Login extends AuthLogin
{


    protected static string $view = 'Admin.Auth.login';
}
