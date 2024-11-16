       
        <!-- add category start  -->
        <div class="modal-body">
            <div class="custom-form-design">
                <?= $this->Form->create($subcatEntity) ?>
                        <div class="row">
                        <div class="col">
                                    <div class="input_group">
                                        <label for="pm">Category Name</label>                                        
                                        <?php
                                        echo $this->Form->select('catname',$CategoryList,['class' => 'form-control','required'=>true,'empty' => true,'label'=>false,'value'=>$subcatEntity['parent_id']]);
                                        ?>
                                    </div>
                                </div>
                            <div class="col">
                                <div class="input_group">
                                <label for="pm">Sub Category Name</label>
                                    <?php  echo $this->Form->control('name',['class'=>'form-control','id'=>'cname','required'=>true,'label'=>false]); ?>
                                </div>
                            </div>                                    
                        </div>                               
                        
                        <div class="modal-footer text-end">
                            <?= $this->Form->submit(__('Save'),['class'=>'admin-theme-btn']) ?>
                        </div> 
                <?= $this->Form->end() ?>
                </div>  
        </div>  
        <!-- add category end  -->

        <!-- category listing start  -->
<div class="right-content-wrapper">
    <!-- <div class="add-new-customer">
        <a type="button" class="admin-theme-btn" href="users/addcustomer">
            Add New Category
        </a>
    </div> -->
    <div class="custom-table-wrapper">
        <h3>Sub Category Listing</h3>
        
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>S no</th>
                        <th>Category Name</th>
                        <th>Sub Category Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $count=1; foreach($AllSubCategories as $data){ 
                        
                        ?>
                    <tr>
                        <td><?= $count?></td>
                        <td> <?php echo $this->Common->getCategoryName($data['parent_id'])?></td>
                        <td><?= $data['name'] ?></td>                         
                        <td>
                            <div class="action-btns">
                            <!-- <div class="form-check form-switch">
                                <input class="form-check-input checkStatus" name="status" type="checkbox" role="switch" value="" id="status" >
                            </div> -->
                                <a href="/orders/subcategory/<?=$data['id']?>">
                                    <svg width="18" height="20" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M1 19H17M2.666 11.187C2.23943 11.6142 1.99989 12.1933 2 12.797V16H5.223C5.827 16 6.406 15.76 6.833 15.332L16.333 5.82697C16.7597 5.39983 16.9994 4.82075 16.9994 4.21697C16.9994 3.61319 16.7597 3.03411 16.333 2.60697L15.395 1.66697C15.1835 1.45532 14.9323 1.28744 14.6558 1.17294C14.3794 1.05843 14.083 0.999541 13.7838 0.999634C13.4846 0.999727 13.1883 1.0588 12.9119 1.17348C12.6355 1.28816 12.3844 1.45619 12.173 1.66797L2.666 11.187Z"
                                            stroke="#4D4D4D" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </a>                                
                            </div>
                        </td>
                    </tr>
                    <?php $count++; }  ?>
                </tbody>
            </table>
        </div>
           </div>
</div>
        <!-- category listing end  -->