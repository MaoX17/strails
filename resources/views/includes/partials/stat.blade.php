<div class="row text-center">
    
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title"><b>I nostri numeri:</b></h3>
        </div>
        <div class="panel-body">
            <div class="col-sm-6">
                Utenti registrati: <span class="badge">{{ \App\Models\Access\User\User::count() }}</span>
            </div>
            <div class="col-sm-6">
                Valutazioni presenti: <span class="badge">{{ \App\Models\Map\Evaluation::count() }}</span>
            </div>  
        </div>
    </div>
</div>