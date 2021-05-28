@extends('frontend.layouts.app')


@section('after-scripts')
<script>
    if ('serviceWorker' in navigator && 'PushManager' in window) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/service-worker.js').then(function(registration) {
                //navigator.serviceWorker.register('/sw.js').then(function(registration) {
                    // Registration was successful
                    console.log('ServiceWorker registration successful with scope: ', registration.scope);
                }, function(err) {
                    // registration failed :(
                    console.log('ServiceWorker registration failed: ', err);
                });
            });
        }

</script>
@endsection




@section('content')

    <div class="row">
        <div class="col-md-2 col-md-offset-5 col-xs-4 col-xs-offset-4">
            <a href="{{ route('frontend.index_home') }}" class="thumbnail" title="Strails">
                <img src="{{ secure_asset('img/logo/logo468_t.png') }}" alt="Logo">
            </a>
        </div>
    </div>



    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Strails - guida e suggerimenti</h3>
            </div>
            <div class="panel-body">
                <ul>
                    <li><a class="btn btn-sm btn-primary" target="_blanc" href="https://www.youtube.com/watch?v=Wv71IQIUBWA">
                        <i class="fa fa-youtube" aria-hidden="true"></i>
                         Guarda la nostra Video guida
                    </a>
                </li>
                </ul>
                <h3>
                    Da oggi Strails può essere una comoda app...
                    vedi i seguenti esempi per capire come trasformare Strails in una app
                </h3>
                <ul>
                    <br>
                    <li><a class="btn btn-sm btn-primary" target="_blanc" href="https://www.youtube.com/watch?v=7itm4HteOQ8">
                        <i class="fa fa-youtube" aria-hidden="true"></i>
                        Trasforma Strails in una app - per IOS Iphone
                    </a>
                    </li>
                    <br>
                    <li>
                        <a class="btn btn-sm btn-primary" target="_blanc" href="https://www.youtube.com/watch?v=n3TjhNblvqs">
                            <i class="fa fa-youtube" aria-hidden="true"></i>
                            Trasforma Strails in una app - per Android - Chrome
                        </a>
                    </li>
                </ul>

                <h3>Come funziona e come si usa Strails</h3>
                <p>
                L'applicazione Strails ha un funzionamento molto semplice. </p>
                <p>
                Il suo scopo è quello di poter descrivere lo stato di un sentiero o di un segmento Strava. </p>
                <p>
                Gli utenti di Strails possono conoscere quasi in tempo reale le condizioni di un tragitto e decidere quali sentieri percorrere, preventivamente consapevoli e informati
                su eventuali pericoli presenti nel tracciato scelto. Queste informazioni, condivise in modo social su Strails, permettono
                quindi di migliorare la sicurezza degli utenti e di poter scegliere il percorso più divertente e nelle migliori condizioni.</p>
                <p>
                Inoltre anche i trail builder potranno intervenire in modo più preciso e puntuale per il ripristino dei sentieri grazie alle
                segnalazioni di Strails. Strails necessità di una banale registrazione con email e password oppure del login con il proprio
                account Google, Facebook o Strava. La registrazione consente di commentare i soli sentieri presenti su openstreetmap. </p>
                <p>
                Cliccando anche su "Connect with Strava" si può accedere ai propri dati ed a tutti i segmenti Strava.
                La connessione con Strava permette di poter recuperare le proprie attività e visualizzarle su una mappa (in arancione), contestualmente appaiono anche i sentieri
                OSM (in blu) ed i segmenti Strava (in rosso). Gli ultimi due potranno quindi essere più facilmente individuabili per essere
                "commentati" cliccandoci sopra. </p>
                <p>Per individuare un sentiero e poterlo "commentare", è anche possibile scorrere semplicemente
                la mappa cliccando su "apri e scorri la mappa". Zommando fino alla zona di interesse, oppure cliccando sull'icona in alto
                a sinistra per zommare sulla posizione corrente, si possono visualizzare tutti i sentieri cliccabili. Ogni utente può anche
                inserire un sentiero o un segmento nei propri preferiti per poterlo consultare e commentare più agevolmente. Tutto ciò che
                è nei preferiti può avere colore celeste (se non ha mai avuto commenti), verde (se l'ultimo commento è positivo), giallo
                (se l'ultimo commento è un avviso) o rosso (se l'ultimo commento segnala un pericolo o problema grave). Il colore permette
                quindi una rapida valutazione. </p>
                <p>Strails è in continuo aggiornamento.
                Ogni volta che ho un po' di tempo libero cerco di aumentarne funzionalità e semplicità d'uso. Ogni consiglio e suggerimento sarà prezioso per renderlo migliore.
                </p>

            </div>
        </div>

    </div>

    <div class="row text-center">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">Inizia ad usare Strails</h3>
            </div>
            <div class="panel-body">
                <div class="col-md-6">
                    <a class="btn btn-sm btn-info" href="{{ route('frontend.index_home') }}" title="Strails Login">Vai alla pagina principale</a>
                </div>
                <div class="col-md-6">
                    <a class="btn btn-sm btn-info" href="{{ route('frontend.auth.login') }}" title="Strails Login">Esegui il login</a>
                </div>

            </div>
        </div>
    </div>


    @include('includes.partials.stat')

@endsection
