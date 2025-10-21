<?php $title = $this->fetch('title'); ?>
<!-- Main Sidebar Container -->
<aside class="main-sidebar elevation-4 sidebar-light-primary">
    <?php
    echo $this->Html->link('<span class="brand-text font-weight-light text-dark">ACISS</span>', '/Dashboard', [
        'class' => 'brand-link',
        'escape' => false,
        'style' => 'background-color:#007BFF;'
    ]);
    ?>
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="#" class="d-block">Welcome <b>
                        <?php if ($auth['role'] == 'inspector'): ?><?= $auth['inspector_name'] ?><?php endif; ?>
                        <?php if ($auth['role'] == 'admin'): ?><?= $auth['username'] ?><?php endif; ?>
                    </b></a>
            </div>
        </div>
        <nav class="mt-2 mb-0">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <?php if ($auth['role'] !== 'admin'): ?>
                    <li class="nav-item">
                        <?php $active = $title == 'DashboardInspector' ? 'active' : '' ?>
                        <?= $this->Html->link('<i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>', '/DashboardInspector', ['class' => 'nav-link ' . $active, 'escape' => false]) ?>
                    </li>
                <?php endif; ?>
                <?php if ($auth['role'] !== 'inspector'): ?>
                    <li class="nav-item">
                        <?php $active = $title == 'Dashboard' ? 'active' : '' ?>
                        <?= $this->Html->link('<i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>', '/Dashboard', ['class' => 'nav-link ' . $active, 'escape' => false]) ?>
                    </li>
                    <li class="nav-item">
                        <?php $active = $title == 'Inspectors' ? 'active' : '' ?>
                        <?= $this->Html->link('<i class="nav-icon fas fa-user-tie"></i>
                        <p>
                            Inspectors
                        </p>', '/Inspectors', ['class' => 'nav-link ' . $active, 'escape' => false]) ?>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <?php $active = $title == 'Inspections' ? 'active' : '' ?>
                    <?= $this->Html->link('<i class="nav-icon fas fa-clipboard-list"></i>
                        <p>
                            Inspections
                        </p>', '/Inspections', ['class' => 'nav-link ' . $active, 'escape' => false]) ?>
                </li>
                <li class="nav-item">
                    <?php $active = $title == 'Availabilities' ? 'active' : '' ?>
                    <?= $this->Html->link('<i class="nav-icon fas fa-calendar-check"></i>
                        <p> 
                            Availabilities
                        </p>', '/Availabilities', ['class' => 'nav-link ' . $active, 'escape' => false]) ?>
                </li>
                <li class="nav-item">
                    <?php $active = $title == 'Clients' ? 'active' : '' ?>
                    <?= $this->Html->link('<i class="nav-icon fas fa-users"></i>
                        <p>
                            Clients
                        </p>', '/Clients', ['class' => 'nav-link ' . $active, 'escape' => false]) ?>
                </li>
                <?php if ($auth['role'] !== 'inspector'): ?>
                    <li class="nav-item">
                        <?php $active = $title == 'Users' ? 'active' : '' ?>
                        <?= $this->Html->link('<i class="nav-icon fas fa-user-cog"></i>
                        <p>
                            Users
                        </p>', '/Users', ['class' => 'nav-link ' . $active, 'escape' => false]) ?>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</aside>