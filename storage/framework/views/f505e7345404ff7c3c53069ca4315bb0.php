    <div wire:key="<?php echo e($uuid); ?>">                
        <!-- STANDARD LABEL -->
        <!--[if BLOCK]><![endif]--><?php if($label && !$inline): ?>
            <label class="pt-0 label label-text font-semibold"><?php echo e($label); ?></label>
        <?php endif; ?> <!--[if ENDBLOCK]><![endif]-->
        
        <div class="relative">                 
            <select 
                <?php echo e($attributes->whereDoesntStartWith('class')); ?> 
                <?php echo e($attributes->class([
                            'select select-primary w-full font-normal', 
                            'pl-10' => ($icon), 
                            'h-14' => ($inline),
                            'pt-3' => ($inline && $label),
                            'border border-dashed' => $attributes->has('readonly'),
                            'select-error' => $errors->has($modelName())
                        ])); ?>

                
            >
                <!--[if BLOCK]><![endif]--><?php if($placeholder): ?>
                    <option><?php echo e($placeholder); ?></option>
                <?php endif; ?> <!--[if ENDBLOCK]><![endif]-->

                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($option[$optionValue]); ?>" <?php if(isset($option['disabled'])): ?> disabled <?php endif; ?>><?php echo e($option[$optionLabel]); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <!--[if ENDBLOCK]><![endif]-->
            </select>

            <!-- ICON -->
            <!--[if BLOCK]><![endif]--><?php if($icon): ?>
                <?php if (isset($component)) { $__componentOriginalce0070e6ae017cca68172d0230e44821 = $component; } ?>
<?php $component = Mary\View\Components\Icon::resolve(['name' => $icon] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Mary\View\Components\Icon::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'absolute top-1/2 -translate-y-1/2 left-3 text-gray-400']); ?>
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
<?php $component->withAttributes(['class' => 'absolute top-1/2 right-8 -translate-y-1/2 text-gray-400 ']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce0070e6ae017cca68172d0230e44821)): ?>
<?php $component = $__componentOriginalce0070e6ae017cca68172d0230e44821; ?>
<?php unset($__componentOriginalce0070e6ae017cca68172d0230e44821); ?>
<?php endif; ?>
            <?php endif; ?> <!--[if ENDBLOCK]><![endif]-->

            <!-- INLINE LABEL -->
            <!--[if BLOCK]><![endif]--><?php if($label && $inline): ?>                        
                <label class="absolute text-gray-500 duration-300 transform -translate-y-1 scale-75 top-2 origin-[0] bg-white rounded dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-1 <?php if($inline && $icon): ?> left-9 <?php else: ?> left-3 <?php endif; ?>">
                    <?php echo e($label); ?>                                
                </label>                                                 
            <?php endif; ?> <!--[if ENDBLOCK]><![endif]-->
        </div>

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
            <div class="label-text-alt text-gray-400 pl-1 mt-2"><?php echo e($hint); ?></div>
        <?php endif; ?> <!--[if ENDBLOCK]><![endif]-->
    </div><?php /**PATH /var/www/core/storage/framework/views/e335b47a729f35610e3a56d6a392e01d.blade.php ENDPATH**/ ?>