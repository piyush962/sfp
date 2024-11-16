<div class="right-content-wrapper order-right-cnt">
    <?php
    if (!empty($customerDetails)) {
    ?>
        <div class="order_customer_info">
            <div class="row">
                <div class="col-lg-6">
                    <div class="info_wrapper customer_info">
                        <h3>Customers Info</h3>
                        <ul>
                            <li>
                                <h6>Company Name</h6>
                                <p><?= $customerDetails['company_name'] ?></p>
                            </li>
                            <li>
                                <h6>Owner Name</h6>
                                <p><?= $customerDetails['name'] ?></p>
                            </li>
                            <li>
                                <h6>Email</h6>
                                <p><?= $customerDetails['email'] ?></p>
                            </li>
                            <li>
                                <h6>Phone</h6>
                                <p><?= $customerDetails['phone_number'] ?></p>
                            </li>
                            <li>
                                <h6>User ID</h6>
                                <p><?= $customerDetails['user_id'] ?></p>
                            </li>
                            <li>
                                <h6>Date Added</h6>
                                <p><?= $customerDetails['created'] ?></p>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="info_wrapper orders_info">
                        <h3>Top Orders</h3>
                        <div class="chart-progress">

                        <?php
                        if(!empty($productCategoryData)){
                            foreach($productCategoryData as $k => $v){
                               $colors = ["#3366cc","#dc3912","#ff9900","#109618","#990099","#0099c6","#dd4477","#66aa00","#b82e2e","#316395",
                        "#994499","#22aa99","#aaaa11","#6633cc","#e67300","#8b0707","#651067","#329262","#5574a6","#3b3eac","#b77322",
                        "#16d620","#b91383","#f4359e","#9c5935","#a9c413","#2a778d","#668d1c","#bea413","#0c5922","#743411"];
                        if($v['product']->p_category > 0){
                            $categoryName=$this->Common->getCategoryName($v['product']->p_category);
                            ?>                          
                            <div class="progressItem">
                                <h6> <?=$categoryName?> <strong><?= $v['productCount']?></strong></h6>
                                <div class="progress">
                                    
                                    <div class="progress-bar" role="progressbar" style="width: 25%;background-color: <?= $colors[$k] ?>" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <?php }}}else{?>
                           <p style="color:red">No Record Found!</p>
                                <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <div class="custom-table-wrapper">
        <div class="table-head-box">
            <h3>Orders</h3>
            <div class="add-new-customer">
                <div class="filterField">
                    <select id="filterStatus" class="form-select" onchange="filterstatus(this)"> 
                                    <option value='' <?php echo $optionval == '' ? 'selected' : ''?>>Status Filter</option>                                   
                                    <option value='0' <?php echo $optionval == '0' ? 'selected' : ''?>>Approval Pending</option>
                                    <option value='1' <?php echo $optionval == '1' ? 'selected' : ''?>>In Process</option>
                                    <option value='2' <?php echo $optionval == '2' ? 'selected' : ''?>>Dispatch</option>
                                    <option value='3' <?php echo $optionval == '3' ? 'selected' : ''?>>Delivered</option>
                                    <option value='4' <?php echo $optionval == '4' ? 'selected' : ''?>>Cancel</option>                                           
                                </select>
                </div>
                <?php
                if (!empty($customerDetails['id'])) {
                    $customerId = $customerDetails['id'];
                } else {
                    $customerId = '';
                }
                ?>
                <a href="/orders/add/<?= $customerId ?>" class="admin-theme-btn">
                    Add New Order
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>S no</th>
                        <th>Customer</th>
                        <th>Product</th>
                        <th>QTY</th>
                        <th>Order Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 1;
                    foreach ($orderData as $data) {
                    ?>
                        <tr>
                            <td><?= $count ?></td>
                            <td><?php echo $data->user->name; ?></td>
                            <td><?php echo $this->Common->getProductName($data['p_name']) ?></td>
                            <td><?= $data['quantity'] ?></td>
                            <td><?= $data['order_date'] ?></td>
                            <td>₹<?php echo  $data['total_price'] > 0 ? $data['total_price'] : '0' ?></td>
                            <td>
                                <input type="hidden" name="orderId" value="<?= $data['id'] ?>">
                                <select id="statusDropdown" class="form-select" onchange="changestatus(this)">                                    
                                    <option value='0' <?php echo $data['status'] == 0 ? 'selected' : '' ?>>Approval Pending</option>
                                    <option value='1' <?php echo $data['status'] == 1 ? 'selected' : '' ?>>In Process</option>
                                    <option value='2' <?php echo $data['status'] == 2 ? 'selected' : '' ?>>Dispatch</option>
                                    <option value='3' <?php echo $data['status'] == 3 ? 'selected' : '' ?>>Delivered</option>
                                    <option value='4' <?php echo $data['status'] == 4 ? 'selected' : '' ?>>Cancel</option>                                           
                                </select>
                            </td>
                            <td>
                                <div class="action-btns">
                                    <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Action
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="actionDropdown">
                                        <?php

                                        if ($data['status'] != '3') {
                                        ?>
                                            <li>
                                                <a href="/orders/add/<?= $data['client_id'] ?>/<?= $data['id'] ?>">
                                                    Edit Order
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php
                                        if (!empty($customerDetails['id'])) {
                                        ?>
                                            <li>
                                                <a href="/orders/add/<?= $data['client_id'] ?>/<?= $data['id'] ?>?reorder=1">
                                                    Re-Order
                                                </a>
                                            </li>
                                        <?php
                                        }
                                        ?>
                                        <li>
                                            <a href="javascript:void(0)" onclick="deleteorderlist(<?= $data['client_id'] ?>,<?= $data['id'] ?>)">
                                                Delete
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

<script>
function filterstatus(e) {
        var option = $(e).val();
        if(option != ''){
            window.location.href = "/orders/index?optionval="+option;
        }else{
            window.location.href = "/orders/index";
        }
        
    }

    function changestatus(e) {
        var orderId = $(e).closest('td').find('input').val();
        var option = $(e).val();
        window.location.href = "/orders/changeorderstatus/" + orderId + "/" + option;
    }

    function deleteorderlist(customerId, orderId) {
        if (confirm("Are You Sure Want To Delete Order List")) {
            window.location.href = "/orders/deleteorderlist/" + customerId + "/" + orderId;
        }
    }
</script>