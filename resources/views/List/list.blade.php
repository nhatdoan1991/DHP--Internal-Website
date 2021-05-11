@extends ('layouts.app')

@section('content')	
	<div class="container content">
		<h2 class="content-heading">Order List</h2>

		<!-- button to create a new custom order -->
    	<button type="button" class="btn btn-primary">
			<a href="{{url('list/create')}}"><i class="fa fa-plus"></i> New Order</a>
		</button>

		<!-- filter bar -->
		<div class="search-group">
			<input type="text" id="searchInput" onkeyup="filterTable()"
         		placeholder="Search" title="Type in a order" />
			<i class="fa fa-search"></i>
		</div>

		<!-- creating a table to display all orders with status 1/list -->
		<table class="order-list" id="searchTable"> 
			<tr>
				<th>Order Number</th>
				<th>Name</th>
				<th>Item</th>
				<th>Address</th>
				<th>Delivery Date</th>
				<th>Action</th>
			</tr>
			@foreach($data as $customer)

				
			<tr>
				<td>
					<!-- displaying order details when hover over order number -->
					<div class="dropdown">
						<span><u>{{$customer->id}}</u><span>
						<div class="dropdown-content">
							<div><b>Order #:</b> {{$customer->id}}</div>
							<div><b>Customer ID #:</b> {{$customer->customer->id}}</div>
							<div><b>Name: </b>	{{$customer->customer->firstname}} {{$customer->customer->lastname}} </div>
							<div><b>Email: </b>{{$customer->customer->email}}</div>
							<div><b>Address: </b>{{$customer->customer->address}}</div>
							<div><b>City: </b>{{$customer->customer->city}}</div>
							<div><b>Zipcode: </b>{{$customer->customer->zipcode}}</div>
							<div><b>State: </b>{{$customer->customer->state}}</div>
							<div><b>Delivery Date:</b> {{$customer->deliverydate}}</div>
							<div><b>Delivery instruction: </b>{{$customer->deliveryinstruction}}</div>
							<div><b>Items: </b> </div>

							<!-- displaying all items in an order -->
							@foreach($customer->item as $item)
								{{$item->itemname}} x {{$item->itemquantity}}<br>
							@endforeach
						</div>
					</div> 
				</td>

				<!-- displaying customer first and last name -->
				<td>{{$customer->customer->firstname}} {{$customer->customer->lastname}}</td>
				
				<!-- displaying all items in an order -->
				<td>
					@foreach($customer->item as $item)
								{{$item->itemname}} x {{$item->itemquantity}}<br>
					@endforeach
				</td>

				<!-- displaying order delivery zipcode -->
				<td>{{$customer->customer->city}}, {{@substr($customer->customer->zipcode, 0,5)}}</td>

				
				<td>{{date('m-d-Y', strtotime($customer->deliverydate))}}</td>
				<td>

					<!-- a button to push the order into the queue/2 -->
					<button class="btn btn-primary">
						<a href="{{url('/push/'.$customer->id)}}"><i class="fa fa-arrow-up"></i> Push</a>
					</button>

					<!-- a button to edit an order -->
					<button class="btn btn-warning">
						<a href="{{ url('list/' . $customer->id . '/edit') }}"><i class="fa fa-edit"></i> Edit</a>
						
					</button>
				</td>
			</tr>
			@endforeach <!-- end of order list foreach -->
		</table> <!-- end of table -->
			
			<!-- creating a pagination select -->
			<div class="paginate">
				<div class="paginate-select">
					<form>
						Show
						<select id="pagination">
							<option value="5"  @if($items == 5) selected @endif >5</option>
							<option value="10" @if($items == 10) selected @endif >10</option>
							<option value="25" @if($items == 25) selected @endif >25</option>
							<option value="50" @if($items == 50) selected @endif >50</option>
						</select>
						entries
					</form>
				</div>
				<div class="paginate-bar">
					<!-- displaying to buttons to go to next page or back -->
			
				{{$data->links()}}
				</div>
				

			</div>
			
			
			
	</div> 
    
    <!-- script to get what pagination select option was selected -->
    <script>
        document.getElementById('pagination').onchange = function() {

            window.location = "{{route('list')}}?page=1&items=" + this.value;
        };
    </script>

@endsection