<?php

namespace Domain\Ldap;

use Domain\Users\Models\User as DatabaseUser;
use LdapRecord\Models\ActiveDirectory\User as LdapUser;

class UserAttributeHandler
{
    public function handle(LdapUser $ldap, DatabaseUser $database)
    {
        // $company = $ldap->getFirstAttribute('physicalDeliveryOfficeName');
        // if (!empty($company) || $company !== '') {
        //     switch ($company) {
        //         case 'Indonesia-Boyo':
        //             $company = 3;
        //             break;
        //         case 'Indonesia-Solo':
        //             $company = 2;
        //             break;
        //         case 'Indonesia-Semarang':
        //             $company = 4;
        //             break;
        //         default:
        //             $company = null;
        //     }
        // }

        $database->name = $ldap->getFirstAttribute('cn');
        $database->email = $ldap->getFirstAttribute('mail');
        $database->department = $ldap->getFirstAttribute('department');
        $database->mobile = $ldap->getFirstAttribute('mobile');
        // $database->current_company_id = $company;
    }
}
