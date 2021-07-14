<?php

namespace App\Jobs;

use App\Models\Row;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ImportingFromFileJob implements ToCollection, WithChunkReading, WithCalculatedFormulas, ShouldQueue, WithStartRow
{
    private $reportId;
    private $redis;

    public function  __construct($reportId)
    {
        $this->reportId = $reportId;
        Redis::set($this->reportId, 0);
        // $this->redis = Redis::connection("redis");
        // $this->redis->set($this->reportId, 0);
    }

    public function collection(Collection $collection)
    {
        foreach ($collection as $item) {
            $row = new Row();
            $row->id = $item[0];
            $row->name = $item[1];
            $row->date = Date::excelToDateTimeObject($item[2])->format('Y-m-d');
            $row->save();
        }
        $this->processedLines($collection->count());
    }

    private function processedLines(Int $linesCount)
    {
        Redis::set($this->reportId, $linesCount);
        Log::info($this->reportId . " -> " . Redis::get($this->reportId));
        // $this->redis = Redis::connection("redis");
        // $this->redis->set($this->reportId, $linesCount);
    }

    public function startRow(): int
    {
        return 2;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
