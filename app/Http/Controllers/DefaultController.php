<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DefaultController extends Controller
{
    public function emailValidation(Request $request)
    {
        $customer_id = $request->customer_id;
        $customer_email = $request->client_email;

        $validator = Validator::make($request->all(), [
            'client_email' => 'required|email|unique:customers,customer_email,' . $customer_id,
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        } else {
            return response()->json(['status' => true, 'message' => 'Email available.']);
        }
    }
}
