<!DOCTYPE html>
<html lang="en" >
	<!-- begin::Head -->
	<head>
		<meta charset="utf-8" />
		<title>
			Fasilitas {{ @$title ? '| ' . $title : '' }}
		</title>
		<meta name="description" content="Flaticon icons">
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
		<link href="{{Pel::customUrl('datatable/style.min.css')}}" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" type="text/css" href="{{Pel::customUrl('datatable/datatables.min.css')}}"/>
		<link rel="stylesheet" type="text/css" href="{{Pel::customUrl('datatable/DataTables/css/dataTables.bootstrap.min.css')}}"/>
		<link rel="stylesheet" type="text/css" href="{{Pel::customUrl('datatable/DataTables/css/dataTables.bootstrap4.min.css')}}"/>
		<!--end::Base Styles -->
		<link rel="shortcut icon" href="{{Pel::customUrl('img/logo.webp')}}" />
		
		<!--begin::Base Scripts -->
		<script src="{{Pel::templateUrl('vendors/base/vendors.bundle.js')}}" type="text/javascript"></script>
		<script src="{{Pel::templateUrl('demo/default/base/scripts.bundle.js')}}" type="text/javascript"></script>
		<script src="{{Pel::customUrl('image-compressor.js')}}" type="text/javascript"></script>
		<script src="{{Pel::customUrl('script.js')}}" type="text/javascript"></script>
		<script src="{{Pel::customUrl('datatable/datatables.min.js')}}" type="text/javascript"></script>
		<!--end::Base Scripts -->
	</head>
	  
	
	<!-- end::Head -->
    <!-- end::Body -->
	<body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default"  >
		<!-- begin:: Page -->
		<div class="m-grid m-grid--hor m-grid--root m-page">
			<!-- BEGIN: Header -->
			<header class="m-grid__item    m-header "  data-minimize-offset="200" data-minimize-mobile-offset="200" >
				<div class="m-container m-container--fluid m-container--full-height">
					<div class="m-stack m-stack--ver m-stack--desktop">
						<!-- BEGIN: Brand -->
						<div class="m-stack__item m-brand  m-brand--skin-dark ">
							<div class="m-stack m-stack--ver m-stack--general">
								<div class="m-stack__item m-stack__item--middle m-brand__logo">
									<a href="{{url('/')}}" class="m-brand__logo-wrapper">
										<img alt="" height="45px" src="{{Pel::customUrl('img/logo.webp')}}"/>
									</a>
								</div>
								<div class="m-stack__item m-stack__item--middle m-brand__tools">
									<!-- BEGIN: Left Aside Minimize Toggle -->
									<a href="javascript:;" id="m_aside_left_minimize_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-desktop-inline-block 
					 ">
										<span></span>
									</a>
									<!-- END -->
							<!-- BEGIN: Responsive Aside Left Menu Toggler -->
									<a href="javascript:;" id="m_aside_left_offcanvas_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-tablet-and-mobile-inline-block">
										<span></span>
									</a>
									<!-- END -->
									<!-- END -->
			<!-- BEGIN: Topbar Toggler -->
									<a id="m_aside_header_topbar_mobile_toggle" href="javascript:;" class="m-brand__icon m--visible-tablet-and-mobile-inline-block">
										<i class="flaticon-more"></i>
									</a>
									<!-- BEGIN: Topbar Toggler -->
								</div>
							</div>
						</div>
						<!-- END: Brand -->
						<div class="m-stack__item m-stack__item--fluid m-header-head" id="m_header_nav">
							<!-- BEGIN: Horizontal Menu -->
							<button class="m-aside-header-menu-mobile-close  m-aside-header-menu-mobile-close--skin-dark " id="m_aside_header_menu_mobile_close_btn">
								<i class="la la-close"></i>
							</button>
							<div id="m_header_menu" class="m-header-menu m-aside-header-menu-mobile m-aside-header-menu-mobile--offcanvas  m-header-menu--skin-light m-header-menu--submenu-skin-light m-aside-header-menu-mobile--skin-dark m-aside-header-menu-mobile--submenu-skin-dark "  >
								
							</div>
							<!-- END: Horizontal Menu -->								<!-- BEGIN: Topbar -->
							<div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general">
								<div class="m-stack__item m-topbar__nav-wrapper">
									<ul class="m-topbar__nav m-nav m-nav--inline">
										<li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img  m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light" data-dropdown-toggle="click">
											<a href="#" class="m-nav__link m-dropdown__toggle">
												<span class="m-topbar__userpic">
													<img src="{{Pel::customUrl('img/man.png')}}" class="m--img-rounded m--marginless m--img-centered" alt=""/>
												</span>
												<span class="m-topbar__username m--hide">
													{{Auth::user()->username}}
												</span>
											</a>
											<div class="m-dropdown__wrapper">
												<span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
												<div class="m-dropdown__inner">
													<div class="m-dropdown__header m--align-center" style="background: url({{Pel::templateUrl('app/media/img/misc/user_profile_bg.jpg')}}); background-size: cover;">
														<div class="m-card-user m-card-user--skin-dark">
															<div class="m-card-user__pic">
																<img src="{{Pel::customUrl('img/man.png')}}" class="m--img-rounded m--marginless" alt=""/>
															</div>
															<div class="m-card-user__details">
																<span class="m-card-user__name m--font-weight-500">
																	{{Auth::user()->nama}}
																</span>
																<a class="m-card-user__email m--font-weight-300 m-link">
																	{{Auth::user()->username}}
																</a>
															</div>
														</div>
													</div>
													<div class="m-dropdown__body">
														<div class="m-dropdown__content">
															<ul class="m-nav m-nav--skin-light">
																<li class="m-nav__section m--hide">
																	<span class="m-nav__section-text">
																		Section
																	</span>
																</li>
																<li class="m-nav__item">
																	<a style="cursor:pointer"  data-toggle="modal" data-target="#changepassword" class="m-nav__link">
																		<i class="m-nav__link-icon flaticon-settings-1"></i>
																		<span class="m-nav__link-title">
																			<span class="m-nav__link-wrap">
																				<span class="m-nav__link-text">
																					Ganti Password
																				</span>
																			</span>
																		</span>
																	</a>
																</li>
																<li class="m-nav__separator m-nav__separator--fit"></li>
																<li class="m-nav__item">
																	<a href="{{Pel::baseUrl('home/logout')}}" class="btn m-btn--pill    btn-secondary m-btn m-btn--custom m-btn--label-brand m-btn--bolder">
																		Logout
																	</a>
																</li>
															</ul>
														</div>
													</div>
												</div>
											</div>
										</li>
									</ul>
								</div>
							</div>
							<!-- END: Topbar -->
						</div>
					</div>
				</div>
			</header>
			<!-- END: Header -->		
		<!-- begin::Body -->
			<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
				<!-- BEGIN: Left Aside -->
				<button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn">
					<i class="la la-close"></i>
				</button>
				<div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-dark ">
					<!-- BEGIN: Aside Menu -->
	<div 
		id="m_ver_menu" 
		class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark " 
		data-menu-vertical="true"
		 data-menu-scrollable="false" data-menu-dropdown-timeout="500"  
		>
						<ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">
							@foreach ($menus as $key => $mn)
								@if (count($mn->sub_menu) > 0)
								<li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true"  m-menu-submenu-toggle="hover">
									<a  href="#" class="m-menu__link m-menu__toggle">
										<i class="m-menu__link-icon {{$mn->ikon}}"></i>
										<span class="m-menu__link-text">
											{{$mn->nama}}
										</span>
										<i class="m-menu__ver-arrow la la-angle-right"></i>
									</a>
									<div class="m-menu__submenu ">
										<span class="m-menu__arrow"></span>
										<ul class="m-menu__subnav">
											@foreach ($mn->sub_menu as $sm)
											<li class="m-menu__item  m-menu__item--parent" aria-haspopup="true" >
												<span class="m-menu__link">
													<span class="m-menu__link-text">
														{{$mn->nama}}
													</span>
												</span>
											</li>
											<li class="m-menu__item " aria-haspopup="true" >
												<a  href="{{Pel::baseUrl($sm->link_sub)}}" class="m-menu__link ">
													<i class="m-menu__link-bullet m-menu__link-bullet--dot">
														<span></span>
													</i>
													<span class="m-menu__link-text">
														{{$sm->nama_sub}}
													</span>
												</a>
											</li>
											@endforeach
												
										</ul>
									</div>
								</li>
								@else
								<li class="m-menu__item " aria-haspopup="true" >
									<a  href="{{Pel::baseUrl($mn->link)}}" class="m-menu__link ">
										<i class="m-menu__link-icon {{$mn->ikon}}"></i>
										<span class="m-menu__link-title">
											<span class="m-menu__link-wrap">
												<span class="m-menu__link-text">
													{{$mn->nama}}
												</span>
											</span>
										</span>
									</a>
								</li>
								@endif
							@endforeach
						</ul>
					</div>
					<!-- END: Aside Menu -->
				</div>
				<!-- END: Left Aside -->
				<div class="m-grid__item m-grid__item--fluid m-wrapper">

					{!!$contents!!}
				</div>
			</div>
			<!-- end:: Body -->
<!-- begin::Footer -->
			<footer class="m-grid__item		m-footer ">
				<div class="m-container m-container--fluid m-container--full-height m-page__container">
					<div class="m-stack m-stack--flex-tablet-and-mobile m-stack--ver m-stack--desktop">
						<div class="m-stack__item m-stack__item--left m-stack__item--middle m-stack__item--last">
							<span class="m-footer__copyright">
								2020 &copy; Fasilitas Pelabuhan -
								<a href="http://pel.co.id" class="m-link">
									PT Pelindo Energi Logistik
								</a>
							</span>
						</div>
						{{-- <div class="m-stack__item m-stack__item--right m-stack__item--middle m-stack__item--first">
							<ul class="m-footer__nav m-nav m-nav--inline m--pull-right">
								<li class="m-nav__item m-nav__item">
									<a href="#" class="m-nav__link" data-toggle="m-tooltip" title="Support Center" data-placement="left">
										<i class="m-nav__link-icon flaticon-info m--icon-font-size-lg3"></i>
									</a>
								</li>
							</ul>
						</div> --}}
					</div>
				</div>
			</footer>
			<!-- end::Footer -->
		</div>
		<!-- end:: Page -->	    
	    <!-- begin::Scroll Top -->
		<div class="m-scroll-top m-scroll-top--skin-top" data-toggle="m-scroll-top" data-scroll-offset="500" data-scroll-speed="300">
			<i class="la la-arrow-up"></i>
		</div>
		<!-- end::Scroll Top -->	
		<div class="modal fade" id="changepassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Ganti Password</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="form-password">
					{!!Pel::formInput('Password Lama','password','old_password')!!}
					{!!Pel::formInput('Password Baru','password','new_password')!!}
					{!!Pel::formInput('Ulangi Password Baru','password','new_password2')!!}
					<br/>
					{!!Pel::formSubmit('Ubah','ubah-password','la la-pencil')!!}
					</form>
				</div>
				</div>
			</div>
		</div>
		<script>
			$("#ubah-password").click(function(e){
				e.preventDefault();
				var btn = $(this);
				var form = $(this).closest('form');

				form.validate({
					rules: {
						old_password: {
							required: true,
						},
						new_password: {
							required: true,
						},
						new_password2: {
							required: true,
							equalTo: "#new_password"
						}
					}
				});
				if (!form.valid()) {
					return;
				}

				btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
				form.ajaxSubmit({
					url : "{{Pel::baseUrl('home/ganti-password')}}",
					data: { _token: "{{ csrf_token() }}" },
					type: 'POST',
					success: function(response, status, xhr, $form) {
						if(response.api_status == 1){
							showErrorMsg(form, 'success', response.api_message);
							// setTimeout(function() {
							// 	location.reload();
							// }, 1000);
							location.reload();
						}else{
							btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
							showErrorMsg(form, 'danger', response.api_message);
						}
						
					},
					error: function(err){
						btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
						showErrorMsg(form, 'danger', 'Error: ' + err.statusText);
					},
				});
			})
		</script>
	</body>
	<!-- end::Body -->
</html>
