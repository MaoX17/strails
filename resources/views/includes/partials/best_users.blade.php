
<h4>I migliori utenti di sempre:</h4>
<table class="table">
    <thead>
        <tr>
            <th>Utente</th>
            <th>Valutazioni inserite</th>


        </tr>
    </thead>
    <tbody>

        @foreach ($best_users as $utente)

        <tr>
            <td>
                {{$utente->first_name}} {{$utente->last_name}}
            </td>
            <td>
                <span class="badge badge-secondary">
                    {{$utente->count_eval}}
                </span>

            </tr>
            @endforeach

        </tbody>
    </table>
