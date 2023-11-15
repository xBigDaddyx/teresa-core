<form 
    <?php echo e($attributes->whereDoesntStartWith('class')); ?> 
    <?php echo e($attributes->class(['grid grid-flow-row auto-rows-min gap-3'])); ?>

>

    <?php echo e($slot); ?>


    <!--[if BLOCK]><![endif]--><?php if($actions): ?>
        <hr class="my-3" />

        <div class="flex justify-end gap-3">
            <?php echo e($actions); ?>

        </div>
    <?php endif; ?> <!--[if ENDBLOCK]><![endif]-->
</form><?php /**PATH /var/www/core/storage/framework/views/1480faad35acc31b4d52ab7da1a11042.blade.php ENDPATH**/ ?>