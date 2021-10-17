@extends('layouts.main')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Dashboard
            <!-- <small>Control panel</small> -->
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content" ng-controller="homeCtrl">
        <!-- Small boxes (Stat box) -->
        <div class="row">

            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>0</h3>

                        <p><h4>ลาป่วยทั้งหมด</h4></p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div><!-- ./col -->

            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>0</h3><!-- <sup style="font-size: 20px">%</sup> -->

                        <p><h4>ลากิจทั้งหมด</h4></p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div><!-- ./col -->

            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>0</h3>

                        <p><h4>ลาพักผ่อนทั้งหมด</h4></p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div><!-- ./col -->

            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>0</h3>

                        <p><h4>ลาคลอดทั้งหมด</h4></p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div><!-- ./col -->

        </div><!-- /.row -->

        <!-- Main row -->
        <div class="row">

            <!-- <section class="col-lg-6 connectedSortable">
                
                <div id="barContainer1" style="width: 100%; height: 400px; margin: 0 auto; margin-top: 20px;"></div>

            </section>

            <section class="col-lg-6 connectedSortable">

                <div id="barContainer2" style="width: 100%; height: 400px; margin: 0 auto; margin-top: 20px;"></div>

            </section> -->

            <section class="col-lg-8 connectedSortable">
                <!-- // หัวหน้า -->
                @include('dashboard._head')

            </section>

            <section class="col-lg-4 connectedSortable">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">จำนวนการลา รายหน่วยงาน</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-triped">
                            <tr>
                                <th>กลุ่มงาน</th>
                                <th style="width: 10%; text-align: center;">จำนวน</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </section>

        </div><!-- /.row (main row) -->

    </section>

    <script>
        $(function () {
            $('.select2').select2();
        });
    </script>
@endsection