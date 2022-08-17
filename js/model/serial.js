

 
      upstatus();
      function upstatus(){
   mystatus = $("#re_status").val();
   $(".sta .btn-primary  ").prop("disabled",true);
   $(".sta .btn-warning").prop("disabled",true);
   $(".sta .btn-success").prop("disabled",true);
  $(".sta .btn-success").prop("disabled",true);
   if(mystatus=="เสร็จ"){
    $(".sta .btn-success").prop("disabled",false);
   }else if(mystatus=="กำลังดำเนินการ"){
    $(".sta .btn-warning").prop("disabled",false);
   }else  {
    $(".sta .btn-primary").prop("disabled",false);
   }

      }
   
   $(document).ready(function() {
       // dom: 'Bfrtip'
// dom: 'Pfrtip'
// dom: 'lrtip'
// dom: '<"wrapper"flipt>'
 $("#tablekulu").DataTable( {

    columns: [
  

      { title : 'id_hardware' },
      
      { title: 'ประเภท' },
      { title: 're_model' },
      { title: 'img' }
  
             ],
             select: true,
           
             
              "processing": true,
              "serverSide": true,
              "pageLength": 4,
    // dom: 'Bfrtip',   
              "ajax":{
                  url :"model/hardware.php?keyword=view", // json datasource
                  type: "post",                // method  , by default get
                  error: function(data){                 // error handling
                      $(".customer-grid-error").html("");
                      $("#customer-grid").append('<tbody class="customer-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                      $("#customer-grid_processing").css("display","none");
  
                  }
              }, columnDefs:
              [{
                  "targets": 3,
                  data: 'img',
                  "render": function (data, type, row, meta) {
                
                  
                    if (row[3] != null && row[3] != "" && row[3] != 0){
                     
                    src= row[3];
                  }else{
                    src= "imagesystem/null.png";
 
                  }
                  let result = Math.random();
                      return '<img class="img-table" src="' + src + '?result=' + result + '" width="100" height="100" alt="' + src + '"height="16" width="16"/>';
                  }
              }],
                
          
      } );

        var dataTable =  $("#myTables").DataTable( {
            buttons: [""
                //  `  <table class="ta-show table table-hover text-center  align-middle table-sm" ><tr></tr></table>`  
  // ` <button  data-bs-toggle="modal" data-bs-target="#pdfModal" aria-describedby="helpId" placeholder="" class="p-report d-none dt-button buttons-pdf buttons-html5" tabindex="0" aria-controls="myTables" type="button"><span>ใบส่งซ๋อม</span></button>`      
          ],
  columns: [

    { title : 'serial_id' },


    { title: 'serial_agency' },
    { title: 'phones' },
    { title: 'room' } , 
    { title: 'id_hardware' },  
     { title : 'type' },
    { title : 'model' }
 
           ],
           select: true,
         
           
            "processing": true,
            "serverSide": true,
            "pageLength": 10,
  dom: 'lfrBtip',
     
            "ajax":{
              type:"json",
                url :"model/serial.php?keyword=view", // json datasource
                type: "post",                // method  , by default get
                error: function(datas){  
                  
                  data = datas.responseJSON;
                  $("#liveToast .toast-header ").even().removeClass( "bg-success" );
$("#liveToast .toast-header ").even().removeClass( "bg-warning" );
$("#liveToast .toast-header ").even().removeClass( "bg-danger" );
$("#liveToast .toast-header ").addClass(data.bg);

// $("#liveToast .toast-header  me-auto").html("บันทึกข้อมูลเรียบร้อย");
$("#liveToast .toast-header").html("พบข้อผิดพลาด !!");
$("#liveToast .toast-body").html(data.message);
$("#liveToast").toast('show');
             // error handling
                    $(".customer-grid-error").html("");
                    $("#customer-grid").append('<tbody class="customer-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#customer-grid_processing").css("display","none");

                }
            } 
              // , "columnDefs": [
              //   {
              //       "targets": [ 12 ],
              //       "visible": false,
              //       "searchable": false
              //   }]  
        } );
        
    } );

    $("#form-add").submit(function( event ){

event.preventDefault();
key =   $('.form-add #action').val()
//  alert(key);
$.ajax({
// dataType: "json",
type: "POST",
url:  `model/serial.php?keyword=${key}`,
data: $( this ).serialize(),
success: function(data){
// $("#liveToast").toggle();
$("#myTables").DataTable().ajax.reload();
$("#liveToast .toast-header ").even().removeClass( "bg-success" );
$("#liveToast .toast-header ").even().removeClass( "bg-warning" );
$("#liveToast .toast-header ").even().removeClass( "bg-danger" );
$("#liveToast .toast-header ").addClass(data.bg);

$("#liveToast .toast-header  me-auto").html("บันทึกข้อมูลเรียบร้อย");
$("#liveToast .toast-header").html("บันทึกข้อมูล");
$("#liveToast .toast-body").html(data.message);
$("#liveToast").toast('show');
if(key=="add"){
$('.form-add .input  ').val("");

}else{
$('.form-add ').hide();
}


}
}); 




});

$("#myTables tbody").on("mouseup","tr",function(e){
var Name = $(this);

if(Name.find('td:first').text()!="No data available in table"){


$('.btn-edit ').prop( "disabled", false );
$('.form-add').hide();
if ( $(this).hasClass('selected') ) {

    $(this).removeClass('selected');
    $('.btn-edit ').prop( "disabled", true );
   $('.ta-show tr').html("");
   $(".p-report").even().removeClass( "d-inline" );
   $(".p-report").addClass( "d-none" );

} else {
  $('.ta-show tr').html($(this).html());
    $('tr.selected').removeClass('selected');

    $(".p-report").even().removeClass( "d-none" );
    $(".p-report").addClass( "d-inline" );
}

$('#serial_id').val(Name.find('td:first').text());
$('#serial_agency').val(Name.find('td:nth-child(2)').text());
 $('#phones').val(Name.find('td:nth-child(3)').text());
 $('#room').val(Name.find('td:nth-child(4)').text());
 


$('#id_hardware').val(Name.find('td:nth-child(5)').text());
$('#re_type').val(Name.find('td:nth-child(6)').text());
$('#re_model').val(Name.find('td:nth-child(7)').text());




if(Name.find('td:nth-child(12)').text()==1){
  $('#re_sendreport').prop("checked",true) ;
}else{
  $('#re_sendreport').prop("checked",false) ;
}
if(Name.find('td:nth-child(13)').text()==1){
  $('#re_sendreturn').prop("checked",true) ;
}else{
  $('#re_sendreturn').prop("checked",false) ;
}
// $('#re_sendreport').prop("checked",true) ;
// $('#re_sendreturn').prop("checked",false) ;
// $('#').val(Name.find('td:nth-child(12)').text());
// $('#').val(Name.find('td:nth-child(13)').text());

}
});
  function  showcreate(key) {

    $('.form-add .action').show();
    $('.form-add #action').val(key);
    switch (key) {
      
      case 'add':
      $('.form-add .action').hide();
   $('.form-add ').hide();
     
        $('.form-add .input  ').val("");
  
      $('.form-add .card-header').html("เพิ่มข้อมูล");
      $('#myTables tbody .selected').even().removeClass( "selected" );
      //  $('.btn-edit ').hide();
      $('.btn-edit ').prop( "disabled", true );
    $('.form-add ').show();
        break;
        case 'edit':
    $('.form-add ').hide();
        $('.form-add .card-header').html("แก้ไขข้อมูล");
    $('.form-add ').show();
    break;
  
      default:
      alert("Error 404 การเรียกใช้งานฟังก์ชั่นไม่ถูกต้อง หรือไม่มีฟังก์ชั่นนี้อยู่");
        break;
    }
    upstatus();
  }
  
$("#re_due_date").change(function(){
    if($("#re_due_date").val()!=""){
      $("#re_status").val("เสร็จ");
      upstatus();
    }
       });

    function kuluclose(){
     $("#tablekulu tbody").removeClass('selected');
         $('#tablekulu tbody tr.selected').removeClass('selected');
            $('#kuluModal').modal('toggle');
             $('#mod_id').val("");
             $('#mod_type').val("");
             $('#mod_model').val("");
             $(this).removeClass('selected');


   }  
 
   function pdfclose(){
  
           $('#pdfModal').modal('toggle');
          


  } 
       $(".kulu-ok").on("click",function(){
 
             $('#id_hardware').val($('#mod_id').val())    ;
              $('#re_type').val($('#mod_type').val())    ;
               $('#re_model').val($('#mod_model').val())    ;
         
               kuluclose();


       });
     

      $("#tablekulu tbody").on("mouseup","tr",function(e){
       
        
        var Name = $(this);
       
      if(Name.find('td:first').text()!="No data available in table"){
       
      if ( $(this).hasClass('selected') ) {
      
                  $(this).removeClass('selected');
                  $('#mod_id').val("");
                 $('#mod_type').val("");
                 $('#mod_model').val("");
              } else {
                $('#mod_id').val(Name.find('td:first').text());
              
                    $('#mod_type').val(Name.find('td:nth-child(2)').text());
                     $('#mod_model').val(Name.find('td:nth-child(3)').text());  
                  $('tr.selected').removeClass('selected');
              
               
              }
          
           
           
          }
      });