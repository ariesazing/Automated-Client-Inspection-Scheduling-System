<?php $title = $this->fetch('title'); ?>
<!-- Main Sidebar Container -->
<aside class="main-sidebar elevation-4 sidebar-light-primary">
    <?php
    $image = $this->Html->image('deped_logo.png',['class'=>'brand-image img-circle elevation-3','style'=>'opacity:0.8;']);
    echo $this->Html->link($image.'<span class="brand-text font-weight-light text-dark">ECounselor</span>','/Dashboard',['class'=>'brand-link',
        'escape'=>false,'style'=>'background-color:#007BFF;']);
    ?>
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="#" class="d-block">Welcome <?= $auth['username'] ?></a>
            </div>
        </div>
        <nav class="mt-2 mb-0">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <?php $active = $title=='POS'?'active':'' ?>
                    <?= $this->Html->link('<i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            POS
                        </p>','/POS',['class'=>'nav-link '.$active,'escape'=>false]) ?>
                </li>
            </ul>
        </nav>
    </div>
</aside>