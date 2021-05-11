@extends ('layouts.driverapp')

@section('content')
<a href="{{url('/driverhome')}}"><button class="btn-return-driver"><i class="fas fa-home"></i></button></a>
<div class="container">
    <h2 class="driverContent-heading">User Profile</h2>
    <section>
        <div class="driverProfile-content">
            <header class="driverInfoHeader">Basic info</header>
            <div class="driverInfoContent">
                <div class="driverInfoLeft">NAME</div>
                <div class="driverInfoRight">{{Auth::user()->firstname}} {{Auth::user()->lastname}}</div>
                <div style="text-align:right"><i class="fas fa-angle-right"></i></div>
            </div>

            <div class="driverInfoContent">
                <div class="driverInfoLeft">PASSWORD</div>
                <div class="driverInfoRight">
                    <div class="infoRight">
                        <button id="resetpassword-button" style="float:left;" type="button" class="btn btn-primary">
                            <i class="fas fa-edit"></i>
                            Reset Password
                        </button>
                    </div>
                </div>
                <div style="text-align:right"><i class="fas fa-angle-right"></i></div>
            </div>

        </div>
        <div class="driverProfile-content">
            <header class="driverInfoHeader">Contact info</header>
            <div class="driverInfoContent">
                <div class="driverInfoLeft">EMAIL</div>
                <div class="driverInfoRight">{{Auth::user()->email}}</div>
                <div style="text-align:right"><i class="fas fa-angle-right"></i></div>
            </div>
            <div class="driverInfoContent">
                <div class="driverInfoLeft">PHONE</div>
                <div class="driverInfoRight">{{Auth::user()->phonenumber}}</div>
                <div style="text-align:right"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
        <div id="editProfile-modal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <span id="close-editProfile" class="close">&times;</span>
                    <h2>Edit Profile</h2>
                </div>
                <div class="modal-body">
                    <form class="form" action="{{url('/editprofile')}}" method="POST">
                        @csrf
                        <fieldset>
                            <br>
                            <label style="padding-bottom:5px;" for="groupname">First Name: </label>
                            <input type="text" name="firstname" value="{{Auth::user()->firstname}}" style="margin-left:20px; width :30%" required>
                            <br>
                            <label style="padding-bottom:5px;" for="groupname">Last Name: </label>
                            <input type="text" name="lastname" value="{{Auth::user()->lastname}}" style="margin-left:20px; width :30%" required>
                            <br>
                            <label style="padding-bottom:5px;" for="groupname">Phone Number : </label>
                            <input type="text" name="phonenumber" value="{{Auth::user()->phonenumber}}" style="margin-left:20px; width :30%" required>
                            <br>
                        </fieldset>
                        <br>
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-check" aria-hidden="true"></i>
                            Update
                        </button>
                        <button onclick="document.getElementById('editProfile-modal').style.display = 'none';" type="button" class="btn btn-danger">
                            <i class="fa fa-times" aria-hidden="true"></i>
                            Cancel
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div id="resetpassword-modal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <span id="close-resetpassword" class="close">&times;</span>
                    <h2>Reset Password</h2>
                </div>
                <div class="modal-body">
                    <form class="form" action="{{url('/resetpassword')}}" method="POST">
                        @csrf
                        <fieldset>
                            <br>
                            <label style="padding-bottom:5px;" for="groupname">Your Password: </label>
                            <input type="text" name="password" value="" placeholder="Type in your password" style="margin-left:90px; width :30%" required>
                            <br>
                            <label style="padding-bottom:5px;" for="groupname">New Password: </label>
                            <input type="text" name="newpassword" value="" placeholder="Type in new password" style="margin-left:90px; width :30%" required>
                            <br>
                            <label style="padding-bottom:5px;" for="groupname">Confirm New Password: </label>
                            <input type="text" name="confirmpassword" value="" placeholder="confirm your new password" style="margin-left:20px; width :30%" required>
                            <br>
                        </fieldset>
                        <br>
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-check" aria-hidden="true"></i>
                            Reset
                        </button>
                        <button onclick="document.getElementById('resetpassword-modal').style.display = 'none';" type="button" class="btn btn-danger">
                            <i class="fa fa-times" aria-hidden="true"></i>
                            Cancel
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="logout">
            <button id="editProfile-button" type="button" class="driverLogOut btn-warning">Edit Profile</button>
        </div>
        <div class="driverLogout">
            @auth
            <form class="btn-danger" method="post" action="{{ route('logout') }}">
                <button type="submit" class="driverLogOut btn-danger">Logout</button>
                {{ csrf_field() }}
            </form>
            @endauth
        </div>
    </section>


</div>
<script type="text/javascript">
    const editProfileButton = document.getElementById('editProfile-button')
    const edutProfileCloseModalbtn = document.getElementById('close-editProfile')
    const resetpasswordButton = document.getElementById('resetpassword-button')
    const resetpasswordCloseModalbtn = document.getElementById('close-resetpassword')
    editProfileButton.addEventListener('click', function() {
        document.getElementById('editProfile-modal').style.display = 'block';
    })
    edutProfileCloseModalbtn.addEventListener('click', function() {
        document.getElementById('editProfile-modal').style.display = 'none';
    })
    resetpasswordButton.addEventListener('click', function() {
        document.getElementById('resetpassword-modal').style.display = 'block';
    })
    resetpasswordCloseModalbtn.addEventListener('click', function() {
        document.getElementById('resetpassword-modal').style.display = 'none';
    })
</script>
@endsection