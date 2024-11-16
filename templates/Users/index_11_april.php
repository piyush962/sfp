<div class="right-content-wrapper customer_page">

    <div class="custom-table-wrapper">
        <div class="table-head-box">
            <h3>Customers Listing</h3>
            <div class="add-new-customer">
                <div class="filterField">
                   <input type="search" name="filterCustomer" id="filterCustomer" class="form-control" placeholder="Search customer...">
                </div>
                <a type="button" class="admin-theme-btn" href="users/addcustomer">
                    Add New Customer
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>S no</th>
                        <th>Customer</th>
                        <th>Company</th>
                        <th>User ID</th>
                        <th>Products</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Added Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 1;
                    foreach ($userData as $data) {
                    ?>
                        <tr>
                            <td><?= $count ?></td>
                            <td><?= $data['name'] ?></td>
                            <td><?= $data['company_name'] ?></td>
                            <td><?= $data['user_id'] ?></td>
                            <td>10</td>
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
                                    <!-- <div class="form-check form-switch">
                                        <input class="form-check-input checkStatus" name="status" type="checkbox" role="switch" value="<?= $data['id']; ?>" id="status" <?php echo $data['status'] ? "checked" : ""; ?>>
                        
                                    </div>

                                    <a href="/users/addcustomer/<?= $data['id'] ?>">
                                        <svg width="18" height="20" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1 19H17M2.666 11.187C2.23943 11.6142 1.99989 12.1933 2 12.797V16H5.223C5.827 16 6.406 15.76 6.833 15.332L16.333 5.82697C16.7597 5.39983 16.9994 4.82075 16.9994 4.21697C16.9994 3.61319 16.7597 3.03411 16.333 2.60697L15.395 1.66697C15.1835 1.45532 14.9323 1.28744 14.6558 1.17294C14.3794 1.05843 14.083 0.999541 13.7838 0.999634C13.4846 0.999727 13.1883 1.0588 12.9119 1.17348C12.6355 1.28816 12.3844 1.45619 12.173 1.66797L2.666 11.187Z" stroke="#4D4D4D" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </a>
                                    <button onclick="deletedata(<?= $data['id'] ?>)">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M7 21C6.45 21 5.97933 20.8043 5.588 20.413C5.19667 20.0217 5.00067 19.5507 5 19V6H4V4H9V3H15V4H20V6H19V19C19 19.55 18.8043 20.021 18.413 20.413C18.0217 20.805 17.5507 21.0007 17 21H7ZM17 6H7V19H17V6ZM9 17H11V8H9V17ZM13 17H15V8H13V17Z" fill="#4D4D4D" />
                                        </svg>
                                    </button>
                                    <a href="/products/index/<?= $data['id'] ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                                        </svg>
                                    </a>
                                    <a href="/orders/index/<?= $data['id'] ?>">
                                        view
                                    </a> -->
                                </div>
                            </td>
                        </tr>
                    <?php $count++;
                    }  ?>
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
    function deletedata(id) {
        if (confirm("Are You Sure Want To Delete ")) {
            window.location.href = '/users/deleteuserdata/' + id;
        }
    }
    $('.checkStatus').on('click', function() {
        var id = $(this).val();
        if (confirm("Are You Want To Change Status")) {
            window.location.href = '/users/updateStatus/' + id;
        }
    });
</script>