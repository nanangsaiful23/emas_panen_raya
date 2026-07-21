<style>
  .sidebar, li, span, i, a
  {
    color:  black;
    /*border-radius: 0px 10px 10px 0px;*/
  }

  .treeview-menu li, .treeview-menu li a {
    word-wrap: break-word;
    white-space: normal;
  }

  .header
  {
    background-color: #DDDDDD;
    font-weight: 500;
  }

  .treeview-menu .active a
  {
    background-color: #89CFFD;
  }
</style>

<aside class="main-sidebar" style="border-right: 0.5px; border: solid #DDDDDD;">
  <section class="sidebar">
    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">MENU UTAMA</li>
      @if($role == 'admin' || \Auth::user()->role == 'penjualan_cashier') 
        <li class="treeview {{ (Request::segment(2) == 'by-order-transaction' ) ? 'active' : ''  }}">
          <a href="#">
              <i class="fa fa-superpowers"></i><span> Pesanan Emas</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
              <li class="{{ Request::segment(2) == 'by-order-transaction' && Request::segment(3) == 'create' ? 'active' : ''  }}"><a href="{{ url('/' . $role . '/by-order-transaction/create') }}"><i class="fa fa-circle-o"></i> Tambah Pesanan</a></li>
              <li class="{{ Request::segment(2) == 'by-order-transaction' && Request::segment(3) != 'create' ? 'active' : ''  }}"><a href="{{ url('/' . $role . '/by-order-transaction/' . date('Y-m-d') . '/' . date('Y-m-d') . '/20') }}"><i class="fa fa-circle-o"></i> Daftar Pesanan</a></li>
          </ul>
        </li>
      @endif
    </ul>
  </section>
</aside>
