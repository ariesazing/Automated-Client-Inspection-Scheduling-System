<div class="col-12">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Sample Table</h3>
            <div class="card-tools">
                <?= $this->Html->link('<i class="fas fa-plus"></i>','',
                    ['id'=>'add','data-toggle'=>'tooltip','data-placement'=>'bottom','title'=>'Add School Year','escape'=>false]) ?>
            </div>
        </div>
        <div class="card-body">
            <table id="sample-table" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Rendering engine</th>
                        <th>Browser</th>
                        <th>Platform(s)</th>
                        <th>Engine version</th>
                        <th>CSS grade</th>
                    </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Trident</td>
                    <td>Internet
                        Explorer 4.0
                    </td>
                    <td>Win 95+</td>
                    <td> 4</td>
                    <td>X</td>
                </tr>
                <tr>
                    <td>Trident</td>
                    <td>Internet
                        Explorer 5.0
                    </td>
                    <td>Win 95+</td>
                    <td>5</td>
                    <td>C</td>
                </tr>
                <tr>
                    <td>Gecko</td>
                    <td>Firefox 1.0</td>
                    <td>Win 98+ / OSX.2+</td>
                    <td>1.7</td>
                    <td>A</td>
                </tr>
                <tr>
                    <td>Gecko</td>
                    <td>Firefox 1.5</td>
                    <td>Win 98+ / OSX.2+</td>
                    <td>1.8</td>
                    <td>A</td>
                </tr>
                <tr>
                    <td>Webkit</td>
                    <td>Safari 1.2</td>
                    <td>OSX.3</td>
                    <td>125.5</td>
                    <td>A</td>
                </tr>
                <tr>
                    <td>Webkit</td>
                    <td>Safari 1.3</td>
                    <td>OSX.3</td>
                    <td>312.8</td>
                    <td>A</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="sample-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= $this->Form->create(null,['id'=>'sample-form']) ?>
            <div class="modal-body">
                <div class="form-group">
                    <label for="sy">Sample Control</label>
                    <?= $this->Form->control('sample',['class'=>'form-control','label'=>false]) ?>
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
