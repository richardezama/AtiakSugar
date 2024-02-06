<?php

namespace App\Http\Controllers;

use App\Lib\BusLayout;
use App\Models\AdminNotification;
use App\Models\FleetType;
use App\Models\Frontend;
use App\Models\Language;
use App\Models\Page;
use App\Models\Schedule;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Models\Trip;
use App\Models\Booktype;
use App\Models\Book;
use App\Models\TicketPrice;
use App\Models\BookedTicket;
use App\Models\VehicleRoute;
use App\Models\Counter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\Vehicle;
use App\Models\Brand;
use App\Models\Steering;


class SiteController extends Controller
{
    public function __construct(){
        $this->activeTemplate = activeTemplate();
    }

    public function index(){
        //build a home page
       return redirect()->route('admin.login');
       //return redirect()->route('user.login');
       $pageTitle = "Home";
       //return view($this->activeTemplate . 'home', compact('pageTitle'));
    }

    public function pages($slug)
    {
        $page = Page::where('tempname',$this->activeTemplate)->where('slug',$slug)->firstOrFail();
        $pageTitle = $page->name;
        $sections = $page->secs;
        return view($this->activeTemplate . 'pages', compact('pageTitle','sections'));
    }


    public function contact()
    {
        $pageTitle = "Contact Us";
        $sections = Page::where('tempname',$this->activeTemplate)->where('slug','contact')->first();
        $content = Frontend::where('data_keys', 'contact.content')->first();

        return view($this->activeTemplate . 'contact',compact('pageTitle', 'sections', 'content'));
    }

    public function locations()
    {
        $pageTitle = "Where we are";
       
        //locations
        $routes = DB::table('counters as c')
        ->where('c.latitude',"!=", "")
        ->get();
       // return $routes;

        return view($this->activeTemplate . 'locations',compact('pageTitle','routes'));
    }

    public function mapurl()
    {
        $routes = DB::table('counters as c')
        ->where('c.latitude',"!=", "")
        ->get();
        return $routes;
    }


    public function contactSubmit(Request $request)
    {
        $attachments = $request->file('attachments');
        $allowedExts = array('jpg', 'png', 'jpeg', 'pdf');

        $this->validate($request, [
            'name' => 'required|max:191',
            'email' => 'required|max:191',
            'subject' => 'required|max:100',
            'message' => 'required',
        ]);


        $random = getNumber();

        $ticket = new SupportTicket();
        $ticket->user_id = auth()->id() ?? 0;
        $ticket->name = $request->name;
        $ticket->email = $request->email;
        $ticket->priority = 2;


        $ticket->ticket = $random;
        $ticket->subject = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status = 0;
        $ticket->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = auth()->user() ? auth()->user()->id : 0;
        $adminNotification->title = 'A new support ticket has opened ';
        $adminNotification->click_url = urlPath('admin.ticket.view',$ticket->id);
        $adminNotification->save();

        $message = new SupportMessage();
        $message->supportticket_id = $ticket->id;
        $message->message = $request->message;
        $message->save();

        $notify[] = ['success', 'ticket created successfully!'];

        return redirect()->route('ticket.view', [$ticket->ticket])->withNotify($notify);
    }

    public function changeLanguage($lang = null)
    {
        $language = Language::where('code', $lang)->first();
        if (!$language) $lang = 'en';
        session()->put('lang', $lang);
        return redirect()->back();
    }

    public function blog(){
        $pageTitle = 'Blog Page';
        $blogs = Frontend::where('data_keys','blog.element')->orderBy('id', 'desc')->paginate(getPaginate(16));
        $latestPost = Frontend::where('data_keys', 'blog.element')->orderBy('id','desc')->take(10)->get();
        $sections = Page::where('tempname',$this->activeTemplate)->where('slug','blog')->first();
        return view($this->activeTemplate.'blog',compact('blogs','pageTitle', 'latestPost', 'sections'));
    }

    public function blogDetails($id,$slug){
        $blog = Frontend::where('id',$id)->where('data_keys','blog.element')->firstOrFail();
        $pageTitle = "Blog Details";
        $latestPost = Frontend::where('data_keys', 'blog.element')->where('id', '!=', $id)->orderBy('id','desc')->take(10)->get();
        if(auth()->user()){
            $layout = 'layouts.master';
        }else{
            $layout = 'layouts.frontend';
        }
        return view($this->activeTemplate.'blog_details',compact('blog','pageTitle','layout', 'latestPost'));
    }

    public function policyDetails($id, $slug){
        $pageTitle = 'Policy Details';
        $policy = Frontend::where('id', $id)->where('data_keys', 'policies.element')->firstOrFail();
        return view($this->activeTemplate.'policy_details',compact('pageTitle', 'policy'));
    }

    public function cookieDetails(){
        $pageTitle = 'Cookie Details';
        $cookie = Frontend::where('data_keys', 'cookie_policy.content')->first();
        return view($this->activeTemplate.'cookie_policy',compact('pageTitle', 'cookie'));
    }

    public function cookieAccept(){
        session()->put('cookie_accepted',true);
        return response()->json(['success' => 'Cookie accepted successfully']);
    }

    public function ticket(){
        $pageTitle = 'Available Cars';
        $emptyMessage = 'There are no car available for hire';
        $fleetType = FleetType::active()->get();
        $trips =[];// Trip::with(['fleetType' ,'schedule', 'startFrom' , 'endTo','company'])->where('status', 1)->paginate(getPaginate(10));
        $trips =Vehicle::with(['fleetType','brand','steering'])
       ->paginate(getPaginate(10));
       $fleets = FleetType::active()->get();
       $steering = Steering::get();
       $brands = Brand::get();
       
       
        if(auth()->user()){
            $layout = 'layouts.master';
        }else{
            $layout = 'layouts.frontend';
        }
        $schedules = Schedule::all();
        $routes = VehicleRoute::active()->get();
       /* $trip = Trip::find(4);
        $company_id=$trip->company_id;
        return $company_id;*/
        return view($this->activeTemplate.'ticket', compact('pageTitle' ,'fleetType',
         'trips','routes' ,'schedules', 'emptyMessage', 'layout','fleets','steering','brands'));
    }

    public function showbook($id){
       
        $bookdata =Book::with(['booktype','author'])
        ->where('id', $id)->get();
        $pageTitle = $bookdata[0]->title;
        if(auth()->user()){
            $layout = 'layouts.master';
        }else{
            $layout = 'layouts.frontend';
        }
      
        $book=$bookdata[0];   
        $bookname=str_replace("books/","",$book->book);
        $bookname=str_replace(".epub","",$bookname);
       // return $bookname;
        $link="https://nrmlibrary.netlify.app/?book=".$bookname;
       return view($this->activeTemplate.'book_ticket', compact('pageTitle','book' , 'layout','link'));
    }

    public function getTicketPrice(Request $request){
        //first get the company id of this trip
        $trip = Trip::find($request->trip_id);
        $company_id=$trip->company_id;
        //each bus company has a different price per route
        $ticketPrice  = TicketPrice::where('vehicle_route_id', 
        $request->vehicle_route_id)->where('fleet_type_id', $request->fleet_type_id)
        ->where('company_id', $company_id)
        ->with('route')->first();
        $route              = $ticketPrice->route;
        $stoppages          = $ticketPrice->route->stoppages;
        $sourcePos         = array_search($request->source_id, $stoppages);
        $destinationPos    = array_search($request->destination_id, $stoppages);

        $bookedTicket  = BookedTicket::where('trip_id', $request->trip_id)->where('date_of_journey', Carbon::parse($request->date)->format('Y-m-d'))->whereIn('status', [1,2])->get()->toArray();

        $startPoint = array_search($trip->start_from , array_values($trip->route->stoppages));
        $endPoint = array_search($trip->end_to , array_values($trip->route->stoppages));
        if($startPoint < $endPoint){
            $reverse = false;
        }else{
            $reverse = true;
        }

        if(!$reverse){
            $can_go = ($sourcePos < $destinationPos)?true:false;
        }else{
            $can_go = ($sourcePos > $destinationPos)?true:false;
        }

        if(!$can_go){
            $data = [
                'error' => 'Select Pickup Point & Dropping Point Properly'
            ];
            return response()->json($data);
        }
        $sdArray  = [$request->source_id, $request->destination_id];
        //prices for stoppages
        $getPrice = $ticketPrice->prices()->where('source_destination', 
        json_encode($sdArray))->orWhere('source_destination', json_encode(array_reverse($sdArray)))
        ->where('company_id', $company_id)
        ->first();
        if($getPrice){
            $price = $getPrice->price;
        }else{
            $price = [
                'error' => 'Admin may not set prices for this route. So, you can\'t buy ticket for this trip.'
            ];
        }
       // $price = $ticketPrice->price;
        $data['bookedSeats']        = $bookedTicket;
        $data['reqSource']         = $request->source_id;
        $data['reqDestination']    = $request->destination_id;
        $data['reverse']            = $reverse;
        $data['stoppages']          = $stoppages;
        $data['price']              = $price;
        return response()->json($data);
    }

    public function bookTicket(Request $request,$id){
        $request->validate([
            //"pickup_point"   => "required|integer|gt:0",
            //"dropping_point"  => "required|integer|gt:0",
            "date_of_journey" => "required|date",
            //"seats"           => "required|string",
            //"gender"          => "required|integer"
        ],[
            "seats.required"  => "Please Select at Least One Seat"
        ]);

        if(!auth()->user()){
            $notify[] = ['error', 'Without login you can\'t rent a car'];
            return redirect()->route('user.login')->withNotify($notify);
        }

        $date_of_journey  = Carbon::parse($request->date_of_journey);
        $today            = Carbon::today()->format('Y-m-d');
        if($date_of_journey->format('Y-m-d') < $today ){
            $notify[] = ['error', 'Date of journey cant\'t be less than today.'];
            return redirect()->back()->withNotify($notify);
        }

       /* $dayOff =  $date_of_journey->format('w');
        $trip   = Trip::findOrFail($id);
        $company_id=$trip->company_id;
        $route              = $trip->route;
        $stoppages          = $trip->route->stoppages;
        $source_pos         = array_search($request->pickup_point, $stoppages);
        $destination_pos    = array_search($request->dropping_point, $stoppages);

        if(!empty($trip->day_off)) {
            if(in_array($dayOff, $trip->day_off)) {
                $notify[] = ['error', 'The trip is not available for '.$date_of_journey->format('l')];
                return redirect()->back()->withNotify($notify);
            }
        }

        $booked_ticket  = BookedTicket::where('trip_id', $id)->where('date_of_journey', Carbon::parse($request->date)->format('Y-m-d'))->whereIn('status',[1,2])->where('pickup_point', $request->pickup_point)->where('dropping_point', $request->dropping_point)->whereJsonContains('seats', rtrim($request->seats, ","))->get();
        if($booked_ticket->count() > 0){
            $notify[] = ['error', 'Why are you choosing those seats which are already booked?'];
            return redirect()->back()->withNotify($notify);
        }

        $startPoint = array_search($trip->start_from , array_values($trip->route->stoppages));
        $endPoint = array_search($trip->end_to , array_values($trip->route->stoppages));
        if($startPoint < $endPoint){
            $reverse = false;
        }else{
            $reverse = true;
        }

        if(!$reverse){
            $can_go = ($source_pos < $destination_pos)?true:false;
        }else{
            $can_go = ($source_pos > $destination_pos)?true:false;
        }

        if(!$can_go){
            $notify[] = ['error', 'Select Pickup Point & Dropping Point Properly'];
            return redirect()->back()->withNotify($notify);
        }

        $route = $trip->route;
        $ticketPrice = TicketPrice::where('fleet_type_id', $trip->fleetType->id)
        ->where('vehicle_route_id', $route->id)
        ->where('company_id', $company_id)
        ->first();
        $sdArray     = [$request->pickup_point, $request->dropping_point];
        $getPrice    = $ticketPrice->prices()
                    ->where('source_destination', json_encode($sdArray))
                    ->where('company_id', $company_id)
                    ->orWhere('source_destination', json_encode(array_reverse($sdArray)))
                    ->first();
        if (!$getPrice) {
            $notify[] = ['error','Invalid selection'];
            return back()->withNotify($notify);
        }
        $seats = array_filter((explode(',', $request->seats)));
        $unitPrice = getAmount($getPrice->price);
        */
        $pnr_number = getTrx(10);
        //we set vat here  
        $vehicle   = Vehicle::findOrFail($id);
        $bookedTicket = new BookedTicket();
        $bookedTicket->user_id = auth()->user()->id;
       // $bookedTicket->gender = $request->gender;
        $bookedTicket->vehicle_id = $vehicle->id;
       // $bookedTicket->source_destination = [$request->pickup_point, $request->dropping_point];
        //$bookedTicket->pickup_point = $request->pickup_point;
        //$bookedTicket->dropping_point = $request->dropping_point;
        $days=$request->days;
        $unitPrice=$vehicle->cost;
        $bookedTicket->seats =1;
        $bookedTicket->status = 0;
        $bookedTicket->ticket_count =$days;
        $bookedTicket->unit_price = $unitPrice;
        $bookedTicket->sub_total = $days * $unitPrice;
        $bookedTicket->date_of_journey = Carbon::parse($request->date_of_journey)->format('Y-m-d');
        $bookedTicket->pnr_number = $pnr_number;
        //$bookedTicket->company_id=$company_id;
        $bookedTicket->save();
        session()->put('pnr_number',$pnr_number);
        return redirect()->route('user.deposit');
    }

    public function booksearch(Request $request)
    {
      
        $books =Book::with(['booktype','author']);
        if($request->booktype)
        {
        $books=$books->where('book_type',$request->booktype);
      
        }

        


        $pageTitle = 'Search Result';
        $emptyMessage = 'There is no data available';
        $fleetType = FleetType::active()->get();
        $schedules = Schedule::all();
        $routes = VehicleRoute::active()->get();
        $fleets = FleetType::active()->get();
        $booktypes = Booktype::orderBy('name','asc')->get();
        if(auth()->user()){
            $layout = 'layouts.master';
        }else{
            $layout = 'layouts.frontend';
        }
        $brands = Brand::orderBy('name','asc')->get();
        $steering = Steering::orderBy('name','asc')->get();
        $books=$books->paginate(getPaginate(10));
        return view($this->activeTemplate.'book', compact('pageTitle' ,'fleetType','routes',
         'schedules', 'emptyMessage', 'layout','brands','steering','fleets','booktypes','books'));
    }

    public function placeholderImage($size = null){
        $imgWidth = explode('x',$size)[0];
        $imgHeight = explode('x',$size)[1];
        $text = $imgWidth . '×' . $imgHeight;
        $fontFile = realpath('assets/font') . DIRECTORY_SEPARATOR . 'RobotoMono-Regular.ttf';
        $fontSize = round(($imgWidth - 50) / 8);
        if ($fontSize <= 9) {
            $fontSize = 9;
        }
        if($imgHeight < 100 && $fontSize > 30){
            $fontSize = 30;
        }

        $image     = imagecreatetruecolor($imgWidth, $imgHeight);
        $colorFill = imagecolorallocate($image, 100, 100, 100);
        $bgFill    = imagecolorallocate($image, 175, 175, 175);
        imagefill($image, 0, 0, $bgFill);
        $textBox = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth  = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);
        $textX      = ($imgWidth - $textWidth) / 2;
        $textY      = ($imgHeight + $textHeight) / 2;
        header('Content-Type: image/jpeg');
        imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);
        imagejpeg($image);
        imagedestroy($image);
    }


}
