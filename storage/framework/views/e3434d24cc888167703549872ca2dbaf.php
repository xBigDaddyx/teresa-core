<?php $__env->startSection('content'); ?>
<div class="max-w-screen px-4 py-2 sm:px-6 lg:px-8 lg:py-4 mx-auto ">

    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('pages.validating-polybag', ['carton'=>$carton]);

$__html = app('livewire')->mount($__name, $__params, 'rDT0HCe', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

</div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('accuracy.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/core/resources/views/accuracy/pages/validating-polybag.blade.php ENDPATH**/ ?>