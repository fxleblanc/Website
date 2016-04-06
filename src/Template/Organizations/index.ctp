<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h1 class="page-header"><?= __('List of organizations'); ?> <?= $this->Wiki->addHelper('Organizations'); ?></h1>
        <?php
        $this->Html->addCrumb(__('Home'), '/');
        $this->Html->addCrumb(__('Organizations'), '/Organizations');
        $this->Html->addCrumb(__('List of organizations'));

        echo $this->Html->getCrumbList(); ?>
    </div>
</div>
<?php $paginatorParams = $this->Paginator->params(); ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?=__("The goal of an organization is to submit projects to be able to recruit students on project missions. The organizations allow to gather actors and projects in a single community, this allows a better idea of the entity.")?>
        </div>
    </div>
</div>
<div class="panel panel-primary">
    <div class="panel-body">
        <?= $this->Form->create($organizations); ?>
        <div class="col-xs-12 col-md-12 col-sm-12">
            <?= $this->Form->input('name', ['required' => false]); ?>
        </div>
        <div class="col-xs-12">
            <?= $this->Form->button(__('Search for an organization'), ['class' => 'btn btn-info']) ?>
        </div>
        <?= $this->Form->end(); ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <h3 class="header-title"><?= __('List of organizations'); ?></h3>
                <div class="table-responsive">
                    <table id="organizations" class="table table-striped table-bordered dataTable">
                        <thead>
                        <tr>
                            <th><?= __('Name'); ?></th>
                            <th><?= __('Website'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($organizations as $organization): ?>
                            <tr>
                              <td><?= $this->Html->link($organization->name, ['controller' => 'Organizations', 'action' => 'view', $organization->id]); ?></td>
                              <td><?= $this->Html->link($organization->website, $organization->website); ?></td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="paginator">
        <ul class="pagination col-lg-12 col-md-12 col-xs-12">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
    </div>
</div>
