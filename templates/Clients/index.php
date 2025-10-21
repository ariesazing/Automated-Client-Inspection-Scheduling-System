<div class="col-12">
    <div class="card card-danger">
        <div class="card-header">
            <h3 class="card-title">Clients</h3>
            <div class="card-tools">
                <?= $this->Html->link('<i class="fas fa-plus"></i>','',
                    ['id'=>'add','data-toggle'=>'tooltip','data-placement'=>'bottom','title'=>'Add Client','escape'=>false]) ?>
            </div>
        </div>
        <div class="card-body">
            <table id="clients-table" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Owner Name</th>
                        <th>Establishment Name</th>
                        <th>Address</th>
                        <th>Type</th>
                        <th>Risk Level</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="clients-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h4 id="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= $this->Form->create($client,['id'=>'clients-form']) ?>
            <div class="modal-body">
                <div class="form-group">
                    <label for="owner-name">Owner Name</label>
                    <?= $this->Form->control('owner_name',['class'=>'form-control','label'=>false]) ?>
                    <label for="establishment-name">Establishment Name</label>
                    <?= $this->Form->control('establishment_name',['class'=>'form-control','label'=>false]) ?>
                    <label for="address">Address</label>
                    <?= $this->Form->control('address',['class'=>'form-control','label'=>false]) ?>
                   <label for="type">Type</label>
                    <?= $this->Form->control('type',['class'=>'form-control',
                    'options'=>$this->Options->client_type(),'label'=>false]) ?>
                    <label for="risk-level">Risk Level</label>
                    <?= $this->Form->control('risk_level',['class'=>'form-control',
                    'options'=>$this->Options->risk_level(),'label'=>false]) ?>
                    <label for="status">Status</label>
                    <?= $this->Form->control('status',['class'=>'form-control',
                    'options'=>$this->Options->client_status(),'label'=>false]) ?>
                </div>
            </div>
            <div class="modal-footer">
                <?= $this->Form->control('id',['type'=>'hidden','label'=>false]) ?>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-danger">Save</button>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>