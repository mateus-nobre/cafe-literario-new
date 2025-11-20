<header class="header-principal">
  @if (has_nav_menu('main_menu'))
    <nav class="menu-principal" aria-label="{{ wp_get_nav_menu_name('main_menu') }}">
      {!! wp_nav_menu(['theme_location' => 'main_menu', 'menu_class' => 'nav', 'echo' => false]) !!}
    </nav>
  @endif
</header>
