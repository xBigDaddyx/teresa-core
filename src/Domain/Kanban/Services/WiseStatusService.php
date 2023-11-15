<?php

namespace Domain\Kanban\Services;


use Carbon\Carbon;
use Domain\Kanban\Models\Rule;
use Domain\Kanban\Models\Wise;

class WiseStatusService
{
    // private const maxBra = 840;
    // private const maxBrief = 960;
    // private const midBra = 420;
    // private const midBrief = 480;
    public $quantity;
    public $sewing;
    public ?Wise $wise;
    private $maxBra;
    private $maxBrief;
    private $midBra;
    private $midBrief;

    public function __construct($quantity, $sewing, $company_id)
    {
        $rules = Rule::where('company_id', $company_id)->get();
        foreach ($rules as $rule) {
            if ($rule->level === 'MAX') {
                if ($rule->sewing_type === 'BRA') {
                    $this->maxBra = $rule->value;
                }
                $this->maxBrief = $rule->value;
            } else if ($rule->level === 'MIDDLE') {
                if ($rule->sewing_type === 'BRA') {
                    $this->midBra = $rule->value;
                }
                $this->midBrief = $rule->value;
            }
        }
        $this->quantity = $quantity;
        $this->sewing = $sewing;

        $this->wise = Wise::where('sewing_id', $sewing->id)->first();
    }
    public function hasWise(): bool
    {
        if (!empty($this->wise)) {
            if ($this->wise->count() > 0) {
                return true;
            }
        }
        return false;
    }
    public function getBlinkedAt(): ?string
    {
        if ($this->hasWise()) {
            if ($this->wise->is_blinked) {
                if ($this->checkTimeoutBlink()) {
                    return null;
                }
                return $this->wise->blinked_at;
            }
            return null;
        } else if ($this->getStatus() === 'Low') {
            return Carbon::now();
        }
        return null;
    }
    public function getBlink(): bool
    {
        if ($this->hasWise()) {
            if ($this->wise->is_blinked) {
                if ($this->checkTimeoutBlink()) {
                    return false;
                }
                return true;
            }
            return false;
        } else if ($this->getStatus() === 'Low') {
            return true;
        }
        return false;

        // if ($this->hasWise() && $this->checkTimeoutBlink()) {
        //     return false;
        // } else if ($this->hasWise() && !$this->checkTimeoutBlink()) {
        //     if ($this->wise->sewing_line_type === 'BRA') {
        //         if ($this->quantity > 0 && $this->quantity < $this->midBra) {
        //             if ($this->wise->is_blinked !== true) {
        //                 return true;
        //             }
        //         }
        //     } else if ($this->wise->sewing_line_type === 'BRIEF') {
        //         if ($this->quantity > 0 && $this->quantity < $this->midBrief) {
        //             if ($this->wise->is_blinked !== true) {
        //                 return true;
        //             }
        //         }
        //     }
        // } else {
        //     if ($this->sewing->type === 'BRA') {
        //         if ($this->quantity > 0 && $this->quantity < $this->midBra) {
        //             return true;
        //         }
        //     } else if ($this->sewing->type === 'BRIEF') {
        //         if ($this->quantity > 0 && $this->quantity < $this->midBrief) {
        //             return true;
        //         }
        //     }
        // }
        // return false;
    }

    public function getStatus(): string
    {
        if ($this->sewing->type === 'BRA') {
            if ($this->quantity > $this->maxBra) {
                return 'Excess';
            } else if ($this->quantity >= $this->midBra && $this->quantity <= $this->maxBra) {
                return 'Standard';
            } else if ($this->quantity > 0 && $this->quantity <= $this->midBra) {
                if ($this->hasWise() && $this->wise->status === 'Low') {
                    if (!$this->getBlink()) {
                        return 'Timeout';
                    }
                } else if ($this->hasWise() && $this->wise->status === 'Timeout') {
                    return 'Timeout';
                }
                return 'Low';
            } else if ($this->quantity == 0) {
                return 'Zero';
            } else {

                return 'Minus';
            }
        } else {
            if ($this->quantity > $this->maxBrief) {
                return 'Excess';
            } else if ($this->quantity >= $this->midBrief && $this->quantity <= $this->maxBrief) {
                return 'Standard';
            } else if ($this->quantity > 0 && $this->quantity <= $this->midBrief) {
                if ($this->hasWise() && $this->wise->status === 'Low') {
                    if (!$this->getBlink()) {
                        return 'Timeout';
                    }
                } else if ($this->hasWise() && $this->wise->status === 'Timeout') {
                    return 'Timeout';
                }
                return 'Low';
            } else if ($this->quantity == 0) {
                return 'Zero';
            } else {

                return 'Minus';
            }
        }
    }
    public function checkTimeoutBlink(): bool
    {
        $now = Carbon::now();
        $blinked_at = Carbon::parse($this->wise->blinked_at);
        if ($blinked_at->diffInMinutes($now) >= 10) {
            return true;
        }
        return false;
    }
}
