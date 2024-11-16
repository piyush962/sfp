<?php $count = 1;
    foreach ($userData as $data) {
    ?>
        <tr>
            <td><?= $count ?></td>
            <td><?= $data['name'] ?></td>
            <td><?= $data['company_name'] ?></td>
            <td><?= $data['user_id'] ?></td>
            <td>
                <?php $proCount= $this->Common->getproductcount($data['id']);
                    if(!empty($proCount)){
                        echo $proCount['productCount'];
                    }else{
                        echo '0';
                    }                                
                ?>
            </td>
            <td>+91-<?= $data['phone_number'] ?></td>
            <td><?= $data['email'] ?></td>
            <td><?php
                $dateTime = DateTime::createFromFormat('n/j/y, g:i A', $data['created']);
                if ($dateTime) {
                    $formattedDate = $dateTime->format('d-M-Y');
                    echo $formattedDate;
                }
                ?></td>
            <td>
                <div class="action-btns">
                    <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Action
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="actionDropdown">
                        <li>
                            <a href="#">
                                Change status
                            </a>
                        </li>
                        <li>
                            <a href="/users/addcustomer/<?= $data['id'] ?>">
                                Edit Customer
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" onclick="deletedata(<?= $data['id'] ?>)">Delete</a>
                        </li>
                        <li>
                            <a href="/products/index/<?= $data['id'] ?>">
                                Add Products
                            </a>
                        </li>
                        <li>
                            <a href="/orders/index/<?= $data['id'] ?>">
                                View Order
                            </a>
                        </li>
                    </ul>
                </div>
            </td>
        </tr>
    <?php $count++;
    }  ?>
@@@@@
<ul class="pagination">
    <?= $this->Paginator->first('<< ' . __('first')) ?>
    <?= $this->Paginator->prev('' . __('previous')) ?>
    <?= $this->Paginator->numbers() ?>
    <?= $this->Paginator->next(__('next') . '') ?>
    <?= $this->Paginator->last(__('last') . ' >>') ?>
</ul>
<p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>