<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportAttachment;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Models\AdminNotification;
use App\Models\Admin;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;

class SupportTicketController extends Controller
{
    //for users
    public function createtickets()
    {
        /*if (!Auth::user()) {
            abort(404);
        }*/
        $pageTitle = "Support Tickets";
        $user = Auth::guard('admin')->user();
        $supports = SupportTicket::where('user_id', $user->id)->orderBy('priority', 'desc')->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.support.index', compact('supports', 'pageTitle'));
    }

     // Support Ticket
     public function supportTicketlegacy()
     {
         if (!Auth::user()) {
             abort(404);
         }
         $pageTitle = "Support Tickets";
         $supports = SupportTicket::where('user_id', Auth::id())->orderBy('priority', 'desc')->orderBy('id','desc')->paginate(getPaginate());
         return view('admin.support.index', compact('supports', 'pageTitle'));
     }




    public function openSupportTicket()
    {
        if (!Auth::user()) {
          //  abort(404);
        }
        $pageTitle = "Support Tickets";
        $user = Auth::guard('admin')->user();
       // return $user;
        //$user = Admin::findOrFail($user->id);
        return view('admin.support.create', compact('pageTitle', 'user'));
    }

    public function storeSupportTicket(Request $request)
    {
        $ticket = new SupportTicket();
        $message = new SupportMessage();

        $files = $request->file('attachments');
        $allowedExts = array('jpg', 'png', 'jpeg', 'pdf','doc','docx');

        $this->validate($request, [
            'attachments' => [
                'max:4096',
                function ($attribute, $value, $fail) use ($files, $allowedExts) {
                    foreach ($files as $file) {
                        $ext = strtolower($file->getClientOriginalExtension());
                        if (($file->getSize() / 1000000) > 2) {
                            return $fail("Miximum 2MB file size allowed!");
                        }
                        if (!in_array($ext, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg, pdf, doc, docx files are allowed");
                        }
                    }
                    if (count($files) > 5) {
                        return $fail("Maximum 5 files can be uploaded");
                    }
                },
            ],
            'name' => 'required|max:191',
            'email' => 'required|email|max:191',
            'subject' => 'required|max:100',
            'message' => 'required',
            'priority' => 'required|in:1,2,3',
        ]);

        $user = Auth::guard('admin')->user();
       
       // $user = auth()->user();
        $ticket->user_id = $user->id;
        $random = rand(100000, 999999);
        $ticket->ticket = $random;
        $ticket->name = $request->name;
        $ticket->email = $request->email;
        $ticket->subject = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status = 0;
        $ticket->priority = $request->priority;
        $ticket->save();

        $message->supportticket_id = $ticket->id;
        $message->message = $request->message;
        $message->save();


        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $user->id;
        $adminNotification->title = 'New support ticket has opened';
        $adminNotification->click_url = urlPath('admin.ticket.view',$ticket->id);
        $adminNotification->save();


        $path = imagePath()['ticket']['path'];
        if ($request->hasFile('attachments')) {
            foreach ($files as  $file) {
                try {
                    $attachment = new SupportAttachment();
                    $attachment->support_message_id = $message->id;
                    $attachment->attachment = uploadFile($file, $path);
                    $attachment->save();
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Could not upload your file'];
                    return back()->withNotify($notify);
                }
            }
        }
        $notify[] = ['success', 'ticket created successfully!'];
        //return
        return redirect()->back()->withNotify($notify);
        //return redirect()->route('admin.supportticket.support_ticket')->withNotify($notify);
    }








    public function tickets()
    {
        $pageTitle = 'Support Tickets';
        $emptyMessage = 'No Data found.';
        $companyid=\Auth::guard("admin")->user()->company_id;
        $users=User::orderby('name','asc')
        ->where("company_id",$companyid)
        ->get();
        $us=[];
        foreach($users as $u)
        {
              array_push($us,$u->id);
        }


        $items = SupportTicket::orderBy('id','desc')
        ->whereIn("id",$us)
        ->with('user')->paginate(getPaginate());
        return view('admin.support.tickets', compact('items', 'pageTitle','emptyMessage'));
    }

    public function pendingTicket()
    {
        $companyid=\Auth::guard("admin")->user()->company_id;
       
        $pageTitle = 'Pending Tickets';
        $emptyMessage = 'No Data found.';
        $users=User::orderby('name','asc')
        ->where("company_id",$companyid)
        ->get();
        $us=[];
        foreach($users as $u)
        {
              array_push($us,$u->id);
        }

        $items = SupportTicket::whereIn('status', [0,2])
        ->whereIn("id",$us)
        ->orderBy('priority', 'DESC')->orderBy('id','desc')->with('user')->paginate(getPaginate());
        return view('admin.support.tickets', compact('items', 'pageTitle','emptyMessage'));
    }

    public function closedTicket()
    {
        $companyid=\Auth::guard("admin")->user()->company_id;
       
        $users=User::orderby('name','asc')
        ->where("company_id",$companyid)
        ->get();
        $us=[];
        foreach($users as $u)
        {
              array_push($us,$u->id);
        }

        $emptyMessage = 'No Data found.';
        $pageTitle = 'Closed Tickets';
        $items = SupportTicket::where('status',3)
        ->whereIn("id",$us)
        ->orderBy('id','desc')->with('user')->paginate(getPaginate());
        return view('admin.support.tickets', compact('items', 'pageTitle','emptyMessage'));
    }

    public function answeredTicket()
    {
        $companyid=\Auth::guard("admin")->user()->company_id;
       
        $users=User::orderby('name','asc')
        ->where("company_id",$companyid)
        ->get();
        $us=[];
        foreach($users as $u)
        {
              array_push($us,$u->id);
        }

        $pageTitle = 'Answered Tickets';
        $emptyMessage = 'No Data found.';
        $items = SupportTicket::orderBy('id','desc')
        ->whereIn("id",$us)
        ->with('user')->where('status',1)->paginate(getPaginate());
        return view('admin.support.tickets', compact('items', 'pageTitle','emptyMessage'));
    }


    public function ticketReply($id)
    {
        $ticket = SupportTicket::with('user')->where('id', $id)->firstOrFail();
        $pageTitle = 'Reply Ticket';
        $messages = SupportMessage::with('ticket')->where('supportticket_id', $ticket->id)->orderBy('id','desc')->get();
        return view('admin.support.reply', compact('ticket', 'messages', 'pageTitle'));
    }
    public function ticketReplySend(Request $request, $id)
    {
        $ticket = SupportTicket::with('user')->where('id', $id)->firstOrFail();
        $message = new SupportMessage();
        if ($request->replayTicket == 1) {

            $attachments = $request->file('attachments');
            $allowedExts = array('jpg', 'png', 'jpeg', 'pdf', 'doc', 'docx');

            $this->validate($request, [
                'attachments' => [
                    'max:4096',
                    function ($attribute, $value, $fail) use ($attachments, $allowedExts) {
                        foreach ($attachments as $attachment) {
                            $ext = strtolower($attachment->getClientOriginalExtension());
                            if (($attachment->getSize() / 1000000) > 2) {
                                return $fail("Miximum 2MB file size allowed!");
                            }

                            if (!in_array($ext, $allowedExts)) {
                                return $fail("Only png, jpg, jpeg, pdf, doc, docx files are allowed");
                            }
                        }
                        if (count($attachments) > 5) {
                            return $fail("Maximum 5 files can be uploaded");
                        }
                    }
                ],
                'message' => 'required',
            ]);
            $ticket->status = 1;
            $ticket->last_reply = Carbon::now();
            $ticket->save();

            $message->supportticket_id = $ticket->id;
            $message->admin_id = Auth::guard('admin')->id();
            $message->message = $request->message;
            $message->save();

            $path = imagePath()['ticket']['path'];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    try {
                        $attachment = new SupportAttachment();
                        $attachment->support_message_id = $message->id;
                        $attachment->attachment = uploadFile($file, $path);
                        $attachment->save();
                    } catch (\Exception $exp) {
                        $notify[] = ['error', 'Could not upload your ' . $file];
                        return back()->withNotify($notify)->withInput();
                    }
                }
            }

            notify($ticket, 'ADMIN_SUPPORT_REPLY', [
                'ticket_id' => $ticket->ticket,
                'ticket_subject' => $ticket->subject,
                'reply' => $request->message,
                'link' => route('ticket.view',$ticket->ticket),
            ]);

            $notify[] = ['success', "Support ticket replied successfully"];

        } elseif ($request->replayTicket == 2) {
            $ticket->status = 3;
            $ticket->save();
            $notify[] = ['success', "Support ticket closed successfully"];
        }
        return back()->withNotify($notify);
    }


    public function ticketDownload($ticket_id)
    {
        $attachment = SupportAttachment::findOrFail(decrypt($ticket_id));
        $file = $attachment->attachment;


        $path = imagePath()['ticket']['path'];

        $full_path = $path.'/' . $file;
        $title = slug($attachment->supportMessage->ticket->subject).'-'.$file;
        $mimetype = mime_content_type($full_path);
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($full_path);
    }
    public function ticketDelete(Request $request)
    {
        $message = SupportMessage::findOrFail($request->message_id);
        $path = imagePath()['ticket']['path'];
        if ($message->attachments()->count() > 0) {
            foreach ($message->attachments as $attachment) {
                removeFile($path.'/'.$attachment->attachment);
                $attachment->delete();
            }
        }
        $message->delete();
        $notify[] = ['success', "Delete successfully"];
        return back()->withNotify($notify);

    }

    public function viewTicketuser($ticket)
    {
        $pageTitle = "Support Tickets";
        $userId = 0;
        $my_ticket = SupportTicket::where('ticket', $ticket)->orderBy('id','desc')->firstOrFail();
        $user = Auth::guard('admin')->user();
       

            /*if($my_ticket->user_id > 0){
                if (Auth::user()) {
                     $userId = Auth::id();
                }else{
                    return redirect()->route('user.login');
                }
            }*/

        $layout = 'admin.layouts.app';


        $my_ticket = SupportTicket::where('ticket', $ticket)->where('user_id',$user->id)->orderBy('id','desc')->firstOrFail();
        $messages = SupportMessage::where('supportticket_id', $my_ticket->id)->orderBy('id','desc')->get();
     
        return view('admin.support.view', compact('my_ticket', 'messages', 'pageTitle', 'user', 'layout'));

    }

    public function replyTicketuser(Request $request, $id)
    {
      
        $user = Auth::guard('admin')->user();
        $userId = $user->id;
        $ticket = SupportTicket::where('user_id',$userId)->where('id',$id)->firstOrFail();
        $message = new SupportMessage();
        if ($request->replayTicket == 1) {
            $attachments = $request->file('attachments');
            $allowedExts = array('jpg', 'png', 'jpeg', 'pdf', 'doc','docx');

            $this->validate($request, [
                'attachments' => [
                    'max:4096',
                    function ($attribute, $value, $fail) use ($attachments, $allowedExts) {
                        foreach ($attachments as $file) {
                            $ext = strtolower($file->getClientOriginalExtension());
                            if (($file->getSize() / 1000000) > 2) {
                                return $fail("Miximum 2MB file size allowed!");
                            }
                            if (!in_array($ext, $allowedExts)) {
                                return $fail("Only png, jpg, jpeg, pdf doc docx files are allowed");
                            }
                        }
                        if (count($attachments) > 5) {
                            return $fail("Maximum 5 files can be uploaded");
                        }
                    },
                ],
                'message' => 'required',
            ]);

            $ticket->status = 2;
            $ticket->last_reply = Carbon::now();
            $ticket->save();

            $message->supportticket_id = $ticket->id;
            $message->message = $request->message;
            $message->save();

            $path = imagePath()['ticket']['path'];

            if ($request->hasFile('attachments')) {
                foreach ($attachments as $file) {
                    try {
                        $attachment = new SupportAttachment();
                        $attachment->support_message_id = $message->id;
                        $attachment->attachment = uploadFile($file, $path);
                        $attachment->save();

                    } catch (\Exception $exp) {
                        $notify[] = ['error', 'Could not upload your ' . $file];
                        return back()->withNotify($notify)->withInput();
                    }
                }
            }

            $notify[] = ['success', 'Support ticket replied successfully!'];
        } elseif ($request->replayTicket == 2) {
            $ticket->status = 3;
            $ticket->last_reply = Carbon::now();
            $ticket->save();
            $notify[] = ['success', 'Support ticket closed successfully!'];
        }else{
            $notify[] = ['error','Invalid request'];
        }
        return back()->withNotify($notify);

    }


}
