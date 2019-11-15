<?php

namespace App\Http\Controllers;

use App\Contacts;
use App\Tasks;
use App\TaskTypes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class ContactsController extends Controller
{
    public function index () {
        $contacts = DB::table('contacts')
            ->where('user_id', Auth::user()->id)
            ->select('id', 'name', 'company', 'job', 'email', 'phone', 'notes')
            ->get();

        return response()->json(['contacts' => $contacts]);
    }

    public function add (Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        if (\JWTAuth::check()) {
            $contact = new Contacts();
            $contact->user_id = JWTAuth::user()->id;
            $contact->name = $request->name;
            $contact->company = $request->company;
            $contact->job = $request->job;
            $contact->email = $request->email;
            $contact->phone = $request->phone;
            $contact->notes = $request->notes;
            $contact->created_at = Carbon::now();
            $contact->save();

            return response([
                'status' => 200,
                'data' => $contact
            ]);
        }
    }

    public function delete($id) {
        Contacts::where('id', $id)->delete();

        return response([
            'status' => 'success',
            'data' => 'Contact was deleted.'
        ], 200);
    }

    public function edit (Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        if (\JWTAuth::check()) {
            $contact = Contacts::find($id);
            if($contact) {
                if($contact->user_id === JWTAuth::user()->id) {
                    $contact->name = $request->name;
                    $contact->company = $request->company;
                    $contact->job = $request->job;
                    $contact->email = $request->email;
                    $contact->phone = $request->phone;
                    $contact->notes = $request->notes;
                    $contact->updated_at = Carbon::now();
                    $contact->save();
                }
            }

            return response([
                'status' => 200,
                'data' => $contact
            ]);
        }
    }
}
