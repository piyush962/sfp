<div class="right-content-wrapper">

    <div class="custom-table-wrapper">
        <div class="table-head-box">
            <h3>Product Listing</h3>
            <div class="add-new-customer">
                <?php
                $productId = $this->getRequest()->getParam('pass.0');

                ?>
                <a type="button" class="admin-theme-btn" href="/products/add/<?= $productId ?>">
                    Add New Product
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>S no</th>
                        <th>Customer</th>
                        <th>Products</th>
                        <th>Category</th>
                        <th>Variation</th>
                        <th>Added Date</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $count = 1;
                    foreach ($productData as $data) {
                        $clientCount = $data['client_id'];
                    ?>
                        <tr>
                            <td><?= $count ?></td>
                            <td><?php echo $data->user->name; ?></td>
                            <td><?php echo $data['p_name']; ?></td>
                            <td><?php echo $data->category->name; ?></td>
                            <td><?php echo count($data->product_details); ?></td>
                            <td><?= $data['created'] ?></td>
                            <td>
                                <div class="action-btns">
                                    <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Action
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="actionDropdown">
                                        <li>
                                            <a href="/products/add/<?= $productId ?>/<?= $data['id'] ?>">
                                                Edit product
                                            </a>
                                        </li>
                                    </ul>

                                </div>
                            </td>
                        </tr>
                    <?php $count++;
                    } ?>
                </tbody>
            </table>
        </div>
        <div class="paginator">
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



<!-- <script>
        function deletedata(id){
                if(confirm("Are You Want To Delete Data"+id)){
                    window.location.href='/users/deleteuserdata/'+id;
                }
            }
            $('.checkStatus').on('click',function(){
                var id=$(this).val();
                if(confirm("Are You Want To Change Status")){
                    window.location.href='/users/updateStatus/'+id;
                }
            });
    

        </script> -->