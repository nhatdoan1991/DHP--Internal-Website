@extends ('layouts.app')

@section('content')
<link rel="stylesheet" href="{{asset('css/create.css')}} ">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <div class="container content">
    
    <form action="/list" method="POST" name="myForm" id="myForm" onsubmit="return validateForm()" style="border:none" >
    
    @csrf

    <h2 class="content-heading">Custom Order</h2>
      <div>
        <form action="/action_page.php" >
        <div class="form-container">
          
        <label for="fname" class="order-label">First Name</label>
        <input class="form"type="text" id="firstname" name="firstname" value= "{{old('firstname')}}" placeholder="First name"> <br>
        @error('firstname')
        <div class='error'>
        {{ $message }}
        </div>
        @enderror
      </div>

      <div class="form-container">
        <label for="lname">Last Name</label>
        <input class="form"type="text" id="lastname" name="lastname" value= "{{old('lastname')}}" placeholder="Last name" value="{{old('name')}}">
        @error('lastname')
        <div class="error">
        {{ $message }}
        </div>
        @enderror
      </div>

      <div class="form-container">
        <label for="email">Email</label>
        <input class="form"type="text" id="email" name="email" value= "{{old('email')}}" placeholder="i.e. hello@deltahandpies.com">
        @error('email')
        <div class='error'>
        {{ $message }}
        </div>
        @enderror
      </div>

      <div class="form-container">
        <label for="phonenumber">Phone Number</label>
        <input class="form"type="text" id="phonenumber" name="phonenumber" value= "{{old('phonenumber')}}" placeholder="Your phonenumber..">
        @error('phonenumber')
        <div class='error'>
        {{ $message }}
        </div>
        @enderror
      </div>

      <div class="form-container">
        <label for="adrs">Address</label>
        <input class="form"type="text" id="address" name="address" value= "{{old('address')}}" placeholder="Your address..">
        @error('address')
        <div class='error'>
        {{ $message }}
        </div>
        @enderror
      </div>

      <div class="form-container">
        <label for="city">City</label>
        <input class="form"type="text" id="city" name="city" value= "{{old('city')}}" placeholder="i.e. Sacramento">
        @error('city')
        <div class='error'>
        {{ $message }}
        </div>
        @enderror
      </div>

      <div class="form-container">
        <label for="zip">Zipcode</label>
        <input class="form"type="text" id="zip" name="zipcode" value= "{{old('zipcode')}}" placeholder="i.e. 95825">
        @error('zipcode')
        <div class='error'>
        {{ $message }}
        </div>
        @enderror
      </div>




      <div class="form-container">
        <label for="state">State</label>
        <input class="form"type="text" id="state" name="state" value= "{{old('state')}}" placeholder="i.e. CA">
        @error('state')
        <div class='error'>
        {{ $message }}
        </div>
        @enderror
      </div>

      <div class="form-container">
        <label for="state">Delivery Instruction</label>
        <input class="textarea" type="text" id="deliveryinstruction" value= "{{old('deliveryinstruction')}}" name="deliveryinstruction" placeholder="instruction...">
      </div>


      <div class="form-container">
        <label for="zip">Delivery Date</label>
        <input class="form" type="date" id="deliverydate" name="deliverydate" value= "{{old('deliverydate')}}">
        @error('deliverydate')
        <div class='error'>
        {{ $message }}
        </div>
        @enderror
      </div>



      <div class="container1 add-item">

        <label for="itemname" >Item Name</label>
        <label for="itemname"style="padding-left:35%;">Item Quantity</label><br>
        <input type="text" name="myItems[]"  placeholder="Item name" value= "{{old('myItems.0')}}">
        @error('myItems')
        <div class='error'>
        <p>Items is Required</p>
        </div>
        @enderror
        <input type="text" name="myQuantity[]"  placeholder="Item quantity" value= "{{old('myQuantity.0')}}">
        @error('myQuantity')
        <div class='error' style="padding-left:50%;">
        <p>Item Quantity is Required</p>
        </div>
        @enderror

        <button class="add_form_field btn btn-primary"><i class="fa fa-plus"></i></button>
      </div>
      <div class="form-container">
        <button type="submit" class="btn btn-primary">Submit</button>  
      </div>
      
    </div>
  </form>
</div>

  

  <script>
    $(document).ready(function() {
      var max_fields = 40;
      var wrapper = $(".container1");
      var add_button = $(".add_form_field");

      var x = 1;
      $(add_button).click(function(e) {
        e.preventDefault();
        if (x < max_fields) {
          x++;
          $(wrapper).append('<div class="row "><input type="text" name="myItems[]"placeholder="Item name"> <input type="text" name="myQuantity[]"placeholder="Item quantity"> <a href="javascript:void(0);" class="delete btn btn-danger"><i class="fa fa-minus"></i></a></div>'); //add input box
          } else {
            alert('You Reached the limits')
        }
      });

      $(wrapper).on("click", ".delete", function(e) {
        e.preventDefault();
        $(this).closest('div.row').remove();
        x--;
      })
    });
  </script>


@endsection