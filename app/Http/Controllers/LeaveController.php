<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\Position;
use App\Models\History;


class LeaveController extends Controller
{   
    protected $status = [
        '1' => 'รอเบิก',
        '2' => 'ใช้งานอยู่',
        '3' => 'ถูกยืม',
        '4' => 'จำหน่าย',
    ];

    protected $periods = [
        '1'  => 'เต็มวัน',
        '2'  => 'ช่วงเช้า (08.00-12.00น.)',
        '3'  => 'ช่วงบ่าย (13.00-16.00น.)',
    ];

    public function formValidate (Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'leave_place'   => 'required',
            'leave_type'   => 'required',
            'leave_to'      => 'required',
            'leave_reason'  => 'required',
            'start_date'    => 'required',
            'start_period'  => 'required',
            'end_date'      => 'required',
            'end_period'    => 'required',
            'leave_contact' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'success' => 0,
                'errors' => $validator->getMessageBag()->toArray(),
            ];
        } else {
            return [
                'success' => 1,
                'errors' => $validator->getMessageBag()->toArray(),
            ];
        }
    }

    public function index()
    {
        return view('leaves.list', [
            "leave_types"   => LeaveType::all(),
            "statuses"      => $this->status
        ]);
    }

    public function search($year, $type, $status)
    {
        $conditions = [];
        if($year != '0') array_push($conditions, ['year', '=', $year]);
        if($type !== '0') array_push($conditions, ['leave_type', $type]);
        if($status != '0') array_push($conditions, ['status', '=', $status]);

        if($conditions == '0') {
            $leaves = Leave::with('person', 'person.prefix', 'person.position', 'person.academic')
                        ->with('person.memberOf', 'person.memberOf.depart', 'leaveType')
                        ->orderBy('year', 'desc')
                        ->orderBy('leave_date', 'desc')
                        ->paginate(20);
        } else {
            $leaves = Leave::where($conditions)
                        ->with('person', 'person.prefix', 'person.position', 'person.academic')
                        ->with('person.memberOf', 'person.memberOf.depart', 'leaveType')
                        ->orderBy('year', 'desc')
                        ->orderBy('leave_date', 'desc')
                        ->paginate(20);
        }

        return [
            'leaves' => $leaves,
        ];
    }

    public function getAll()
    {
        return [
            'leaves' => Leave::orderBy('leave_date')->get(),
        ];
    }

    public function getById($id)
    {
        return [
            'leave' => Leave::where('id', $id)
                        ->with('delegate')
                        ->with('delegate.prefix','delegate.position','delegate.academic')
                        ->first(),
        ];
    }

    public function add()
    {
        return view('leaves.add', [
            "leave_types"   => LeaveType::all(),
            "positions"     => Position::all(),
            "statuses"      => $this->status,
            "periods"       => $this->periods,
        ]);
    }

    public function store(Request $req)
    {
        $leave = new Leave();
        $leave->leave_date      = convThDateToDbDate($req['leave_date']);
        $leave->leave_place     = $req['leave_place'];
        $leave->leave_topic     = $req['leave_topic'];
        $leave->leave_to        = $req['leave_to'];
        $leave->leave_person    = $req['leave_person'];
        $leave->leave_type      = $req['leave_type'];
        $leave->leave_reason    = $req['leave_reason'];
        $leave->leave_contact   = $req['leave_contact'];
        $leave->leave_delegate  = $req['leave_delegate'];
        $leave->start_date      = convThDateToDbDate($req['start_date']);
        $leave->start_period    = '1';
        $leave->end_date        = convThDateToDbDate($req['end_date']);
        $leave->end_period      = $req['end_period'];
        $leave->leave_days      = $req['leave_days'];
        $leave->year            = calcBudgetYear($req['start_date']);
        $leave->status          = '1';

        /** Upload attach file */
        $attachment = uploadFile($req->file('attachment'), 'uploads/');
        if (!empty($attachment)) {
            $leave->attachment = $attachment;
        }

        if($leave->save()) {
            $count = History::where('person_id', $req['leave_person'])
                        ->where('year', $leave->year)
                        ->count();

            if ($count > 0) {
                $history = History::where('person_id', $req['leave_person'])->first();
            } else {
                $history = new History;
            }

            if (empty($history->person_id)) {
                $history->person_id = $req['leave_person'];
                $history->year      = $leave->year;
            }

            if ($req['leave_type'] == '1') {
                $history->ill_days += (double)$req['leave_days'];
            } else if ($req['leave_type'] == '2') {
                $history->per_days += (double)$req['leave_days'];
            } else if ($req['leave_type'] == '3') {
                $history->vac_days += (double)$req['leave_days'];
            } else if ($req['leave_type'] == '4') {
                $history->abr_days += (double)$req['leave_days'];
            } else if ($req['leave_type'] == '5') {
                $history->lab_days += (double)$req['leave_days'];
            } else if ($req['leave_type'] == '6') {
                $history->ord_days += (double)$req['leave_days'];
            }

            $history->save();

            return redirect('/leaves/list');
        }
    }

    public function edit($id)
    {
        return view('leaves.edit', [
            "leave"         => Leave::find($id),
            "leave_types"   => LeaveType::all(),
            "positions"     => Position::all(),
            "statuses"      => $this->status,
            "periods"       => $this->periods,
        ]);
    }

    public function update(Request $req)
    {
        $leave = Leave::find($req['id']);
        $leave->leave_date      = convThDateToDbDate($req['leave_date']);
        $leave->leave_place     = $req['leave_place'];
        $leave->leave_topic     = $req['leave_topic'];
        $leave->leave_to        = $req['leave_to'];
        $leave->leave_person    = $req['leave_person'];
        $leave->leave_type      = $req['leave_type'];
        $leave->leave_reason    = $req['leave_reason'];
        $leave->leave_contact   = $req['leave_contact'];
        $leave->leave_delegate  = $req['leave_delegate'];
        $leave->start_date      = convThDateToDbDate($req['start_date']);
        $leave->start_period    = '1';
        $leave->end_date        = convThDateToDbDate($req['end_date']);
        $leave->end_period      = $req['end_period'];
        $leave->leave_days      = $req['leave_days'];
        $leave->year            = calcBudgetYear($req['start_date']);
        $leave->status          = '1';

        /** Upload image */
        $leave->image = '';

        if($leave->save()) {
            return [
                "status" => "success",
                "message" => "Insert success.",
            ];
        } else {
            return [
                "status" => "error",
                "message" => "Insert failed.",
            ];
        }

    }

    public function delete($assetId)
    {
        $asset = Asset::find($assetId);

        if($asset->delete()) {
            return [
                "status" => "success",
                "message" => "Delete success.",
            ];
        } else {
            return [
                "status" => "error",
                "message" => "Delete failed.",
            ];
        }   
    }

    public function getApprove()
    {
        return view('leaves.approve-list', [
            // "suppliers" => Supplier::all(),
            // "cates"     => AssetCategory::all(),
            "leave_types"     => LeaveType::all(),
            "statuses"  => $this->status
        ]);
    }

    public function getCancel()
    {
        return view('leaves.cancel-list', [
            // "suppliers" => Supplier::all(),
            // "cates"     => AssetCategory::all(),
            "leave_types"     => LeaveType::all(),
            "statuses"  => $this->status
        ]);
    }

    public function doDischarge(Request $req)
    {
        if(Asset::where('asset_id', '=', $req['asset_id'])
                ->update(['status' => '4']) <> 0) {
            return [
                'status' => 'success',
                'message' => 'Updated id ' .$req['asset_id']. 'is successed.',
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Updated id ' .$req['asset_id']. 'is failed.',
            ];
        }
    }

    private function createForm($id)
    {
        $appointment = Appointment::with(['patient' => function($q) {
                            $q->select('id','hn','pname','fname','lname','cid','tel1','sex','birthdate');
                        }])
                        ->with(['clinic' => function($q) {
                            $q->select('id', 'clinic_name');
                        }])
                        ->with(['clinic.room' => function($q) {
                            $q->select('id', 'room_name', 'room_tel1');
                        }])
                        ->with(['doctor' => function($q) {
                            $q->select('emp_id', 'title', 'license_no');
                        }])
                        ->with(['doctor.employee' => function($q) {
                            $q->select('id', 'prefix', 'fname', 'lname');
                        }])
                        ->with(['diag' => function($q) {
                            $q->select('id', 'name');
                        }])
                        ->with(['right' => function($q) {
                            $q->select('id', 'right_name');
                        }])
                        ->with(['referCause' => function($q) {
                            $q->select('id', 'name');
                        }])
                        ->with(['hosp' => function($q) {
                            $q->select('hospcode', 'name', 'hospital_phone');
                        }])
                        ->where('id', $id)
                        ->first();

        $building = $appointment['relations']['clinic']->id == '9' ? 'อาคาร M Park' : 'อาคารผู้ป่วยนอก';
        $appointTime = $appointment->appoint_date == '1' ? '08.00 - 12.00 น.' : '12.00 - 16.00 น.';
        $doctor = $appointment['relations']['clinic']->id == 9 ? '' : '
                    <p>นัดพบ <span>' .$appointment['relations']['doctor']->title.$appointment['relations']['doctor']['relations']['employee']->fname. ' ' .$appointment['relations']['doctor']['relations']['employee']->lname. '</span></p>
                ';
        $before = '';

        if ($appointment['relations']['clinic']->id == 9) {
            $before = '<p><span>-</span></p>';
        } else {
            $before = '
                <div class="checkbox-container">
                <div class="checkmark">
                    <img src="assets/img/checkmark.png" width="20" height="20" />
                </div>
                <div class="checkbox-label">
                    <span>EKG (ตรวจคลื่นไฟฟ้าหัวใจ)</span>
                </div>
            </div>
            <div class="checkbox-container">
                <div class="checkmark">
                    <img src="assets/img/checkmark.png" width="20" height="20" />
                </div>
                <div class="checkbox-label">
                    <span>Chest X-Ray (ทำ X-Ray หน้าอก)</span>
                </div>
            </div>
            <div class="checkbox-container">
                <div class="checkmark">
                    <img src="assets/img/checkmark.png" width="20" height="20" />
                </div>
                <div class="checkbox-label">
                    <span>ไม่ต้องงดน้ำงดอาหารก่อนมาตรวจ</span>
                </div>
            </div>
            ';
        }

        $stylesheet = file_get_contents('assets/css/styles.css');
        $content = '
            <div class="container">
                <div class="header">
                    <div class="header-img">
                        <img src="assets/img/logo_mnrh_512x512.jpg" width="100%" height="100" />
                    </div>
                    <div class="header-text">
                        <h1>ใบนัดตรวจ' .$appointment['relations']['clinic']->clinic_name. '</h1>
                        <h2>โรงพยาบาลมหาราชนครราชสีมา</h2>
                    </div>
                </div>
                <div class="content">
                    <div class="left__content-container">
                        <div class="left__content-patient">
                            <p>เลขที่ใบส่งตัว <span>' .$appointment->refer_no. '</span></p>
                            <p>เลขที่บัตรประชาชน <span>' .$appointment['relations']['patient']->cid. '</span></p>
                            <p>ชื่อ-สกุล <span>
                                ' .$appointment['relations']['patient']->pname.$appointment['relations']['patient']->fname. ' '.$appointment['relations']['patient']->lname. '
                            </span></p>
                            <p>โทรศัพท์ <span>' .$appointment['relations']['patient']->tel1. '</span></p>
                            <p>สิทธิการรักษา <span>' .$appointment['relations']['right']->right_name. '</span></p>
                            <p>ผลการวินิจฉัย <span>' .$appointment['relations']['diag']->name. '</span></p>
                        </div>
                        <div class="left__content-before">
                            <p>การปฎิบัติก่อนมา</p>
                            ' .$before. '
                        </div>
                    </div>
                    <div class="right__content-container">
                        <div class="right__content-appoint">
                            ' .$doctor. '
                            <p>วันนัด <span>' .$appointment->appoint_date. '</span></p>
                            <p>เวลา <span>' .$appointTime. '</span></p>
                            </div>
                        <div class="right__content-clinic">
                            <p>ยื่นใบนัดที่ <span>' .$appointment['relations']['clinic']['relations']['room']->room_name. '</span></p>
                            <p><span>' .$building. '</span></p>
                            <p>หมายเลขโทรศัพท์ <span>' .$appointment['relations']['clinic']['relations']['room']->room_tel1. '</span></p>
                        </div>
                        <div class="right__content-remark">
                            <p>หมายเหตุ : <span>กรณีไม่สามารถมาตามนัดได้ หรือต้องการเลื่อนนัด ให้ติดต่อที่โรงพยาบาลที่ทำการออกใบนัด</span></p>
                        </div>
                    </div>
                    <div class="bottom-content">
                        <p>ขั้นตอนการรับบริการ</p>
                        <ul>
                            <li>1. ยื่นใบนัด / ใบส่งตัว (ออกจากระบบ R9Refer เท่านั้น) <span class="text-underline">ที่' .$appointment['relations']['clinic']['relations']['room']->room_name. '</span></li>
                            <li>2. ชั่งน้ำหนัก วัดความดันโลหิต</li>
                            <li>3. รอพยาบาลเรียกซักประวัติ</li>
                            <li>4. พบแพทย์</li>
                            <li>5. พบพยาบาลหลังตรวจ รับใบสั่งยา และ / หรือ ใบนัดครั้งต่อไป</li>
                        </ul>
                    </div>
                </div>
                <div class="footer">
                    <div class="footer-header">
                        <p>หมายเหตุ : <span>กรณีไม่สามารถมาตามนัดได้ หรือต้องการเลื่อนนัด ให้ติดต่อที่โรงพยาบาลที่ออกใบนัด</span></p>
                    </div>
                    <div class="footer-content">
                        <div class="left-footer">
                            <p>ผู้ลงเวลานัด <span>-</span></p>
                            <p>ผู้พิมพ์ใบนัด <span>-</span></p>
                            <p>วัน/เวลา ที่ลงนัด <span>' .$appointment->created_at. '</span></p>
                        </div>
                        <div class="right-footer">
                            <p>สถานพยาบาลออกใบส่งตัว</p>
                            <p><span>' .$appointment['relations']['hosp']->name. '</span></p>
                            <p>โทรศัพท์ <span>044395000 ต่อ 2510</span></p>
                        </div>
                    </div>
                </div>
            </div>
        ';

        // TODO: should create pdf file with generated unduplicate name for avoid browser caching
        $filename = APP_ROOT_DIR . '/public/downloads/' .$appointment->id. '.pdf';

        $this->generatePdf($stylesheet, $content, $filename);
    }

    private function generatePdf($stylesheet, $content, $path)
    {
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new \Mpdf\Mpdf([
            'fontDir' => array_merge($fontDirs, [
                APP_ROOT_DIR . '/public/assets/fonts',
            ]),
            'fontdata' => $fontData + [
                    'sarabun' => [
                        'R' => 'THSarabunNew.ttf',
                        'I' => 'THSarabunNew Italic.ttf',
                        'B' => 'THSarabunNew Bold.ttf',
                    ]
                ],
        ]);

        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($content, \Mpdf\HTMLParserMode::HTML_BODY);
        $mpdf->Output($path, 'F');
    }
}
