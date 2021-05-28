<nav class="navbar navbar-light bg-light navbar-expand-md">
    <div class="container">
        <button type="button"
            class="navbar-toggler collapsed"
            data-toggle="collapse"
            data-target="#frontend-navbar-collapse">
                <span class="sr-only">Menu Navigazione</span>
            &#x2630;
        </button>

            {{ link_to_route('index_home', app_name(), [], ['class' => 'navbar-brand', 'rel' => 'noreferrer']) }}

        <!--navbar-header-->
        <div class="collapse navbar-collapse ml-auto" id="frontend-navbar-collapse">
            <ul class="navbar-nav ml-auto">
                @if (! $logged_in_user)
                    <li class="nav-item">{{ link_to_route('auth.login', 'Login', [], ['class' => 'nav-link' ]) }}</li>
                @else
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" title="Strails">
                            {{ $logged_in_user->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li class="dropdown-item">{{ link_to_route('auth.logout', 'Logout', [], ['class' => 'nav-link' ]) }}</li>
                        </ul>
                    </li>
                @endif

                <li class="nav-item">{{ link_to_route('guide', 'Guida', [], ['class' => 'nav-link' ]) }}</li>
                <li class="nav-item">{{ link_to_route('contact', 'Contatti', [], ['class' => 'nav-link' ]) }}</li>
            </ul>
        </div>
        <!--navbar-collapse-->
    </div>
    <!--container-->
</nav>

