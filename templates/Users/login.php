<div class="container-fluid">
    <div class="row min-vh-100 align-items-center justify-content-center bg-light">
        <div class="col-11 col-sm-8 col-md-6 col-lg-4">
            <div class="card border-0 shadow-lg">
                <!-- Logo/Header Section -->
                <div class="card-header bg-white border-0 text-center pt-4">
                    <h4 class="text-dark mb-0">Welcome Back</h4>
                    <p class="text-muted">Sign in to your account</p>
                </div>

                <div class="card-body px-4 pb-4 m-0">
                    <?php echo $this->Flash->render(); ?>
                    
                    <?= $this->Form->create(null, [
                        'templates' => ['inputContainer' => '{{content}}']
                    ]) ?>

                    <!-- Username Input -->
                    <div class="form-group mb-1">
                        <label class="text-muted small mb-2">Username</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light border-right-0">
                                    <i class="fas fa-user text-danger"></i>
                                </span>
                            </div>
                            <?= $this->Form->control('username', [
                                'class' => 'form-control border-left-0 pl-0',
                                'placeholder' => 'Enter your username',
                                'label' => false,
                                'required'
                            ])?>
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div class="form-group mb-4">
                        <label class="text-muted small mb-2">Password</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light border-right-0">
                                    <i class="fas fa-lock text-danger"></i>
                                </span>
                            </div>
                            <?= $this->Form->control('password', [
                                'class' => 'form-control border-left-0 pl-0',
                                'placeholder' => 'Enter your password',
                                'label' => false,
                                'required'
                            ])?>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-danger btn-block py-2">
                            <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                        </button>
                    </div>
                    
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
    </div>
</div>