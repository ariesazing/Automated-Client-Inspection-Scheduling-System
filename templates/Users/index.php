<div class="col-12">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Users</h3>
            <div class="card-tools">
                <?= $this->Html->link('<i class="fas fa-plus"></i>','',
                    ['id'=>'add','data-toggle'=>'tooltip','data-placement'=>'bottom','title'=>'Add User','escape'=>false]) ?>
            </div>
        </div>
        <div class="card-body">
            <table id="users-table" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="users-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 id="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= $this->Form->create($user,['id'=>'users-form']) ?>
            <div class="modal-body">
                <div class="form-group">
                    <label for="username">Username</label>
                    <?= $this->Form->control('username',['class'=>'form-control','label'=>false]) ?>
                    <label for="password">Password</label>
                    <?= $this->Form->control('password',['class'=>'form-control','label'=>false]) ?>
                    <label for="role">Role</label>
                    <?= $this->Form->control('role',['class'=>'form-control',
                    'options'=>$this->Options->roles(),'label'=>false]) ?>
                    <label for="status">Status</label>
                    <?= $this->Form->control('status',['class'=>'form-control',
                    'options'=>$this->Options->status(),'label'=>false]) ?>
                </div>
            </div>
            <div class="modal-footer">
                <?= $this->Form->control('id',['type'=>'hidden','label'=>false]) ?>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>