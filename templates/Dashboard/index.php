<div class="right-content-wrapper">
	<div class="dashboard-boxes">
		<div class="row">
			<div class="col">
				<a href="/users">
					<div class="dashbox">
						<h3>
							<i>
								<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M7.00004 0.333344C7.8841 0.333344 8.73194 0.684533 9.35706 1.30965C9.98218 1.93478 10.3334 2.78262 10.3334 3.66668C10.3334 4.55073 9.98218 5.39858 9.35706 6.0237C8.73194 6.64882 7.8841 7.00001 7.00004 7.00001C6.11599 7.00001 5.26814 6.64882 4.64302 6.0237C4.0179 5.39858 3.66671 4.55073 3.66671 3.66668C3.66671 2.78262 4.0179 1.93478 4.64302 1.30965C5.26814 0.684533 6.11599 0.333344 7.00004 0.333344ZM7.00004 2.00001C6.55801 2.00001 6.13409 2.1756 5.82153 2.48817C5.50897 2.80073 5.33337 3.22465 5.33337 3.66668C5.33337 4.1087 5.50897 4.53263 5.82153 4.84519C6.13409 5.15775 6.55801 5.33334 7.00004 5.33334C7.44207 5.33334 7.86599 5.15775 8.17855 4.84519C8.49111 4.53263 8.66671 4.1087 8.66671 3.66668C8.66671 3.22465 8.49111 2.80073 8.17855 2.48817C7.86599 2.1756 7.44207 2.00001 7.00004 2.00001ZM7.00004 7.83334C9.22504 7.83334 13.6667 8.94168 13.6667 11.1667V13.6667H0.333374V11.1667C0.333374 8.94168 4.77504 7.83334 7.00004 7.83334ZM7.00004 9.41668C4.52504 9.41668 1.91671 10.6333 1.91671 11.1667V12.0833H12.0834V11.1667C12.0834 10.6333 9.47504 9.41668 7.00004 9.41668Z" fill="#6A41FF" />
								</svg>
							</i>
							<?= $totalUser ?>
						</h3>
						Total Active Customers
					</div>
				</a>
			</div>
			<div class="col">
				<a href="/orders/index?optionval=0">
					<div class="dashbox">
						<h3>
							<i>
								<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M17 18H1V7H3V16H15V7H17V18ZM0 0H18V6H0V0ZM2 2V4H16V2M7.5 14V11H5L9 7L13 11H10.5V14" fill="#6A41FF" />
								</svg>
							</i>
							<?= $totalOrder ?>
						</h3>
						Recent Orders
					</div>
				</a>
			</div>
			<div class="col">
				<a href="/orders/index?optionval=1">
					<div class="dashbox">
						<h3>
							<i>
								<svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M18 0H0V6H18V0ZM16 4H2V2H16V4ZM14 7C12.69 7 11.46 7.37 10.41 8H6.5C6.22 8 6 8.22 6 8.5V10H8.26C7.43755 11.1715 6.99745 12.5686 7 14C7 14.7 7.11 15.37 7.29 16H3V7H1V18H8.26C9.53 19.81 11.62 21 14 21C17.87 21 21 17.87 21 14C21 10.13 17.87 7 14 7ZM14 19C11.24 19 9 16.76 9 14C9 11.24 11.24 9 14 9C16.76 9 19 11.24 19 14C19 16.76 16.76 19 14 19ZM14.5 14.25L17.36 15.94L16.61 17.16L13 15V10H14.5V14.25Z" fill="#6A41FF" />
								</svg>
							</i>
							<?= $processOrder ?>
						</h3>
						In Process
					</div>
				</a>
			</div>
			<div class="col">
				<a href="/orders/index?optionval=2">
					<div class="dashbox">
						<h3>
							<i>
								<svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M11.5 8C11.72 8 11.9 8.14 11.97 8.33C11.4163 8.79165 10.9607 9.35947 10.63 10H6V8.5C6 8.22 6.22 8 6.5 8H11.5ZM18 6H0V0H18V6ZM16 2H2V4H16V2ZM3 16V7H1V18H12.19C11.78 17.4 11.36 16.72 11 16H3ZM19 12.5C19 15.1 15.5 19 15.5 19C15.5 19 12 15.1 12 12.5C12 10.6 13.6 9 15.5 9C17.4 9 19 10.6 19 12.5ZM16.7 12.6C16.7 12 16.1 11.4 15.5 11.4C14.9 11.4 14.3 11.9 14.3 12.6C14.3 13.2 14.8 13.8 15.5 13.8C16.2 13.8 16.8 13.2 16.7 12.6Z" fill="#6A41FF" />
								</svg>
							</i>
							<?= $dispatchOrder ?>
						</h3>
						Order Dispatched
					</div>
				</a>
			</div>
			<div class="col">
				<a href="/orders/index?optionval=3">
					<div class="dashbox">
						<h3>
							<i>
								<svg width="20" height="19" viewBox="0 0 20 19" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M18 0H0V6H18V0ZM16 4H2V2H16V4ZM11.5 8C11.78 8 12 8.22 12 8.5V10H6V8.5C6 8.22 6.22 8 6.5 8H11.5ZM15 10.09V7H17V10.09C16.67 10.04 16.34 10 16 10C15.66 10 15.33 10.04 15 10.09ZM10 16C10 16.7 10.13 17.37 10.35 18H1V7H3V16H10ZM19.5 14.25L14.75 19L12 16L13.16 14.84L14.75 16.43L18.34 12.84L19.5 14.25Z" fill="#6A41FF" />
								</svg>
							</i>
							<?= $deliveredOrder ?>
						</h3>
						Order Delivered
					</div>
				</a>
			</div>

		</div>
	</div>
	<div class="custom-table-wrapper">
		<h3>Recent Orders</h3>
		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>S no</th>
						<th>Customer</th>
						<th>Product</th>
						<th>QTY</th>
						<th>Date</th>
						<th>Total</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
					<?php $count = 1;
					foreach ($query as $data) { ?>
						<tr>
							<td><?= $count ?></td>
							<td><?= $data['user']->name ?></td>
							<td><?= $data['product']->p_name ?></td>
							<td><?= $data['sumOfQuantity'] ?></td>
							<td><?php echo  $formattedDate = date("j-M-Y, g:i A", strtotime($data['order_date'])); ?></td>
							<td>₹<?php echo  $data['sumOfPrice'] > 0 ? $data['sumOfPrice'] : '0' ?></td>
							<td>
								<a href="javascript:void(0)" class="status-btn in-process"><?php echo  $data['status'] == 0 ? 'Approval Pending' : ($data['status'] == 1 ? "IN PROCESS" : ($data['status'] == 2 ? "Dispatch" : ($data['status'] == 3 ? "Delivered" : "Cancel"))) ?></a>
							</td>
						</tr>
					<?php $count++;
					} ?>

				</tbody>
			</table>
		</div>
	</div>
	<div class="top-customers-data">
		<div class="row">
			<div class="col-xl-6">
				<div class="custom-table-wrapper">
					<h3>Top Customer</h3>
					<div class="table-responsive">
						<table class="table">
							<thead>
								<tr>
									<th>S no</th>
									<th>Client Name</th>
									<th>Orders</th>
									<th>Revenue</th>
								</tr>
							</thead>
							<tbody>
								<?php $count = 1;
								foreach ($query as $data) { ?>
									<tr>
										<td><?= $count ?></td>
										<td><?= $data['user']->name ?></td>
										<td>12</td>
										<td>₹<?php echo  $data['sumOfPrice'] > 0 ? $data['sumOfPrice'] : '0' ?></td>
									</tr>
								<?php $count++;
								} ?>

							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-xl-6">
				<div class="custom-table-wrapper">
					<h3>Top Products</h3>
					<div class="chart_box">
						<div id="donutchart" style="width: 100%;height: 300px;"></div>
						<div class="chart-progress" id="progressBars">

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>