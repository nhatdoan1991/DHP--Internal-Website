
//Show assign button when there is at least a grouporder
const droptable = document.querySelectorAll(".droptarget");
if(droptable.length>1)
{ 
   document.getElementById('assign-button').style.removeProperty('display');
}

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
      content = content.nextElementSibling;
    }
  });
}
//dropdown
function dropDown() {
  document.getElementById("myDropdown").classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('.button-drop')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
 }
//Filter for orders
function filterTable() {
  var input, filter, table, tr, td, i, txtValue,txtValue2, txtValue3, txtValue4;
  input = document.getElementById("mySearchInput");
  filter = input.value.toUpperCase();
  //table = document.getElementById("allOrderTable");
  tables= document.getElementsByClassName("table-content");
  for(j = 0; j < tables.length; j++)
  {
    tr = tables[j].getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
      td = tr[i].getElementsByTagName("td")[0];
      td2 = tr[i].getElementsByTagName("td")[1];
      td3 = tr[i].getElementsByTagName("td")[2];
      td4 = tr[i].getElementsByTagName("td")[3];
      if (td || td2 || td3) {
        txtValue = td.textContent || td.innerText;
        txtValue2 = td2.textContent || td2.innerText;
        txtValue3 = td3.textContent || td3.innerText;
        txtValue4 = td4.textContent || td4.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1 ||
            txtValue2.toUpperCase().indexOf(filter) > -1 ||
            txtValue3.toUpperCase().indexOf(filter) > -1 ||
            txtValue4.toUpperCase().indexOf(filter) > -1) {
          tr[i].style.display = "";
        }else {
          tr[i].style.display = "none";
        }
      }
    }
  }
  //tr = table.getElementsByTagName("tr");
 
}

// Drag and Drop JS
const draggables = document.querySelectorAll(".draggable")
const containers = document.querySelectorAll(".droptarget")
//flag for draggable button
var submitFlag = false
//
const dragform = document.getElementById('draggable-form')
draggables.forEach(d =>{
  d.addEventListener('dragstart',()=>{
    d.classList.add('dragging')
    containers.forEach(c=>{
      c.classList.add('targetting')
    })
    const dragging = document.querySelector('.dragging')
    let nextSib = dragging.nextElementSibling
    while(nextSib)
    {
      nextSib.setAttribute('value',parseInt(nextSib.getAttribute('value'))-1)
      nextSib=nextSib.nextElementSibling
    }
  })
  d.addEventListener('dragend',(e)=>{
    e.preventDefault();
    d.classList.remove('dragging')
    const dropzone = document.querySelectorAll('.droptarget')
    dropzone.forEach(drop=>{
      if(drop.className != "droptarget dropping")
      {
        const deletecollapse = drop.querySelectorAll('.content-collapsible')

        deletecollapse.forEach(z=>{
          const deletetable = z.querySelectorAll('.table-body')
          deletetable.forEach(t=>{
            const tr = t.querySelectorAll('.draggable')
            if(tr.length == 0)
            {
              if(drop.id != 'null'){
                var deletebut = document.createElement('button')
                deletebut.setAttribute('class','btn btn-danger btn-drop')
                var itag = document.createElement('i')
                itag.setAttribute('class','fas fa-trash-alt')
                itag.setAttribute('aria-hidden','true')
                itag.appendChild(document.createTextNode('Drop'))
                deletebut.appendChild(itag)
                drop.appendChild(deletebut)
              }
              var ptag = document.createElement('p')
              ptag.setAttribute('class','no-order-table')
              var text = document.createTextNode('There is no orders in this group')
              ptag.appendChild(text)
              drop.appendChild(ptag)
              z.remove()
            }
          })
        })
      }
      drop.classList.remove('dropping')
      containers.forEach(c=>{
        c.classList.remove('targetting')
      })
    })
    const groupsIndex = document.querySelectorAll(".table-body")
    groupsIndex.forEach(g=>{
      let firstOrder = g.firstElementChild
      while(firstOrder){
        var index = document.createElement('input')
        index.setAttribute('type','hidden')
        index.setAttribute('name','indexof'+firstOrder.id)
        if(g.classList.contains('table-pending-body'))
        {
          index.setAttribute('value',0)
        }else{
          index.setAttribute('value',firstOrder.getAttribute('value'))
        }
        firstOrder=firstOrder.nextElementSibling
        dragform.appendChild(index)
      }
    })
  })
})

containers.forEach(c =>{
  c.addEventListener('dragover',e=>{
      e.preventDefault();
      const table = c.querySelector('.table-body')
      const dragging = document.querySelector('.dragging')
      const afterElement = getDragAfterElement(table, e.clientY)
      if(afterElement ==null){
        table.appendChild(dragging)
      }else{
        table.insertBefore(dragging,afterElement)
      }
  })
  // actions on drop
  c.addEventListener('drop',e=>{

    e.preventDefault();
    const dragging = document.querySelector('.dragging')
    const table = c.querySelector('.table-body')
    //set the submit flag to be true so when clicking on the draggable button again. the form will be submit
    submitFlag=true
    //set up input request for the form
    var orderid = document.createElement('input')
    orderid.setAttribute('type','hidden')
    orderid.setAttribute('name','orderid'+dragging.id)
    orderid.setAttribute('value',c.id)
    dragform.appendChild(orderid)
    //if empty group
    if(table == null)
    {
      //create new table with table header + table body to append dragging tag to the body
      var ptag = c.querySelectorAll(".no-order-table")  
      ptag.forEach(p =>{
        p.parentNode.removeChild(p);
      })
      var deleteButton = c.querySelectorAll(".btn-drop")  
      deleteButton.forEach(b =>{
        b.parentNode.removeChild(b);
      })
      var collapsiblediv = document.createElement("DIV")
      collapsiblediv.setAttribute("class","content-collapsible")
      var newtable = document.createElement("TABLE")
      newtable.setAttribute("class","order-queue")
      var newthead = document.createElement("thead")
      var th1 = document.createElement("th")
      var text1 = document.createTextNode('Order Number')
      th1.appendChild(text1);
      var th2 = document.createElement("th")
      var text2 = document.createTextNode('Name')
      th2.appendChild(text2);
      var th3 = document.createElement("th")
      var text3 = document.createTextNode('Item')
      th3.appendChild(text3);
      var th4 = document.createElement("th")
      var text4 = document.createTextNode('Address')
      th4.appendChild(text4);
      var th5 = document.createElement("th")
      var text5 = document.createTextNode('Delivery Date')
      th5.appendChild(text5)
      var th6 = document.createElement("th")
      var text6 = document.createTextNode('Action')
      th6.appendChild(text6)
      newthead.appendChild(th1)
      newthead.appendChild(th2)
      newthead.appendChild(th3)
      newthead.appendChild(th4)
      newthead.appendChild(th5)
      newthead.appendChild(th6)
      var newtbody = document.createElement("tbody")
      newtbody.setAttribute('class','table-body')
      newtbody.appendChild(dragging)
      newtable.appendChild(newthead)
      newtable.appendChild(newtbody)
      collapsiblediv.appendChild(newtable)
      newtbody.appendChild(dragging)
      c.appendChild(collapsiblediv)
      c.classList.add('dropping')
      dragging.setAttribute('value',1)
    }
    else{ // if not then attach to the end of the table
      const afterElement = getDragAfterElement(table, e.clientY)
      if(afterElement ==null){
        table.appendChild(dragging)
        dragging.setAttribute('value',parseInt(dragging.previousElementSibling.getAttribute('value'))+1)
      }else{
        table.insertBefore(dragging,afterElement)
        dragging.setAttribute('value',afterElement.getAttribute('value'))
        let nextSib = dragging.nextElementSibling
        while(nextSib)
        {
          nextSib.setAttribute('value',parseInt(nextSib.getAttribute('value'))+1)
          nextSib=nextSib.nextElementSibling
        }
      }
      
    }
  })
})
//able/unable draggable button

document.getElementById("dragble-button").onclick = function() {ableDraggableButton()};
function ableDraggableButton() {
    if(submitFlag){
      document.getElementById("dragble-button").type = "submit"
      submitFlag=false
    } 
    var x =document.getElementsByClassName("draggable");
    for (i = 0; i < x.length; i++) {
        let temp = x[i].getAttribute("Draggable");
        if(temp=="false")
        { 
        x[i].setAttribute("draggable","true");
        document.getElementById("dragble-button").style.backgroundColor = "#333333";
        document.getElementById("unlockIcon").className = "fa fa-lock-open";
        }else if(temp=="true")
        {
        x[i].setAttribute("draggable","false");
        document.getElementById("dragble-button").style.backgroundColor = "#007BFF";
        document.getElementById("unlockIcon").className = "fa fa-lock"; 
        }
    }
}
function getDragAfterElement (table, y)
{
  const draggableElements = [...table.querySelectorAll('.draggable:not(.dragging)')]
  return draggableElements.reduce((closest, child)=>{
    const box = child.getBoundingClientRect()
    const offset = y- box.top-box.height/2
    if(offset<0 && offset > closest.offset)
    {
      return {offset: offset, element: child}
    }else{
      return closest
    }
  },{offset: Number.NEGATIVE_INFINITY}).element
}