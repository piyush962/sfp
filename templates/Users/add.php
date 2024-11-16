<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<?php //pr($errors); ?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Users'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="users form content">
        <?= $this->Form->create($user, ['id' => 'addForm']) ?>

            <fieldset>
                <legend><?= __('Add User') ?></legend>
                <?php
                    echo $this->Form->control('owner_name',['class'=>'validate[required]','required'=>false]);
                    echo $this->Form->control('name',['class'=>'validate[required,custom[fullname]]','required'=>false,'type'=>'text']);                    
                    echo $this->Form->control('email',['class'=>'validate[required,custom[email]]','required'=>false,'type'=>'text']);                   
                    echo $this->Form->control('phone_number',['class'=>'validate[required,custom[phone]]','required'=>false,'type'=>'text']);
                    echo $this->Form->control('shipping_address',['class'=>'validate[required]','required'=>false]);
                    echo $this->Form->control('billing_address',['class'=>'validate[required]','required'=>false]);
                    echo $this->Form->control('same as shipping address',['required'=>false,'type'=>'checkbox']);
                    echo $this->Form->control('password',['class'=>'validate[required]','required'=>false,'type'=>'text']);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

