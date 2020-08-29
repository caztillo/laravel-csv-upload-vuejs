<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;

class fileUploadTest extends TestCase
{
    public function testCSVUpload()
    {
        $header = 'team_id,name,phone,email,sticky_phone_number_id,extra_param1,extra2';
        $row1 = '99,test name6, 616161, test6@email.com, 66,Lorem ipsum6, 6dipiscing elit ';

        $content = implode("\n", [$header, $row1]);

        $data = ['csv_file' => UploadedFile::fake()->createWithContent('test.csv',$content)];



        $response = $this->postJson('/api/csv-upload', $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
            ]);
    }


    public function testCSVUploadInvalidFile()
    {


        $data = ['csv_file' => UploadedFile::fake()->create('test.pdf')];


        $response = $this->postJson('/api/csv-upload', $data);

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'error',
            ]);
    }
}
