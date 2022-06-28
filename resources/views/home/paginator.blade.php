<nav aria-label="Page navigation" class="mt-3">
    <ul class="pagination justify-content-center">
        <li class="page-item">
            <a class="page-link" href="{{$objects['prev_page_url']}}">Trước</a>
        </li>
        <?php
        $from = $objects['current_page']==1?1 : $objects['current_page'] - 1;

        if($from == 1){
            $to     =  $objects['current_page'] + 2;
        }else{
            $to     = $objects['current_page'] + 1;
        }

        if($to > $objects['last_page'])
            $to = $objects['last_page'];

        for($i = $from; $i<= $to;$i++ ){
        ?>
        <li class="page-item <?=$i==$objects['current_page']?'active':''?>"><a class="page-link" href="?page={{$i}}<?=!empty($link_paginate)?'&'.$link_paginate:''?>">{{$i}}</a></li>
        <?php } ?>

        <li class="page-item">
            <a class="page-link" href="{{$objects['next_page_url']}}">Sau</a>
        </li>
    </ul>
</nav>