@extends('frontend.layouts.app')

@section('title', app_name() . ' | Login')

@section('content')

    <div class="row">

        <div class="col-md-8 col-md-offset-2">

            <div class="card">
                <div class="card-header">Login</div>

                <div class="card-body">


                    <div class="row text-center">
                        {!! $socialite_links !!}
                    </div>
<!--
                    {{ Form::open(['route' => 'auth.login.post', 'class' => 'form-horizontal']) }}

                    <div class="form-group">
                        {{ Form::label('email', 'Indirizzo e-mail', ['class' => 'col-md-4 control-label']) }}
                        <div class="col-md-6">
                            {{ Form::email('email', null, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'info@strails.it']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('password', 'Password', ['class' => 'col-md-4 control-label']) }}
                        <div class="col-md-6">
                            {{ Form::password('password', ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'MyPassword']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <div class="checkbox">
                                <label>
                                    {{ Form::checkbox('remember') }} Ricordami
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            {{ Form::submit('Login', ['class' => 'btn btn-primary', 'style' => 'margin-right:15px']) }}

                            {{ link_to_route('auth.password.reset', 'Password dimenticata?') }}
                        </div>
                    </div>

                    {{ Form::close() }}
-->

                </div><!-- card body -->

            </div><!-- card -->

        </div><!-- col-md-8 -->

    </div><!-- row -->

@endsection
