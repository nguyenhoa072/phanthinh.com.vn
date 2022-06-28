<?php
$partners = \App\Helpers\General::get_partners();
if ($partners) {
?>
@foreach($partners as $item)
    <div class="col-sm-3 col-6 mt-4">
        <a href="{{$item['link']}}" class="h-100 d-flex box-partner d-block hvr-grow-shadow" title="{{$item['name']}}">
            <div class="align-self-center">
                <img class="img-fluid" src="{{$item['image_url'].$item['image']}}" alt=""></div>
        </a>
    </div>
@endforeach
<?php } ?>