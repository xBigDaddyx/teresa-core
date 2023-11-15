<?php

namespace Teresa\CartonBoxGuard\Services;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Teresa\CartonBoxGuard\Interfaces\CartonBoxValidationInterface;

use Teresa\CartonBoxGuard\Models\CartonBoxAttribute;
use Teresa\CartonBoxGuard\Models\Polybag;
use Teresa\CartonBoxGuard\Models\Tag;

class CartonBoxValidationService implements CartonBoxValidationInterface
{
    public function validateSolid(Model $cartonBox, string $current_polybag, string $first_polybag = null)
    {
        $first_polybag = session()->get('carton.first_polybag');


        if ($cartonBox->is_completed !== true) {

            //if (!empty($cartonBox->polybags->first()->polybag_code)) {
            if (!empty($first_polybag)) {

                //if ($current_polybag !== $cartonBox->polybags->first()->polybag_code) {
                if ($current_polybag !== $first_polybag) {
                    return 'invalid';
                }
            }


            return 'validated'; // Validasi solid berhasil
        }

        return 'completed';
    }
    public function validateMix($cartonBox, $tag, $polybag, $attribute, $polybag_completed)
    {

        if ($attribute->where('tag', $tag)->first() !== null || !empty($attribute->where('tag', $tag)->first())) {
            $tag = CartonBoxAttribute::with('tags')->find($attribute->where('tag', $tag)->first()->id);


            if ($tag->count() > 0) {
                if ($polybag_completed !== true) {
                    if ($tag->tags->count() !== (int)$tag->quantity) {
                        Tag::create([
                            'tag' => $tag->tag,
                            'type' => 'MIX',
                            'attributable_id' => $tag->id,
                            'attributable_type' => 'Teresa\CartonBoxGuard\Models\CartonBoxAttribute',
                        ]);
                        return 'saved';
                    }
                    return 'polybag completed';
                } else if ($polybag_completed) {
                    $attributable_tags = Tag::whereHas('attributable', function (Builder $a) use ($cartonBox) {
                        $a->where('carton_box_id', $cartonBox->id);
                    })->whereNull('taggable_id')->get();
                    $attribute_counts = $attributable_tags->count();
                    if ($attribute_counts > 0 && $attribute_counts === (int)$tag->quantity) {
                        if ($polybag !== null || !empty($polybag)) {
                            $polybag_value = Polybag::create(
                                [
                                    'polybag_code' => $polybag,
                                    'carton_box_id' => $cartonBox->id,

                                ]
                            );

                            if ($polybag_value || empty($polybag_value)) {
                                $polybag_id = Polybag::where('carton_box_id', $cartonBox->id)->orderBy('created_at', 'DESC')->first()->id;
                                DB::transaction(function () use ($attribute_counts, $cartonBox, $polybag_id) {
                                    for ($i = 0; $i <= $attribute_counts; $i++) {
                                        Tag::whereHas('attributable', function (Builder $a) use ($cartonBox) {
                                            $a->where('carton_box_id', $cartonBox->id);
                                        })->whereNull('taggable_id')->update([
                                            'taggable_id' => $polybag_id,
                                            'taggable_type' => 'Teresa\CartonBoxGuard\Models\Polybag',
                                        ]);
                                    }
                                });
                                return 'updated';
                            }
                        }
                    }
                }
                return 'scan polybag';
            }
        }
        return 'incorrect';
    }

    public function validateRatio($cartonBox, $tag, $polybag, $attribute, $polybag_completed)
    {

        $attributable_tags = Tag::whereHas('attributable', function (Builder $a) use ($cartonBox) {
            $a->where('carton_box_id', $cartonBox->id);
        })->whereNull('taggable_id')->get();
        $attribute_counts = $attributable_tags->count();

        if ($attribute_counts > 0 && $attribute_counts === $attribute->sum('quantity')) {
            if ($polybag !== null || !empty($polybag)) {

                $polybag_value = Polybag::create(
                    [
                        'polybag_code' => $polybag,
                        'carton_box_id' => $cartonBox->id,

                    ]
                );
                if ($polybag_value || empty($polybag_value)) {
                    $polybag_id = Polybag::where('carton_box_id', $cartonBox->id)->orderBy('created_at', 'DESC')->first()->id;
                    DB::transaction(function () use ($attribute_counts, $cartonBox, $polybag_id) {
                        for ($i = 0; $i <= $attribute_counts; $i++) {
                            Tag::whereHas('attributable', function (Builder $a) use ($cartonBox) {
                                $a->where('carton_box_id', $cartonBox->id);
                            })->whereNull('taggable_id')->update([
                                'taggable_id' => $polybag_id,
                                'taggable_type' => 'Teresa\CartonBoxGuard\Models\Polybag',
                            ]);
                        }
                    });
                    return 'updated';
                }
            }

            return 'polybag completed';
        }
        if ($polybag_completed !== true) {
            if ($attribute->contains('tag', (string)$tag)) {
                $attribute_model = CartonBoxAttribute::find($attribute->where('tag', (string)$tag)->first()->id);

                $tag_quantity = $attribute_model->quantity;
                $scanned_tag = Tag::whereHas('attributable', function (Builder $query) use ($attribute_model) {
                    $query->where('id', $attribute_model->id);
                })->where('taggable_id', null);
                if ($scanned_tag->count() === (int)$tag_quantity) {
                    return 'max';
                } else {
                    $tag_value = new Tag();
                    $tag_value->type = 'RATIO';
                    $tag_value->tag = (string)$tag;
                    $attribute_model->tags()->save($tag_value);
                    return 'saved';
                }
            }
            return 'incorrect';
        }


        // if(Tag::whereHas('')$attribute->count())

        // return [
        //     'status' => 'incorrect_tag',
        //     'type' => 'warning',
        //     'message' => 'Incorrect attribute or no size/color attribute found for this carton box.',
        //     'timer' => null,
        //     'toast' => false,
        //     'position' => 'center',
        //     'allowOutsideClick' => false,
        //     'showConfirmButton' => true,
        // ];

        // Implementasikan validasi lain sesuai dengan aturan yang Anda sebutkan
    }
}
