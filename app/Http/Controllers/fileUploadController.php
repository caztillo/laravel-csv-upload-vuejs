<?php

namespace App\Http\Controllers;

use App\Contact;
use App\CustomAttribute;
use App\Http\Requests\CSVUpload;

class fileUploadController extends Controller
{
    public function csvUpload(CSVUpload $request)
    {

    
        if ($request->hasFile('csv_file')) 
        {
            $fillable = ['team_id', 'name', 'phone', 'email','sticky_phone_number_id'];
            $path = $request->file('csv_file')->getRealPath();
            $file = file($path);
            $csv_data = $this->get_csv_data($file);
            if(empty($csv_data))
            {
                return response()->json(['status' => 'error','message'=>'Invalid CSV format.']);
            }

            foreach ($csv_data as $row) 
            {
                $team_id = 0;
                $name = '';
                $phone = '';
                $email = '';
                $sticky_phone_number_id = 0;
                $custome_key = '';
                $custome_value = '';
                $extra_attributes = [];

                foreach ($row  as $key => $value) 
                {
                    if(in_array($key, $fillable) )
                    {
                        
                        $team_id = $row['team_id'];
                        $name = $row['name'];
                        $phone = $row['phone'];
                        $email = $row['email'];
                        $sticky_phone_number_id = $row['sticky_phone_number_id'];
                    }
                    else
                    {
                        array_push($extra_attributes, ['key' => $key, 'value' => $row[$key]]);

                        
                    }
                }

                $contact = new Contact;
                $contact->team_id = $team_id;
                $contact->name = $name;
                $contact->phone = $phone;
                $contact->email = $email;
                $contact->sticky_phone_number_id = $sticky_phone_number_id;

                $contact->save();

                if(!empty($extra_attributes))
                {
                    foreach ($extra_attributes as $extra) 
                    {
                        $custome_attribute = new CustomAttribute;
                        $custome_attribute->contact_id = $contact->id;
                        $custome_attribute->key = $extra['key'];
                        $custome_attribute->value = $extra['value'];

                        $custome_attribute->save();
                    }

                    
                }

                

                
            }

            return response()->json(['status' => 'success','message'=>'CSV file uploaded successfully']);

            
        }

        return response()->json(['status' => 'error','message'=>'Unable to upload file.']);

        
    }


    public function get_csv_data($file)
    {
        $csv_rows = [];
        $rows = array_map('str_getcsv', $file);
        $header = array_shift($rows);

        if(in_array(null, $header) || in_array('', array_map('trim',$header))) 
        {
           return $csv_rows;
        }


        foreach($rows as $row) {
            $csv_rows[] = array_combine($header, $row);
        }

        return $csv_rows;
    }
}
