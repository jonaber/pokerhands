@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">PokerHands Results</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    Hi {{Auth::user()->name}}, Tournament finished and below are the results:
                </div>
                <hr>
                <div class="row display-1" style="text-align:center">
                    <div class="col-md-12 center">
    					{{Auth::user()->name}} is the WINNER!!
                    </div>
                </div>
                
                
                <hr>
                <div style="margin-left:25px;">
                    <div class="row justify-content-center">
                        <div class="col-md-12 center">
        					<h3>Statistics:</h3>
                        </div>
                    </div>
                    <hr>
                    <div class="row justify-content-center">
                        <div class="col-md-6 center">
        					<h3>{{Auth::user()->name}}</h3>
        					<hr>
        					Total Rounds Played: {{$total_rounds}}<br>
        					Winnings: {{$player_1_wins}}<br>
        					Losses: {{$player_1_losses}}<br>
        					Draws: {{$player_1_draws}}<br>
                        </div>
                        
                        <div class="col-md-6 center">
        					<h3>Guest</h3>
        					<hr>
        					Total Rounds Played: {{$total_rounds}}<br>
        					Winnings: {{$player_2_wins}}<br>
        					Losses: {{$player_2_losses}}<br>
        					Draws: {{$player_2_draws}}<br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
