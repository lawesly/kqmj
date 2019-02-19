function shows(){
  document.getElementById('t1').style.display:none;
  document.getElementById('t2').style.display:none;
  var value=document.getElementById('type').value;
  if(value=='dev'){
    document.getElementById('t1').style.display='block';
    }
  if(value=='ass'){
    document.getElementById('t2').style.display='block';
   }
}
