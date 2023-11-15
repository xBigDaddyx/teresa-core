    <!--[if BLOCK]><![endif]--><?php if($link): ?>
        <a href="<?php echo e($link); ?>"
    <?php else: ?>
        <button
    <?php endif; ?>

        wire:key="<?php echo e($uuid); ?>"
        <?php echo e($attributes->whereDoesntStartWith('class')); ?>

        <?php echo e($attributes->class(['btn normal-case', "tooltip $tooltipPosition" => $tooltip])); ?>

        <?php echo e($attributes->merge(['type' => 'button'])); ?>


        <?php if($link && $external): ?>
            target="_blank"
        <?php endif; ?>

        <?php if($link && ! $external): ?>
            wire:navigate
        <?php endif; ?>

        <?php if($tooltip): ?>
            data-tip="<?php echo e($tooltip); ?>"
        <?php endif; ?>

        <?php if($spinner): ?>
            wire:target="<?php echo e($spinnerTarget()); ?>"
            wire:loading.attr="disabled"
        <?php endif; ?>
    >

        <!-- SPINNER -->
        <!--[if BLOCK]><![endif]--><?php if($spinner): ?>
            <span wire:loading wire:target="<?php echo e($spinnerTarget()); ?>" class="loading loading-spinner w-5 h-5"></span>
        <?php endif; ?> <!--[if ENDBLOCK]><![endif]-->

        <!-- ICON -->
        <!--[if BLOCK]><![endif]--><?php if($icon): ?>
            <span <?php if($spinner): ?> wire:loading.remove wire:target="<?php echo e($spinnerTarget()); ?>" <?php endif; ?>>
                <?php if (isset($component)) { $__componentOriginalce0070e6ae017cca68172d0230e44821 = $component; } ?>
<?php $component = Mary\View\Components\Icon::resolve(['name' => $icon] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Mary\View\Components\Icon::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce0070e6ae017cca68172d0230e44821)): ?>
<?php $component = $__componentOriginalce0070e6ae017cca68172d0230e44821; ?>
<?php unset($__componentOriginalce0070e6ae017cca68172d0230e44821); ?>
<?php endif; ?>
            </span>
        <?php endif; ?> <!--[if ENDBLOCK]><![endif]-->

        <?php echo e($label ?? $slot); ?>


        <!-- ICON RIGHT -->
        <!--[if BLOCK]><![endif]--><?php if($iconRight): ?>
            <span <?php if($spinner): ?> wire:loading.remove wire:target="<?php echo e($spinnerTarget()); ?>" <?php endif; ?>>
                <?php if (isset($component)) { $__componentOriginalce0070e6ae017cca68172d0230e44821 = $component; } ?>
<?php $component = Mary\View\Components\Icon::resolve(['name' => $iconRight] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Mary\View\Components\Icon::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce0070e6ae017cca68172d0230e44821)): ?>
<?php $component = $__componentOriginalce0070e6ae017cca68172d0230e44821; ?>
<?php unset($__componentOriginalce0070e6ae017cca68172d0230e44821); ?>
<?php endif; ?>
            </span>
        <?php endif; ?> <!--[if ENDBLOCK]><![endif]-->

    <!--[if BLOCK]><![endif]--><?php if(!$link): ?>
        </button>
    <?php else: ?>
        </a>
    <?php endif; ?> <!--[if ENDBLOCK]><![endif]-->

    <!--  Force tailwind compile tooltip classes   -->
    <span class="hidden tooltip tooltip-left tooltip-right tooltip-bottom tooltip-top"></span><?php /**PATH /var/www/core/storage/framework/views/a743b14cf62c5a54401f43e29f76cab2.blade.php ENDPATH**/ ?>