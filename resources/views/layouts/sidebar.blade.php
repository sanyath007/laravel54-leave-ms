		<!-- Left side column. contains the logo and sidebar -->
		<aside class="main-sidebar">
			<section class="sidebar">
				<!-- Sidebar user panel -->
				<div class="user-panel">
					<div class="pull-left image">
						<?php $userAvatarUrl = (Auth::user()->person_photo != '') ? "http://192.168.20.4:3839/ps/PhotoPersonal/" .Auth::user()->person_photo : asset('img/user2-160x160.jpg'); ?>
						<img
							src="{{ $userAvatarUrl }}"
							class="img-circle"
							alt="User Image"
						/>
					</div>
					<div class="pull-left info">
						<p>

							@if (!Auth::guest())
								{{ Auth::user()->person_firstname }} {{ Auth::user()->person_lastname }}
							@endif

						</p>
						<a href="#"><i class="fa fa-circle text-success"></i> Online</a>
					</div>
				</div>
				<!-- search form -->
				<form action="#" method="get" class="sidebar-form">
					<div class="input-group">
						<input type="text" name="q" class="form-control" placeholder="Search...">
						<span class="input-group-btn">
							<button type="submit" name="search" id="search-btn" class="btn btn-flat">
								<i class="fa fa-search"></i>
							</button>
						</span>
					</div>
				</form>
				<!-- /.search form -->
				<!-- sidebar menu: style can be found in sidebar.less -->
				<ul class="sidebar-menu" data-widget="tree">
					<li class="header">MAIN NAVIGATION</li>

					<li ng-class="{ 'active': menu == 'home' }">
						<a href="{{ url('/home') }}">
							<i class="fa fa-dashboard"></i> <span>Dashboard</span>
						</a>
					</li>
					<li class="treeview" ng-class="{ 'menu-open active': menu == 'histories' }">
						<a href="#">
							<i class="fa fa-laptop"></i>
							<span>ประวัติ</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li ng-class="{ 'active': submenu == 'profile' }">
								<a href="#" ng-click="redirectTo($event, 'histories/profile/' + {{ Auth::user()->person_id }})">
									<i class="fa fa-circle-o"></i> ข้อมูลส่วนตัว
								</a>
							</li>
							<li ng-class="{ 'active': submenu == 'summary' }">
								<a href="#" ng-click="redirectTo($event, 'histories/summary/' + {{ Auth::user()->person_id }})">
									<i class="fa fa-circle-o"></i> ข้อมูลประวัติการลา
								</a>
							</li>
						</ul>
					</li>
					<li class="treeview" ng-class="{ 'menu-open active': menu == 'leaves' }">
						<a href="#">
							<i class="fa fa-calendar"></i>
							<span>การลา</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu" ng-style="{ 'display': (menu == 'leaves') ? 'block' : 'none' }">
							<li ng-class="{ 'active': ['list','add','edit','detail'].includes(submenu)}">
								<a href="{{ url('/leaves/list') }}">
									<i class="fa fa-circle-o"></i> รายการลา
								</a>
							</li>
							<li ng-class="{ 'active': submenu == 'cancel' }">
								<a href="{{ url('/cancellations/cancel') }}">
									<i class="fa fa-circle-o"></i> ยกเลิกใบลา
								</a>
							</li>
						</ul>
					</li>

					<!-- // Authorize เฉพาะหัวหน้ากลุ่มภารกิจ/ธุรการหรือเลขาฯกลุ่มภารกิจ/หัวหน้ากลุ่มงาน -->
					@if (
						Auth::user()->person_id == '1300200009261' ||
						Auth::user()->person_id == '1309900322504' ||
						Auth::user()->memberOf->duty_id == 1 ||
						Auth::user()->memberOf->duty_id == 2
					)
						<li class="treeview" ng-class="{ 'menu-open active': menu == 'approvals' }">
							<a href="#">
								<i class="fa fa-check-square-o"></i>
								<span>การอนุมัติ</span>
								<span class="pull-right-container">
									<i class="fa fa-angle-left pull-right"></i>
								</span>
							</a>
							<ul class="treeview-menu" ng-style="{ 'display': (menu == 'approvals') ? 'block' : 'none' }">
								<!-- // Authorize เฉพาะหัวหน้ากลุ่มงาน -->
								@if (
									Auth::user()->person_id == '1300200009261' ||
									Auth::user()->memberOf->duty_id == 2
								)
									<li ng-class="{ 'active': submenu == 'comment' }">
										<a href="{{ url('approvals/comment') }}">
											<i class="fa fa-circle-o"></i> หัวหน้ากลุ่มงาน
										</a>
									</li>
								@endif

								<!-- // Authorize เฉพาะธุรการหรือเลขาฯกลุ่มภารกิจ -->
								@if (
									Auth::user()->person_id == '1300200009261' ||
									Auth::user()->person_id == '1309900322504'
								)
									<li ng-class="{ 'active': submenu == 'receive' }">
										<a href="{{ url('approvals/receive') }}">
											<i class="fa fa-circle-o"></i> รับเอกสาร
										</a>
									</li>
								@endif

								<!-- // Authorize เฉพาะหัวหน้ากลุ่มภารกิจ/ธุรการหรือเลขาฯกลุ่มภารกิจ -->
								@if (
									Auth::user()->person_id == '1300200009261' ||
									Auth::user()->person_id == '1309900322504' ||
									Auth::user()->memberOf->duty_id == 1
								)
									<li ng-class="{ 'active': submenu == 'approve' }">
										<a href="{{ url('approvals/approve') }}">
											<i class="fa fa-circle-o"></i> อนุมัติใบลา
										</a>
									</li>
								@endif

							</ul>
						</li>
					@endif
					
					<!-- // Authorize เฉพาะกลุ่มงาน HR -->
					@if (Auth::user()->person_id == '1300200009261')
						<li class="treeview" ng-class="{ 'menu-open active': menu == 'vacations' }">
							<a href="#">
								<i class="fa fa-bar-chart"></i>
								<span>วันสะสม</span>
								<span class="pull-right-container">
									<i class="fa fa-angle-left pull-right"></i>
								</span>
							</a>
							<ul class="treeview-menu">
								<li>
									<a href="{{ url('vacations/summary') }}">
										<i class="fa fa-circle-o"></i> สรุปวันสะสม
									</a>
								</li>
							</ul>
						</li>
					@endif

					<!-- // Authorize เฉพาะหัวหน้ากลุ่มภารกิจ/ธุรการหรือเลขาฯกลุ่มภารกิจ/หัวหน้ากลุ่มงาน -->
					@if (
						Auth::user()->person_id == '1300200009261' ||
						Auth::user()->person_id == '1309900322504' ||
						Auth::user()->memberOf->duty_id == 1 ||
						Auth::user()->memberOf->duty_id == 2
					)
						<li class="treeview" ng-class="{ 'menu-open active': menu == 'reports' }">
							<a href="#">
								<i class="fa fa-pie-chart"></i>
								<span>รายงาน</span>
								<span class="pull-right-container">
									<i class="fa fa-angle-left pull-right"></i>
								</span>
							</a>
							<ul class="treeview-menu">
								<li>
									<a href="{{ url('reports/summary') }}">
										<i class="fa fa-circle-o"></i> สรุปการลา
									</a>
								</li>
								<li>
									<a href="{{ url('reports/remain') }}">
										<i class="fa fa-circle-o"></i> สรุปวันลาคงเหลือ
									</a>
								</li>
							</ul>
						</li>
					@endif

					<!-- <li class="treeview">
						<a href="#">
							<i class="fa fa-gear"></i> <span>ข้อมูลพื้นฐาน</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li>
								<a href="{{ url('/parcel/list') }}">
									<i class="fa fa-circle-o"></i> พัสดุหลัก
								</a>
							</li>
							<li>
								<a href="{{ url('/asset-group/list') }}">
									<i class="fa fa-circle-o"></i> กลุ่มครุภัณฑ์
								</a>
							</li>
							<li>
								<a href="{{ url('/asset-cate/list') }}">
									<i class="fa fa-circle-o"></i> หมวดครุภัณฑ์
								</a>
							</li>
							<li>
								<a href="{{ url('/asset-type/list') }}">
									<i class="fa fa-circle-o"></i> ชนิดครุภัณฑ์
								</a>
							</li>
							<li>
								<a href="{{ url('/asset-unit/list') }}">
									<i class="fa fa-circle-o"></i> หน่วยครุภัณฑ์
								</a>
							</li>
							<li>
								<a href="{{ url('/deprec-type/list') }}">
									<i class="fa fa-circle-o"></i> เกณฑ์การคิดค่าเสื่อม
								</a>
							</li>
							<li>
								<a href="{{ url('/supplier/list') }}">
									<i class="fa fa-circle-o"></i> ผู้จัดจำหน่าย
								</a>
							</li>
						</ul>
					</li> -->
				</ul>
			</section><!-- /.sidebar -->

		</aside>
