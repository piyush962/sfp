<div class="right-content-wrapper">
                <div class="profile-wrapper custom-form-design">
                    <?= $this->Form->create($userData,['type' => 'file']) ?>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="profile-label">
                                    <h4>Avatar</h4>
                                    <p>Edit your profile picture</p>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="profile-ipnuts">
                                    <div class="profile-upload-img">
                                       
                                        <img src="/uploads/<?=$userData['image']?>" alt="" id="profile-pic" name="image">
                                        <a href="javascript:void(0)" class="edit-profile-pic">
                                        <?= $this->Form->control('img_product', [
                                            'class' => 'form-control form-control-user',
                                            'type' => 'file',
                                            'allowempty' => true,
                                            'label' => false,
                                            'onchange'=>'readURL(this)'
                                            ]);?>
                                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M10.8749 6.00001C10.7313 6.00001 10.5935 6.05708 10.4919 6.15866C10.3903 6.26024 10.3333 6.39802 10.3333 6.54167V9.79167C10.3333 9.93533 10.2762 10.0731 10.1746 10.1747C10.073 10.2763 9.93524 10.3333 9.79159 10.3333H2.20825C2.06459 10.3333 1.92682 10.2763 1.82524 10.1747C1.72365 10.0731 1.66659 9.93533 1.66659 9.79167V2.20834C1.66659 2.06468 1.72365 1.92691 1.82524 1.82532C1.92682 1.72374 2.06459 1.66667 2.20825 1.66667H5.45825C5.60191 1.66667 5.73969 1.60961 5.84127 1.50802C5.94285 1.40644 5.99992 1.26867 5.99992 1.12501C5.99992 0.981349 5.94285 0.843574 5.84127 0.741991C5.73969 0.640409 5.60191 0.583341 5.45825 0.583341H2.20825C1.77728 0.583341 1.36395 0.754546 1.0592 1.05929C0.754457 1.36404 0.583252 1.77736 0.583252 2.20834V9.79167C0.583252 10.2227 0.754457 10.636 1.0592 10.9407C1.36395 11.2455 1.77728 11.4167 2.20825 11.4167H9.79159C10.2226 11.4167 10.6359 11.2455 10.9406 10.9407C11.2454 10.636 11.4166 10.2227 11.4166 9.79167V6.54167C11.4166 6.39802 11.3595 6.26024 11.2579 6.15866C11.1564 6.05708 11.0186 6.00001 10.8749 6.00001ZM2.74992 6.41167V8.70834C2.74992 8.852 2.80699 8.98978 2.90857 9.09136C3.01015 9.19294 3.14793 9.25001 3.29159 9.25001H5.58825C5.65954 9.25042 5.73021 9.23676 5.7962 9.2098C5.8622 9.18285 5.92222 9.14313 5.97284 9.09292L9.72117 5.33917L11.2595 3.83334C11.3103 3.78299 11.3506 3.72308 11.3781 3.65707C11.4056 3.59106 11.4197 3.52026 11.4197 3.44876C11.4197 3.37725 11.4056 3.30645 11.3781 3.24045C11.3506 3.17444 11.3103 3.11453 11.2595 3.06417L8.96284 0.740424C8.91248 0.689655 8.85257 0.649358 8.78656 0.621858C8.72056 0.594358 8.64976 0.5802 8.57825 0.5802C8.50675 0.5802 8.43595 0.594358 8.36994 0.621858C8.30393 0.649358 8.24402 0.689655 8.19367 0.740424L6.66617 2.27334L2.907 6.02709C2.8568 6.0777 2.81708 6.13773 2.79013 6.20373C2.76317 6.26972 2.74951 6.34039 2.74992 6.41167ZM8.57825 1.88876L10.1112 3.42167L9.342 4.19084L7.80909 2.65792L8.57825 1.88876ZM3.83325 6.63376L7.04534 3.42167L8.57825 4.95459L5.36617 8.16667H3.83325V6.63376Z"
                                                    fill="black" />
                                            </svg>
                                        </a>
                                        <!-- <a href="javascript:void(0)" class="delete-profile-pic">
                                            <svg width="9" height="11" viewBox="0 0 9 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M1.79175 10.375C1.49383 10.375 1.23889 10.269 1.02691 10.057C0.814942 9.84507 0.708776 9.58994 0.708415 9.29167V2.25H0.166748V1.16667H2.87508V0.625H6.12508V1.16667H8.83342V2.25H8.29175V9.29167C8.29175 9.58958 8.18576 9.84471 7.97379 10.057C7.76182 10.2694 7.50669 10.3754 7.20842 10.375H1.79175ZM7.20842 2.25H1.79175V9.29167H7.20842V2.25ZM2.87508 8.20833H3.95841V3.33333H2.87508V8.20833ZM5.04175 8.20833H6.12508V3.33333H5.04175V8.20833Z"
                                                    fill="white" />
                                            </svg>
                                        </a> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="profile-label">
                                    <h4>Personal Information</h4>
                                    <p>Change your personal information here</p>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="profile-ipnuts">
                                    <div class="input_group">
                                        <?php echo $this->Form->control('name',['class'=>'form-control']) ?>
                                    </div>
                                    <div class="input_group">
                                    <?php echo $this->Form->control('email',['class'=>'form-control','type'=>'email']) ?>
                                    </div>
                                    <div class="input_group">
                                    <?php echo $this->Form->control('phone_number',['class'=>'form-control']) ?>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="profile-label">
                                    <h4>Security</h4>
                                    <p><input type="checkbox" class="showPassword"> Want to change your password? </p>
                                </div>
                            </div>
                            <div class="col-md-8 passwordField " style="display:none">
                                <div class="profile-ipnuts">
                                    <div class="input_group">
                                    <?php echo $this->Form->control('new_password',['class'=>'form-control newpassword','type'=>'text','value'=>'']) ?>
                                    </div>
                                    <div class="input_group">   
                                    <?php echo $this->Form->control('confirm password',['class'=>'form-control confirmpassword','type'=>'text','value'=>'']) ?>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"></div>
                            <div class="col-md-8">
                                <div class="update-box">
                                    <button type="submit" class="admin-theme-btn">
                                        Update Changes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?= $this->Form->end() ?>
                </div>
            </div>
            <script>
            $(".showPassword").on('click',function(){
                if($(this).is(":checked")){
                  $('.passwordField').show();
                  $('.newpassword, .confirmpassword').val("");
                  $('.newpassword, .confirmpassword').attr("type",'password');
                  $(".newpassword, .confirmpassword").attr("required", "true");
                }else{
                    $('.passwordField').hide();
                    $('.newpassword, .confirmpassword').attr("type",'text');
                    $(".newpassword, .confirmpassword").attr("required", "false");
                }
            });
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#profile-pic').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            </script>