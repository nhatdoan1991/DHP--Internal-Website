@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{asset('css/delivery.css')}} ">
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<div class="container content">
    <h2 class="content-heading"></i>DELIVERING ORDER</h2>
    <div class="content-label">

    </div>

    <div>

        @foreach($grouporder as $group)
        @if($group->count())
        <div>
            <button class="button button-collapsible delivery-group-heading">
                {{$group->first()->grouporder->groupname}}
            </button>
            <!-- check if the number of order has status unfilled and completed = total number in a group-->
            @if($group->where('deliverystatus',3)->count()+$group->where('deliverystatus',4)->count()== $group->count())
            <!-- add archive button-->
            <form style="float:right" class="archive-push" action="{{url('/pusharchive/'.$group->first()->grouporderid)}}" method="post">
                @csrf
                @method('POST')
                <button class="button archive-push-button" type="submit"><i class="fa fa-arrow-up"></i> Push Archive
                </button>
            </form>
            @endif

            <div class="content-collapsible">
                <table class="order-delivery">
                    <thead>
                        <th>Order Number</th>
                        <th>Name</th>
                        <th>Item</th>
                        <th>Address</th>
                        <th>Delivery Date</th>
                        <th>Last Update</th>
                        <th>Status</th>
                    </thead>
                    <tbody>

                        @foreach($group as $order)
                        <tr>
                            <td>{{$order->id}}</td>
                            <td>{{$order->customer->firstname}} {{$order->customer->lastname}}</td>
                            <td>
                                @foreach ($order->item as $item)
                                <p>{{$item->itemname}} x{{$item->itemquantity}}</p>
                                @endforeach
                            </td>
                            <td>{{$order->customer->city}}, {{@substr($order->customer->zipcode, 0,5)}}</td>

                            <td>{{$order->grouporder->deliverydate}}</td>
                            <td>{{$order->updated_at->format('m-d-Y H:i:s')}}</td>
                            @if($order->deliverystatus == 2)
                            <td class="status inDelivery">
                                <i class="fa fa-caret-right"></i>In delivery
                            </td>
                            @elseif($order->deliverystatus == 3)
                            <td class="status unfulfilled">

                                <button id="{{$order->id}}" class="button button-unfulfilled" onclick='unfulfilledModal(this.id)'>
                                    <a style="color:white"> <i class="fa fa-times"></i>Unfulfilled</a>
                                </button>
                            </td>
                            <div id="unfulfilled-order-modal{{$order->id}}" class="modal">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <span class="close">&times;</span>
                                        <h2> Details of Order <{{$order->id}}>
                                        </h2>
                                    </div>
                                    <div class="modal-body">
                                        <div><b>Order #:</b> {{$order->id}}</div>
                                        <div><b>Customer ID #:</b> {{$order->customer->id}}</div>
                                        <div><b>Name: </b> {{$order->customer->firstname}} {{$order->customer->lastname}} </div>
                                        <div><b>Email: </b>{{$order->customer->email}}</div>
                                        <div><b>Address: </b>{{$order->customer->address}}</div>
                                        <div><b>City: </b>{{$order->customer->city}}</div>
                                        <div><b>Zipcode: </b>{{$order->customer->zipcode}}</div>
                                        <div><b>State: </b>{{$order->customer->state}}</div>
                                        <div><b>Delivery instruction: </b>{{$order->deliveryinstruction}}</div>
                                        <div><b>Items: </b> </div>
                                        @foreach($order->item as $item)
                                        {{$item->itemname}} x {{$item->itemquantity}}<br>
                                        @endforeach
                                        <div><b>Drivers: {{$order->grouporder->user->firstname}} {{$order->grouporder->user->lastname}}</b> </div>
                                        <div><b>Drivers's phone: {{$order->grouporder->user->phonenumber}}</b> </div>
                                        <div><b>Drivers's Note: </b>
                                            @foreach($order->message->where('isOperator',false) as $message)
                                            @if($loop->last)
                                            {{$message->message}}
                                            @endif
                                            @endforeach
                                        </div>
                                        <br>
                                        <form id="unfulfilledform{{$order->id}}" method="POST" action="{{url('/editdeliveringstatus')}}">
                                            @csrf
                                            <div>
                                                <fieldset>
                                                    <input type="hidden" name="orderid" value="{{$order->id}}">
                                                    <input type="hidden" name="groupid" value="{{$order->grouporderid}}">
                                                    <label style="background:rgb(250,226,232); padding:5px 5px; border-radius:5px;"> Select an status to edit </label>
                                                    <select style="font-size:20px" name="status">
                                                        <option value="" disabled selected> Choose one</option>
                                                        <option value="2"> In Delivery</option>
                                                        <option value="4"> Completed</option>
                                                    </select>
                                                    <br>
                                                    <label for="message"> <b> Note for the Driver:</b></label>
                                                    <textarea form="unfulfilledform{{$order->id}}" class="report-text" name="message" required></textarea>
                                                </fieldset>
                                                <span id="status_error"></span>
                                                <br><br>
                                                <button type="submit" class="button btn-success">
                                                    <a style="color:white;"> <i class="fa fa-eject" aria-hidden="true"></i></i> Edit Order Status</a>
                                                </button>
                                                <button id="close" onclick="document.getElementById('unfulfilled-order-modal{{$order->id}}').style.display='none'" type="button" class="button btn-danger">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                    Cancel
                                                </button>

                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                            @elseif($order->deliverystatus == 1)
                            <td class="status ontheway">
                                <i class="fa fa-pause"></i>In Kitchen
                            </td>
                            @elseif($order->deliverystatus == 4)
                            <td class="status completed">
                                <i class="fa fa-check"></i>Completed
                            </td>
                            @endif

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <br>
        @endif
        @endforeach
        <script>
            function unfulfilledModal(id) {
                document.getElementById('unfulfilled-order-modal' + id).style.display = 'block';
            }
            window.addEventListener('click', outsideClick);

            function outsideClick(e) {
                if (e.target == document.getElementById('unfulfilled-order-modal')) {
                    document.getElementById('unfulfilled-order-modal').style.display = 'none';
                }
            }
        </script>
        @endsection