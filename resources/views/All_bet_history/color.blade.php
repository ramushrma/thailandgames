@extends('admin.body.adminmaster')

@section('admin')

<div class="container-fluid mt-5">
  <div class="row">
<div class="col-md-12">
  <div class="white_shd full margin_bottom_30">
	  <div class="full graph_head">
		  <div class="heading1 margin_0" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
			  <div style="display: flex; align-items: center;">
				  <h2>Total Bet</h2>
				  <span style="margin-left: 10px; font-weight: bold;">- {{$total_bet}}</span>
			  </div>
			  <h2>Bet History</h2>
		  </div>
	  </div>

     <div class="table_section padding_infor_info">
        <div class="table-responsive-sm">
           <table id="example" class="table table-striped" style="width:150%"> 
              <thead class="thead-dark">
                 <tr>
                    <th>id</th>
					<th>user name</th>
                    <th>amount</th>
                    <th>commission</th>
                    <th>trade_amount</th>
                    <th>win_amount</th>
					 <th>Bet number</th>
					 <th>win_number</th>
					  <th>games_no</th>
					 <th>order_id</th>
					 <th>status</th>
					 <th>Date Time</th>
                 </tr>
              </thead>
              <tbody>
                @foreach($bets as $item)
                 <tr>
					<td>{{$item->id}}</td>
					<td>{{$item->username}}</td>
                    <td>{{$item->amount}}</td>
                    <td>{{$item->commission}}</td>
                    <td>{{$item->trade_amount}}</td>
                    <td>{{$item->win_amount}}</td>
					 <td>{{$item->number}}</td>
					 <td>{{$item->win_number}}</td>
					  <td>{{$item->games_no}}</td>
					 
					 <td>{{$item->order_id}}</td>
					 <td>{{$item->status}}</td>
					 <td>{{$item->created_at}}</td> 
                 </tr>
                 @endforeach
              </tbody>
           </table>
			
			<nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center">
        <li class="page-item {{ $bets->onFirstPage() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $bets->url(1) }}" aria-label="First">
                <span aria-hidden="true">&laquo;&laquo;</span>
            </a>
        </li>
        <li class="page-item {{ $bets->onFirstPage() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $bets->previousPageUrl() }}" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>

        @php
            $half_total_links = floor(9 / 2);
            $from = $bets->currentPage() - $half_total_links;
            $to = $bets->currentPage() + $half_total_links;

            if ($bets->currentPage() < $half_total_links) {
                $to += $half_total_links - $bets->currentPage();
            }

            if ($bets->lastPage() - $bets->currentPage() < $half_total_links) {
                $from -= $half_total_links - ($bets->lastPage() - $bets->currentPage()) - 1;
            }
        @endphp

        @for ($i = $from; $i <= $to; $i++)
            @if ($i > 0 && $i <= $bets->lastPage())
                <li class="page-item {{ $bets->currentPage() == $i ? 'active' : '' }}">
                    <a class="page-link" href="{{ $bets->url($i) }}">{{ $i }}</a>
                </li>
            @endif
        @endfor

        <li class="page-item {{ $bets->hasMorePages() ? '' : 'disabled' }}">
            <a class="page-link" href="{{ $bets->nextPageUrl() }}" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
        <li class="page-item {{ $bets->currentPage() == $bets->lastPage() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $bets->url($bets->lastPage()) }}" aria-label="Last">
                <span aria-hidden="true">&raquo;&raquo;</span>
            </a>
        </li>
    </ul>
</nav>
			
			
			
        </div>
     </div>
  </div>
</div>
</div>
</div> 




@endsection