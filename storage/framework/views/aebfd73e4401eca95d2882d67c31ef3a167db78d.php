<?php $__env->startSection('container'); ?>
    <style>
        body {
        background: linear-gradient(180deg, #e0e0e0, #dcdcdc);
        backdrop-filter: blur(6px);
        border-radius: 20px;
        border: 1px solid #ccc;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        }

        .main-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            margin: 20px 0;
            /* [RESPONSIVE] Dihapus agar diatur oleh grid Bootstrap */
            /* max-width: 800px;
            margin-left: auto;
            margin-right: auto; */
        }

        .card-header-custom {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 25px 30px;
            border: none;
        }

        .card-header-custom h4 {
            margin: 0;
            font-weight: 600;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-section {
            padding: 1.5rem; /* [RESPONSIVE] Mengurangi padding di layar kecil */
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-control, .form-select {
            border: 2px solid #e3f2fd;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 14px;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
        }

        .form-control:focus, .form-select:focus {
            border-color: #28a745, #20c997;
            box-shadow: 0 0 0 0.2rem rgba(79, 172, 254, 0.25);
            outline: none;
            background-color: white;
        }

        .form-control[readonly] {
            background-color: #e9ecef;
            opacity: 0.8;
        }

        .btn-primary, .btn-secondary {
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #28a745, #20c997);
            box-shadow: 0 4px 15px rgba(79, 172, 254, 0.3);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 172, 254, 0.4);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
            color: white;
        }

        .alert {
            border: none;
            border-radius: 15px;
            padding: 15px 20px;
            margin-bottom: 20px;
        }

        .user-info-card {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            border-left: 4px solid #28a745, #20c997;
        }

        .user-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #28a745, #20c997);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 24px;
            margin-right: 15px;
        }

        .page-title {
            color: white;
            text-align: center;
            margin-bottom: 30px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .page-title h1 {
            font-size: 3rem;
            font-weight: 900;
            margin-bottom: 15px;
            color: #ffffff;
            text-shadow: 0 3px 6px rgba(0,0,0,0.4);
        }

        .page-title p {
            font-size: 1.5rem;
            font-weight: 600;
            color: #ffffff;
            opacity: 1;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        @media (min-width: 768px) {
            .page-title h1 {
                font-size: 2.5rem;
            }
            .form-section {
                padding: 30px;
            }
        }
    </style>

    <div class="container-fluid py-4">
        <div class="page-title">
            <h1><i class="bi bi-pencil-square"></i> EDIT PENGGUNA</h1>
            <p>Perbarui informasi administrator atau kasir</p>
        </div>

        <div class="row justify-content-center">
            
            <div class="col-xl-8 col-lg-10 col-md-12">
                <?php if(session()->has('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <ul class="mb-0">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="main-card">
                    <div class="card-header-custom">
                        <h4>
                            <i class="bi bi-pencil-square"></i>
                            Edit Data User
                        </h4>
                    </div>

                    <div class="form-section">
                        <div class="user-info-card">
                            <div class="d-flex align-items-center">
                                <div class="user-avatar">
                                    <?php echo e(strtoupper(substr($user->nama, 0, 1))); ?>

                                </div>
                                <div>
                                    <h5 class="mb-1"><?php echo e($user->nama); ?></h5>
                                    <p class="mb-0 text-muted">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        Bergabung sejak <?php echo e($user->created_at->format('d M Y')); ?>

                                    </p>
                                </div>
                            </div>
                        </div>

                        <form method="post" action="/dashboard/users/<?php echo e($user->id); ?>">
                            <?php echo method_field('put'); ?>
                            <?php echo csrf_field(); ?>
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="nama" name="nama"
                                    required autofocus value="<?php echo e(old('nama', $user->nama)); ?>">
                                <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback">
                                        <?php echo e($message); ?>

                                    </div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="username" name="username"
                                    required value="<?php echo e(old('username', $user->username)); ?>">
                                <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback">
                                        <?php echo e($message); ?>

                                    </div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="role" name="role" required>
                                    <option value="">Pilih Role</option>
                                    <option value="ADMIN" <?php echo e(old('role', $user->role) == 'ADMIN' ? 'selected' : ''); ?>>Admin</option>
                                    <option value="KASIR" <?php echo e(old('role', $user->role) == 'KASIR' ? 'selected' : ''); ?>>Kasir</option>
                                </select>
                                <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback">
                                        <?php echo e($message); ?>

                                    </div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password Baru</label>
                                <input type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="password"
                                    name="password">
                                <div class="form-text">Kosongkan jika tidak ingin mengubah password</div>
                                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback">
                                        <?php echo e($message); ?>

                                    </div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            
                            <div class="d-grid d-sm-flex justify-content-sm-between pt-3 gap-2">
                                <a href="/dashboard/users" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Update Pengguna
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Repo_Git\Gerai-umkm-mart-finalisasi\resources\views/dashboard/users/edit.blade.php ENDPATH**/ ?>