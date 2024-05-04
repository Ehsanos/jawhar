<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="utf-8">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    
    <title><?php echo e(ucwords(__('cp.jawhar'))); ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="<?php echo e(url('login/css/bootstrap.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(url('login/css/style.css')); ?>" rel="stylesheet">

    <link rel="shortcut icon" href="<?php echo e(url('login/images/favicon.png')); ?>"/>
</head>
<body>

 <div class="login-wrapper">
      <div class="bg-pic">
        <img src="<?php echo e(url('login/images/imgSign.png')); ?>" alt="" class="lazy">
      </div>
      <div class="login-container bg-white">
        <div class="logoLogin">
          <img src="<?php echo e(url('login/images/logo.png')); ?>" alt="logo">
          <p class="titleLog"><?php echo e(ucwords(__('cp.login'))); ?></p>
          <p class="titleLog1"><?php echo e(ucwords(__('cp.jawhar'))); ?> <?php echo e(ucwords(__('cp.admincp'))); ?></p>
          <form id="formLogin" class="formLogin" role="form" action="<?php echo e(url(app()->getLocale().'/admin/login')); ?>" method="post">
            <?php echo e(csrf_field()); ?>              
            <div class="form-group form-group-default">
              <label><?php echo e(ucwords(__('cp.email'))); ?></label>
              <div class="controls">
                <input type="email" name="email" placeholder="<?php echo e(ucwords(__('cp.email'))); ?>" class="form-control">
              </div>
            </div>
            <div class="form-group form-group-default">
              <label><?php echo e(ucwords(__('cp.password'))); ?></label>
              <div class="controls">
                <input type="password" class="form-control" name="password" placeholder="<?php echo e(ucwords(__('cp.password'))); ?>">
              </div>
            </div>
			<button class="btn btnSub" type="submit"><?php echo e(ucwords(__('cp.login'))); ?></button>
          </form>
          
           <?php if(count($errors) > 0): ?>
            <div class="alert alert-danger">
                <strong><?php echo e(ucwords(__('cp.error'))); ?> ! </strong><?php echo e(ucwords(__('cp.wrongdataEntry'))); ?><br>
                <ul class="list-unstyled">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>
        <?php if(session('status')): ?>
            <div class="alert alert-success">
                <?php echo e(session('status')); ?>

            </div>
        <?php endif; ?>

			
           <div class="pull-bottom pull-left">
                <p>Powerd By <a href="http://hexacit.com/" class="text-info"><img src="<?php echo e(asset('website/images/hexaLogo.svg')); ?>" alt="Hexa Cit" /></a></p>
        </div>
      </div>
    </div>
    </body>
</html> <?php /**PATH E:\Downloads\jawharBackUp\resources\views/admin/auth/login.blade.php ENDPATH**/ ?>