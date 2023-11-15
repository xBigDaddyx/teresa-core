<?php

namespace App\Ldap;

use LdapRecord\Models\Model;

class UserLdap extends Model
{
    /**
     * The object classes of the LDAP model.
     */
    public static $objectClasses = [
        'top',
        'person',
        'organizationalperson',
        'user',
    ];
}
