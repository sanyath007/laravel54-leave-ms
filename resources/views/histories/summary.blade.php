@extends('layouts.main')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            ประวัติการลา
            <!-- <small>preview of simple tables</small> -->
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">ประวัติการลา</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content" ng-controller="historyCtrl" ng-init="getData()">

        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">ค้นหาข้อมูล</h3>
                    </div>

                    <form id="frmSearch" name="frmSearch" role="form">
                        <div class="box-body">
                            <div class="col-md-6">

                                <!-- <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(asset, 'year')}">
                                    <label>ปีงบประมาณ</label>
                                    <input  type="text" 
                                            id="year" 
                                            name="year" 
                                            ng-model="asset.year"
                                            class="form-control"
                                            pattern="[0-9]{4}"
                                            tabindex="16" required>
                                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(asset, 'year')"></span>
                                    <span class="help-block" ng-show="checkValidate(asset, 'year')">กรุณาระบุปีงบประมาณ</span>
                                </div>

                                <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(asset, 'cal_date')}">
                                    <label>ณ วันที่ :</label>

                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                        <input  type="text" 
                                                id="cal_date" 
                                                name="cal_date" 
                                                ng-model="asset.cal_date" 
                                                class="form-control pull-right"
                                                tabindex="1" required>
                                    </div>
                                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(asset, 'cal_date')"></span>
                                    <span class="help-block" ng-show="checkValidate(asset, 'cal_date')">กรุณาเลือกวันที่รับเข้าระบบ</span>
                                </div> -->

                            </div><!-- /.col-md-6 -->                 
                        </div><!-- /.box-body -->

                        <!-- <div class="box-footer">
                            <a ng-click="deprecCalulate()" class="btn btn-primary">
                                คำนวณค่าเสื่อม
                            </a>
                            
                            <a ng-click="store()" class="btn btn-primary">
                                บันทึกค่าเสื่อม
                            </a>
                        </div> -->
                    </form>
                </div><!-- /.box -->

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">ประวัติการลา</h3>
                    </div><!-- /.box-header -->

                    <div class="box-body" style="background-color: #F5F7FA;">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="box box-success">
                                    <div class="box-header">
                                        <h3 class="box-title">สถิติการลา ปีงบประมาณ</h3>
                                    </div>
                                    <div class="box-body">
                                        <div style="border: 1px solid grey; margin-bottom: 1rem; padding: 0.5em;">
                                            <p>ลาป่วย</p>
                                            <p>จำนวนวันลาสะสม - วัน</p>
                                            <p>จำนวนวันที่ลา - วัน</p>
                                        </div>
                                        <div style="border: 1px solid grey; margin-bottom: 1rem; padding: 0.5em;">
                                            <p>ลากิจส่วนตัว</p>
                                            <p>จำนวนวันลาสะสม - วัน</p>
                                            <p>จำนวนวันที่ลา - วัน</p>
                                        </div>
                                        <div style="border: 1px solid grey; margin-bottom: 1rem; padding: 0.5em;">
                                            <p>ลาพักผ่อน</p>
                                            <p>จำนวนวันลาสะสม - วัน</p>
                                            <p>จำนวนวันที่ลา - วัน</p>
                                        </div>
                                        <div style="border: 1px solid grey; margin-bottom: 1rem; padding: 0.5em;">
                                            <p>ลาคลอด</p>
                                            <p>จำนวนวันลาสะสม - วัน</p>
                                            <p>จำนวนวันที่ลา - วัน</p>
                                        </div>
                                        <div style="border: 1px solid grey; margin-bottom: 1rem; padding: 0.5em;">
                                            <p>ลาอุปสมบท</p>
                                            <p>จำนวนวันลาสะสม - วัน</p>
                                            <p>จำนวนวันที่ลา - วัน</p>
                                        </div>
                                        <div style="border: 1px solid grey; padding: 0.5em;">
                                            <p>ลาไปต่างประเทศ</p>
                                            <p>จำนวนวันลาสะสม - วัน</p>
                                            <p>จำนวนวันที่ลา - วัน</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-8">
                                <div class="box box-danger">
                                    <div class="box-header">
                                        <h3 class="box-title">รายละเอียดข้อมูลการลา</h3>
                                    </div>
                                    <div class="box-body">
                                        <table class="table table-bordered table-striped" style="font-size: 12px; margin-bottom: 1rem;">
                                            <thead>
                                                <tr>
                                                    <th style="width: 5%; text-align: center;">#</th>
                                                    <th style="width: 50%; text-align: center;">วันที่ลา</th>
                                                    <th style="width: 20%; text-align: center;">จน.วัน</th>
                                                    <th style="text-align: center;">สถานะ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr ng-repeat="(index, leave) in leaves">
                                                    <td style="text-align: center;">@{{ index+pager.from }}</td>
                                                    <td style="text-align: center;">
                                                        <span>@{{ leave.start_date | thdate }} - </span>
                                                        <span>@{{ leave.end_date | thdate }}</span>
                                                    </td>
                                                    <td style="text-align: right;">@{{ leave.leave_days | currency:"":0 }}</td>
                                                    <td style="text-align: left;">
                                                        <span class="label label-info" ng-show="paid.asset_status!=0">
                                                            @{{ (leave.status==0) ? 'อยู่ระหว่างการสร้างเอกสาร' :
                                                                (leave.status==1) ? 'อยู่ระหว่างดำเนินการ' :
                                                                (leave.status==2) ? 'ผ่านการอนุมัติ' :
                                                                (leave.status==3) ? 'ไม่ผ่านการอนุมัติ' :
                                                                (leave.status==9) ? 'ยกเลิก' : 'อยู่ระหว่างการแก้ไข' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <ul class="pagination pagination-sm no-margin pull-right">
                                            <li ng-if="debtPager.current_page !== 1">
                                                <a href="#" ng-click="getDebtWithURL(debtPager.first_page_url)" aria-label="Previous">
                                                    <span aria-hidden="true">First</span>
                                                </a>
                                            </li>
                                        
                                            <li ng-class="{'disabled': (debtPager.current_page==1)}">
                                                <a href="#" ng-click="getDebtWithURL(debtPager.prev_page_url)" aria-label="Prev">
                                                    <span aria-hidden="true">Prev</span>
                                                </a>
                                            </li>

                                            <li ng-repeat="i in debtPages" ng-class="{'active': debtPager.current_page==i}">
                                                <a href="#" ng-click="getDebtWithURL(debtPager.path + '?page=' +i)">
                                                    @{{ i }}
                                                </a>
                                            </li>

                                            <!-- <li ng-if="debtPager.current_page < debtPager.last_page && (debtPager.last_page - debtPager.current_page) > 10">
                                                <a href="#" ng-click="debtPager.path">
                                                    ...
                                                </a>
                                            </li> -->

                                            <li ng-class="{'disabled': (debtPager.current_page==debtPager.last_page)}">
                                                <a href="#" ng-click="getDebtWithURL(debtPager.next_page_url)" aria-label="Next">
                                                    <span aria-hidden="true">Next</span>
                                                </a>
                                            </li>

                                            <li ng-if="debtPager.current_page !== debtPager.last_page">
                                                <a href="#" ng-click="getDebtWithURL(debtPager.last_page_url)" aria-label="Previous">
                                                    <span aria-hidden="true">Last</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.row -->

                    </div><!-- /.box-body -->

                    <!-- Loading (remove the following to stop the loading)-->
                    <div ng-show="loading" class="overlay">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                    <!-- end loading -->

                </div><!-- /.box -->

            </div><!-- /.col -->
        </div><!-- /.row -->

    </section>

    <script>
        $(function () {
            $('#cal_date').datepicker({
                autoclose: true,
                language: 'th',
                format: 'dd/mm/yyyy',
                thaiyear: true
            });
        });
    </script>

@endsection