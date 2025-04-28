<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use League\Csv\Writer;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function downloadDaily()
    {
        $sensors = Sensor::where('status', 'active')->get();
        $data = $this->getLast24HoursData($sensors);
        return $this->generateReport($data, 'daily', 'Last 24 Hours Report');
    }

    public function downloadWeekly()
    {
        $sensors = Sensor::where('status', 'active')->get();
        $data = $this->getLastWeekData($sensors);
        return $this->generateReport($data, 'weekly', 'Last 7 Days Report');
    }

    public function generateCustomReport(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'sensors' => 'required_without:all_sensors|array',
            'all_sensors' => 'boolean',
            'format' => 'required|in:csv,excel,pdf'
        ]);

        $sensors = $validated['all_sensors'] ? 
            Sensor::where('status', 'active')->get() :
            Sensor::whereIn('id', $validated['sensors'])->get();

        $data = $this->getCustomDateRangeData(
            $sensors,
            Carbon::parse($validated['start_date']),
            Carbon::parse($validated['end_date'])
        );

        return $this->generateReport(
            $data,
            'custom',
            'Custom Date Range Report',
            $validated['format']
        );
    }

    private function getLast24HoursData($sensors)
    {
        $data = [];
        $now = now();
        $startDate = $now->copy()->subHours(24);

        foreach ($sensors as $sensor) {
            $readings = Cache::get("sensor_{$sensor->id}_readings", []);
            $filteredReadings = array_filter($readings, function($reading) use ($startDate) {
                return Carbon::parse($reading['timestamp'])->gte($startDate);
            });

            if (!empty($filteredReadings)) {
                $data[] = [
                    'sensor' => $sensor,
                    'readings' => array_values($filteredReadings)
                ];
            }
        }

        return $data;
    }

    private function getLastWeekData($sensors)
    {
        $data = [];
        $now = now();
        $startDate = $now->copy()->subDays(7);

        foreach ($sensors as $sensor) {
            $readings = Cache::get("sensor_{$sensor->id}_readings", []);
            $filteredReadings = array_filter($readings, function($reading) use ($startDate) {
                return Carbon::parse($reading['timestamp'])->gte($startDate);
            });

            if (!empty($filteredReadings)) {
                $data[] = [
                    'sensor' => $sensor,
                    'readings' => array_values($filteredReadings)
                ];
            }
        }

        return $data;
    }

    private function getCustomDateRangeData($sensors, $startDate, $endDate)
    {
        $data = [];

        foreach ($sensors as $sensor) {
            $readings = Cache::get("sensor_{$sensor->id}_readings", []);
            $filteredReadings = array_filter($readings, function($reading) use ($startDate, $endDate) {
                $timestamp = Carbon::parse($reading['timestamp']);
                return $timestamp->gte($startDate) && $timestamp->lte($endDate);
            });

            if (!empty($filteredReadings)) {
                $data[] = [
                    'sensor' => $sensor,
                    'readings' => array_values($filteredReadings)
                ];
            }
        }

        return $data;
    }

    private function generateReport($data, $type, $title, $format = 'pdf')
    {
        switch ($format) {
            case 'csv':
                return $this->generateCsvReport($data, $type);
            case 'excel':
                return $this->generateExcelReport($data, $type);
            default:
                return $this->generatePdfReport($data, $type, $title);
        }
    }

    private function generateCsvReport($data, $type)
    {
        $csv = Writer::createFromString('');
        
        // Add headers
        $csv->insertOne(['Timestamp', 'Sensor Name', 'Location', 'Reading Value', 'Unit']);
        
        // Add data
        foreach ($data as $sensorData) {
            $sensor = $sensorData['sensor'];
            foreach ($sensorData['readings'] as $reading) {
                $csv->insertOne([
                    $reading['timestamp'],
                    $sensor->name,
                    $sensor->location,
                    $reading['value'],
                    $this->getSensorUnit($sensor->type)
                ]);
            }
        }

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="air_quality_report_' . $type . '_' . now()->format('Y-m-d') . '.csv"',
        ];

        return response($csv->toString(), 200, $headers);
    }

    private function generateExcelReport($data, $type)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Add headers
        $sheet->fromArray([['Timestamp', 'Sensor Name', 'Location', 'Reading Value', 'Unit']], null, 'A1');
        
        // Add data
        $row = 2;
        foreach ($data as $sensorData) {
            $sensor = $sensorData['sensor'];
            foreach ($sensorData['readings'] as $reading) {
                $sheet->fromArray([[
                    $reading['timestamp'],
                    $sensor->name,
                    $sensor->location,
                    $reading['value'],
                    $this->getSensorUnit($sensor->type)
                ]], null, 'A' . $row);
                $row++;
            }
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'air_quality_report_' . $type . '_' . now()->format('Y-m-d') . '.xlsx';
        $path = storage_path('app/public/' . $filename);
        $writer->save($path);

        return response()->download($path, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ])->deleteFileAfterSend(true);
    }

    private function generatePdfReport($data, $type, $title)
    {
        $pdf = PDF::loadView('reports.pdf', [
            'data' => $data,
            'title' => $title,
            'generated_at' => now()->format('Y-m-d H:i:s')
        ]);

        return $pdf->download('air_quality_report_' . $type . '_' . now()->format('Y-m-d') . '.pdf');
    }

    private function getSensorUnit($type)
    {
        switch ($type) {
            case 'co2':
                return 'ppm';
            case 'no2':
                return 'ppb';
            case 'pm25':
                return 'µg/m³';
            default:
                return 'units';
        }
    }
}