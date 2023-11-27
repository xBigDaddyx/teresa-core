<?php

namespace Xbigdaddyx\HarmonyFlow;

use Xbigdaddyx\HarmonyFlow\Contracts\ApprovalContract;
use Xbigdaddyx\HarmonyFlow\Contracts\DepartmentContract;
use Xbigdaddyx\HarmonyFlow\Contracts\FlowContract;

class HarmonyFlow
{
    public string $pivotFlow;
    public string $pivotDepartment;
    protected string $approvalClass;
    protected string $flowClass;
    protected string $department;
    public function __construct()
    {
        $this->initializeCache();
    }
    public function initializeCache(): void
    {
        $this->approvalClass = config('harmony-flow.models.approvals');
        $this->flowClass = config('harmony-flow.models.approval-flows');
        $this->department = config('harmony-flow.models.departments');
        $this->pivotFlow = config('permission.column_names.flow_pivot_key') ?: 'flow_id';
        $this->pivotDepartment = config('permission.column_names.user_morph_key') ?: 'user_id';
    }

    public function getApprovalClass(): string
    {
        return $this->approvalClass;
    }

    public function setApprovalClass($approvalClass)
    {
        $this->approvalClass = $approvalClass;
        config()->set('harmony-flow.models.approvals', $approvalClass);
        app()->bind(ApprovalContract::class, $approvalClass);

        return $this;
    }
    public function getFlowClass(): string
    {
        return $this->flowClass;
    }

    public function setFlowClass($flowClass)
    {
        $this->flowClass = $flowClass;
        config()->set('harmony-flow.models.approvals', $flowClass);
        app()->bind(ApprovalContract::class, $flowClass);

        return $this;
    }
    public function getDepartmentClass(): string
    {
        return $this->department;
    }
    public function setDepartmentClass($department)
    {
        $this->department = $department;
        config()->set('harmony-flow.models.departments', $department);
        app()->bind(DepartmentContract::class, $department);

        return $this;
    }
}
