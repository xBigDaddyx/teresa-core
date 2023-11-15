    <x-svg
        :name="$icon()"
        {{
            $attributes->class([
                'inline',
                'w-5 h-5' => !Str::contains($attributes->get('class'), ['w-', 'h-'])
            ])
         }}
    />