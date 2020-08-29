<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\fileUploadController;
use Illuminate\Http\UploadedFile;



class fileUploadTest extends TestCase
{

    public function testDataArrayFromCSV()
    {
        $file_upload = new fileUploadController();

	    $header = 'team_id,name,phone,email,sticky_phone_number_id,extra_param1,extra2';
	    $row1 = '99,test name6, 616161, test6@email.com, 66,Lorem ipsum6, 6dipiscing elit ';

	    $content = implode("\n", [$header, $row1]);

	    $fake_upload = UploadedFile::fake()->createWithContent('test.csv',$content);

	    $path = $fake_upload->getRealPath();
        $file = file($path);

        $csv_data_array = $file_upload->get_csv_data($file);


        $test_csv_array =  ["team_id" => "99","name" => "test name6","phone" => " 616161","email" => " test6@email.com","sticky_phone_number_id" => " 66","extra_param1" => "Lorem ipsum6","extra2" => " 6dipiscing elit "];
        $this->assertContains($test_csv_array, $csv_data_array);
    }

    public function testInvalidCSVFormat()
    {
        $file_upload = new fileUploadController();

	    $header = 'team_id,name,phone,email,sticky_phone_number_id,extra_param1,';
	    $row1 = '99,test name6, 616161, test6@email.com, 66,Lorem ipsum6, 6dipiscing elit ';

	    $content = implode("\n", [$header, $row1]);

	    $fake_upload = UploadedFile::fake()->createWithContent('test.csv',$content);

	    $path = $fake_upload->getRealPath();
        $file = file($path);

        $csv_data_array = $file_upload->get_csv_data($file);


        $this->assertEmpty($csv_data_array);
    }
}
