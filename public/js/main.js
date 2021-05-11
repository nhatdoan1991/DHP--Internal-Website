//collapsible for group header 
var coll = document.getElementsByClassName("button-collapsible");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    
    this.classList.toggle("active");
    var content = this.nextElementSibling;
    while(content){
      if(content.className == "content-collapsible")
      {
        if (content.style.display==="none"){
          content.style.display="block";
        } else {
          content.style.display="none";
        } 
      }
      if(content.className == "content-collapsible-driver")
      {
        if (content.style.maxHeight){
          content.style.maxHeight = null
        } else {
          content.style.maxHeight = content.scrollHeight + "px"
        } 
      }
      content = content.nextElementSibling;
    }
  });
}

//fade alert after 3 seconds
window.onload = function() {
  checkCollapsibleButton();//show order that have not been delivered in driver delivering page
  fadeAlert();
  setUpCalendar();
  checkResume();// for driver home to check is there is any group is in delivering
}
function checkCollapsibleButton(){
  const collapsibleButtons1 = document.querySelectorAll(".delivery-group-future-order")
  collapsibleButtons1.forEach(c=>{
    c.nextElementSibling.style.maxHeight = c.nextElementSibling.scrollHeight + "px"
  })
}
function setUpCalendar(){
  const today = new Date();
  //document.getElementById("calendar").value = today.getFullYear() + '-' + ('0' + (today.getMonth() + 1)).slice(-2) + '-' + ('0' + today.getDate()).slice(-2);
  let celandar = document.querySelectorAll('.calendar');
  celandar.forEach(c =>{
    c.min = today.getFullYear() + '-' + ('0' + (today.getMonth() + 1)).slice(-2) + '-' + ('0' + today.getDate()).slice(-2);
  });
  //celandar.forEach(celandar.min = today.getFullYear() + '-' + ('0' + (today.getMonth() + 1)).slice(-2) + '-' + ('0' + today.getDate()).slice(-2));
}
//Fade out the arlert after 3 second
function fadeAlert(){
  const success = document.querySelectorAll('.alert-success')
  success.forEach(s=>{
      var strongSucess = document.createElement('strong')
      strongSucess.appendChild(document.createTextNode('Success! '))
      s.prepend(strongSucess)
  })
  const failed = document.querySelectorAll('.alert-danger')
  failed.forEach(f=>{
      var strongFailed = document.createElement('strong')
      strongFailed.appendChild(document.createTextNode('Failedd! '))
      f.prepend(strongFailed)
  })
  $('.alert-dhp').fadeOut(3000);
}
function checkResume() {
  if (document.getElementById("btn-resume") != null) {
    // do something, it exists 
    const startbuttons = document.getElementsByClassName('button-start');
    for (var i = 0; i < startbuttons.length; i++) {
      //unable start button action and change it color
      if(!startbuttons[i].classList.contains('button-report'))
      startbuttons[i].classList.add("unabled-btn")
      startbuttons[i].classList.remove("button-red")
      startbuttons[i].parentNode.removeAttribute("href")
    }
  }
}

//user profile modal
var profileModal = document.getElementById("profileID");
var profileBtn = document.getElementById("profileBtn");
var span = document.getElementsByClassName("close")[0];

btn.onclick = function() {
  profileModal.style.display = "block";
}
span.onclick = function() {
  profileModal.style.display = "none";
}
window.onclick = function(event) {
  if (event.target == profileModal) {
    modal.style.display = "none";
  }
}