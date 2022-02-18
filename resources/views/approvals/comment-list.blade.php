@extends('layouts.main')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            หัวหน้ากลุ่มงาน
            <!-- <small>preview of simple tables</small> -->
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">หัวหน้ากลุ่มงาน</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content" ng-controller="leaveCtrl" ng-init="onCommentLoad({{ Auth::user()->memberOf->depart_id }})">

        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">ค้นหาข้อมูล</h3>
                    </div>

                    <form id="frmSearch" name="frmSearch" role="form">
                        <div class="box-body">
                            <div class="row">

                                <div class="form-group col-md-6">
                                    <label>ประเภทการลา</label>
                                    <select
                                        id="cboLeaveType"
                                        name="cboLeaveType"
                                        ng-model="cboLeaveType"
                                        class="form-control"
                                        ng-change="getAll($event)"
                                    >
                                        <option value="">-- ทั้งหมด --</option>
                                        @foreach($leave_types as $type)

                                            <option value="{{ $type->id }}">
                                                {{ $type->name }}
                                            </option>

                                        @endforeach
                                    </select>
                                </div><!-- /.form group -->

                            </div><!-- /.row -->
                        </div><!-- /.box-body -->
                    </form>
                </div><!-- /.box -->

                <div class="box">
                    <div class="box-body">
                    <ul class="nav nav-tabs">
                            <li class="active"><a href="#approve" data-toggle="tab">
                                <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                                รายการขออนุมัติใบลา
                                <span class="badge badge-light">@{{ leaves.length }}</span>
                            </a></li>
                            <li><a href="#cancel" data-toggle="tab">
                                <i class="fa fa-ban" aria-hidden="true"></i>
                                รายการขอยกเลิกวันลา
                                <span class="badge badge-light">@{{ cancellations.length }}</span>
                            </a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="active tab-pane" id="approve">

                                @include('approvals._comment-approves')

                            </div><!-- /.tab-pane -->
                            <div class="tab-pane" id="cancel">

                                @include('approvals._comment-cancels')

                            </div><!-- /.tab-pane -->
                        </div><!-- /.tab-content -->

                        @include('approvals._comment-form')
                        @include('approvals._cancel-comment-form')

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