<?php

namespace Support\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HasProfileLogo
{
    /**
     * Update the user's profile photo.
     */
    public function updateLogo(UploadedFile $photo, $storagePath = 'profile-photos'): void
    {
        tap($this->logo, function ($previous) use ($photo, $storagePath) {
            $this->forceFill([
                'logo' => $photo->storePublicly(
                    $storagePath, ['disk' => $this->profilePhotoDisk()]
                ),
            ])->save();

            if ($previous) {
                Storage::disk($this->profilePhotoDisk())->delete($previous);
            }
        });
    }

    /**
     * Delete the user's profile photo.
     */
    public function deleteLogo(): void
    {
        if (! Features::managesLogos() || $this->logo === null) {
            return;
        }

        Storage::disk($this->profilePhotoDisk())->delete($this->logo);

        $this->forceFill([
            'logo' => null,
        ])->save();
    }

    /**
     * Get the URL to the user's profile photo.
     */
    public function logoUrl(): Attribute
    {
        return Attribute::get(function () {
            return $this->logo
                ? Storage::disk($this->profilePhotoDisk())->url($this->logo)
                : $this->defaultLogoUrl();
        });
    }

    /**
     * Get the default profile photo URL if no profile photo has been uploaded.
     */
    protected function defaultLogoUrl(): string
    {
        $name = trim(collect(explode(' ', $this->name))->map(static function ($segment) {
            return mb_substr($segment, 0, 1);
        })->join(' '));

        return sprintf('https://ui-avatars.com/api/?name=%s&color=FFFFFF&background=111827', urlencode($name));
    }

    /**
     * Get the disk that profile photos should be stored on.
     */
    protected function profilePhotoDisk(): string
    {
        return isset($_ENV['VAPOR_ARTIFACT_NAME']) ? 's3' : config('filament-companies.profile_photo_disk', 'public');
    }
}
