		<!-- Left side column. contains the logo and sidebar -->
		<aside class="main-sidebar">
			<section class="sidebar">
				<!-- Sidebar user panel -->
				<div class="user-panel">
					<div class="pull-left image">
						<img src="{{ asset('/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
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

					<li class="active">
						<a href="{{ url('/home') }}">
							<i class="fa fa-dashboard"></i> <span>Dashboard</span>
						</a>
					</li>
					<li class="treeview">
						<a href="#">
							<i class="fa fa-university"></i>
							<span>ครุภัณฑ์</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li>
								<a href="{{ url('/asset/list') }}">
									<i class="fa fa-circle-o"></i> รายการ
								</a>
							</li>
							<li>
								<a href="{{ url('/asset/add') }}">
									<i class="fa fa-circle-o"></i> ลงทะเบียน
								</a>
							</li>
							<li>
								<a href="{{ url('asset/discharge') }}">
									<i class="fa fa-circle-o"></i> จำหน่าย
								</a>
							</li>
							<li>
								<a href="{{ url('reparation/list') }}">
									<i class="fa fa-circle-o"></i> ประวัติการซ่อม
								</a>
							</li>
						</ul>
					</li>					
					<li class="treeview">
						<a href="#">
							<i class="fa fa-laptop"></i>
							<span>ทะเบียนทรัพย์สิน</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li>
								<a href="{{ url('/deprec/calc') }}">
									<i class="fa fa-circle-o"></i> คำนวณค่าเสื่อมครุภัณฑ์
								</a>
							</li>
							<li>
								<a href="{{ url('/deprec/list') }}">
									<i class="fa fa-circle-o"></i> ทะเบียนคุมทรัพย์สิน
								</a>
							</li>
						</ul>
					</li>
					<li class="treeview">
						<a href="#">
							<i class="fa fa-pie-chart"></i>
							<span>รายงาน</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li>
								<a href="pages/charts/flot.html">
									<i class="fa fa-circle-o"></i> Flot
								</a>
							</li>
							<li>
								<a href="pages/charts/inline.html">
									<i class="fa fa-circle-o"></i> Inline charts
								</a>
							</li>
						</ul>
					</li>
					<li class="treeview">
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
					</li>													
				</ul>
			</section><!-- /.sidebar -->

		</aside>
