@php
    $aboutContent = getContent('about.content', true);
@endphp

<section class="about-section padding-top padding-bottom">
    <div class="container">
        <div class="row mb-4 mb-md-5 gy-4">
            <div class="col-lg-7 col-xl-6">
                <div class="about-content">
                    <div class="section-header">
                        <h2 class="title">{{ __(@$aboutContent->data_values->heading) }}</h2>
                    </div>
                    <p>
                        @php
                            echo @$aboutContent->data_values->short_description
                        @endphp
                    </p>
                </div>
            </div>
          
        </div>
      
    </div>
</section>
