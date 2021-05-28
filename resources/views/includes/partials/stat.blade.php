

    <div class="card border-primary text-center">
        <div class="card-header">
            <h3><b>I nostri numeri:</b></h3>
        </div>
        <div class="card-body">
            <div class="row">
            <div class="col-sm-6">
                Utenti registrati: <span class="badge badge-primary">{{ \App\Models\Access\User\User::count() }}</span>
            </div>
            <div class="col-sm-6">
                Valutazioni presenti: <span class="badge badge-primary">{{ \App\Models\Map\Evaluation::count() }}</span>
            </div>
        </div>
        </div>
    </div>
