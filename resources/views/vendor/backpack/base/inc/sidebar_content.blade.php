<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li><a href="{{backpack_url('dashboard')}}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
<li><a href="{{backpack_url('product-home') }}"><i class="fa fa-list"></i> <span>Sản phẩm trang chủ</span></a></li>
<li><a href="{{backpack_url('article') }}"><i class="fa fa-file-text"></i> <span>QL Bài viết</span></a></li>
<li><a href="{{backpack_url('product') }}"><i class="fa fa-list-alt"></i> <span>QL Sản phẩm</span></a></li>
<li><a href="{{backpack_url('warehouse') }}"><i class="fa fa-square"></i> <span>QL Kho hàng</span></a></li>
<li><a href="{{backpack_url('manufacturer') }}"><i class="fa fa-th-list"></i> <span>QL Hãng sản xuất</span></a></li>
<li><a href="{{backpack_url('product-category') }}"><i class="fa fa-list-ul"></i> <span>QL Nhóm hàng</span></a></li>
<li><a href="{{backpack_url('partner') }}"><i class="fa fa-user-secret"></i> <span>QL Đối tác</span></a></li>
<li><a href="{{backpack_url('support') }}"><i class="fa fa-skype"></i> <span>QL Hỗ trợ tực tuyến</span></a></li>
<li><a href="{{backpack_url('slide-show') }}"><i class="fa fa-picture-o"></i> <span>QL Slide Show</span></a></li>
<li><a href="{{backpack_url('page') }}"><i class="fa fa-file"></i> <span>Trang tĩnh</span></a></li>
<li><a href="{{backpack_url('menu-item') }}"><i class="fa fa-list-ol"></i> <span>Menu Items</span></a></li>
<li><a href="{{backpack_url('setting') }}"><i class="fa fa-cog"></i> <span>Cài đặt</span></a></li>

<li class="header"></li>
<li>
    <a href="{{ route('backpack.auth.logout') }}"
       onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
        <i class="fa fa-sign-out"></i> <span>{{ __('Logout') }}</span>
    </a>
</li>