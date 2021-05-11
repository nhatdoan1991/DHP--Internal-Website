@extends('layouts.driverapp')

@section('content')

<link rel="stylesheet" href="{{asset('css/driver.css')}} ">
<header>
	<div class="driver-container">
		<a href="{{ route('driverprofile') }}" ><button class="btn-profile-driver">
			<?php echo strtoupper((substr(Auth::user()->firstname,0,1).substr(Auth::user()->lastname,0,1)))?> </button></a> 
		
		<form class="logoutButton" method="post" action="{{ route('logout') }}">
			<button type="submit" class="btn-logout-driver"><i class="fas fa-sign-out-alt"></i>Log Out</button>
			{{ csrf_field() }}
		</form>
			
	</div>		

		

</header>
<div class="driver-content">
	
	<div id="cPost">
		<h1><b class="DashTitle">Dashboard</b></h1>
		<h3 class="subDashTitle">Upcoming</h3>
		@foreach($upcoming as $group)

		<div>
			@if($group->order->contains('deliverystatus', 1))
			@if($group->deliverydate == $today)
			<button class="button-collapsible delivery-group delivery-group-available">

				@else
				<button class="button-collapsible delivery-group delivery-group-future">
					@endif
					<div class="group-heading-day">
						<span class="circle">
							{{date('D', strtotime($group->deliverydate))}}
						</span>
					</div>
					<div class="group-heading-date">
						{{date('m-d-Y', strtotime($group->deliverydate))}}
					</div>
					<div class="group-heading-orders">
						{{count($group->order)}} Orders
					</div>

				</button>


				@elseif($group->order->contains('deliverystatus', 2))
				<button class="button-collapsible delivery-group delivery-group-inprogress">
					<div class="group-heading-day">
						<span class="circle">
							{{date('D', strtotime($group->deliverydate))}}
						</span>
					</div>
					<div class="group-heading-date">
						{{date('m-d-Y', strtotime($group->deliverydate))}}
					</div>
					<div class="group-heading-orders">
						{{count($group->order)}} Orders
					</div>
				</button>

				@endif

				<div class="content-collapsible-driver">
					<div class="group-content" >
						<h2>{{count($group->order)}} Orders</h2>
						<section class="group-table">
							@foreach($group->order as $order)
							<div class="group-table-row">
								<div class="group-table-cell" style="text-align:left; width:40%">{{$loop->iteration}}. {{$order->customer->firstname}} {{$order->customer->lastname}}</div>
								<div class="group-table-cell">|</div>
								<div class="group-table-cell">{{@substr($order->customer->zipcode, 0,5)}}</div>
								<div class="group-table-cell">|</div>
								<div class="group-table-cell" style="text-align: right;">{{count($order->item)}} Bags</div>
							</div>

							@endforeach
						</section>



						@if($group->order->contains('deliverystatus', 2))

						<a href="{{url('/driver/'.$group->id)}}">
							<button id="btn-resume" class="button button-report"><i class="fas fa-play"></i> Resume</button>
						</a>

						@endif
						@if($group->order->contains('deliverystatus', 1))
						@if ($group->deliverydate == $today)

						<a href="{{url('/driver/'.$group->id)}}">
							<button class="button button-start button-red"><i class="fas fa-play"></i> Start</button>
						</a>
						@else
							<button class="button button-start button-disabled"><i class="fas fa-play"></i> Start</button>
						@endif
			</button>
			@endif

		</div>


	</div>
</div>

@endforeach
<h3 class="subDashTitle">History</h3>
@foreach($completed as $group)

	<div>

		<label class="delivery-group delivery-group-completed">
			<div class="group-heading-day">
				Completed
			</div>
			<div class="group-heading-date">
				{{date('m-d-Y', strtotime($group->deliverydate))}}
			</div>
			<div class="group-heading-orders">
				{{count($group->order)}} Orders
			</div>
		</label>


		
	</div>

@endforeach
</div>
</div>
<script type="text/javascript">

</script>
@endsection('content')