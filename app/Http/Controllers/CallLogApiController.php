<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CallLogApiController extends Controller
{
    //
    public function callLogApi(Request $request)
    {
     
        // Define validation rules
        $rules = [
            'callernumber' => 'required',
            'date' => 'required|date',
            'time' => 'required',
            'agentnumber' => 'required',
            'ivrduration' => 'required',
            'callduration' => 'required',
            'callstatus' => 'required',
            'state' => 'required',
            'ibizfonenumber' => 'required',
            'recordingurl' => 'required',
            'calllogid' => 'required',
            'department' => 'required',
            'calltype' => 'required'
        ];

        // Validate the incoming data
        $validator = Validator::make($request->all(), $rules);

        // If validation fails, return an error response
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // If validation passes, insert the data into the 'call_logs' table using DB Query Builder
        DB::table('call_logs')->insert([
            'callernumber' => $request->input('callernumber'),
            'date' => $request->input('date'),
            'time' => $request->input('time'),
            'agentnumber' => $request->input('agentnumber'),
            'ivrduration' => $request->input('ivrduration'),
            'callduration' => $request->input('callduration'),
            'callstatus' => $request->input('callstatus'),
            'state' => $request->input('state'),
            'ibizfonenumber' => $request->input('ibizfonenumber'),
            'recordingurl' => $request->input('recordingurl'),
            'calllogid' => $request->input('calllogid'),
            'department' => $request->input('department'),
            'calltype' => $request->input('calltype'),
            'created_at' => now(),
        ]);

        // Return a success response
        return response()->json([
            'status' => 'success',
            'message' => 'Call log created successfully'
        ], 200);
    
    }
}
