<div class="card overflow-x-auto bg-white rounded-xl shadow-md p-4 max-h-[30rem] mt-4">
    <div class="overflow-x-auto">
        <h2 class="card-title">Ratio Attributes</h2>
        <p>Below attributes need to be validated for this carton box.</p>
        <table class="table">
            <thead>
                <tr class="font-bold text-primary-content">
                    <th>Tag</th>
                    <th>Size</th>
                    <th>Color</th>
                    <th>Quantity</th>

                </tr>
            </thead>
            <tbody>
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = session()->get('carton.attributes'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attribute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <th class="text-error"><?php echo e($attribute['tag']); ?></th>
                    <td><?php echo e($attribute['size']); ?></td>
                    <td><?php echo e($attribute['color']); ?></td>
                    <td><?php echo e($attribute['quantity']); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <!--[if ENDBLOCK]><![endif]-->
            </tbody>

        </table>
    </div>
</div><?php /**PATH /var/www/core/resources/views/livewire/components/polybag-attributes.blade.php ENDPATH**/ ?>