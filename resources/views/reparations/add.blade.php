@extends('layouts.main')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            สร้างรายการซ่อมครุภัณฑ์
            <!-- <small>preview of simple tables</small> -->
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">สร้างรายการซ่อมครุภัณฑ์</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content" ng-controller="reparationCtrl" ng-init="initData()">

        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">สร้างรรายการซ่อมครุภัณฑ์</h3>
                    </div>

                    <form id="frmNewReparation" name="frmNewReparation" method="post" action="{{ url('/reparation/store') }}" role="form">
                        <input type="hidden" id="user" name="user" value="{{ Auth::user()->person_id }}">
                        {{ csrf_field() }}
                    
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">                                

                                    <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(reparation, 'asset_id')}">
                                        <label>ครุภัณฑ์ :</label>
                                        <select id="asset_id" 
                                                name="asset_id"
                                                ng-model="reparation.asset_id"
                                                class="form-control select2"
                                                style="width: 100%; font-size: 12px;"
                                                tabindex="0">
                                            <option value="" selected="selected">-- กรุณาเลือก --</option>

                                            @foreach($assets as $asset)

                                                <option value="{{ $asset->asset_id }}">
                                                    {{ $asset->asset_no. ' ' .$asset->asset_name }}
                                                </option>

                                            @endforeach
                                            
                                        </select>
                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(reparation, 'asset_id')"></span>
                                        <div class="help-block" ng-show="checkValidate(reparation, 'asset_id')">
                                            กรุณาเลือกครุภัณฑ์
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-6">
                                    
                                    <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(reparation, 'reparation_doc_no')}">
                                        <label>เลขที่เอกสาร :</label>
                                        <input
                                            type="text" 
                                            id="reparation_doc_no" .
                                            name="reparation_doc_no" 
                                            ng-model="reparation.reparation_doc_no" 
                                            class="form-control"
                                            tabindex="4">
                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(reparation, 'reparation_doc_no')"></span>
                                        <div class="help-block" ng-show="checkValidate(reparation, 'reparation_doc_no')">
                                            กรุณาระบุเลขที่ขออนุมัติ
                                        </div>
                                    </div>

                                    <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(reparation, 'reparation_cause')}">
                                        <label>อาการเสีย :</label>
                                        <input
                                            type="text" 
                                            id="reparation_cause" 
                                            name="reparation_cause" 
                                            ng-model="reparation.reparation_cause" 
                                            class="form-control"
                                            tabindex="8">
                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(reparation, 'reparation_cause')"></span>
                                        <div class="help-block" ng-show="checkValidate(reparation, 'reparation_cause')">
                                            กรุณาระบุอาการเสีย
                                        </div>
                                    </div>

                                    <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(reparation, 'reparation_price')}">
                                        <label>ค่าซ่อม :</label>
                                        <input
                                            type="text" 
                                            id="reparation_price" 
                                            name="reparation_price" 
                                            ng-model="reparation.reparation_price" 
                                            class="form-control"
                                            tabindex="8">
                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(reparation, 'reparation_price')"></span>
                                        <div class="help-block" ng-show="checkValidate(reparation, 'reparation_price')">
                                            กรุณาระบุค่าซ่อม
                                        </div>
                                    </div>

                                    <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(reparation, 'reparation_type')}">
                                        <label>ประเภทการซ่อม :</label>

                                        @foreach($types as $key => $value)

                                        <div class="radio">
                                            <label>
                                                <input
                                                    type="radio"
                                                    name="reparation_type"
                                                    ng-model="reparation.reparation_type"
                                                    value="{{ $key }}"
                                                > {{ $value }}
                                            </label>
                                        </div>                                            

                                        @endforeach

                                    </div>

                                </div><!-- /.col -->

                                <div class="col-md-6">

                                    <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(reparation, 'reparation_doc_date')}">
                                        <label>วันที่เอกสาร :</label>

                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                            <input
                                                type="text" 
                                                id="reparation_doc_date" 
                                                name="reparation_doc_date" 
                                                ng-model="reparation.reparation_doc_date" 
                                                class="form-control pull-right"
                                                tabindex="1">
                                        </div>
                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(reparation, 'reparation_doc_date')"></span>
                                        <div class="help-block" ng-show="checkValidate(reparation, 'reparation_doc_date')">
                                            กรุณาเลือกวันที่เอกสาร
                                        </div>
                                    </div>

                                    <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(reparation, 'reparation_date')}">
                                        <label>วันที่ซ่อม :</label>

                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                            <input
                                                type="text" 
                                                id="reparation_date" 
                                                name="reparation_date" 
                                                ng-model="reparation.reparation_date" 
                                                class="form-control pull-right"
                                                tabindex="5">
                                        </div>
                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(reparation, 'reparation_date')"></span>
                                        <div class="help-block" ng-show="checkValidate(reparation, 'reparation_date')">
                                            กรุณาเลือกวันที่ซ่อม
                                        </div>
                                    </div>

                                    <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(reparation, 'reparation_handler')}">
                                        <label>ผู้ซ่อม :</label>
                                        <input
                                            type="text" 
                                            id="reparation_handler" 
                                            name="reparation_handler" 
                                            ng-model="reparation.reparation_handler" 
                                            class="form-control"
                                            tabindex="8">
                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(reparation, 'reparation_handler')"></span>
                                        <div class="help-block" ng-show="checkValidate(reparation, 'reparation_handler')">
                                            กรุณาระบุผู้ซ่อม
                                        </div>
                                    </div>

                                    <!-- <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(reparation, 'reparation_status')}">
                                        <label>สถานะ :</label>
                                        <select
                                            id="reparation_status" 
                                            name="reparation_status"
                                            ng-model="reparation.reparation_status"
                                            class="form-control select2"
                                            style="width: 100%; font-size: 12px;"
                                            tabindex="0"
                                        >
                                            <option value="" selected="selected">-- กรุณาเลือก --</option>
                                            
                                            @foreach($statuses as $key => $value)

                                                <option value="{{ $key }}">{{ $value }}</option>

                                            @endforeach

                                        </select>
                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(reparation, 'reparation_status')"></span>
                                        <div class="help-block" ng-show="checkValidate(reparation, 'reparation_status')">
                                            กรุณาเลือกสถานะ
                                        </div>
                                    </div> -->
                                    
                                </div><!-- /.col -->
                                
                                <div class="col-md-12">                                

                                    <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(reparation, 'reparation_detail')}">
                                        <label>รายละเอียดการซ่อม :</label>
                                        <textarea
                                            id="reparation_detail"
                                            name="reparation_detail"
                                            ng-model="reparation.reparation_detail"
                                            rows=""
                                            cols=""
                                            class="form-control"></textarea>
                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(reparation, 'reparation_detail')"></span>
                                        <div class="help-block" ng-show="checkValidate(reparation, 'reparation_detail')">
                                            กรุณาระบุรายละเอียดการซ่อม
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.row -->
                            
                        </div><!-- /.box-body -->
                  
                        <div class="box-footer clearfix">
                            <button ng-click="formValidate($event, '/reparation/validate', reparation, 'frmNewReparation', store)" class="btn btn-success pull-right">
                                บันทึก
                            </button>
                        </div><!-- /.box-footer -->
                    </form>

                </div><!-- /.box -->

                <!-- Modal -->
                <div class="modal fade" id="dlgSupplierDebtList" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="">รายการหนี้</h4>
                            </div>
                            <div class="modal-body" style="padding-top: 0; padding-bottom: 0;">

                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" style="font-size: 12px; margin-top: 20px;">
                                        <thead>
                                            <tr>
                                                <th style="width: 2%; text-align: center;">#</th>
                                                <th style="width: 5%; text-align: center;">รหัส</th>
                                                <th style="width: 7%; text-align: center;">วันที่ลงบัญชี</th>
                                                <th style="width: 7%; text-align: center;">เลขที่ใบส่งของ</th>
                                                <!-- <th style="width: 8%; text-align: center;">วันที่ใบส่งของ</th> -->
                                                <th style="text-align: left;">ประเภทหนี้</th>
                                                <th style="width: 6%; text-align: center;">ยอดหนี้</th>
                                                <th style="width: 6%; text-align: center;">ภาษี</th>
                                                <th style="width: 6%; text-align: center;">สุทธิ</th>
                                                <!-- <th style="width: 6%; text-align: center;">สถานะ</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="(index, debt) in debts">
                                                <td class="text-center">
                                                    <input type="checkbox" 
                                                            ng-click="addSupplierDebtData($event, debt)">
                                                </td>
                                                <td>@{{ debt.debt_id }}</td>
                                                <td>@{{ debt.debt_date }}</td>
                                                <td>@{{ debt.deliver_no }}</td>
                                                <!-- <td>@{{ debt.deliver_date }}</td> -->
                                                <td>@{{ debt.debttype.debt_type_name }}</td>
                                                <td class="text-right">@{{ debt.debt_amount | number:2 }}</td>
                                                <td class="text-right">@{{ debt.debt_vat | number:2 }}</td>
                                                <td class="text-right">@{{ debt.debt_total | number:2 }}</td>
                                                <!-- <td>@{{ debt.debt_status }}</td> -->
                                            </tr>
                                        </tbody>
                                    </table>
                                </div><!-- /.table-responsive -->

                            </div>
                            <div class="modal-footer">
                                <ul class="pagination pagination-sm no-margin pull-left">
                                    <li ng-if="debtPager.current_page !== 1">
                                        <a ng-click="getSupplierDebtDataWithURL(debtPager.first_page_url)" aria-label="Previous">
                                            <span aria-hidden="true">First</span>
                                        </a>
                                    </li>
                                
                                    <li ng-class="{'disabled': (debtPager.current_page==1)}">
                                        <a ng-click="getSupplierDebtDataWithURL(debtPager.first_page_url)" aria-label="Prev">
                                            <span aria-hidden="true">Prev</span>
                                        </a>
                                    </li>
                                   
                                    <li ng-if="debtPager.current_page < debtPager.last_page && (debtPager.last_page - debtPager.current_page) > 10">
                                        <a href="@{{ debtPager.url(debtPager.current_page + 10) }}">
                                            ...
                                        </a>
                                    </li>
                                
                                    <li ng-class="{'disabled': (debtPager.current_page==debtPager.last_page)}">
                                        <a ng-click="getSupplierDebtDataWithURL(debtPager.next_page_url)" aria-label="Next">
                                            <span aria-hidden="true">Next</span>
                                        </a>
                                    </li>

                                    <li ng-if="debtPager.current_page !== debtPager.last_page">
                                        <a ng-click="getSupplierDebtDataWithURL(debtPager.last_page_url)" aria-label="Previous">
                                            <span aria-hidden="true">Last</span>
                                        </a>
                                    </li>
                                </ul>

                                <span class="pull-left" style="margin: 5px 10px;">
                                    @{{ debtPager.current_page+ ' of '+debtPager.last_page }} pages
                                </span>

                                <button type="button" class="btn btn-primary" data-dismiss="modal">
                                    ตกลง
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal -->

            </div><!-- /.col -->
        </div><!-- /.row -->

    </section>

    <script>
        $(function () {
            $('.select2').select2()
        });
    </script>

@endsection