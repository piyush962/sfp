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
                    @@@@@
                    <ul class="pagination">
                <?= $this->Paginator->first('<< ' . __('first')) ?>
                <?= $this->Paginator->prev('' . __('previous')) ?>
                <?= $this->Paginator->numbers() ?>
                <?= $this->Paginator->next(__('next') . '') ?>
                <?= $this->Paginator->last(__('last') . ' >>') ?>
            </ul>
            <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>