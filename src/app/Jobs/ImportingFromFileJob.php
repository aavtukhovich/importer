<?php

namespace App\Jobs;

use App\Models\Row;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Redis;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ImportingFromFileJob implements ToModel, WithChunkReading, WithHeadingRow, WithBatchInserts, WithCalculatedFormulas, ShouldQueue
{
    use RemembersRowNumber;

    private $reportId;
    private $redis;

    public function  __construct($reportId)
    {
        $this->reportId = $reportId;
        Redis::set($this->reportId, 0);
    }

    public function model(array $row)
    {
        $currentRowNumber = $this->getRowNumber();
        if (!empty($row['id'])) {
            // Количество обработанных строк
            Redis::set($this->reportId, $currentRowNumber);
            return new Row([
                'id' => $row['id'],
                'name' => $row['name'],
                'date' => Date::excelToDateTimeObject($row['date'])->format('Y-m-d'),
            ]);
        }
    }
    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
