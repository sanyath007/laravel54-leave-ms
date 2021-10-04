@extends('layouts.main')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            รายการใบลา
            <!-- <small>preview of simple tables</small> -->
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">รายการใบลา</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content" ng-controller="assetCtrl" ng-init="getData()">

        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">ค้นหาข้อมูล</h3>
                    </div>

                    <form id="frmSearch" name="frmSearch" role="form">
                        <div class="box-body">
                            <div class="col-md-6">
                                
                                <div class="form-group">
                                    <label>ปีงบประมาณ</label>
                                    <select
                                            id="assetType"
                                            name="assetType"
                                            ng-model="cboAssetType"
                                            ng-change="getParcel(cboAssetType)"
                                            class="form-control select2"
                                            style="width: 100%; font-size: 12px;"
                                    >
                                        <option value="" selected="selected">-- กรุณาเลือก --</option>
                                        @foreach($types as $type)

                                            <option value="{{ $type->type_id }}">
                                                {{ $type->type_no.'-'.$type->type_name }}
                                            </option>

                                        @endforeach
                                        
                                    </select>
                                </div><!-- /.form group -->

                                <div class="form-group">
                                    <label>สถานะ</label>

                                    <select
                                            id="assetStatus"
                                            name="assetStatus"
                                            ng-model="cboAssetStatus"
                                            ng-change="getData($event)"
                                            class="form-control select2"
                                            style="width: 100%; font-size: 12px;">

                                        <option value="" selected="selected">-- กรุณาเลือก --</option>
                                        @foreach($statuses as $key => $status)

                                            <option value="{{ $key }}">
                                                {{ $status }}
                                            </option>

                                        @endforeach
                                        
                                    </select>
                                </div>
                            </div>

                            <!-- <div class="col-md-6">
                                <div class="form-group">
                                    <label>พัสดุหลัก</label>
                                    <select
                                            id="cboParcel"
                                            name="cboParcel"
                                            ng-model="cboParcel"
                                            ng-change="getData($event);"
                                            class="form-control select2"
                                            style="width: 100%; font-size: 12px;">

                                        <option value="" selected="selected">-- กรุณาเลือก --</option>
                                        <option ng-repeat="(index, parcel) in parcels" value="@{{ parcel.parcel_id }}">
                                            @{{ parcel.parcel_no + '-' +parcel.parcel_name }}
                                        </option>
                                    </select>
                                </div>                            
                                
                                <div class="form-group">
                                    <label>ชื่อครุภัณฑ์</label>
                                    <input
                                        type="text"
                                        id="searchKey"
                                        name="searchKey"
                                        ng-model="searchKeyword"
                                        ng-keyup="getData($event)"
                                        class="form-control">
                                </div>

                            </div> -->
                        </div><!-- /.box-body -->

                        <div class="box-footer">
                            <a href="{{ url('/leaves/add') }}" class="btn btn-primary">
                                สร้างใบลา
                            </a>
                        </div>
                    </form>
                </div><!-- /.box -->

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">รายการใบลา</h3>
                    </div><!-- /.box-header -->

                    <div class="box-body">
                        <!-- <div class="form-group pull-right">
                            <input  type="text" 
                                    id="table_search" 
                                    name="table_search"
                                    ng-model="searchKeyword"
                                    class="form-control pull-right" 
                                    placeholder="ค้นหาเลขที่ใบส่งของ">                                       
                        </div> -->

                        <table class="table table-bordered table-striped" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th style="width: 3%; text-align: center;">#</th>
                                    <th style="width: 10%; text-align: center;">ประเภทการลา</th>
                                    <!-- <th style="text-align: left;">ชื่อครุภัณฑ์</th> -->
                                    <th style="width: 8%; text-align: center;">วันที่ลา</th>
                                    <th style="width: 8%; text-align: center;">วันที่ลงทะเบียน</th>
                                    <th style="width: 6%; text-align: center;">สถานะ</th>
                                    <th style="width: 10%; text-align: center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="(index, asset) in assets">
                                    <td style="text-align: center;">@{{ index+pager.from }}</td>
                                    <td style="text-align: center;">@{{ asset.asset_no }}</td>
                                    <td style="text-align: left;">@{{ asset.asset_name }}</td>
                                    <td style="text-align: center;">@{{ asset.date_in | thdate }}</td>
                                    <td style="text-align: center;">@{{ asset.budget_type.budget_type_name }}</td>
                                    <td style="text-align: center;">
                                        <span class="label label-info" ng-show="paid.asset_status!=0">
                                            @{{ (asset.status==1) ? 'รอเบิก' : 
                                                (asset.status==2) ? 'ใช้งานอยู่' : 
                                                (asset.status==3) ? 'ถูกยืม' : 
                                                (asset.status==4) ? 'จำหน่าย' : 'รอตรวจสอบ' }}
                                        </span>
                                    </td>             
                                    <td style="text-align: center;">
                                        <a  ng-click="detail(asset.asset_id)"
                                            class="btn btn-primary btn-xs" 
                                            title="รายละเอียด">
                                            <i class="fa fa-search"></i>
                                        </a>
                                        <a  ng-click="edit(asset.asset_id)" 
                                            ng-show="(asset.status!==4 || asset.status!==3)" 
                                            class="btn btn-warning btn-xs"
                                            title="แก้ไขรายการ">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a  ng-click="delete(asset.asset_id)" 
                                            ng-show="(asset.status!==4 || asset.status!==3)" 
                                            class="btn btn-danger btn-xs"
                                            title="ลบรายการ">
                                            <i class="fa fa-trash"></i>
                                        </a>
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
                    </div><!-- /.box-body -->

                    <!-- Loading (remove the following to stop the loading)-->
                    <div ng-show="loading" class="overlay">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                    <!-- end loading -->

                </div><!-- /.box -->

            </div><!-- /.col -->
        </div><!-- /.row -->

        <!-- Modal -->
        <div class="modal fade" id="dlgEditForm" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="">เพิ่มชนิดครุภัณฑ์</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">ชื่อสถานที่</label>
                            <input type="text" id="locationName" name="locationName" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">ที่อยู่</label>
                            <input type="text" id="locationAddress" name="locationAddress" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">ถนน</label>
                            <input type="text" id="locationRoad" name="locationRoad" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="ID">จังหวัด</label>
                            <select 
                                id="chw_id"
                                name="chw_id" 
                                class="form-control" 
                                ng-model="selectedChangwat" 
                                ng-change="getAmphur($event, selectedChangwat)">
                                <option value="">-- กรุณาเลือกจังหวัด --</option>
                                <option value="@{{ c.chw_id }}" ng-repeat="c in changwats">
                                    @{{ c.changwat }}
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="ID">อำเภอ</label>
                            <select 
                                id="amp_id"
                                name="amp_id" 
                                class="form-control" 
                                ng-model="selectedAmphur"
                                ng-change="getTambon($event, selectedAmphur)">
                                <option value="">-- กรุณาเลือกอำเภอ --</option>
                                <option value="@{{ a.id }}" ng-repeat="a in amphurs">
                                    @{{ a.amphur }}
                                </option>
                            </select>
                        </div>                    

                        <div class="form-group">
                            <label for="ID">ตำบล</label>                    
                            <select 
                                id="tam_id"
                                name="tam_id" 
                                class="form-control" 
                                ng-model="selectedTambon">
                                <option value="">-- กรุณาเลือกตำบล --</option>
                                <option value="@{{ t.id }}" ng-repeat="t in tambons">
                                    @{{ t.tambon }}
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">รหัสไปรษณี</label>
                            <input type="text" id="locationPostcode" name="locationPostcode" class="form-control">
                        </div>              
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" ng-click="addNewLocation($event)">
                            Save
                        </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->

    </section>

    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2()

            //Date range picker with time picker
            $('#debtDate').daterangepicker({
                timePickerIncrement: 30,
                locale: {
                    format: 'YYYY-MM-DD',
                    separator: " , ",
                }
            }, function(e) {
                console.log(e);
            });
        });
    </script>

@endsection