@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">PokerHands Lobby</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    Hi {{Auth::user()->name}}, You are logged in and can play PokerHands!
                </div>
                <hr>
                <div class="card-body">
                    @if (\Session::has('success'))
                        <div class="alert alert-success"  style="text-align:center;">
							{{ \Session::get('success') }}
                        </div>
                        <br><hr>
                        <div class="links" style="text-align:center;">
                            <a class="btn btn-primary btn-lg" href="/play">PLAY</a>
                        </div>
                        @else
                        <form method="POST" action="/fileupload" enctype="multipart/form-data" style="text-align:center;">
                        @csrf
                            <div class="form-group">
                                <input type="file" name="cardhand"></input>
                                <button type="submit" class="btn btn-primary">Upload File</button>
                            </div>
                        </form> 
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
