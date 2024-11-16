<div class="right-content-wrapper">                
                <div class="custom-table-wrapper">
                   
                    <div class="custom-form-design">
                    <?= $this->Form->create($orderNewEntity) ?>
                            <div class="row">
                            <div class="col">
                                    <div class="input_group">
                                        <label>Customer Name</label>
                                        
                                        <?php
                                        echo $this->Form->select('client_id',$customerName,['class' => 'form-control select2-box','empty' => 'Select Customer','label'=>false,'required'=>true,'onchange'=>'getshippingAdd(this),getproductName(this)','value'=>$Customer_id]);
                                        ?>                                         
                                    </div>
                                </div>
                                
                                <div class="col">
                                    <div class="input_group">   
                                    <label for="pm">Product Name</label>                                     
                                    <?php
                                        echo $this->Form->select(
                                            'p_name',$AllProductName,['class' => 'form-control','empty' => 'Select Product Name','required'=>true,'onchange'=>'getDimension(this)']
                                        );
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                            <div class="col">
                                    <div class="input_group">
                                        <label for="">Packaging Name</label>                                        
                                        <?php echo $this->Form->control('packaging_name',['class'=>'form-control','label'=>false,'required'=>true]);?>
                                    </div>
                                </div>
                            
                                <div class="col">
                                    <div class="input_group">
                                    <label for="pm">Packaging Dimensions (l x b x h)</label>
                                    <?php
                                    if(isset($orderNewEntity['p_dimensions']) && $orderNewEntity['p_dimensions'] != ''){
                                        $disabled='true';
                                    }else{
                                        $disabled='false';
                                    }
                                        echo $this->Form->select(
                                            'p_dimensions',$Alldimension,['class' => 'form-control','empty' => 'Select Dimensions','required'=>true,'disabled'=>$disabled]
                                        );
                                        ?>
                                        
                                    </div>
                                </div>
                                
                            </div>
                            <div class="row">
                            <div class="col">
                                    <div class="input_group">
                                        <label for="pm">Packaging Material</label>                                        
                                        <?php
                                        echo $this->Form->select('p_material',['1' => 'Plastic','2'=>'Paper'],['class' => 'form-control','empty' => 'Select Material','label'=>false,'required'=>true]);
                                        ?>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="input_group">
                                        <label for="qun">Quantity</label>
                                        <?php echo $this->Form->control('quantity',['class'=>'form-control','label'=>false,'type'=>'number','required'=>true,'min'=>0]);?>
                                    </div>
                                </div>
                                
                            </div> 
                            
                            <div class="row">
                            <div class="col">
                                    <div class="input_group">
                                        <label for="unit">Unit Price</label>                                        
                                        <?php echo $this->Form->control('unit_price',['class'=>'form-control','label'=>false,'required'=>true]);?>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="input_group">
                                        <label for="order-date">Order Date</label>
                                        <input type="text" id="order-date" class="form-control datepicker" name="order_date" value='<?=$orderNewEntity['order_date'] ?>' required>
                                        <?php //echo $this->Form->control('order_date',['class'=>'form-control']);?>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="row">
                            <div class="col">
                                    <div class="input_group">
                                        <label for="order-deadline">Order Deadline</label>
                                        <input type="text" id="order-deadline" class="form-control datepicker datepickerDeadline" name="order_deadline" value='<?=$orderNewEntity['order_deadline'] ?>' required>
                                        <?php //echo $this->Form->control('order_deadline',['class'=>'form-control']);?>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="input_group">   
                                        <label for="reminder">Send Reminder</label>                                     
                                            <?php
                                                echo $this->Form->select(
                                                'send_reminder',$durationData,['class' => 'form-control','empty' => 'Select Send Reminder','required'=>true]
                                                );
                                            ?>
                                    </div>                                    
                                </div>                               
                                
                            </div>
                            <div class="row">
                            
                                <div class="col">
                                    <div class="input_group">
                                        <label for="shipto">Ship to</label>
                                        <?php
                                        echo $this->Form->select(
                                            'ship_to',$AllAddress,['class' => 'form-control shippingAddress select2-box','empty' => 'Select Shipping Address','required'=>true,'value'=>$orderNewEntity['ship_to']                                            
                                            ]
                                        );
                                        ?>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col text-end">
                                    <button type="submit" class="admin-theme-btn" id="addNewOrder">
                                        Save
                                    </button>
                                </div>
                            </div>
                            <?= $this->Form->end() ?>
                    </div>
                </div>
            </div>
<script>

flatpickr('.datepicker', {
    minDate: new Date(),
});

                
function timeFun(dateVal){
    console.log('after fun: ', dateVal);
    flatpickr('.datepickerDeadline', {
        minDate: new Date(dateVal),
    });
}

jQuery(document).on('change', '#order-date', function(){
    var selectedDate = jQuery(this).val();
    timeFun(selectedDate);
    // console.log(selectedDate);
})


                
/*function getsubcategory(e){
    var categoryId=$(e).val();
    $.ajax({       
       headers: {
           'X-CSRF-Token': ""
        },
       method:'POST',
       url:'./getsubcategory',
       data:{categoryId:categoryId},
       success:function(res){
        $('select[name="p_subcategory"]').append(res);
       },
       error:function(errro){
           alert("something went error");
       }
   });
}*/
function getproductName(e){
    var customerId=$(e).val();
    $.ajax({       
       headers: {
           'X-CSRF-Token': "<?= $this->request->getAttribute('csrfToken'); ?>"
        },
       method:'POST',
       url:'/orders/getproductName',
       data:{customerId:customerId},
       success:function(res){  
        $('select[name="p_name"]').html(res);
        $('select[name="p_name"]').trigger('change');
        
       },
       error:function(errro){
           alert("something went error");
       }
   });
}
//Y-m-d H:i:s
function getDimension(e){
    var productId=$(e).val();
    $.ajax({       
       headers: {
           'X-CSRF-Token': "<?= $this->request->getAttribute('csrfToken'); ?>"
        },
       method:'POST',
       url:'/orders/getDimension',
       data:{productId:productId},
       success:function(res){  
        $('select[name="p_dimensions"]').html(res);
        
       },
       error:function(errro){
           alert("something went error");
       }
   });
}
function getshippingAdd(e){    
    var customerId=$(e).val();
    $.ajax({       
       headers: {
           'X-CSRF-Token': "<?= $this->request->getAttribute('csrfToken'); ?>"
        },
       method:'POST',
       url:'/orders/getshippingAdd',
       data:{customerId:customerId},
       success:function(res){  
        $('select[name="ship_to"]').html(res);
       },
       error:function(errro){
           alert("something went error");
       }
   });
}

            </script>

            <script>
                var $disabledResults = $(".select2-box");
                $disabledResults.select2();

$(document).ready(function(){
    var order_date_value = $('#order-date').val();
    if(order_date_value != ''){
        var parsed_date = new Date(order_date_value);
        var todayDate = parsed_date.getFullYear() + '-' + ('0' + (parsed_date.getMonth() + 1)).slice(-2) + '-' + ('0' + parsed_date.getDate()).slice(-2);
        $('#order-date').val(todayDate);
    }else{
        var today = new Date();
        var todayDate = today.getFullYear() + '-' + ((today.getMonth() + 1) < 10 ? ('0' + (today.getMonth() + 1)) : (today.getMonth() + 1)) + '-' + (today.getDate() < 10 ? ('0' + today.getDate()) : today.getDate());
        
        $('#order-date').val(todayDate);
    }
    
})
$(document).ready(function(){
    var order_deadline_date_value = $('#order-deadline').val();
    if(order_deadline_date_value != ''){
        var parsed_date = new Date(order_deadline_date_value);
        var deadlinedate = parsed_date.getFullYear() + '-' + ('0' + (parsed_date.getMonth() + 1)).slice(-2) + '-' + ('0' + parsed_date.getDate()).slice(-2);
        $('#order-deadline').val(deadlinedate);
    }
    
})

               


            </script>