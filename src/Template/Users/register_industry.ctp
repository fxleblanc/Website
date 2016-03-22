<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta http-equiv="X-UA-COMPATIBLE" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700,600' rel='stylesheet' type='text/css'>

    <?= $this->Less->less('less/styles.less'); ?>
    <?= $this->Html->css('bootstrap-switch.min'); ?>
</head>
<body>
<div id="register-wrapper">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="terms-and-conditions-container">
                <?= $this->element('Users/terms'); ?>
                <hr>
                <?= $this->Form->checkbox('accepted'); ?>
                <?= $this->Form->label('accepted', ['text' => __('I accept the terms and conditions')]); ?>
            </div>
            <?= $this->Form->create($user, ['class' => 'form-horizontal']) ?>
            <div class="col-sm-12 col-xs-12">
                <h2><?= __("Sign Up to {0}", '<strong>' . __('ML2') . '</strong>') ?></h2>
            </div>
            <div id="register-alert" class="col-sm-12"><?= $this->Flash->render() ?></div>
            <div class="col-sm-5 col-xs-12">
                <fieldset>
                    <legend><?= __('Personal info') ?> <span class="sub"><?= __('(required)'); ?></span></legend>
                    <?= $this->Form->input('firstName', ['label' => false, 'placeholder' => __('Enter your firstname'), 'autocomplete' => 'off']); ?>
                    <?= $this->Form->input('lastName', ['label' => false, 'placeholder' => __('Enter your lastname'), 'autocomplete' => 'off']); ?>
                </fieldset>
            </div>
            <div class="col-sm-offset-1 col-sm-6 col-xs-12">
                <fieldset>
                    <legend><?= __('Account info') ?> <span class="sub"><?= __('(required)'); ?></span></legend>
                    <?= $this->Form->input('username', ['pattern' => '[a-zA-Z0-9_.-]{3,16}', 'title' => __('Letters (a-z), numbers, periods, underscore, and between 3 and 16 characters'), 'label' => false, 'placeholder' => __('Choose your username'), 'autocomplete' => 'off']); ?>
                    <?= $this->Form->input('password', ['label' => false, 'placeholder' => __('Choose a password'), 'autocomplete' => 'off']); ?>
                    <?= $this->Form->input('confirm_password', ['label' => false, 'type' => 'password', 'placeholder' => __('Confirm password'), 'autocomplete' => 'off']); ?>
                </fieldset>
            </div>
            <div class="col-sm-5 col-xs-12">
                <fieldset>
                    <legend><?= __('Contact info') ?> <span class="sub"><?= __('(required)'); ?></span></legend>
                    <?= $this->Form->input('email', ['label' => false, 'placeholder' => __('Email adress'), 'autocomplete' => 'off']); ?>
                    <?= $this->Form->input('confirm_email', ['label' => false, 'placeholder' => __('Confirm email adress'), 'autocomplete' => 'off']); ?>
                </fieldset>
            </div>
            <div class="col-sm-offset-1 col-sm-6 col-xs-12">
                <fieldset>
                    <legend><?= __('Organization info'); ?></legend>
                    <?= $this->Form->checkbox('isMember'); ?>
                    <?= $this->Form->label('isMember', ['text' => __('Are you member of an organization?')]); ?>
                </fieldset>
            </div>

            <?= $this->Form->button(__('Sign Up'), ['class' => 'btn btn-info']); ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
    <div id="register-login-link" class="col-sm-12 col-xs-12">
        <?= __('Already have account?'); ?>
        <a href="<?= $this->Url->build(["controller" => "Users", "action" => "login"]); ?>">
            <strong><?= __('Sign In'); ?></strong>
        </a>
    </div>
</div>
<?= $this->Html->script(
    [
        'jquery-2.1.4.min',
        'bootstrap.min',
        'googleAnalytics',
        'bootstrap/bootstrap-switch.min'
    ]
); ?>
<script>
    <?= 'var yesTr="' . __('Yes') . '";'; ?>
    <?= 'var noTr="' . __('No') . '";'; ?>
</script>
<?= $this->Html->script('users/register'); ?>
</body>
</html>
