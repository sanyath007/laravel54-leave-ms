<?php

use Intervention\Image\ImageManagerStatic as Image;

function uploadFile ($file, $destPath)
{
    $filename = '';
    if ($file) {
        $filename = date('mdYHis') . uniqid(). '.' .$file->getClientOriginalExtension();

        $file->move($destPath, $filename);
    }

    return $filename;
}

function uploadThumbnail ($img, $destPath)
{
    $img_name = '';
    if ($img) {
        $img_name = date('mdYHis') . uniqid(). '.' .$img->getClientOriginalExtension();

        $img_resized = Image::make($img->getRealPath());
        $img_resized->resize(300, null, function($constraint) {
            $constraint->aspectRatio();
        });
        $img_resized->save(public_path($destPath. '/' .$img_name));
    }

    return $img_name;
}

function convDbDateToThDate ($dbDate)
{
    if(empty($dbDate)) return '';

    $arrDate = explode('-', $dbDate);

    return $arrDate[2]. '/' .$arrDate[1]. '/' .((int)$arrDate[0] + 543);
}

function convThDateToDbDate ($dbDate)
{
    if(empty($dbDate)) return '';

    $arrDate = explode('/', $dbDate);

    return ((int)$arrDate[2] - 543). '-' .$arrDate[1]. '-' .$arrDate[0];
}

function calcBudgetYear ($sdate)
{
    $budgetYear = date('Y') + 543;
    list($day, $month, $year) = explode('/', $sdate);

    if ((int)$month >= 10) {
        $budgetYear = (int)$year + 1;
    } else {
        $budgetYear = (int)$year;
    }

    return $budgetYear;
}


function generatePdf($stylesheet, $content, $path)
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
