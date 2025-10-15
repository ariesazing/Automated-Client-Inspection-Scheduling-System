<div class="col-12">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Inspector Availability Calendar</h3>
        </div>
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="availabilities-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 id="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <?= $this->Form->create($availability, ['id' => 'availabilities-form']) ?>
            <div class="modal-body">
                <div class="form-group">
                    <label for="is-available">Inspector Availability</label>
                    <?= $this->Form->control('is_available', [
                        'class' => 'form-control',
                        'options' => $this->Options->inspector_availability(),
                        'label' => false,
                        'empty' => false
                    ]) ?>
                    <label for="reason">Reason</label>
                    <?= $this->Form->control('reason', ['class' => 'form-control', 'label' => false]) ?>
                </div>
            </div>
            <div class="modal-footer">
                <?= $this->Form->control('id', ['type' => 'hidden', 'label' => false]) ?>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
</div>