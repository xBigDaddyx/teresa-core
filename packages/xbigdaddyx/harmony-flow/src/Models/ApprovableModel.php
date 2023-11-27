<?php

namespace Xbigdaddyx\HarmonyFlow\Models;

use Xbigdaddyx\HarmonyFlow\Traits\Approvable;
use Illuminate\Database\Eloquent\Model;
use RingleSoft\LaravelProcessApproval\Contracts\ApprovableModel as ContractsApprovableModel;

class ApprovableModel extends Model implements ContractsApprovableModel
{
    use Approvable;
}
