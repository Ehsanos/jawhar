@extends('layout.adminLayout')
@section('title') {{__('cp.info')}}
@endsection
@section('css')
@endsection

@section('content')

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<body>

<div class="w3-container">
  <h2> <i class="fa fa-user" style="font-size:60px;color:orange;"></i> {{@$user->name}}</h2>
 <p> <i class="fa fa-phone" style="font-size:20px;color:orange;"></i> {{@$user->mobile}} <i class="fa fa-envelope" style="font-size:20px;color:orange;"></i> {{@$user->email}}  </p>
</div>

<div class="w3-bar w3-gray">
  <button class="w3-bar-item w3-button" onclick="openUser('history')">History</button>
  <button class="w3-bar-item w3-button" onclick="openUser('points')">Points</button>
</div>

<div id="history" class="w3-container user">
            <table class="table table-striped table-bordered table-hover table-checkable order-column" id="toolsTable">
                <thead>
                <tr>
                    <th>#</th>
                    <th> {{ucwords(__('cp.home_club'))}}</th>
                    <th> {{ucwords(__('cp.result'))}}</th>
                    <th> {{ucwords(__('cp.away_club'))}}</th>
                    <th> {{ucwords(__('cp.champion'))}}</th>
                    <th> {{ucwords(__('cp.guess'))}}</th>
                    <th> {{ucwords(__('cp.prize'))}}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($items as $item)
                    <tr class="odd gradeX" id="tr-{{$item->id}}">
                        <td> {{@$item->id}}</td>
                        <td> {{@$item->match->home_club_name}}
                        {{@$item->home_goals}}
                        </td>
                        <td> {{@$item->match->home_goals}} - {{@$item->match->away_goals}}  </td>
                        <td> {{@$item->match->away_club_name}}
                               {{@$item->away_goals}}

                        </td>
                        <td> {{@$item->match->champion_name}}</td>
                        <td> {{@$item->guess}}</td>
                        <td> {{@$item->match->winner_prize * $item->prize }}</td>
                    </tr>

                @empty
                    {{__('cp.no')}}
                @endforelse
                </tbody>
            </table>

</div>

<div id="points" class="w3-container user" style="display:none">
  <h2>points</h2>
  <p>{{@$monthPoints}}</p> 
</div>



@endsection

@section('js')
@endsection
@section('script')
<script>
function openUser(userName) {
  var i;
  var x = document.getElementsByClassName("user");
  for (i = 0; i < x.length; i++) {
    x[i].style.display = "none";  
  }
  document.getElementById(userName).style.display = "block";  
}
</script>@endsection
