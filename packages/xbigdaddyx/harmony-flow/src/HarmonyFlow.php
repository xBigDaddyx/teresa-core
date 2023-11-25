<?php

namespace Xbigdaddyx\HarmonyFlow;

use Xbigdaddyx\HarmonyFlow\Contracts\ApprovalContract;
use Xbigdaddyx\HarmonyFlow\Contracts\DesignationContract;
use Xbigdaddyx\HarmonyFlow\Contracts\FlowContract;

class HarmonyFlow
{
    public string $pivotFlow;
    public string $pivotDesignation;
    protected string $approvalClass;
    protected string $flowClass;
    protected string $designationClass;
    public function __construct()
    {
        $this->initializeCache();
    }
    public function initializeCache(): void
    {
        $this->approvalClass = config('harmony-flow.models.approvals');
        $this->flowClass = config('harmony-flow.models.approval-flows');
        $this->designationClass = config('harmony-flow.models.designations');
        $this->pivotFlow = config('permission.column_names.flow_pivot_key') ?: 'flow_id';
        $this->pivotDesignation = config('permission.column_names.user_morph_key') ?: 'user_id';
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
    public function getDesignationClass(): string
    {
        return $this->designationClass;
    }
    public function setDesignationClass($designationClass)
    {
        $this->designationClass = $designationClass;
        config()->set('harmony-flow.models.designations', $designationClass);
        app()->bind(DesignationContract::class, $designationClass);

        return $this;
    }
}
