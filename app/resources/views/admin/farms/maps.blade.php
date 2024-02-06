@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-md-12">
            <div class="card b-radius--10 ">
                <div class="card-body">
                    <div class="table-responsive--sm table-responsive">
                      <div id="map" style="width: 100%; height: 600px;"></div>
                      <input type="hidden" value="{{($json)}}" class="maps"/>
                    </div>
                </div>

               
            </div>
        </div>
    </div>

@endsection

@push('breadcrumb-plugins')
    <a href="javascript:void(0)" class="btn btn-sm btn--primary box--shadow1 text--small addBtn"><i class="fa fa-fw fa-plus"></i>@lang('Add New')</a>
@endpush

@push('script')
    <script>
let map;

async function initMap() {
  // The location of Uluru
  const position = { lat:2.8185, lng:31.8639 };
  // Request needed libraries.
  //@ts-ignore
  const { Map } = await google.maps.importLibrary("maps");
  const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

  // The map, centered at Uluru
  map = new Map(document.getElementById("map"), {
    zoom: 10,
    center: position,
    mapId: "DEMO_MAP_ID",
  });

  var data=$(".maps").val();
            var json=JSON.parse(data);
           
            $.each(json, function(i, item) {
    const infoWindow = new google.maps.InfoWindow({
    content: "",
    disableAutoPan: true,
  });

  const beachFlagImg = document.createElement("img");
  var iconpath="http://maps.google.com/mapfiles/ms/icons/blue-dot.png";
  if(item.started==0)
  {
    iconpath="http://maps.google.com/mapfiles/ms/icons/red-dot.png";
  }
  
beachFlagImg.src =iconpath;
 // "https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png";


    const marker = new AdvancedMarkerElement({
    map: map,
    position: { lat:item.latitude, lng:item.longitude },
    title: item.farm,
   content:beachFlagImg
  });
// markers can only be keyboard focusable when they have click listeners
    // open info window when marker is clicked
    marker.addListener("click", () => {
        if(item.started==0)
  {
    infoWindow.setContent(item.farm + ", " + item.user+" Started On "+item.created_at);
      
  }
  else{
    infoWindow.setContent(item.farm + ", " + item.user+" Started "+item.created_at +" Finished "+item.finishedon);
      
  }
      infoWindow.open(map, marker);
    });

    



});

  // The marker, positioned at Uluru
  
}
initMap();
        (function ($) {
            "use strict";


        })(jQuery);

    </script>
    <script>
        (g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({
          key: "",
          v: "weekly",
          // Use the 'v' parameter to indicate the version to use (weekly, beta, alpha, etc.).
          // Add other bootstrap parameters as needed, using camel case.
        });
      </script>
      

@endpush
