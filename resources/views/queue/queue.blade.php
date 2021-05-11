@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{asset('css/queue.css')}} ">
<div class="container content">
    <h2 class="content-heading"></i>ORDER QUEUE</h2>
    <div class="content-label">
        <div class="all-buttons">
            <div class="newGroup">
                <button id="newgroup-button" type="button" class="btn btn-primary">
                    <i class="fa fa-plus"></i>
                    New Group
                </button>
            </div>

            <div id="addgroup-modal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <span id="close-newgroup" class="close">&times;</span>
                        <h2>Create New Group</h2>
                    </div>
                    <div class="modal-body">
                        <form class="form" action="{{url('addgroup')}}" method="POST">
                            @csrf
                            <fieldset>
                                <legend>New Group</legend>
                                <br>
                                <label style="padding-bottom:5px;" for="groupname">Group Name:</label>
                                <input class="form" type="text" id="newGroupName" name="groupname" value="{{old('groupname')}}" placeholder="Enter Group Name" style="margin-left:20px; width :40%">
                                <br>
                            </fieldset>
                            <br>
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-check" aria-hidden="true"></i>
                                OK
                            </button>
                            <button id="close" type="button" class="btn btn-danger">
                                <i class="fa fa-times" aria-hidden="true"></i>
                                Cancel
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="search-group">
                <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Search" title="Type in a order" />
                <i class="fa fa-search"></i>
            </div>

            <div class="dropdown">
                <button type="button" class="btn btn-primary" onclick="dropDown()">
                    <i class="fa fa-list-ul" aria-hidden="true"></i>
                    Group By
                </button>
                <div id="myDropdown" class="dropdown-content">
                    <button type="button" class="button button-dropDown" onclick="SortByDate()">
                        Date
                    </button>
                    <button type="button" class="button button-dropDown" onclick="SortByAddress()">
                        Address
                    </button>
                </div>
            </div>
            <button id="assign-button" type="button" class="btn btn-primary" style="display:none">
                <i class="far fa-calendar-plus"></i>
                Assign
            </button>
            <form style="display:inline;" id="draggable-form" action="{{route('queue.store')}}" method="POST">
                @csrf
                <button id="dragble-button" type="button" class="btn btn-primary">
                    <i id="unlockIcon" class="fa fa-lock" aria-hidden="true"></i>
                    Drag Mode
                </button>
            </form>
        </div>
        <div class="droptarget" id="null">
            <button class="button button-collapsible queue-group-heading">
                Pending Order
            </button>
            @if($orders->whereNull('grouporderid')->count())

            <div class="content-collapsible">
                <table id="allOrderTable" class="order-queue">
                    <thead>
                        <th>Order Number</th>
                        <th>Name</th>
                        <th>Item</th>
                        <th>Address</th>
                        <th>Delivery Date</th>
                        <th>Action</th>
                    </thead>
                    <tbody class="table-body table-pending-body">
                        @foreach ($orders->where('grouporderid',null) as $order)
                        <tr class="draggable" draggable="false" id="{{$order->id}}">
                            <td>
                                <div class="dropdown">
                                    <span><u>{{$order->id}}</u><span>
                                            <div class="dropdown-content">
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

                                            </div>

                                </div>

                            </td>
                            <td>{{$order->customer->firstname}} {{$order->customer->lastname}}</td>
                            <td>
                                @foreach ($order->item as $item)
                                <p>{{$item->itemname}} x{{$item->itemquantity}}</p>
                                @endforeach
                            </td>
                            <td>{{$order->customer->city}}, {{@substr($order->customer->zipcode, 0,5)}}</td>
                            <td>{{date('m-d-Y', strtotime($order->deliverydate))}}</td>
                            <td>
                                <button class="btn btn-warning">
                                    <a href="{{url('queue/'.$order->id . '/edit')}} " style="color:white"> <i class="fa fa-share" aria-hidden="true"></i></i> Pop
                                </button>
                            </td>
                        </tr>
                        @endforeach
                        <!--end for each order -->

                    </tbody>
                </table>
            </div>
            @else
            <p class="no-order-table"> There is no orders in this group</p>

            @endif

        </div>
        <!-- For some reason if I put this in the end of the file. it would not regconize $grouporderreview-->
        <div id="review-order-modal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="close">&times;</span>
                    <h2>Review Order Before Push To Delivery</h2>
                </div>
                <div class="modal-body">
                    <form action="{{url('/pushtodelivery')}}" method="post">
                        @csrf
                        <div>
                            @foreach($grouporders as $grouporderreview)

                            <div>
                                @if($orders->where('grouporderid',$grouporderreview->id)->count())
                                <div class="queue-group-heading">
                                    {{$grouporderreview->groupname}}
                                </div>


                                <div class="content-collapsible">
                                    <table style="width: 100%" class="table-content" name="table">
                                        <thead>
                                            <th>Order Number</th>
                                            <th>Name</th>
                                            <th>Item</th>
                                            <th>Address</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($orders->where('grouporderid',$grouporderreview->id) as $order)
                                            <tr>
                                                <td>{{$order->id}}</td>
                                                <td>{{$order->customer->firstname}} {{$order->customer->lastname}}</td>
                                                <td>
                                                    @foreach ($order->item as $item)
                                                    <p>{{$item->itemname}} x{{$item->itemquantity}}</p>
                                                    @endforeach
                                                </td>
                                                <td>{{$order->customer->zipcode}}</td>
                                            </tr>

                                            @endforeach
                                            <!--end for each order -->

                                        </tbody>
                                    </table>
                                </div>
                                @endif
                                <br>
                            </div>
                            @endforeach
                            <!--end for each of group order-->
                            <br>
                            <button type="submit" class="button btn-success">
                                <a style="color:white;"> <i class="fa fa-eject" aria-hidden="true"></i></i> Push To Delivery</a>
                            </button>
                            <button onclick="document.getElementById('review-order-modal').style.display='none'" type="button" class="button btn-danger">
                                <i class="fa fa-times" aria-hidden="true"></i>
                                Cancel
                            </button>

                        </div>

                    </form>
                </div>
            </div>
        </div>

        @foreach($grouporders as $grouporder)
        <div class="droptarget" id="{{$grouporder->id}}">
            <button class="button button-collapsible queue-group-heading">
                {{$grouporder->groupname}}
            </button>

            @if($grouporder->refuserid!=null)
            @foreach($drivers as $thedriver)
            @if($thedriver->id == $grouporder->refuserid)
            <P class='grouporder-assigned-detail'> Driver: {{$thedriver->firstname}} {{$thedriver->lastname}}</P>
            @endif
            @endforeach
            @else
            <P class='grouporder-assigned-detail-missing'> Driver: not assigned</P>
            @endif
            @if($grouporder->deliverydate==null)
            <P class='grouporder-assigned-detail-missing'>Delivery Date: not assigned</P>
            @else
            <P class='grouporder-assigned-detail'>Delivery Date: {{date('m-d-Y', strtotime($grouporder->deliverydate))}}</P>
            @endif

            @if($orders->where('grouporderid',$grouporder->id)->count())
            <?php
            /*$mapString = "https://www.google.com/maps/dir/?api=1&origin=6000%20J%20St,%20Sacramento,%20CA,%2085819";
            $lastOrder = $orders->where('grouporderid', $grouporder->id)->last();
            $mapString .= "&destination=";
            $mapString .= "{$lastOrder->customer->address}%20{$lastOrder->customer->city}%20{$lastOrder->customer->state}%20{$lastOrder->customer->zipcode}";
            foreach ($orders->where('grouporderid', $grouporder->id) as $order) {
                if ($orders->where('grouporderid', $grouporder->id)->first() == $order && $orders->where('grouporderid', $grouporder->id)->last() != $order) {
                    $mapString .= "&waypoints=";
                    $mapString .= "{$order->customer->address}%20{$order->customer->city}%20{$order->customer->state}%20{$order->customer->zipcode}";
                } elseif ($orders->where('grouporderid', $grouporder->id)->last() != $order) {
                    $mapString .= "%7C";
                    $mapString .= "{$order->customer->address}%20{$order->customer->city}%20{$order->customer->state}%20{$order->customer->zipcode}";
                }
            }*/
            //above is to show directions with route 
            $mapString = "https://www.google.com/maps/dir/";
            foreach ($orders->where('grouporderid', $grouporder->id) as $order) {
                if ($lastOrder = $orders->where('grouporderid', $grouporder->id)->last() == $order) {
                    $mapString .= "/{$order->customer->address}%20{$order->customer->city}%20{$order->customer->state}%20{$order->customer->zipcode}//@";
                } else {
                    $mapString .= "/{$order->customer->address}%20{$order->customer->city}%20{$order->customer->state}%20{$order->customer->zipcode}";
                }
            }
            ?>
            <button class="queue-group-heading checkmap-button">
                <a href="{{$mapString}}"><i class="fas fa-directions"></i> Check Map</a>
            </button>
            <br>
            <div class="content-collapsible">
                <table class="order-queue" name="table">
                    <thead>
                        <th>Order Number</th>
                        <th>Name</th>
                        <th>Item</th>
                        <th>Address</th>
                        <th>Delivery Date</th>
                        <th>Action</th>
                    </thead>
                    <tbody class="table-body">
                        @foreach ($orders->where('grouporderid',$grouporder->id) as $order)
                        <tr class="draggable" draggable="false" id="{{$order->id}}" value="{{$order->index}}">
                            <td>
                                <div class="dropdown">
                                    <span><u>{{$order->id}}</u><span>
                                            <div class="dropdown-content">
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

                                            </div>

                                </div>

                            </td>
                            <td>{{$order->customer->firstname}} {{$order->customer->lastname}}</td>
                            <td>
                                @foreach ($order->item as $item)
                                <p>{{$item->itemname}} x{{$item->itemquantity}}</p>
                                @endforeach
                            </td>
                            <td>{{$order->customer->city}}, {{@substr($order->customer->zipcode, 0,5)}}</td>
                            <td>{{date('m-d-Y', strtotime($order->deliverydate))}}</td>
                            <td>
                                <button class="btn btn-warning">
                                    <a href="{{url('/queue/'. $order->id . '/edit')}}" style="color:white"> <i class="fa fa-share" aria-hidden="true"></i></i>Pop</a>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                        <!--end for each order -->
                    </tbody>
                </table>
            </div>
            @else
            <button onclick="openDeleteGroupModal('deleteGroup{{$grouporder->id}}')" class="btn btn-danger btn-drop">
                <i class="fas fa-trash-alt" aria-hidden="true"> Drop</i>
            </button>
            <div id="deleteGroup{{$grouporder->id}}" class="modal">
                <span onclick="document.getElementById('deleteGroup{{$grouporder->id}}').style.display='none'" class="close">×</span>
                <form style="border-radius: 50px" class="modal-content" action="{{route('queue.destroy',[$grouporder->id])}}" method="post" style="background-color:rgb(248, 103, 103);border-radius: 50px;">
                    @csrf
                    @method('DELETE')
                    <div style="border-radius: 50px" class="form-container" style="text-align: center">
                        <h1>Are you sure you want to delete this group order?</h1>
                        <br>
                        <div class="clearfix">
                            <button type="button" onclick="document.getElementById('deleteGroup{{$grouporder->id}}').style.display='none'" class="button cancelbtn">Cancel</button>

                            <button type="submit" onclick="" class="button deletebtn">Delete</button>

                        </div>
                    </div>
                </form>
            </div>
            <p class="no-order-table"> There is no orders in this group</p>
            @endif
        </div>
        @endforeach
        <!--end for each of group order-->
        <br>

        <button id="review-order-button" class="btn btn-primary">
            <a style="color:white;"> <i class="fa fa-eject" aria-hidden="true"></i></i> Review Order</a>
        </button>
        <div id="assign-modal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="close">&times;</span>
                    <h2>Assign Groups</h2>
                </div>
                <div class="modal-body">
                    <form class="form" action="{{url('/assigngroup')}}" method="post">
                        @csrf
                        <fieldset>
                            @foreach($grouporders as $grouporder)
                            <legend><input name="groupid{{$grouporder->id}}" value="{{$grouporder->id}}" style="display: none;">
                                Group Name: {{$grouporder->groupname}}
                            </legend>
                            <label style="padding-bottom:5px" for="calendar">Delivery Date:</label>
                            @if($grouporder->deliverydate == null)
                            <input style="margin-left:15px;" name="calendar{{$grouporder->id}}" type="date" id="grouporder-deliverydate-{{$grouporder->id}}" value="" class="calendar">
                            @else
                            <input style="margin-left:15px;" name="calendar{{$grouporder->id}}" type="date" id="grouporder-deliverydate-{{$grouporder->id}}" required value="{{$grouporder->deliverydate}}" class="calendar">
                            @endif
                            <br>
                            <label for="drivers">Driver:</label>
                            <select name="driver{{$grouporder->id}}" class="driver">

                                @if($grouporder->refuserid==null)
                                <option value="" disable selected> Select a driver</option>
                                @endif
                                @foreach($drivers as $driver)
                                @if($grouporder->refuserid == $driver->id)
                                <option value="{{$driver->id}}" selected> {{$driver->firstname}} {{$driver->lastname}}</option>
                                @else
                                <option value="{{$driver->id}}"> {{$driver->firstname}} {{$driver->lastname}}</option>
                                @endif
                                @endforeach
                            </select>
                            <br>
                            <br>
                            @endforeach
                        </fieldset>
                        <button class="btn btn-success" type="submit">
                            <i class="fa fa-check" aria-hidden="true"></i>
                            OK
                        </button>
                        <button type="button" onclick="document.getElementById('assign-modal').style.display='none'" class="btn btn-danger">
                            <i class="fa fa-times" aria-hidden="true"></i>
                            Cancel
                        </button>
                    </form>

                </div>
            </div>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="{{asset('js/queue.js')}}"></script>
        <script src="{{asset('js/main.js')}}"></script>
        <script type="text/javascript">
            // Modal display/ hidden
            // Get DOM Elements
            const assignmodal = document.getElementById('assign-modal');
            const assignBtn = document.getElementById('assign-button');
            const newgroupmodal = document.getElementById('addgroup-modal');
            const newgroupBtn = document.getElementById('newgroup-button');
            const closeBtn = document.getElementById('close-newgroup');
            const cancelBtn = document.querySelector('#close');
            const revieworderBtn = document.getElementById('review-order-button');
            const revieworderModal = document.getElementById('review-order-modal');


            // Events
            assignBtn.addEventListener('click', function() {
                assignmodal.style.display = 'block';
            })
            newgroupBtn.addEventListener('click', openModal);
            closeBtn.addEventListener('click', closeModal);
            window.addEventListener('click', outsideClick);
            cancelBtn.addEventListener('click', closeModal);
            revieworderBtn.addEventListener('click', openReviewOrderModal);
            // Open
            function openReviewOrderModal() {
                revieworderModal.style.display = 'block';
            }

            function openModal() {
                newgroupmodal.style.display = 'block';
            }
            // Close
            function closeModal() {
                newgroupmodal.style.display = 'none';
            }
            // Close If Outside Click
            function outsideClick(e) {
                if (e.target == newgroupmodal) {
                    newgroupmodal.style.display = 'none';
                }
                if (e.target == revieworderModal) {
                    revieworderModal.style.display = 'none';
                }
                if (e.target == assignmodal) {
                    assignmodal.style.display = 'none';
                }
            }
            let deleteGroupModal;

            function openDeleteGroupModal(modalID) {
                console.log(modalID);
                document.getElementById(modalID).style.display = 'block';
                // enable click outsite to close
                deleteGroupModal = document.getElementById(modalID);
                console.log(deleteGroupModal);

            }
            window.addEventListener('click', closeDeleteGroupModal);

            function closeDeleteGroupModal(e) {
                if (e.target == deleteGroupModal) {
                    deleteGroupModal.style.display = 'none';
                }
            }
        </script>

        @endsection