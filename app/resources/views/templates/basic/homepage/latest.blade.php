<div class="row">  
    @foreach ($booktypes as $type)
    <div class="col-sm-2">
        <form action="{{ route('search') }}" class="row g-3 justify-content-left m-0">
            <button class="btn_home" type="submit" name="booktype" value="{{$type->id}}">{{$type->name}}</button>
        </form>

    </div>
    @endforeach
</div>
<!-- Working Process Section Starts Here -->
<section class="working-process book_section white_bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-10">
                <div class="section-header text-center">
                    <h2 class="title">Latest Books</h2>
                   
                </div>
            </div>
        </div>
            <div class="row">
            @foreach ($books as $item)
                <div class="col-lg-2 col-md-2 col-sm-2">
                    <div class="bookdiv">
                  <a  href="{{ route('ticket.seats',
                    [$item->id, slug($item->title)]) }}">
                                <img alt="." height="150" class="img-responsive bookcover"
                                width="100%" src="{{asset('storage/')."/".$item->logo}}"
                              />
                              
                          
                                <h6 class="bus-name dark_blue">{{ __($item->title)}}</h6>
                                <p class="red_text">{{ __($item->booktype["name"]) }}</p>
                                <i>{{ __($item->author["name"]) }}</i>
                              
                           
                  </a>
                    </div>
                </div>
            @endforeach
            <div class="card-footer py-4">
                {{ paginateLinks($books) }}
            </div>
            </div>
               
 

    </div>
</section>
<!-- Working Process Section Ends Here -->


