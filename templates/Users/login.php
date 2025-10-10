<div class="login-box">
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Sign in</p>
            <center><?php echo $this->Flash->render(); ?></center>
            <?= $this->Form->create(null,['templates'=>['inputContainer' => '{{content}}']]) ?>
            <div class="input-group mb-3">
                <?= $this->Form->control('username',['class'=>'form-control',
                    'placeholder'=>'Username','label'=>false,'required'])?>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-user"></span>
                    </div>
                </div>
            </div>
            <div class="input-group mb-3">
                <?= $this->Form->control('password',['class'=>'form-control',
                    'placeholder'=>'Password','label'=>false,'required'])?>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-8"></div>
                <div class="col-4">
                    <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                </div>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>