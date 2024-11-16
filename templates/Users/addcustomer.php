                <div class="modal-body">
                    <div class="custom-form-design">
                        <?= $this->Form->create($newUserentity) ?>
                                <div class="row">
                                    <div class="col">
                                        <div class="input_group">
                                            <?php  echo $this->Form->control('company_name',['class'=>'form-control','id'=>'cname','required'=>true]); ?>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="input_group">                                     
                                            <?php  echo $this->Form->control('name',['class'=>'form-control','id'=>'name','required'=>true]); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="input_group">
                                            <?php  echo $this->Form->control('email',['class'=>'form-control','required'=>true,'id'=>'email','type'=>'email']);    ?>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="input_group">
                                            <?php echo $this->Form->control('phone_number',['class'=>'form-control','required'=>true]);?>
                                           
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="input_group">
                                            <div class="addmore-wrapper repeater">
                                            <div  data-repeater-list="category-group">                                    
                                            <?php if($newUserentity['id'] > 0){
                                                foreach($newUserentity['user_details'] as $k => $v){
                                                    $shipping_address=$v['shipping_address'];
                                                    $billing_address=$v['billing_address']; ?>
                                                    <div class="checkrowcount" data-repeater-item>  
                                                        <?= $this->Form->control('edit_id', ['type'=>'hidden','value'=>$v->id,'class'=>'edit_id']) ?>                                                                                                     
                                                        <div class="addmore-inputs">
                                                            <?= $this->Form->control('billing_address', ['class'=>'form-control billingAddress','required'=>true,'value'=>$billing_address ,'onkeyup'=>'putonshippingaddress(this)']) ?>                                                           
                                                        </div>
                                                        <div class="addmore-inputs">
                                                            <?= $this->Form->control('shipping_address', ['class'=>'form-control shippingaddress','required'=>true,'value'=>$shipping_address]) ?>                                                           
                                                        </div>   
                                                        <div class="filling_check">
                                                            <input type="checkbox" id="shipping_add_same" class="form-check-input samebillingaddress" value="Same as shipping address">
                                                            <label for="shipping_add_same">Same as shipping address</label>
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
                                            <?php } }else{
                                                ?>
                                                    <div class="checkrowcount" data-repeater-item>  
                                                    <?= $this->Form->control('edit_id', ['type'=>'hidden','value'=>'0','class'=>'edit_id']) ?>                                                                                              
                                                        <div class="addmore-inputs">
                                                            <?= $this->Form->control('billing_address', ['class'=>'form-control billingAddress','required'=>true, 'onkeyup'=>'putonshippingaddress(this)']) ?>                                                           
                                                        </div>
                                                        <div class="addmore-inputs">
                                                        <?= $this->Form->control('shipping_address', ['class'=>'form-control shippingaddress','required'=>true]) ?>                                                           
                                                        </div>   
                                                        <div class="filling_check">
                                                            <input type="checkbox" id="shipping_add_same" class="form-check-input samebillingaddress" value="Same as billing address">
                                                            <label for="shipping_add_same">Same as billing address</label>
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
                                <div class="modal-footer text-end">                                    
                                    <?= $this->Form->submit(__('Save'),['class'=>'admin-theme-btn']) ?>                                                             
                                </div> 
                    <?= $this->Form->end() ?>
                </div>  
            </div>  
<script>
//        $(document).ready(function(){
//        $('.checkrowcount').last().find('.delete;
//        });
// function deletefirstrowbtn(){
//     $('.checkrowcount').find('.delete_box').find('button').prop('disabled',false);
//     $('.checkrowcount').last().find('.delete_box').find('button').prop('disabled',true);
// }
        function delmultiaddress(id){
            $.ajax({
                headers: {
                    'X-CSRF-Token': "<?= $this->request->getAttribute('csrfToken'); ?>"
                },
                method:'POST',
                url:'../delmultiaddress',
                data:{id:id},
                success:function(res){
                if(res){
                    console.log("deleted");
                }
                },
                error:function(errro){
                    alert("something went error");
                }
            });
        
        }

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
                            }  else{
                                if (confirm('Are you sure you want to delete this element?')) { 
                                  
                                  if($(this).find('.edit_id').val()>0){
                                      delmultiaddress($(this).find('.edit_id').val());
                                      
                                  }
                              $(this).slideUp(deleteElement);
                              }
                            }
                   
                },
                ready: function(setIndexes) {


                }
            });


        });
        $(document).on('click','.samebillingaddress',function(){
            if($(this).is(':checked')){
                $(this).parent().parent().find('.shippingaddress').val($(this).parent().parent().find('.billingAddress').val())
                //$('.billingAddress').val(sadd);
            }else{
                $(this).parent().parent().find('.shippingaddress').val('')
            }
        })

        function putonshippingaddress(e){
            if($(e).parent().parent().parent().find('.samebillingaddress').is(':checked')){
                $(e).parent().parent().parent().find('.shippingaddress').val($(e).parent().parent().parent().find('.billingAddress').val())
                //$('.billingAddress').val(sadd);
            }else{
                $(this).parent().parent().find('.shippingaddress').val('')
            }
        }
</script>