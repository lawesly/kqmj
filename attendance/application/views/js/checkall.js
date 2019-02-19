function checkall(){
        var k = document.getElementById("k").value;
        if(document.getElementById("row0").checked==true){
                for(var i=1;i<=k;i++){
                        document.getElementById("row"+i).checked=true;
                }

        }else{
                for(var i=1;i<=k;i++){
                document.getElementById("row"+i).checked=false;
                }
        }
}
