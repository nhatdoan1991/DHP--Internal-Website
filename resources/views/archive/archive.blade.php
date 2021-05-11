@extends ('layouts.app')
@section('content')
<link rel="stylesheet" href="{{asset('css/archive.css')}} ">
<div class="container content">
    <h2 class="content-heading">Archives</h2>
    <div>
        <!-- button to filter for today's completed/unfufilled orders -->
        <button class="btn btn-primary">
            <a href="{{route('archive')}}"><i class="fas fa-calendar-day"> Today</i></a>
        </button>

        <!-- button to filter for the last week's completed/unfufilled orders -->
        <button class="btn btn-primary">
            <a href="{{url('/archive/7')}}"><i class="fas fa-calendar-week"> Week</i></a>
        </button>

        <!-- button to filter for the last month's completed/unfufilled orders -->
        <button class="btn btn-primary">
            <a href="{{url('/archive/30')}}"><i class="fas fa-calendar-alt"> Month</i></a>
        </button>

        <!-- button to filter for the last year's completed/unfufilled orders -->
        <button class="btn btn-primary">
            <a href="{{url('/archive/365')}}"><i class="fas fa-calendar"> Year</i></a>
        </button>

        <!-- button to display all completed/unfufilled orders -->
        <button class="btn btn-primary">
            <a href="{{ url('/archive/500')}}"><i class="fas fa-layer-group"> All</i></a>
        </button>

        <!-- database keyword search feature 
        <div class="search-group">
			<input type="text" id="searchInput" onkeyup="filterTable()"
         		placeholder="Search" title="Type in a order" />
			<i class="fa fa-search"></i>
		</div>
        -->
        <div class="search-keyword">
            <form action="/search" method="get">
                @csrf
                <input type="search" name="search" placeholder="Enter keyword...">
                <span class="search-input button">
                    <button type="submit" class="btn btn-primary search-button">Search</button>
                </span>

            </form>
        </div>


    </div>

    <!-- displaying all the filtered groups -->

    @foreach($groups as $group)
    @if(!$searched)

    <div>
        <!-- a collapsible button for each group with group name -->
        <button class="button button-collapsible archive-group-heading">{{$group->groupname}}</button>
        <div class="content-collapsible">
            <table class="order-archive">
                <thead>
                    <th>Order Number</th>
                    <th>Name</th>
                    <th>Item</th>
                    <th>Address</th>
                    <th>Delivery Date</th>
                    <th>Last Updated</th>
                    <th>Status</th>
                </thead>
                <tbody>
                    <!-- displaying all orders in each group -->
                    @foreach($group->order as $order)

                    <tr>
                        <!-- displaying order id -->
                        <td>{{ $order->id}} </td>

                        <!-- displaying the customer's first and last name -->
                        <td>{{ $order->customer->firstname }} {{ $order->customer->lastname }}</td>
                        <td>
                            <!-- displaying all items in each order -->
                            @foreach($order->item as $item)
                            {{ $item->itemname }} x {{ $item->itemquantity }}
                            <br>
                            @endforeach
                        </td>

                        <!-- displaying the delivery zipcode -->
                        <td>{{$order->customer->city}}, {{@substr($order->customer->zipcode, 0,5)}}</td>

                        <!-- displaying the order's delivery date -->
                        <td>{{date('m-d-Y', strtotime($group->deliverydate))}}</td>

                        <!-- displaying the order's last updated time -->
                        <!-- can also be printed like {$order->updated_at->diffForHumans()}} -->
                        <td>{{$order->updated_at->format('m-d-Y H:i:s')}}</td>
                        
                        <!-- checking status to determine how to display it -->
                        @if($order->deliverystatus == 4)
                        <td class="status completed"><i class="fa fa-check"></i> Completed</td>
                        @else
                        <td class="status unfulfilled"><i class="fa fa-times"></i> Unfulfilled</td>
                        @endif
                    </tr>
                    @endforeach
                    <!--end of foreach order  -->
                </tbody>
            </table> <!-- end of table -->
        </div>
        <br>
    </div>
    @else
    <div>
        <!-- a collapsible button for each group with group name -->

        <button class="button button-collapsible archive-group-heading">{{ $group->first()->grouporder->groupname }}</button>
        <div class="content-collapsible">
            <table class="order-archive">
                <thead>
                    <th>Order Number</th>
                    <th>Name</th>
                    <th>Item</th>
                    <th>Address</th>
                    <th>Delivery Date</th>
                    <th>Last Updated</th>
                    <th>Status</th>
                </thead>
                <tbody>
                    <!-- displaying all orders in each group -->
                    @foreach($group as $order)

                    <tr>
                        <!-- displaying order id -->
                        <td>{{ $order->id}} </td>

                        <!-- displaying the customer's first and last name -->
                        <td>{{ $order->customer->firstname }} {{ $order->customer->lastname }}</td>
                        <td>
                            <!-- displaying all items in each order -->
                            @foreach($order->item as $item)
                            {{ $item->itemname }} x {{ $item->itemquantity }}
                            <br>
                            @endforeach
                        </td>

                        <!-- displaying the delivery zipcode -->
                        <td>{{$order->customer->city}}, {{@substr($order->customer->zipcode, 0,5)}}</td>

                        <!-- displaying the order's delivery date -->

                        <td>{{date('m-d-Y', strtotime($order->grouporder->deliverydate))}}</td>

                        <!-- displaying the order's last updated time -->
                        <!-- can also be printed like {$order->updated_at->diffForHumans()}} -->
                        <td>{{ $order->grouporder->updated_at->format('m-d-Y H:i:s')}}</td>

                        <!-- checking status to determine how to display it -->
                        @if($order->deliverystatus == 4)
                        <td class="status completed"><i class="fa fa-check"></i> Completed</td>
                        @else
                        <td class="status unfulfilled"><i class="fa fa-times"></i> Unfulfilled</td>
                        @endif
                    </tr>
                    @endforeach
                    <!--end of foreach order  -->
                </tbody>
            </table> <!-- end of table -->
        </div>
        <br>
    </div>
    @endif

    @endforeach
    <!--end of foreach group  -->

    <!-- creating a pagination select -->
    <div class="paginate">
        <div class="paginate-select">
            <form>
                Show
                <select id="pagination">
                    <option value="5" @if($items==5) selected @endif>5</option>
                    <option value="10" @if($items==10) selected @endif>10</option>
                    <option value="25" @if($items==25) selected @endif>25</option>
                    <option value="50" @if($items==50) selected @endif>50</option>
                </select>
                entries
            </form>
        </div>
        <div class="paginate-bar">
            <!-- displaying to buttons to go to next page or back -->
            @if(!$searched){{$groups->links()}}@endif
        </div>
    </div>






    <!-- script to get what pagination select option was selected -->
    <script>
        document.getElementById('pagination').onchange = function() {

            window.location = "{{url('/archive/'.$days)}}?page=1&items=" + this.value;


        };
    </script>


    @endsection