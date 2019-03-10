<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">

<!-- Sidebar user panel (optional) -->
<div class="user-panel">
  <div class="pull-left image">
    <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
  </div>
  <div class="pull-left info">
    <p>Alexander Pierce</p>
    <!-- Status -->
    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
  </div>
</div>

<!-- Sidebar Menu -->
<ul class="sidebar-menu" data-widget="tree">
  <li class="header">MENUS</li>
  <!-- Optionally, you can add icons to the links -->
  <li class="{{Route::getCurrentRoute()->uri == 'rincian-kegiatan' ? 'active' : ''}}"><a href="/rincian-kegiatan"><i class="fa fa-file"></i> <span>Rincian Kegiatan</span></a></li>
  <li class="{{Route::getCurrentRoute()->uri == 'tambah-kegiatan' ? 'active' : ''}}"><a href="/tambah-kegiatan"><i class="fa fa-plus"></i> <span>Tambah Kegiatan</span></a></li>
</ul>
<!-- /.sidebar-menu -->
</section>
<!-- /.sidebar -->