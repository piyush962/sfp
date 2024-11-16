<div class="right-content-wrapper">                
                <div class="custom-table-wrapper">                   
                    <div class="custom-form-design">
                    <?= $this->Form->create($newProductentity,['type' => 'file']) ?>
                            <div class="row">
                                <div class="col">
                                    <div class="input_group">   
                                    <label for="pm">Product Name</label>                                     
                                        <?php echo $this->Form->control('p_name',['class'=>'form-control','label'=>false,'required'=>true,'value'=> $newProductentity['p_name']]);?>
                                    </div>
                                </div>  
                                <div class="col">
                                    <div class="input_group">
                                        <label for="pcate">Category</label>
                                        <?php
                                        echo $this->Form->select('p_category',$catData,['class' => 'form-control','empty' => 'Select Category','label'=>false,'required'=>true]);
                                        ?>                                        
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                            <div class="col">
                                    <div class="input_group">
                                        <label for="upload-image">Upload Image</label>
                                        <?= $this->Form->control('image', [
                                            'class' => 'form-control form-control-user',
                                            'type' => 'file',
                                            'allowempty' => true,
                                            'label' => false,
                                            
                                            ]);?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                <div class="input_group">
                                            <div class="addmore-wrapper repeater">
                                            <div data-repeater-list="category-group"> 
                                                   <?php if($newProductentity['id'] > 0){ 
                                                    foreach($newProductentity['product_details'] as $k => $v){
                                                    ?>
                                                    <div data-repeater-item> 
                                                    <?= $this->Form->control('edit_id', ['type'=>'hidden','value'=>$v->id,'class'=>'edit_id']) ?>                                                              
                                                        <div class="addmore-inputs">
                                                            <label>Packaging Dimension (l x b x h)</label>
                                                            <?= $this->Form->control('p_dimension', ['class'=>'form-control ','required'=>true,'label'=>false,'value'=>$v['p_dimension']]) ?>                                                           
                                                        </div>                                      
                                                        

                                                    </div>
                                                    <?php }}else{ ?>
                                                        <div data-repeater-item> 
                                                        <?= $this->Form->control('edit_id', ['type'=>'hidden','class'=>'edit_id']) ?>                                                              
                                                        <div class="addmore-inputs">
                                                            <label>Packaging Dimension (l x b x h)</label>
                                                            <?= $this->Form->control('p_dimension', ['class'=>'form-control ','required'=>true,'label'=>false]) ?>                                                           
                                                        </div>                                       
                                                        <div class="delete_box">
                                                            <?= $this->Form->button('-', [
                                                                'class' => 'remove removeextrafield',
                                                                'data-repeater-delete' => true,
                                                                'type' => 'button',  
                                                                'value' => '-'
                                                            ]) ?>
                                                        </div>

                                                    </div>
                                                    <?php }?>
                                                </div>
                                                <button class="addmorebtn" data-repeater-create type="button">
                                                    Add More
                                                </button>  
                                            </div>
                                        </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col text-end">
                                    <button type="submit" class="admin-theme-btn" id="addNewOrder">
                                        Add
                                    </button>
                                </div>
                            </div>
                    <?= $this->Form->end() ?>
                    </div>
                </div>
            </div>
<script>
    $(document).ready(function() {
            'use strict';
            window.id = 0;
        $('.repeater').repeater({
            defaultValues: {
              'id': window.id,
            },
            show: function() {
              $(this).slideDown();
            },
            hide: function(deleteElement) {
                var length = $(this).parent().find('.edit_id').length;
                    if($(this).parent().find('.edit_id').eq(length-1).attr('name') == 'category-group[0][edit_id]'){
                        $(this).find('.delete_box').find('button').prop('disabled',true);
                        alert('cannot delete last element');
                    }else{
                        if (confirm('Are you sure you want to delete this element?')) {              
                            // if($(this).find('.edit_id').val()>0){
                                // delmultiaddress($(this).find('.edit_id').val());
                            // }
                            $(this).slideUp(deleteElement);
                        }
                    }
            },
            ready: function(setIndexes) {
            }
        });
    });
flatpickr('.datepicker');
</script>