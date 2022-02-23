<?php $__env->startSection('content'); ?>
<div class="container-lg">
        <h1 id="h1_url_name" class="mt-5 mb-3"><?php echo e(__('Адрес: ')); ?></h1>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-nowrap">
                <tbody>
                    <tr id="id">
                        <td><?php echo e(__('ID')); ?></td>
                    </tr>
                    <tr id="table_url_name">
                        <td><?php echo e(__('Имя')); ?></td>
                    </tr>
                    <tr id="created_at">
                        <td><?php echo e(__('Дата создания')); ?></td>
                    </tr>
                    <tr id="updated_at">
                        <td><?php echo e(__('Дата обновления')); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

    <div class="table-responsive">
        <h2 class="mt-5 mb-3"><?php echo e(__('Проверки')); ?></h2>
        <form id="check" class="mb-2" method="post">
            <?php echo csrf_field(); ?>
            <input id="check" class="btn btn-primary" type="submit" value="Запустить проверку">
        </form>
        <div class="action-message" id="alert"></div>
        <table class="table table-bordered table-hover text-nowrap">
            <thead>
                <tr>
                    <th><?php echo e(__('ID')); ?></th>
                    <th><?php echo e(__('Код ответа')); ?></th>
                    <th><?php echo e(__('h1')); ?></th>
                    <th><?php echo e(__('keywords')); ?></th>
                    <th><?php echo e(__('description')); ?></th>
                    <th><?php echo e(__('Дата создания')); ?></th>
                </tr>
            </thead>
            <tbody id="check_results">
            </tbody>
        </table>
    </div>
</div>
<script id="entity" src="/js/urls/show.js"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/gai/phpProject/extView/resources/views/urls/show.blade.php ENDPATH**/ ?>