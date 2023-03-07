app.service('StringFormatService', function(CONFIG, $http) {
	const MONTH_LONG_NAMES = [
		{ id: '01', name: 'มกราคม' },
		{ id: '02', name: 'กุมภาพันธ์' },
		{ id: '03', name: 'มีนาคม' },
		{ id: '04', name: 'เมษายน' },
		{ id: '05', name: 'พฤษภาคม' },
		{ id: '06', name: 'มิถุนายน' },
		{ id: '07', name: 'กรกฎาคม' },
		{ id: '08', name: 'สิงหาคม' },
		{ id: '09', name: 'กันยายน' },
		{ id: '10', name: 'ตุลาคม' },
		{ id: '11', name: 'พฤศจิกายน' },
		{ id: '12', name: 'ธันวาคม' },
	];

	// const MONTH_SHORT_NAMES = [
	// 	'01' => 'ม.ค.',
	// 	'02' => 'ก.พ.',
	// 	'03' => 'มี.ค.',
	// 	'04' => 'เม.ย',
	// 	'05' => 'พ.ค.',
	// 	'06' => 'มิ.ย.',
	// 	'07' => 'ก.ค.',
	// 	'08' => 'ส.ค.',
	// 	'09' => 'ก.ย.',
	// 	'10' => 'ต.ค.',
	// 	'11' => 'พ.ย.',
	// 	'12' => 'ธ.ค.',
	// ];

	this.convToDbDate = function (date) {
		const [day, month, year] = date.split('/');

		return `${(parseInt(year) - 543)}-${month}-${day}`;
	}

	this.convFromDbDate = function (date) {
		const [year, month, day] = date.split('-');

		return `${day}/${month}/${(parseInt(year) + 543)}`;
	}

	this.thMonthToDbMonth = function(thmonth) {
		const [month, year] = thmonth.split('/');

		return `${(parseInt(year) - 543)}-${month}`;
	}

	// this.getShortMonth = function($monthDigits) {
	// 	return MONTH_SHORT_NAMES[$monthDigits];
	// }

	// this.convDbDateToLongThDate = function($dbDate) {
	// 	if(empty($dbDate)) return '';

	// 	$arrDate = explode('-', $dbDate);

	// 	return (int)$arrDate[2]. ' ' .MONTH_LONG_NAMES[$arrDate[1]]. ' ' .((int)$arrDate[0] + 543);
	// }

	this.dbDateToLongThMonth = function(dbDate) {
		if(!dbDate) return '';

		const [year, month, day] = dbDate.split('-');

		const monthName = MONTH_LONG_NAMES.find(m => m.id == month).name;

		return `${monthName} ${parseInt(year) + 543}`;
	}

	this.dbDateToShortThMonth = function(dbDate) {
		if(!dbDate) return '';

		const [year, month, day] = dbDate.split('-');

		return `${month}/${parseInt(year) + 543}`;
	}

	this.shortMonthToDbMonth = function(smonth) {
		if(!smonth) return '';

		const [month, year] = smonth.split('/');

		return moment(`${parseInt(year) - 543}-${month}`).format('YYYY-MM');
	}
});