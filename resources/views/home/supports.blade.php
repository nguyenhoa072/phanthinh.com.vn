<?php
$supports = \App\Helpers\General::get_supports();
if ($supports) {
?>
<h5 class="box-title mb-4"><span>Hỗ trợ trực tuyến </span></h5>
<div class="row box-card">
    @foreach($supports as $item)
    <div class="col-6">
        <div class="media mb-3">
            <div class="media-body">
                <div class="row">
                    <div class="col-lg-12 col-auto"><a href="skype:{{$item['account']}}?chat" class="link-skype hvr-buzz-out"><i
                                    class="fab fa-skype fa-3x"></i></a></div>
                    <div class="col-lg-12 col">
                        <a href="skype:{{$item['account']}}?chat" class="link-skype hvr-buzz-out">{{$item['name']}}</a>
                        <br>({{$item['department']}})
                        <br>{{$item['phone']}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
<?php } ?>