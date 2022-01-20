<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>ใบลาไปต่างประเทศ</title>
        <link rel="stylesheet" href="{{ asset('/css/pdf.css') }}">
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1 style="margin: 0px;">ใบลาไปต่างประเทศ</h1>
            </div>
            <div class="content">
                <table style="width: 100%;">
                    <tr style="height: 20px">
                        <td style="width: 5%;"></td>
                        <td style="width: 30%;"></td>
                        <td style="width: 65%;" colspan="2">
                            <p style="margin: 0 0 0 100px;">
                                เขียนที่ <span style="margin: 0px;">{{ $places[$leave->leave_place] }}</span>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 10%;"></td>
                        <td style="width: 30%;"></td>
                        <td style="width: 30%;" colspan="2">
                            <p style="margin: 0 0 0 100px;">
                                วันที่ <span>{{ convDbDateToLongThDate($leave->leave_date) }}</span>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            เรื่อง <span>{{ $leave->leave_topic }}</span>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            เรียน <span>{{ $leave->leave_to }}</span>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <p style="margin: 0 0 0 80px;">
                                ข้าพเจ้า 
                                <span class="text-val" style="margin-right: 50px;">
                                    {{ $leave->person->prefix->prefix_name.$leave->person->person_firstname. ' ' .$leave->person->person_lastname }}
                                </span>
                                เกิดวันที่
                                <span class="text-val" style="margin-right: 20px;">
                                    {{ convDbDateToLongThDate($leave->person->person_birth) }}
                                </span>
                                อายุ
                                <span class="text-val">
                                    {{ $leave->person->person_birth }}
                                </span>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            ได้เข้ารับราชการเมื่อวันที่ 
                            <span class="text-val" style="margin-right: 10px;">
                                {{ convDbDateToLongThDate($leave->person->person_singin) }}
                            </span>
                            ปัจจุบันเป็นข้าราชการ ตำแหน่ง
                            <span class="text-val">
                                {{ $leave->person->position->position_name }}
                            </span>
                            ระดับ
                            <span class="text-val">
                                {{ $leave->person->academic ? $leave->person->academic->ac_name : '' }}
                            </span>
                            </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            แผนก 
                            <span class="text-val" style="margin-right: 5px;">
                                {{ $leave->person->memberOf->depart->depart_name }}
                            </span>
                            โรงพยาบาลเทพรัตน์นครราชสีมา จังหวัดนครราชสีมา กรมสำนักงานปลัดกระทรวงสาธารณสุข
                            ได้รับเงินเดือนๆ ละ
                            <span class="text-val" style="margin-right: 5px;">
                                {{ $leave->person->person_postcode }}
                            </span>บาท
                            มีความประสงค์จะ
                            <span>
                                {{ $leave->leave_topic }}
                            </span>
                            เพื่อ
                            <span class="text-val" style="margin-right: 10px;">
                                {{ $leave->leave_reason }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            ตั้งแต่วันที่
                            <span class="text-val" style="margin-right: 50px;">
                                {{ convDbDateToLongThDate($leave->start_date) }}
                            </span>
                            ถึงวันที่ 
                            <span class="text-val" style="margin-right: 50px;">
                                {{ convDbDateToLongThDate($leave->end_date) }}
                            </span>
                            มีกำหนด <span class="text-val"> {{ $leave->leave_days }} </span> วัน
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <p style="margin: 0 0 0 80px;">
                                ครั้งสุดท้ายข้าพเจ้าได้
                                @if(!empty($last))
                                    <span class="text-val" style="margin-right: 10px;">
                                        {{ $last->type->name }}
                                    </span>
                                @else
                                    <span class="dot">.....................................................................................................................................</span>
                                @endif
                            </p>
                            <p style="margin: 0px;">
                                ไปประเทศ
                                @if(!empty($last))
                                    <span class="text-val" style="margin-right: 10px;">
                                        {{ $last->type->name }}
                                    </span>
                                @else
                                    <span class="dot">...................</span>
                                @endif
                                เป็นเวลา
                                @if(!empty($last))
                                    <span class="text-val"> {{ $last->leave_days }} </span>
                                @else
                                    <span class="dot">......................................</span>
                                @endif
                                วัน
                                เมื่อวันที่
                                @if(!empty($last))
                                    <span class="text-val" style="margin-right: 10px;">
                                        {{ convDbDateToLongThDate($last->start_date) }}
                                    </span>
                                @else
                                    <span class="dot">.................................</span>
                                @endif
                                ถึงวันที่
                                @if(!empty($last))
                                    <span class="text-val" style="margin-right: 10px;">
                                        {{ convDbDateToLongThDate($last->end_date) }}
                                    </span>
                                @else
                                    <span class="dot">.................................</span>
                                @endif
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            
                        </td>
                        <td colspan="2">
                            <p style="margin-left: 100px;">
                                ขอแสดงความนับถือ
                            </p>
                            <p style="margin-left: 50px;">
                                (ลงชื่อ)<span class="dot">......................................................</span>
                            </p>
                            <p style="margin-left: 100px;">
                                ( {{ $leave->person->prefix->prefix_name.$leave->person->person_firstname. ' ' .$leave->person->person_lastname }} )
                            </p>
                            <p style="margin-left: 50px;">
                                ตำแหน่ง <span>{{ $leave->person->position->position_name }}{{ $leave->person->academic ? $leave->person->academic->ac_name : '' }}</span>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            
                        </td>
                        <td colspan="2">
                            <div style="margin-top: 5px;">
                                <p style="margin-left: 50px;">
                                    ความเห็นของผู้บังคับบัญชา
                                </p>
                                <p style="margin-left: 50px;">
                                    <span class="dot">......................................................................</span>
                                </p>
                                <p style="margin-left: 50px;">
                                    (ลงชื่อ)<span class="dot">......................................................</span>
                                </p>
                                <p style="margin-left: 80px;">
                                    (<span class="dot">......................................................</span>)
                                </p>
                                <p style="margin-left: 50px;">
                                    ตำแหน่ง<span class="dot">......................................................</span>
                                </p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            
                        </td>
                        <td colspan="2">
                            <div style="margin-top: 5px;">
                                <p style="margin-left: 50px;">
                                    คำสั่ง
                                </p>
                                <p style="margin-left: 50px;">
                                    <span style="margin-left: 20px;">[&nbsp;&nbsp;] อนุญาต</span>
                                    <span>[&nbsp;&nbsp;] ไม่อนุญาต</span>
                                </p>
                                <p style="margin-left: 50px;">
                                    (ลงชื่อ)<span class="dot">......................................................</span>
                                </p>
                                <p style="margin-left: 80px;">
                                    (<span class="dot">......................................................</span>)
                                </p>
                                <p style="margin-left: 50px;">
                                    ตำแหน่ง<span class="dot">......................................................</span>
                                </p>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>