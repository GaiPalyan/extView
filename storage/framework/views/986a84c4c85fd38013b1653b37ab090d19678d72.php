<?php $__env->startSection('content'); ?>
    <div class="jumbotron jumbotron-fluid bg-dark">
        <div class="container-lg">
            <div class="row">
                <div class="col-12 col-md-10 col-lg-8 mx-auto text-white" id="test">
                    <h1 class="display-3 main-title"><?php echo e(__('Анализатор страниц')); ?></h1>
                    <p class="lead"><?php echo e(__('Бесплатно проверяйте сайты на SEO пригодность')); ?></p>
                    <form id="url_store" class="d-flex justify-content-center" action="<?php echo e(route('api.store')); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <input class="form-control form-control-lg <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="my_url"
                               type="text"
                               name="name"
                               value=""
                               placeholder="https://www.example.com">

                        <input class="btn btn-lg btn-primary ml-3 px-5 text-uppercase"
                               type="submit"
                               value="Отправить">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="action-message" id="alert"></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/gai/phpProject/extView/resources/views/urls/create.blade.php ENDPATH**/ ?>