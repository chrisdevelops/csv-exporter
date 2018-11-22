# csv-exporter

A lightweight package to export to CSV.

## Installation

Install with  Composer:
```sh
composer require crowles/csv-exporter
```

## Basic Usage

Example:

```php
<?php

include('../../vendor/autoload.php');

$filepath = __DIR__ . '/reports/';
$filename = 'report_' . date('Y-m-d'); // File extensions are automatically handled.

$data = [
	0 => [
		'name' => 'Chris Rowles',
		'age'  => '25',
		'type' => 'Human'
	],
	1 =>[
		'name' => 'Marley',
		'age' => '70',
		'type' => 'Dog', 
	]
];

$csv  = new CSVExporter($filepath, $filename, $data, true);
try {
	$csv->generate()->zip($filename, 'your-password-here');
} catch(\Crowles\CSVExporter\CSVException $e) {
	$log->debug($e->getMessage());
} 
```

## Contact

Contact me@rowles.ch for any issues :)

## License

CSV Exporter is open-sourced software licensed under the GPL license.