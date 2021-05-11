@extends('layouts.driverapp')

@section('content')
<link rel="stylesheet" href="{{asset('css/driver.css')}} ">
<a href="{{url('/driverhome')}}"><button class="btn-return-driver"><i class="fas fa-home"></i></button></a>
<div id="exitmodal" class="modal">
    <div style="width:fit-content" class="modal-content">
        <i class="fas fa-spinner"></i>
    </div>
</div>
<div id="confirmmodal" class="modal">
    <div class="modal-content driver-modal-message">
        <div class="modal-header">
            <span class="close">&times;</span>
            <h2>Confirm</h2>
        </div>
        <div class="modal-body">
            <p>There are at least an unfulfilled order</p>
            <br>
            <p>Are you sure you want to go back to the dashboard?</p>
            <button class="button btn-primary button-red">
                <a href="{{url('/driverhome')}}">OK</a>
            </button>
            <button onclick="document.getElementById('confirmmodal').style.display='none'" class="button btn-primary button-red">Cancel</button>
        </div>
    </div>
</div>
<div class="driver-content">
    <div id="cPost" style=" padding: 1.5rem; border-radius: 0.5rem; text-align:center; margin: auto ">
        <h2><b class="DashTitle">Delivery</b></h2>
        @foreach($orders as $order )
        <div>
            <div id="messagemodal{{$order->id}}" class="modal">
                <div class="modal-content  driver-modal-message">
                    <div class="modal-header">
                        @if($order->message->where('isOperator',true)->where('isRead',false)->count())
                        <form onsubmit="closeMessage('{{$order->id}}');openexitmodal()" action="{{url('/markmessageread')}}" method="post">
                            @csrf
                            <input type="hidden" name="index" value="{{$order->index}}">
                            <input type="hidden" name="orderid" value="{{$order->id}}">
                            <input type="hidden" name="groupid" value="{{$order->grouporderid}}">
                            <button type="submit" style="display:inline" class="close">&times;</button>
                        </form>
                        @else
                        <button onclick="closeMessage('{{$order->id}}')" style="display:inline" class="close">&times;</button>
                        @endif

                        <h2>Message</h2>
                    </div>
                    <div class="modal-body">
                        <div class="message-box">
                            @foreach($order->message as $message)
                            @if($message->isOperator)
                            <div class="operator-message">{{$message->message}}:<b>Operator</b></div>
                            @else
                            <div class="driver-message"> <b>You</b>:{{$message->message}}</div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @php
                $contactNumb = preg_replace("/[^0-9]/", "", $order->customer->phonenumber )
            @endphp
            @if($order->deliverystatus==1)
                <button class="button-collapsible delivery-group delivery-group-future">
            @elseif($order->deliverystatus==2)
                @if($order->index == 1)
                    <button class=" button-collapsible delivery-group delivery-group-future-order button-active">
                @else
                    <button class=" button-collapsible delivery-group delivery-group-future-order">
                @endif
            @elseif($order->deliverystatus==3)
                <button class=" button-collapsible delivery-group delivery-group-inprogress">
            @elseif($order->deliverystatus==4)
                <button class=" button-collapsible delivery-group delivery-group-completed">
            @endif
            <div class="group-heading-day">
                {{$order->customer->firstname}} {{$order->customer->lastname}}
            </div>
            @if($order->message->where('isOperator',true)->where('isRead',false)->count())
                <i class="fas fa-sms"></i>
            @endif
            <div class="group-heading-orders">
                {{count($order->item)}} Bags
            </div>
            </button>
            <div class="content-collapsible-driver">
                <div class="group-content">
                    <div class="detail-box">
                        <b>Adddress:</b> {{$order->customer->address}}, {{$order->customer->city}} {{$order->customer->state}} {{@substr($order->customer->zipcode, 0,5)}}<br>
                        <b>Delivery Instruction:</b> {{$order->deliveryinstruction}} <br>
                    </div>
                    @if($orders->where('deliverystatus',1)->count()||$order->deliverystatus==3||$order->deliverystatus==4)
                        <div>
                            <button class="button btn_direction_disable"><i class="fas fa-directions"></i> Direction</button>


                            <button class="button call_btn"><a href="tel:<?php echo $contactNumb; ?>"><i class="fas fa-phone-alt"></i> Call</a></button>
                            <button class="button msg_btn"><a href="sms:<?php echo $contactNumb; ?>"><i class="fas fa-comment-dots"></i>Text</a></button>
                            @if(($order->deliverystatus==4||$order->deliverystatus==3)&& $order->message->count())
                            <br>
                            <button onclick="openMessage('{{$order->id}}')" class="button btn-primary button-red">Message</button>
                            @endif
                        </div>
                    @elseif($loop->first)
                    <div>
                        <?php
                            $address = $order->customer->address.' '.$order->customer->city.' '.$order->customer->state;
                            $address = str_replace(' ', '+', $address);
                        ?>
                        <button class="button btn_direction"><a href="https://www.google.com/maps/dir/?api=1&destination={{$address}}"><i class="fas fa-directions"></i> Direction</a></button>
                        
                        <button class="button call_btn"><a href="tel:<?php echo $contactNumb; ?>"><i class="fas fa-phone-alt"></i> Call</a></button>
                        <button class="button msg_btn"><a href="sms:<?php echo $contactNumb; ?>"><i class="fas fa-comment-dots"></i> Text</a></button>
                        <div>
                            <button class="button button-red btn-arrive-customer" onclick="openModal()"><i class="fas fa-street-view"></i> I'm Here!</button>
                        </div>
                        @if($order->message->count())
                        <br>
                        <button onclick="openMessage('{{$order->id}}')" class="button btn-primary button-red">Message</button>
                        @endif
                    </div>
                    <!--Modal for report and Complete button -->
                    <div id="arrive-modal" class="modal">
                        <div class="modal-content">
                            <div class="modal-header">
                                <span class="close" style="font-size:3em">&times;</span>
                                <h2>{{$order->customer->firstname}} {{$order->customer->lastname}}</h2>
                            </div>
                            <div class="modal-body">
                                <div class="button-in-arrive">
                                    <h1>
                                        <p>You have arrived</p>
                                    </h1>
                                    <h2>
                                        <p>Resolve Order</p>
                                    </h2>
                                    <br>
                                    <button class="button-modal button-modal-report" onclick="openReportModal()">Report</button>
                                    <br>
                                    <form onsubmit="closeModal();openexitmodal()" action="{{url('/sendautomessage')}}" method="post">
                                        @csrf
                                        <input type="hidden" name="customer_phonenumber" value="{{$order->customer->phonenumber}}">
                                        <input type="hidden" name="orderid" value="{{$order->id}}">
                                        <input type="hidden" name="groupid" value="{{$order->grouporderid}}">
                                        <button type="submit" class="button-border-red ">Send Auto Message</button>
                                    </form>
                                    <form onsubmit="closeModal();openexitmodal()" action="{{url('/completeorder')}}" method="post">
                                        @csrf
                                        <input type="hidden" name="orderid" value="{{$order->id}}">
                                        <input type="hidden" name="groupid" value="{{$order->grouporderid}}">

                                        <button type="submit" class="button-modal button-modal-red">Complete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="report-modal" class="modal">
                        <div class="modal-content driver-modal-message">
                            <div class="modal-header">
                                <span class="close">&times;</span>
                                <h2>Report Order {{$order->id}}</h2>
                            </div>
                            <div class="modal-body">
                                <form onsubmit="closeModal();openexitmodal()" id="report-form" style="display: inline-block;" action="{{url('/reportorder')}}" method="post">
                                    @csrf
                                    <input type="hidden" name="orderid" value="{{$order->id}}">
                                    <input type="hidden" name="groupid" value="{{$order->grouporderid}}">
                                    <label for="message"> Report Note:</label>
                                    <textarea form="report-form" class="report-text" name="message" required></textarea>
                                    <button type="submit" class="button btn-primary button-red">Report</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @else
                    <div>
                        <button class="button btn_direction_disable"><a><i class="fas fa-directions"></i> Direction</a></button>
                        <button class="button call_btn"><a href="tel:<?php echo $contactNumb; ?>"><i class="fas fa-phone-alt"></i> Call</a></button>
                        <button class="button msg_btn"><a href="sms:<?php echo $contactNumb; ?>"><i class="fas fa-comment-dots"></i> Text</a></button>
                        <form style="display:inline" onsubmit="closeModal();openexitmodal()" action="{{url('/swaporder')}}" method="post">
                            @csrf
                            <input type="hidden" name="orderid" value="{{$order->id}}">
                            <input type="hidden" name="groupid" value="{{$order->grouporderid}}">
                            <input type="hidden" name="index" value="{{$order->index}}">
                            <button type="submit" class="button button-main swapButton"><i class="fas fa-exchange-alt fa-rotate-90"></i> Swap</button>
                        </form>
                        @if($order->message->count())
                        <button onclick="openMessage('{{$order->id}}')" class="button btn-primary button-red">Message</button>
                        @endif
                    </div>
                    @endif

                </div>
            </div>

        </div>
        @endforeach
        <br>
        @if($orders->where('deliverystatus',1)->count())


        <a href="https://www.google.com/maps/dir//1827+Broadway,+Sacramento,+CA+95818/@37.3392573,-122.0496515,15z/data=!4m2!4m1!3e0"><button class="button button-red-white">
                <i class="fas fa-directions"></i> Kitchen</button></a>
        <a href="{{url('/pickup/'.$orders->first()->grouporderid)}}">
            <button class="button button-red ">Pick Up</button></a>



        <div class="pickup-buttons">

        </div>


        <div>

            @elseif(!$orders->where('deliverystatus',1)->count()&&!$orders->where('deliverystatus',2)->count())

            @if($orders->where('deliverystatus',3)->count())
            <div>
                <button onclick="openConfirmModal()" class="button btn-primary button-red button-finished">
                    <a>WooHoo! All Done!</a>
                </button>
            </div>
            @else
            <div>
                <button class="button btn-primary button-red button-finished">
                    <a href="{{url('/driverhome')}}">WooHoo! All Done!</a>
                </button>
            </div>
            @endif

            @else
            <div>
                <button class="button btn-secondary button-disabled button-finished">
                    WooHoo! All Done!
                </button>
            </div>
            @endif
            <br>


        </div>
    </div>
    <script type="text/javascript">
        function openConfirmModal() {
            document.getElementById('confirmmodal').style.display = 'block';
        }

        function openMessage(id) {
            document.getElementById('messagemodal' + id).style.display = 'block';
        }

        function closeMessage(id) {
            document.getElementById('messagemodal' + id).style.display = 'none';
        }

        function openexitmodal() {
            document.getElementById('exitmodal').style.display = 'block';
        }

        function openModal() {
            document.getElementById('arrive-modal').style.display = 'block';
        }

        function openReportModal() {
            document.getElementById('report-modal').style.display = 'block'
            document.getElementById('arrive-modal').style.display = 'none'
        }
        const closebuttons = document.querySelectorAll(".close")
        closebuttons.forEach(c => {
            c.addEventListener('click', closeModal)
        });

        function closeModal() {
            document.getElementById('confirmmodal').style.display = 'none'
            document.getElementById('arrive-modal').style.display = 'none'
            document.getElementById('report-modal').style.display = 'none'
        }
    </script>

    @endsection('content')