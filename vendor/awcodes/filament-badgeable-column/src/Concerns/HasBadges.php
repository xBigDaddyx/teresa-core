<?php

namespace Awcodes\FilamentBadgeableColumn\Concerns;

use Closure;
use Illuminate\Support\Str;

trait HasBadges
{
    protected array | Closure $prefixBadges = [];

    protected array | Closure $suffixBadges = [];

    protected bool | Closure $asPills = false;

    protected function setUp(): void
    {
        $this->html();
    }

    public function asPills(bool | Closure $condition = true): static
    {
        $this->asPills = $condition;

        return $this;
    }

    public function getBadges(array | Closure $badges): string
    {
        // only evaluate the badges at the point of retrieval, to ensure the rest of the livewire component stack + needed data is available.
        $badges = $this->evaluate($badges);
        $badgesHtml = '';

        foreach ($badges as $k => $badge) {
            $badgeHtml = $badge
                ->column($this)
                ->isPill($this->shouldBePills())
                ->render();

            $badgesHtml .= Str::of($badgeHtml)
                ->replace('<!-- __BLOCK__ --> ', '')
                ->replace('<!-- __ENDBLOCK__ -->', '')
                ->replace('<!--[if BLOCK]><![endif]-->', '')
                ->replace('<!--[if ENDBLOCK]><![endif]-->','')
                ->replace('/n', '')
                ->trim();
        }

        return $badgesHtml;
    }

    public function getPrefix(): ?string
    {
        $badges = $this->getPrefixBadges();

        if ($badges) {
            return '<span style="display:inline-flex;gap:0.375rem;margin-inline-end:0.25rem;">' . $badges . '</span><span style="opacity: 0.375;">&mdash;</span> ' . parent::getPrefix();
        }

        return parent::getPrefix();
    }

    public function getPrefixBadges(): string
    {
        return $this->getBadges($this->prefixBadges);
    }

    public function getSuffix(): ?string
    {
        $badges = $this->getSuffixBadges();

        if ($badges) {
            return parent::getSuffix() . ' <span style="opacity: 0.375;">&mdash;</span><span style="display:inline-flex;gap:0.375rem;margin-inline-start:0.25rem;">' . $badges . '</span>';
        }

        return parent::getSuffix();
    }

    public function getSuffixBadges(): string
    {
        return $this->getBadges($this->suffixBadges);
    }

    public function prefixBadges(array | Closure $badges): static
    {
        $this->prefixBadges = $badges;

        return $this;
    }

    public function shouldBePills(): bool
    {
        return (bool) $this->evaluate($this->asPills);
    }

    public function suffixBadges(array | Closure $badges): static
    {
        $this->suffixBadges = $badges;

        return $this;
    }
}
