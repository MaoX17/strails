                    <h4>I migliori utenti di sempre:</h4>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Utente</th>
                                    <th>Valutazioni</th>
                                    <th>Stelle</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                    
                                @foreach ($users as $utente)
                                
                                <tr>
                                    <td>
                                        {{ $utente->name}}
                                    </td>
                                    <td> 
                                        {{$utente->evaluations()->count()}} </span>
                                    <td>
                                        
                                    </td>
                                    
                                </tr>
                                @endforeach

                            </tbody>
                        </table>