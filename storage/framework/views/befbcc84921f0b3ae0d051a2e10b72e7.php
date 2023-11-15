<div>
    <!-- STANDARD LABEL -->
    <!--[if BLOCK]><![endif]--><?php if($label && !$inline): ?>
        <label class="pt-0 label label-text font-semibold"><?php echo e($label); ?></label>
    <?php endif; ?> <!--[if ENDBLOCK]><![endif]-->

    <!-- PREFIX/SUFFIX/PREPEND/APPEND CONTAINER -->
    <!--[if BLOCK]><![endif]--><?php if($prefix || $suffix || $prepend || $append): ?>
        <div class="flex">
    <?php endif; ?> <!--[if ENDBLOCK]><![endif]-->

    <!-- PREFIX / PREPEND -->
    <!--[if BLOCK]><![endif]--><?php if($prefix || $prepend): ?>
        <div class="rounded-l-lg flex items-center bg-base-200 <?php if($prefix): ?> border border-base-300 px-4 <?php endif; ?>">
            <?php echo e($prepend ?? $prefix); ?>

        </div>
    <?php endif; ?> <!--[if ENDBLOCK]><![endif]-->

    <div class="flex-1 relative">
        <!-- MONEY SETUP -->
        <!--[if BLOCK]><![endif]--><?php if($money): ?>
            <div
                wire:key="money-<?php echo e(rand()); ?>"
                x-data="{ amount: $wire.<?php echo e($modelName()); ?> }" x-init="$nextTick(() => new Currency($refs.myInput, <?php echo e($moneySettings()); ?>))"
            >
        <?php endif; ?> <!--[if ENDBLOCK]><![endif]-->

        <!-- INPUT -->
        <input
            id="<?php echo e($uuid); ?>"
            placeholder = "<?php echo e($attributes->whereStartsWith('placeholder')->first()); ?> "
            x-ref="myInput"

            <?php if($money): ?>
                :value="amount"
                @input="$nextTick(() => $wire.<?php echo e($modelName()); ?> = Currency.getUnmasked())"
            <?php endif; ?>

            <?php echo e($attributes
                    ->merge(['type' => 'text'])
                    ->except($money ? 'wire:model' : '')
                    ->class([
                        'input input-primary w-full peer',
                        'pl-10' => ($icon),
                        'h-14' => ($inline),
                        'pt-3' => ($inline && $label),
                        'rounded-l-none' => $prefix || $prepend,
                        'rounded-r-none' => $suffix || $append,
                        'border border-dashed' => $attributes->has('readonly'),
                        'input-error' => $errors->has($modelName())
                ])); ?>

        />

        <!-- ICON  -->
        <!--[if BLOCK]><![endif]--><?php if($icon): ?>
            <?php if (isset($component)) { $__componentOriginalce0070e6ae017cca68172d0230e44821 = $component; } ?>
<?php $component = Mary\View\Components\Icon::resolve(['name' => $icon] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Mary\View\Components\Icon::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'absolute top-1/2 -translate-y-1/2 left-3 text-gray-400 ']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce0070e6ae017cca68172d0230e44821)): ?>
<?php $component = $__componentOriginalce0070e6ae017cca68172d0230e44821; ?>
<?php unset($__componentOriginalce0070e6ae017cca68172d0230e44821); ?>
<?php endif; ?>
        <?php endif; ?> <!--[if ENDBLOCK]><![endif]-->

        <!-- RIGHT ICON  -->
        <!--[if BLOCK]><![endif]--><?php if($iconRight): ?>
            <?php if (isset($component)) { $__componentOriginalce0070e6ae017cca68172d0230e44821 = $component; } ?>
<?php $component = Mary\View\Components\Icon::resolve(['name' => $iconRight] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Mary\View\Components\Icon::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'absolute top-1/2 right-3 -translate-y-1/2 text-gray-400 ']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce0070e6ae017cca68172d0230e44821)): ?>
<?php $component = $__componentOriginalce0070e6ae017cca68172d0230e44821; ?>
<?php unset($__componentOriginalce0070e6ae017cca68172d0230e44821); ?>
<?php endif; ?>
        <?php endif; ?> <!--[if ENDBLOCK]><![endif]-->

        <!-- INLINE LABEL -->
        <!--[if BLOCK]><![endif]--><?php if($label && $inline): ?>
            <label for="<?php echo e($uuid); ?>" class="absolute text-gray-400 duration-300 transform -translate-y-1 scale-75 top-2 origin-[0] bg-white rounded dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-1 <?php if($inline && $icon): ?> left-9 <?php else: ?> left-3 <?php endif; ?>">
                <?php echo e($label); ?>

            </label>
        <?php endif; ?> <!--[if ENDBLOCK]><![endif]-->

        <!-- HIDDEN MONEY INPUT + END MONEY SETUP -->
        <!--[if BLOCK]><![endif]--><?php if($money): ?>
                <input type="hidden" <?php echo e($attributes->only('wire:model')); ?> />
            </div>
        <?php endif; ?> <!--[if ENDBLOCK]><![endif]-->
    </div>

    <!-- SUFFIX/APPEND -->
    <!--[if BLOCK]><![endif]--><?php if($suffix || $append): ?>
        <div class="rounded-r-lg flex items-center bg-base-200 <?php if($suffix): ?> border border-base-300 px-4 <?php endif; ?>">
            <?php echo e($append ?? $suffix); ?>

        </div>
    <?php endif; ?> <!--[if ENDBLOCK]><![endif]-->

    <!-- END: PREFIX/SUFFIX/APPEND/PREPEND CONTAINER  -->
    <!--[if BLOCK]><![endif]--><?php if($prefix || $suffix || $prepend || $append): ?>
        </div>
    <?php endif; ?> <!--[if ENDBLOCK]><![endif]-->

    <!-- ERROR -->
    <!--[if BLOCK]><![endif]--><?php $__errorArgs = [$modelName()];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="text-red-500 label-text-alt p-1"><?php echo e($message); ?></div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> <!--[if ENDBLOCK]><![endif]-->

    <!-- HINT -->
    <!--[if BLOCK]><![endif]--><?php if($hint): ?>
        <div class="label-text-alt text-gray-400 p-1 pb-0"><?php echo e($hint); ?></div>
    <?php endif; ?> <!--[if ENDBLOCK]><![endif]-->
</div><?php /**PATH /var/www/core/storage/framework/views/8cf0d8a87b2ff3671e331ba86ff3e8fe.blade.php ENDPATH**/ ?>