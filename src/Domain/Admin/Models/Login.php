<?php

namespace Domain\Admin\Models;

use Filament\Pages\Auth\Login as AuthLogin;
use Filament\Tables\Columns\Layout\Panel;

class Login extends AuthLogin
{
    public Panel $panel;
    public function __construct(Panel $panel)
    {
        $this->$panel = $panel;
    }
    public function getPanelId()
    {
        return $this->panel->getId();
    }
    protected static string $view = 'Admin.Auth.login';
}
