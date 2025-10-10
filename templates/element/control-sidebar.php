<aside class="control-sidebar control-sidebar-dark">
    <div class="p-3 control-sidebar-content">
        <a href=""><h5>Marlon L. Castro</h5></a>
        <hr class="mb-2" style="background-color:grey;">
        <div class="mb-4">
            <?= $this->Html->link('<i class="fa fas fa-user-alt"></i> <span>Profile</span>','/admin/Employees/profile/',['escape'=>false]) ?>
        </div>
        <div class="mb-4">
            <?= $this->Html->link('<i class="fa fas fa-sign-out-alt"></i> <span>Logout</span>','/Users/logout',['escape'=>false]) ?>
        </div>
    </div>
</aside>
