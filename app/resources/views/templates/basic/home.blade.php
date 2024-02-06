@extends($activeTemplate.'layouts.frontend')
@section('content')
    @php
        $content = getContent('sign_in.content', true);
    @endphp
    <!-- Account Section Starts Here -->
    <section class="account-section bg_img" 
    style="background: url({{getImage('assets/images/frontend/sign_in/crm.webp', "1920x1280") }}) bottom right;">
  
    <div style="position: absolute; margin-top:30%;text-align: center;margin-left:38%;">
            <h1 class="title" style="color: white;align-content: center;text-align: center; ">
                Inventory Mobi</h1>
                <h4 class="title" style="color: white;align-content: center;text-align: center; ">
                   Mobile powered inventory system</h4>
    </div>
    </section>
    <!-- Account Section Ends Here -->
@endsection

