<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="/home">
                <i class="bi bi-house-door"></i> Home
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="/emplois">
                            <i class="bi bi-briefcase"></i> Emplois
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/employer/dashboard">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/uapplications">
                            <i class="bi bi-envelope"></i> Status
                        </a>
                    </li>
                </ul>
                <a href="{{ route('profile') }}" class="btn btn-success">
                    <i class="bi bi-person-circle"></i> View Profile
                </a>
            </div>
        </div>
    </nav>
</header>
