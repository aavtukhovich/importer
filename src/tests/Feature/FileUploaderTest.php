<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class FileUploaderTest extends TestCase
{
    public function test_different_file_type()
    {
        Storage::fake('avatars');
        $response = $this->json(
            'POST',
            '/api/uploads',
            [
                'file' => UploadedFile::fake()->image('avatar.jpg')
            ]
        );
        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                "errors" => array(
                    "file"
                )
            ]);
    }
    public function test_correct_file_type()
    {
        $file = UploadedFile::fake()->create('myexcel.xlsx');

        Excel::fake();

        $this->json(
            'POST',
            '/api/uploads',
            [
                'file' => $file
            ]
        );
        $this->assertFileExists(storage_path("app/" . $file->getClientOriginalName()));
    }
}
