<?php

namespace App\Http\Controllers;

use App\Contacts;
use App\Exports\LogsExport;
use App\Logs;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Tymon\JWTAuth\Facades\JWTAuth;

class ContactsController extends Controller
{
    public function index () {
        //вывод контактов
        $contacts = DB::table('contacts')
            ->where('user_id', Auth::user()->id)
            ->select('id', 'name', 'company', 'job', 'email', 'phone', 'notes')
            ->get();

        return response()->json(['contacts' => $contacts]);
    }

    public function add (Request $request) {
        //валидация на стороне сервера
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        //добавление записи в таблицу логов
        $changes = DB::table('actions')->where('act_type', 'create')->first();

        $logs = new Logs();
        $logs->updated_by = JWTAuth::user()->id;
        $logs->changes = $changes->id;
        $logs->updated_at = Carbon::now();

        //проверка на авторизованного пользователя
        if (\JWTAuth::check()) {
            //добавление контакта
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

            //вывод успешного лога
            $logs->updated_contact = $contact->id;
            $logs->updated_contact_name = $contact->name;
            $logs->response = 'success';
            $logs->save();

            return response([
                'status' => 200,
                'data' => $contact
            ]);
        }
        //вывод ошибки
        $logs->response = 'error';
        $logs->save();

        return response([
            'status' => 'error',
        ], 500);
    }

    public function delete($id) {
        //поиск контакта с заданным id
        $contact = Contacts::find($id);

        //добавление лога в таблицу логов
        $changes = DB::table('actions')->where('act_type', 'delete')->first();

        $logs = new Logs();
        $logs->updated_contact = $contact->id;
        $logs->updated_contact_name = $contact->name;
        $logs->updated_by = JWTAuth::user()->id;
        $logs->changes = $changes->id;
        $logs->response = 'success';
        $logs->updated_at = Carbon::now();
        $logs->save();

        //удаление контакта
        Contacts::where('id', $id)->delete();

        return response([
            'status' => 'success',
            'data' => 'Contact was deleted.'
        ], 200);
    }

    public function edit (Request $request, $id) {
        //валидация на стороне сервера
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
        ]);

        //добавление лога в таблицу логов
        $changes = DB::table('actions')->where('act_type', 'update')->first();

        $logs = new Logs();
        $logs->updated_by = JWTAuth::user()->id;
        $logs->changes = $changes->id;
        $logs->updated_at = Carbon::now();

        if ($validator->fails()) {
            //при провале добавление ошибки в таблицу логов
            $logs->response = 'success';
            $logs->save();

            return response()->json(['errors' => $validator->errors()]);
        }
        //проверка авторизации
        if (\JWTAuth::check()) {
            $contact = Contacts::find($id);
            //проверка на то, что контакт существует
            if($contact) {
                //проверка на то, что авторизованный пользователь - владелец контакта
                if($contact->user_id === JWTAuth::user()->id) {
                    //выяснение столбцов, в которых были совершены изменения
                    $acts = [$request->name !== $contact->name ? 'name' : null,
                        $request->company !== $contact->company ? 'company' : null,
                        $request->job !== $contact->job ? 'job' : null,
                        $request->email !== $contact->email ? 'email' : null,
                        $request->phone !== $contact->phone ? 'phone' : null,
                        $request->notes !== $contact->notes ? 'notes' : null];

                    $acts = array_filter($acts);

                    //редактирование контакта
                    $contact->name = $request->name;
                    $contact->company = $request->company;
                    $contact->job = $request->job;
                    $contact->email = $request->email;
                    $contact->phone = $request->phone;
                    $contact->notes = $request->notes;
                    $contact->updated_at = Carbon::now();
                    $contact->save();

                    //добавление успешного лога
                    $logs->updated_contact = $contact->id;
                    $logs->updated_contact_name = $contact->name;
                    $logs->act = implode(", ", $acts);
                    $logs->response = 'success';
                    $logs->save();

                    return response([
                        'status' => 200,
                        'data' => $contact
                    ]);
                }
                //добавление лога со статусом ошибки
                $logs->updated_contact = $contact->id;
                $logs->updated_contact_name = $contact->name;
                $logs->response = 'error';
                $logs->save();

                return response([
                    'status' => 'error',
                ], 500);
            }
            //добавление лога со статусом ошибки
            $logs->response = 'error';
            $logs->save();

            return response([
                'status' => 'error',
            ], 400);
        }
    }

    public function get_logs()
    {
        //просмотр таблицы логов
        $logs = $this->logs();

        return response()->json(['logs' => $logs]);
    }

    public function excel()
    {
        //excel-файл с логами
        $logs = $this->logs();
        $download_path = 'logs.xls';

        return Excel::download(new LogsExport($logs), $download_path);
    }

    protected function logs()
    {
        $logs = DB::table('logs as l')
            ->leftJoin('contacts as c', 'c.id', 'l.updated_contact')
            ->leftJoin('actions as a', 'a.id', 'l.changes')
            ->leftJoin('users as u', 'u.id', 'l.updated_by')
            ->where('l.updated_by', Auth::user()->id)
            ->select('l.id', 'l.updated_contact as contact_id', 'l.updated_contact_name as contact', 'u.name as updated_by',
                'a.act_type as changes', 'l.act', 'l.response', 'l.updated_at')
            ->get();

        return $logs;
    }
}
