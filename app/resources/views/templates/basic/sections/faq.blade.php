@php
$faqContent = getContent('faq.content', true);
$faqElements = getContent('faq.element', false, null, true);
@endphp


<section class="faq-section padding-top padding-bottom">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="section-header text-center">
                    <h2 class="title">{{ __(@$faqContent->data_values->heading) }}</h2>
                    <p>Frequesntly Asked Question</p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="faq-wrapper">
                   
                    <div class="faq-item">
                        <div class="faq-title">
                            <span class="icon"></span>
                            <h5 class="title">Can i board before paying</h5>
                        </div>
                        <div class="faq-content">
                            <p>No all passengers must have their tickets confirmed before boarding</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-title">
                            <span class="icon"></span>
                            <h5 class="title">How about if forget my ticket code</h5>
                        </div>
                        <div class="faq-content">
                            <p>We can retrieve your ticket details by serching using yout telephone number</p>
                        </div>
                    </div>
                  
                </div>
            </div>
           
        </div>
    </div>
</section>
