<div class="top-header">
	<h2>
		<button class="mobile-toggler-btn d-block d-lg-none">
			<svg width="27" height="18" viewBox="0 0 27 18" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M0 1.5C0 0.671573 0.671573 0 1.5 0H25.5C26.3284 0 27 0.671573 27 1.5V1.5C27 2.32843 26.3284 3 25.5 3H1.5C0.671574 3 0 2.32843 0 1.5V1.5ZM0 9C0 8.17157 0.671573 7.5 1.5 7.5H18C18.8284 7.5 19.5 8.17157 19.5 9V9C19.5 9.82843 18.8284 10.5 18 10.5H1.5C0.671573 10.5 0 9.82843 0 9V9ZM0 16.5C0 15.6716 0.671573 15 1.5 15H25.5C26.3284 15 27 15.6716 27 16.5V16.5C27 17.3284 26.3284 18 25.5 18H1.5C0.671574 18 0 17.3284 0 16.5V16.5Z" fill="#262626" />
			</svg>
		</button>
		<?= $pageTitle ?>
	</h2>
	<div class="right-header">
		<ul>
			<li>
				<div class="header-notifications dropdown">
					<a type="button" href="javascript:void(0)" class="dropdown-toggle" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
					<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
													<g clip-path="url(#clip0_1_2)">
													<path d="M18.25 15.6278L18.0611 15.4611C17.5252 14.9837 17.0562 14.4361 16.6667 13.8333C16.2412 13.0014 15.9862 12.0929 15.9167 11.1611V8.41667C15.9203 6.95312 15.3895 5.5386 14.4237 4.43887C13.458 3.33915 12.124 2.62992 10.6722 2.44444V1.72778C10.6722 1.53108 10.5941 1.34243 10.455 1.20334C10.3159 1.06425 10.1273 0.986111 9.93056 0.986111C9.73385 0.986111 9.54521 1.06425 9.40612 1.20334C9.26703 1.34243 9.18889 1.53108 9.18889 1.72778V2.45556C7.75014 2.6544 6.43221 3.36792 5.47918 4.46395C4.52616 5.55998 4.00263 6.96425 4.00556 8.41667V11.1611C3.93597 12.0929 3.68099 13.0014 3.25556 13.8333C2.87271 14.4346 2.41122 14.9821 1.88333 15.4611L1.69444 15.6278V17.1944H18.25V15.6278Z" fill="#6A41FF"/>
													<path d="M8.51111 17.7778C8.55983 18.1299 8.73433 18.4526 9.00238 18.6861C9.27044 18.9196 9.61394 19.0483 9.96944 19.0483C10.325 19.0483 10.6685 18.9196 10.9365 18.6861C11.2046 18.4526 11.3791 18.1299 11.4278 17.7778H8.51111Z" fill="#6A41FF"/>
													</g>
													<defs>
													<clipPath id="clip0_1_2">
													<rect width="20" height="20" fill="white"/>
													</clipPath>
													</defs>
												</svg>
					</a>
					<div class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
						<div class="dropdown-header">
							<h4>Notifications</h4>
						</div>
						<ul class="notification_list">
							<?php
							$notificationData = $this->Common->getnotification();
							if (!empty($notificationData)) {
								foreach ($notificationData as $data) {
									if(!empty($data['admin_message'])){
							?>
									<li style="display: none;">
										<div class="notification-box">
											<div class="noti-icon">
												<svg width="17" height="20" viewBox="0 0 17 20" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M9.70258 9.16668H13.0359M9.70258 13.792H13.0359M16.2919 17.3333C16.2919 17.687 16.1514 18.0261 15.9014 18.2762C15.6513 18.5262 15.3122 18.6667 14.9586 18.6667H2.95858C2.60496 18.6667 2.26582 18.5262 2.01577 18.2762C1.76572 18.0261 1.62524 17.687 1.62524 17.3333V2.66668C1.62524 2.31305 1.76572 1.97392 2.01577 1.72387C2.26582 1.47382 2.60496 1.33334 2.95858 1.33334H10.4066C10.7602 1.33342 11.0993 1.47394 11.3492 1.72401L15.9012 6.27601C16.1513 6.526 16.2918 6.86508 16.2919 7.21868V17.3333Z" stroke="url(#paint0_linear_24_1842)" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
													<path d="M4.54663 13.716L5.66396 14.8333L7.52663 12.2267M4.54663 9.00801L5.66396 10.1253L7.52663 7.51868" stroke="url(#paint1_linear_24_1842)" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
													<defs>
														<linearGradient id="paint0_linear_24_1842" x1="1.18183" y1="0.736268" x2="19.0459" y2="4.1213" gradientUnits="userSpaceOnUse">
															<stop stop-color="#6A41FF" />
															<stop offset="1" stop-color="#8465FE" />
														</linearGradient>
														<linearGradient id="paint1_linear_24_1842" x1="4.45654" y1="7.26671" x2="8.18549" y2="7.60692" gradientUnits="userSpaceOnUse">
															<stop stop-color="#6A41FF" />
															<stop offset="1" stop-color="#8465FE" />
														</linearGradient>
													</defs>
												</svg>
											</div>
											<div class="noti-content">
												<a href="/orders/index?orderid=<?= $data['order_id'] ?>"><?php echo $data['admin_message']; ?></a>

											</div>
										</div>
									</li>
								<?php }}
							} else { ?>
								<p> No Record Found !</p>
							<?php } ?>


						</ul>
						<div class="dropdown-footer">
							<input type="button" class="toggle_more" value="See more">
						</div>
					</div>
				</div>
			</li>
			<li>
				<div class="header-profile">
					<a href="/users/profile" class="profile-img">
						<?php
						$userData = $this->Common->getadminprofile();
						?>
						<img src="/uploads/<?= $userData['image'] ?>" alt="img">
					</a>
				</div>
			</li>
		</ul>
	</div>
</div>

<script>
	var csrftoken='<?= $this->request->getAttribute('csrfToken'); ?>';
	</script>