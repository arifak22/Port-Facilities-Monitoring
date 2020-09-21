<!DOCTYPE html>
<html lang="en" >
	<!-- begin::Head -->
	<head>
		<meta charset="utf-8" />
		<title>
			Faspel | Login
		</title>
		<meta name="description" content="Latest updates and statistic charts">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!--begin::Web font -->
		<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
		<script>
          WebFont.load({
            google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
            active: function() {
                sessionStorage.fonts = true;
            }
          });
		</script>
		<!--end::Web font -->
        <!--begin::Base Styles -->
		<link href="{{Pel::templateUrl('vendors/base/vendors.bundle.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{Pel::templateUrl('demo/default/base/style.bundle.css')}}" rel="stylesheet" type="text/css" />
		<!--end::Base Styles -->
		<link rel="shortcut icon" href="{{Pel::customUrl('img/logo.webp')}}" />
	</head>
	<!-- end::Head -->
    <!-- end::Body -->
	<body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default"  >
		<!-- begin:: Page -->
		<div class="m-grid m-grid--hor m-grid--root m-page">
			<div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor m-login m-login--signin m-login--2 m-login-2--skin-1" id="m_login" style="background-image: url({{Pel::customUrl('img/bg.jpg')}});">
				<div class="m-grid__item m-grid__item--fluid	m-login__wrapper">
					<div class="m-login__container">
						<div class="m-login__logo">
							<a href="#">
								<img src="{{Pel::customUrl('img/logo.webp')}}">
							</a>
						</div>
						<div class="m-login__signin">
							<div class="m-login__head">
								<h3 class="m-login__title">
									Sign In To Faspel
								</h3>
							</div>
							<form class="m-login__form m-form" id="form-login" action="">
								<div class="form-group m-form__group">
									<input style="background: #fff;color:#9816f4;" class="form-control m-input"   type="text" placeholder="Username" name="username" autocomplete="off">
								</div>
								<div class="form-group m-form__group">
									<input style="background: #fff;color:#9816f4;" class="form-control m-input m-login__form-input--last" type="password" placeholder="Password" name="password">
								</div>
								{{-- <div class="row m-login__form-sub">
									<div class="col m--align-right m-login__form-right">
										<a href="javascript:;" id="m_login_forget_password" class="m-link">
											Forget Password ?
										</a>
									</div>
								</div> --}}
								<div class="m-login__form-action">
									<button id="m_login_signin_submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air">
										Sign In
									</button>
								</div>
							</form>
						</div>
						<div class="m-login__forget-password">
							<div class="m-login__head">
								<h3 class="m-login__title">
									Forgotten Password ?
								</h3>
								<div class="m-login__desc">
									Enter your email to reset your password:
								</div>
							</div>
							<form class="m-login__form m-form" action="">
								<div class="form-group m-form__group">
									<input class="form-control m-input" type="text" placeholder="Email" name="email" id="m_email" autocomplete="off">
								</div>
								<div class="m-login__form-action">
									<button id="m_login_forget_password_submit" class="btn m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn m-login__btn--primary">
										Request
									</button>
									&nbsp;&nbsp;
									<button id="m_login_forget_password_cancel" class="btn m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn">
										Cancel
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- end:: Page -->
    	<!--begin::Base Scripts -->
		<script src="{{Pel::templateUrl('vendors/base/vendors.bundle.js')}}" type="text/javascript"></script>
		<script src="{{Pel::templateUrl('demo/default/base/scripts.bundle.js')}}" type="text/javascript"></script>
		<!--end::Base Scripts -->   
        <!--begin::Page Snippets -->
        <script>
            //== Class Definition
            var SnippetLogin = function() {

            var login = $('#m_login');

            var showErrorMsg = function(form, type, msg) {
                var alert = $('<div class="m-alert m-alert--outline alert alert-' + type + ' alert-dismissible" role="alert">\
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>\
                    <span></span>\
                </div>');

                form.find('.alert').remove();
                alert.prependTo(form);
                alert.animateClass('fadeIn animated');
                alert.find('span').html(msg);
            }

            //== Private Functions

            var displaySignUpForm = function() {
                login.removeClass('m-login--forget-password');
                login.removeClass('m-login--signin');

                login.addClass('m-login--signup');
                login.find('.m-login__signup').animateClass('flipInX animated');
            }

            var displaySignInForm = function() {
                login.removeClass('m-login--forget-password');
                login.removeClass('m-login--signup');

                login.addClass('m-login--signin');
                login.find('.m-login__signin').animateClass('flipInX animated');
            }

            var displayForgetPasswordForm = function() {
                login.removeClass('m-login--signin');
                login.removeClass('m-login--signup');

                login.addClass('m-login--forget-password');
                login.find('.m-login__forget-password').animateClass('flipInX animated');
            }

            var handleFormSwitch = function() {
                $('#m_login_forget_password').click(function(e) {
                    e.preventDefault();
                    displayForgetPasswordForm();
                });

                $('#m_login_forget_password_cancel').click(function(e) {
                    e.preventDefault();
                    displaySignInForm();
                });

                $('#m_login_signup').click(function(e) {
                    e.preventDefault();
                    displaySignUpForm();
                });

                $('#m_login_signup_cancel').click(function(e) {
                    e.preventDefault();
                    displaySignInForm();
                });
            }

            var handleSignInFormSubmit = function() {
                $('#m_login_signin_submit').click(function(e) {
                    e.preventDefault();
                    var btn = $(this);
                    var form = $(this).closest('form');

                    form.validate({
                        rules: {
                            username: {
                                required: true
                            },
                            password: {
                                required: true
                            }
                        }
                    });
                    if (!form.valid()) {
                        return;
                    }

                    btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
                    form.ajaxSubmit({
                        url : "{{Pel::baseUrl('login/auth')}}",
                        data: { _token: "{{ csrf_token() }}" },
                        type: 'POST',
                        success: function(response, status, xhr, $form) {
                            if(response.api_status == 1){
                                localStorage.setItem("jwt_token", response.jwt_token);
                                setTimeout(function() {
                                    btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
                                    showErrorMsg(form, 'success', response.api_message);
                                    setTimeout(function() {
                                        window.location = "{{Pel::baseUrl('home')}}";
                                    }, 1000);
                                }, 2000);
                            }else{
                                setTimeout(function() {
                                    btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
                                    showErrorMsg(form, 'danger', response.api_message);
                                }, 2000);
                            }
                            
                        }
                    });
                });
            }

            var handleSignUpFormSubmit = function() {
                $('#m_login_signup_submit').click(function(e) {
                    e.preventDefault();

                    var btn = $(this);
                    var form = $(this).closest('form');

                    form.validate({
                        rules: {
                            fullname: {
                                required: true
                            },
                            email: {
                                required: true,
                                email: true
                            },
                            password: {
                                required: true
                            },
                            rpassword: {
                                required: true
                            },
                            agree: {
                                required: true
                            }
                        }
                    });

                    if (!form.valid()) {
                        return;
                    }

                    btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);

                    form.ajaxSubmit({
                        url: '',
                        success: function(response, status, xhr, $form) {
                            // similate 2s delay
                            setTimeout(function() {
                                btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
                                form.clearForm();
                                form.validate().resetForm();

                                // display signup form
                                displaySignInForm();
                                var signInForm = login.find('.m-login__signin form');
                                signInForm.clearForm();
                                signInForm.validate().resetForm();

                                showErrorMsg(signInForm, 'success', 'Thank you. To complete your registration please check your email.');
                            }, 2000);
                        }
                    });
                });
            }

            var handleForgetPasswordFormSubmit = function() {
                $('#m_login_forget_password_submit').click(function(e) {
                    e.preventDefault();

                    var btn = $(this);
                    var form = $(this).closest('form');

                    form.validate({
                        rules: {
                            email: {
                                required: true,
                                email: true
                            }
                        }
                    });

                    if (!form.valid()) {
                        return;
                    }

                    btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);

                    form.ajaxSubmit({
                        url: '',
                        success: function(response, status, xhr, $form) { 
                            // similate 2s delay
                            setTimeout(function() {
                                btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false); // remove 
                                form.clearForm(); // clear form
                                form.validate().resetForm(); // reset validation states

                                // display signup form
                                displaySignInForm();
                                var signInForm = login.find('.m-login__signin form');
                                signInForm.clearForm();
                                signInForm.validate().resetForm();

                                showErrorMsg(signInForm, 'success', 'Cool! Password recovery instruction has been sent to your email.');
                            }, 2000);
                        }
                    });
                });
            }

            //== Public Functions
            return {
                // public functions
                init: function() {
                    handleFormSwitch();
                    handleSignInFormSubmit();
                    handleSignUpFormSubmit();
                    handleForgetPasswordFormSubmit();
                }
            };
            }();

            //== Class Initialization
            jQuery(document).ready(function() {
            SnippetLogin.init();
            });
        </script>
		<!--end::Page Snippets -->
	</body>
	<!-- end::Body -->
</html>
