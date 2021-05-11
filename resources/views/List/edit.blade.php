@extends ('layouts.app')

@section('content')
  <link rel="stylesheet" href="{{asset('css/create.css')}} ">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  @if(Session::has('success'))
            <div class="alert alert-success alert-dhp">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <strong>Success!</strong> {{ Session::get('message', '') }}
            </div>
@endif
  <div class="container content">
    
    <form action="{{url('list/'.$id)}}" method="POST" name="myForm" id="myForm" onsubmit="return validateForm()" style="border:none" >
    @csrf

    <h2 class="content-heading">Edit Order</h2>
      <div>
        <!--<form action="/action_page.php" >-->
        <div class="form-container">
          

          
          @foreach ($data as $data)
       
          <label for="fname" class="order-label">First Name</label>
          <input class="form"type="text" id="firstname" name="firstname" placeholder="First name" value="{{old('firstname') ?? $data->customer->firstname}}"> <br>
          @error('firstname')
            <div class='error'>
              {{ $message }}
            </div>
          @enderror
        </div>
      

      <div class="form-container">
        <label for="lname">Last Name</label>
        <input class="form"type="text" id="lastname" name="lastname" value="{{old('lastname') ?? $data->customer->lastname}}">
        @error('lastname')
        <div class="error">
        {{ $message }}
        </div>
        @enderror
      </div>

      <div class="form-container">
        <label for="email">Email</label>
        <input class="form"type="text" id="email" name="email" value="{{old('email') ?? $data->customer->email}}">
        @error('email')
        <div class='error'>
        {{ $message }}
        </div>
        @enderror
      </div>

      <div class="form-container">
        <label for="phonenumber">phone</label>
        <input class="form"type="text" id="phonenumber" name="phonenumber" value="{{old('phonenumber') ?? $data->customer->phonenumber}}">
        @error('phonenumber')
        <div class='error'>
        {{ $message }}
        </div>
        @enderror
      </div>

      <div class="form-container">
        <label for="adrs">Address</label>
        <input class="form"type="text" id="address" name="address" value="{{old('address') ?? $data->customer->address}}">
        @error('address')
        <div class='error'>
        {{ $message }}
        </div>
        @enderror
      </div>

      <div class="form-container">
        <label for="city">City</label>
        <input class="form"type="text" id="city" name="city" value="{{old('city') ?? $data->customer->city}}">
        @error('city')
        <div class='error'>
        {{ $message }}
        </div>
        @enderror
      </div>

      <div class="form-container">
        <label for="zip">Zipcode</label>
        <input class="form"type="text" id="zip" name="zipcode" value="{{old('zipcode') ?? $data->customer->zipcode}}">
        @error('zipcode')
        <div class='error'>
        {{ $message }}
        </div>
        @enderror
      </div>

      <div class="form-container">
        <label for="state">State</label>
        <input class="form"type="text" id="state" name="state" value="{{old('state') ?? $data->customer->state}}">
        @error('state')
        <div class='error'>
        {{ $message }}
        </div>
        @enderror
      </div>

      <div class="form-container">
        <label for="state">Delivery Instruction</label>
        <input class="textarea" type="text" id="deliveryinstruction" name="deliveryinstruction" value="{{old('email') ?? $data->deliveryinstruction}}">
      </div>

      <div class="form-container">
        <label for="zip">Delivery Date</label>
        <input class="form" type="date" id="deliverydate" name="deliverydate" value= "{{old('email') ?? $data->deliverydate}}">
        @error('deliverydate')
        <div class='error'>
        {{ $message }}
        </div>
        @enderror
      </div>
     
      <div class="container1 add-item">

        <label for="itemname" >Item Name</label>
        <label for="itemname" style="padding-left:35%;">Item Quantity</label><br>
        

        @foreach ($data->item as $item)
          
            <input type="text" id="itemname" name="myItems[]"  value="{{old('itemname') ?? $item->itemname}}">
            <input type="text" id="itemquantity" name="myQuantity[]"  value="{{old('quantity') ?? $item->itemquantity}}">


            <form action="{{route('deleteItem',$item->id)}}" method="POST" style="display:contents">
     
              
            @csrf
            @method('DELETE')

                       
             <button type="submit" value='delete' name='action' class="btn btn-danger"><i class="fa fa-minus"></i></button>
            </form>
            

        @endforeach
        
        
        <button class="add_form_field btn btn-primary"><i class="fa fa-plus"></i></button>

        
      </div>
      @method('PUT')
      <div class="form-container"><button type="submit" name='action' value='update' class="btn btn-primary">Submit</button>  </div>
      
    </div>
  </form>
  <div class="form-container">
    
      
      <button onclick="document.getElementById('deleteModal').style.display='block'" class="btn btn-danger">Delete</button>
      <div id="deleteModal" class="modal">
        <span onclick="document.getElementById('deleteModal').style.display='none'" class="close" title="Close Modal">×</span>
        <form class="modal-content" action="{{route('list.destroy',[$id])}}" method="POST">

          @csrf
          @method('DELETE')
          <div class="form-container" style="text-align: center">
            <h1>Delete Order # <?php echo $data->id ?></h1>
            <p>Are you sure you want to delete your order?</p>
          
            <div class="clearfix">
              <button type="button" onclick="document.getElementById('deleteModal').style.display='none'" class="cancelbtn">Cancel</button>
              
              <button type="submit" onclick="document.getElementById('deleteModal').style.display='none'" class="deletebtn">Delete</button>
              </form>
              
              
            </div>
          </div>
        </form>
      </div>


  
  </div>
 
  @endforeach
</div>

<script>
  // Get the modal
var modal = document.getElementById('deleteModal');

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "block";
  }
}

$(document).ready(function() {
  var max_fields = 40;
  var wrapper = $(".container1");
  var add_button = $(".add_form_field");

  var x = 1;
  $(add_button).click(function(e) {
    e.preventDefault();
    if (x < max_fields) {
      x++;
        $(wrapper).append('<div class="row " style="display:contents""><input type="text" name="myItems[]"placeholder="Item name" style="padding-right: 10%" > <input type="text" name="myQuantity[]"placeholder="Item quantity"> <a href="javascript:void(0);" class="delete btn btn-danger"><i class="fa fa-minus"></i></a></div>'); //add input box
    } 
  
  });

  $(wrapper).on("click", ".delete", function(e) {
    if(x>1){
      e.preventDefault();
      $(this).closest('div.row').remove();
      x--;
    }
    
  })
});
</script>


@endsection