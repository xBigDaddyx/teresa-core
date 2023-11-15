<?php

namespace Pboivin\FilamentPeek\Pages\Concerns;

use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Pboivin\FilamentPeek\CachedPreview;
use Pboivin\FilamentPeek\Support;

trait HasPreviewModal
{
    protected array $initialPreviewModalData = [];

    protected array $previewModalData = [];

    protected ?Model $previewableRecord = null;

    protected function getPreviewModalUrl(): ?string
    {
        return null;
    }

    protected function getPreviewModalView(): ?string
    {
        return null;
    }

    protected function getPreviewModalTitle(): string
    {
        return __('filament-peek::ui.preview-modal-title');
    }

    protected function getPreviewModalDataRecordKey(): string
    {
        return 'record';
    }

    protected function mutatePreviewModalData(array $data): array
    {
        return $data;
    }

    /** @internal */
    public static function renderPreviewModalView(?string $view, array $data): string
    {
        return Support\Html::injectPreviewModalStyle(
            view($view, $data)->render()
        );
    }

    /** @internal */
    protected function preparePreviewModalData(): array
    {
        $record = null;

        if ($this->previewableRecord) {
            $record = $this->previewableRecord;
        } elseif (method_exists($this, 'mutateFormDataBeforeCreate')) {
            $data = $this->mutateFormDataBeforeCreate($this->form->getState());
            $record = $this->getModel()::make($data);
        } elseif (method_exists($this, 'mutateFormDataBeforeSave')) {
            $data = $this->mutateFormDataBeforeSave($this->form->getState());
            $record = $this->getRecord();
            $record->fill($data);
        } elseif (method_exists($this, 'getRecord')) {
            $record = $this->getRecord();
        }

        return array_merge(
            $this->initialPreviewModalData,
            [
                $this->getPreviewModalDataRecordKey() => $record,
                'isPeekPreviewModal' => true,
            ]
        );
    }

    /** @internal */
    public function openPreviewModal(): void
    {
        $previewModalUrl = null;
        $previewModalHtmlContent = null;

        try {
            $this->previewModalData = $this->mutatePreviewModalData($this->preparePreviewModalData());

            if ($previewModalUrl = $this->getPreviewModalUrl()) {
                // pass
            } elseif ($view = $this->getPreviewModalView()) {
                if (config('filament-peek.internalPreviewUrl.enabled', false)) {
                    $token = app(Support\Cache::class)->createPreviewToken();

                    CachedPreview::make(static::class, $view, $this->previewModalData)->put($token);

                    $previewModalUrl = route('filament-peek.preview', ['token' => $token]);
                } else {
                    $previewModalHtmlContent = static::renderPreviewModalView($view, $this->previewModalData);
                }
            } else {
                throw new InvalidArgumentException('Missing preview modal URL or Blade view.');
            }
        } catch (Halt $exception) {
            $this->closePreviewModal();

            return;
        }

        $this->dispatch(
            'open-preview-modal',
            modalTitle: $this->getPreviewModalTitle(),
            iframeUrl: $previewModalUrl,
            iframeContent: $previewModalHtmlContent,
        );
    }

    /** @internal */
    public function closePreviewModal(): void
    {
        $this->dispatch('close-preview-modal');
    }

    /** @internal */
    public function setPreviewableRecord(Model $record): void
    {
        $this->previewableRecord = $record;
    }

    /** @internal */
    public function initialPreviewModalData(array $data): void
    {
        $this->initialPreviewModalData = $data;
    }
}
