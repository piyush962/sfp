       <!-- add category start  -->
       <div class="modal-body">
           <div class="custom-form-design">
               <?= $this->Form->create($newCategoryEntity) ?>
               <div class="row">
                   <div class="col">
                       <div class="input_group">
                           <label for="pm">Category Name</label>
                           <?php echo $this->Form->control('name', ['class' => 'form-control', 'id' => 'cname', 'required' => true, 'label' => false]); ?>
                       </div>
                   </div>
               </div>
               <div class="modal-footer text-end">
                   <?php echo  $this->Form->submit(__('Save'), ['class' => 'admin-theme-btn']) ?>
               </div>
               <?= $this->Form->end() ?>
           </div>
       </div>
       <!-- add category end  -->
       <!-- category listing start  -->
       <div class="right-content-wrapper category_page">
           <div class="custom-table-wrapper ">
               <h3>Category Listing</h3>

               <div class="table-responsive">
                   <table class="table">
                       <thead>
                           <tr>
                               <th>S no</th>
                               <th>Category Name</th>
                               <th>Action</th>
                           </tr>
                       </thead>
                       <tbody>
                           <?php 
                           $count = $this->Paginator->counter('{{start}}'); 

                            foreach ($catData as $data) { ?>
                               <tr>
                                   <td><?= $count ?></td>
                                   <td><?= $data['name'] ?></td>
                                   <td>
                                       <div class="action-btns">
                                           <div class="form-check form-switch">
                                               <input class="form-check-input checkStatus" name="status" type="checkbox" role="switch" value="<?= $data['id'] ?>" id="status" <?php echo $data['status'] ? "checked" : ""; ?>>
                                               <!-- <?php   //echo $this->Form->control('status', ['type' => 'checkbox','role' => 'switch','value'=>$data['id'],'class' => 'checkStatus form-check-input'] ) 
                                                    ?> -->
                                           </div>

                                           <a href="/orders/category/<?= $data['id'] ?>">
                                               <svg width="18" height="20" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                   <path d="M1 19H17M2.666 11.187C2.23943 11.6142 1.99989 12.1933 2 12.797V16H5.223C5.827 16 6.406 15.76 6.833 15.332L16.333 5.82697C16.7597 5.39983 16.9994 4.82075 16.9994 4.21697C16.9994 3.61319 16.7597 3.03411 16.333 2.60697L15.395 1.66697C15.1835 1.45532 14.9323 1.28744 14.6558 1.17294C14.3794 1.05843 14.083 0.999541 13.7838 0.999634C13.4846 0.999727 13.1883 1.0588 12.9119 1.17348C12.6355 1.28816 12.3844 1.45619 12.173 1.66797L2.666 11.187Z" stroke="#4D4D4D" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                               </svg>
                                           </a>

                                       </div>
                                   </td>
                               </tr>
                           <?php
                            $count++; } 
                            
                            ?>
                       </tbody>
                   </table>
               </div>
               <div class="paginator paginator-ajax">
            <ul class="pagination">
                <?= $this->Paginator->first('<< ' . __('first')) ?>
                <?= $this->Paginator->prev('' . __('previous')) ?>
                <?= $this->Paginator->numbers() ?>
                <?= $this->Paginator->next(__('next') . '') ?>
                <?= $this->Paginator->last(__('last') . ' >>') ?>
            </ul>
            <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
        </div>
           </div>
       </div>
       <!-- category listing end  -->
       <script>
           $('.checkStatus').on('click', function() {
               var id = $(this).val();
               if (confirm("Are You Want To Change Status")) {
                   window.location.href = '/orders/updateStatus/' + id;
               }
           });
       </script>