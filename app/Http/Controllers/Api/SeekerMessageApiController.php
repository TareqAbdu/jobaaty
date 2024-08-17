<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\User;
use App\Company;
use App\CompanyMessage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SeekerMessageApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function all_messages()
    {
        $messages = CompanyMessage::where('seeker_id', Auth::user()->id)->get();
        $ids = array();
        foreach ($messages as $value) {
            $ids[] = $value->company_id;
        }
        $companies = Company::whereIn('id', $ids)->get();
        return response()->json(['companies' => $companies, 'messages' => $messages], 200);
    }

    public function append_messages(Request $request)
    {
        $seeker_id = Auth::user()->id;
        $company_id = $request->get('company_id');
        $messages = CompanyMessage::where('company_id', $company_id)->where('seeker_id', $seeker_id)->get();
        $seeker = User::find($seeker_id);
        $company = Company::find($company_id);

        return response()->json(compact('messages', 'seeker', 'company'), 200);
    }

    public function submit_message(Request $request)
    {
        $this->validate($request, [
            'message' => 'required',
            'company_id' => 'required|exists:companies,id',
        ]);

        $message = new CompanyMessage();
        $message->company_id = $request->company_id;
        $message->message = $request->message;
        $message->seeker_id = Auth::user()->id;
        $message->save();

        if ($message->save()) {
            return response()->json(['msg' => 'Your message has successfully been posted.', 'status' => true], 200);
        }

        return response()->json(['msg' => 'Failed to post message.', 'status' => false], 500);
    }

    public function submitnew_message(Request $request)
    {
        $this->validate($request, [
            'message' => 'required',
            'company_id' => 'required|exists:companies,id',
        ]);

        $message = new CompanyMessage();
        $message->company_id = $request->company_id;
        $message->message = $request->message;
        $message->seeker_id = Auth::user()->id;
        $message->save();

        if ($message->save()) {
            $messages = CompanyMessage::where('company_id', $request->company_id)->where('seeker_id', Auth::user()->id)->get();
            $seeker = User::find(Auth::user()->id);
            $company = Company::find($request->company_id);

            return response()->json(compact('messages', 'seeker', 'company'), 200);
        }

        return response()->json(['msg' => 'Failed to post message.', 'status' => false], 500);
    }

    public function appendonly_messages(Request $request)
    {
        $seeker_id = Auth::user()->id;
        $company_id = $request->get('company_id');
        $messages = CompanyMessage::where('company_id', $company_id)->where('seeker_id', $seeker_id)->get();
        $seeker = User::find($seeker_id);
        $company = Company::find($company_id);

        return response()->json(compact('messages', 'seeker', 'company'), 200);
    }

    public function change_message_status(Request $request)
    {
        $company_id = $request->get('sender_id');
        $seeker_id = Auth::user()->id;
        $messages = CompanyMessage::where('company_id', $company_id)->where('seeker_id', $seeker_id)->get();

        if ($messages) {
            foreach ($messages as $message) {
                $message->status = 'viewed';
                $message->update();
            }
        }

        return response()->json(['msg' => 'Message status updated successfully.'], 200);
    }
}
