<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\UploadRequest;
use App\Http\Resources\RowResource;
use App\Jobs\ImportingFromFileJob;
use App\Models\Row;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Util;
use Maatwebsite\Excel\Facades\Excel;

class UploadController extends Controller
{

    public function index()
    {
        return RowResource::collection(Row::all())->groupBy("date");
    }

    /**
     * Store a newly created resource
     *
     * @param  \app\Http\Requests\api\v1\UploadRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UploadRequest $request)
    {
        $validated = $request->validated();
        if (isset($validated['file'])) {
            $file = $validated['file'];
            $fileName = $file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs("/", $fileName);
            $reportId = hash('crc32', $fileName);
            Excel::import(new ImportingFromFileJob($reportId), Storage::path($filePath));

            return response()->json([
                'status' => true,
                'message' => 'The file has been uploaded successfully and will be processed.',
                'report_id' => $reportId,
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'The file has not been uploaded to the server.',
        ]);
    }
}
