<div class="row">
    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h1 class="page-header"><?= __('Accept the member'); ?></h1>
                <?php
                $this->Html->addCrumb(__('Home'), '/');
                $this->Html->addCrumb(__('Users'), '/Users');
                $this->Html->addCrumb($organization->getName(), '/Users/view/'.$user->id);
                $this->Html->addCrumb(__('Accept as member'));

                echo $this->Html->getCrumbList(); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h3 class="header-title"><?= __('Accept as member for organization {0}', $organization->getName()); ?></h3>
                        <?= $this->Form->create($membership, ['type' => 'post', 'url' => ['action' => 'accept']]); ?>
                        <?= $this->Form->button(__('Accept as member'), ['class' => 'btn-info']); ?>
                        <?= $this->Form->button(__('Cancel'), [
                            'type' => 'button',
                            'class' => 'btn btn-default',
                            'onclick' => 'location.href=\'' . $this->url->build([
                                    'controller' => 'Organizations',
                                    'action' => 'view',
                                    $organization->id
                                ]) . '\''
                        ]); ?>
                        <?= $this->Form->end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->Html->script(['initial.min'], ['block' => 'scriptBottom']); ?>
