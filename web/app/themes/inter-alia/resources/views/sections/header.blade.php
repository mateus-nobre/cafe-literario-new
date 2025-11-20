<header class="header-principal">
    <div class="menu-principal-container">
        <button class="menu-toggle md:hidden" type="button" aria-expanded="false" aria-controls="menu-principal">
            <span class="sr-only">Abrir menu</span>
            <span class="linha"></span>
            <span class="linha"></span>
        </button>

        @if (has_nav_menu('main_menu'))
            <nav id="menu-principal" class="menu-principal hidden md:block"
                aria-label="{{ wp_get_nav_menu_name('main_menu') }}">
                {!! wp_nav_menu([
                    'theme_location' => 'main_menu',
                    'menu_class' => 'nav',
                    'link_class' => 'btn',
                    'walker' => new \App\Walkers\MenuWalker(),
                    'echo' => false,
                ]) !!}
            </nav>
        @endif

        <a href="#" class="menu-principal-contato btn">
            Contato
        </a>
    </div>
</header>
