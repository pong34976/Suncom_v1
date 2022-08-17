

 

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
  
      { title : 'img' },
      { title: 'serial_agency' },
      { title: 're_type' },
 
      { title: 're_model' } 
      
  
             ],
             select: true,
           
             
              "processing": true,
              "serverSide": true,
              "pageLength": 10,
    // dom: 'Bfrtip',   
              "ajax":{
                  url :"model/repair.php?keyword=serial", // json datasource
                  type: "post",                // method  , by default get
                  error: function(data){                 // error handling
                      $(".customer-grid-error").html("");
                      $("#customer-grid").append('<tbody class="customer-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                      $("#customer-grid_processing").css("display","none");
  
                  }
              }, columnDefs:
              [{
                  "targets": 0,
                  data: 'img',
                  "render": function (data, type, row, meta) {
                      // console.log(row[0]);
                  
                    if (row[0] != null && row[0] != ""){
                    src= row[0];
                  }else{
                    src= "imagesystem/null.png";
 
                  }
                      return '<img src="' + src + '" width="100" height="100" alt="' + src + '"height="16" width="16"/>';
                  }
              }],
              
      
          
      } );
     $tbj =  $(".tbj").DataTable( {

        columns: [
      
          { title : 'ภาพ' },
          { title: 'หมายเลข' },
          { title: 'อปกรณ์' },
          { title: 'ยี่ห้อ' }  
              ] });

           $tbj.row.add( [ '<img class="zoom img-table" src="images/ss.jpg?result=0.8134995565644354" width="50" height="50" alt="images/ss.jpg">', 32, 'อปกรณ์','ยี่ห้อ' ] ).draw();
        
      $("#tableaddoj").DataTable( {

        columns: [
      
          { title : 'หมายเลข' },
          { title: 'อุปกรณ์' },
          { title: 'ยี่ห้อ/รุ่น' },
          { title: 'ภาพ' } 
          
      
                 ],
                 select: true,
               
                 
                  "processing": true,
                  "serverSide": true,
                  "pageLength": 10,
        // dom: 'Bfrtip',   
                  "ajax":{
                    url :"model/equip.php?keyword=view", // json datasource
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
                          return '<img class="zoom img-table" src="' + src + '?result=' + result + '" width="100" height="100" alt="' + src + '"height="16" width="16"/>';
                      }
                  }],
                  
          
              
          } );
        var dataTable =  $("#myTables").DataTable( {
            buttons: [""
                //  `  <table class="ta-show table table-hover text-center  align-middle table-sm" ><tr></tr></table>`  
  // ` <button  data-bs-toggle="modal" data-bs-target="#pdfModal" aria-describedby="helpId" placeholder="" class="p-report d-none dt-button buttons-pdf buttons-html5" tabindex="0" aria-controls="myTables" type="button"><span>ใบส่งซ๋อม</span></button>`      
          ],
  columns: [

    { title : 'เลขรับ' },
    { title: 'วันที่รับ' },
    { title: 'วันที่คืน' },
    { title: 'เลข' },
    { title: 'ประเภท' },

    { title: 'ยี่ห้อ/รุ่น' },
    { title: 'อาการ' },
    { title: 'การดำเนนการ' },
    { title: 'หน่วยงาน' },
    { title: 'ผู้ประเมิน'  },
    { title: 'สถานะ'  },
    { title: 'ภายนอก'  },
    { title: 'ส่งคืน'  },
           ],
           select: true,
         
           
            "processing": true,
            "serverSide": true,
            "pageLength": 10,
  dom: 'lfrBtip',
     
            "ajax":{
              type:"json",
                url :"model/repair.php?keyword=view", // json datasource
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
            , "columnDefs": [
              {
                  "targets": [ 11,12 ],
                  // "visible": false,
                  // "searchable": false
                  "class": "d-none",
              },
             
            ]
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
url:  `model/repair.php?keyword=${key}`,
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
$("#form-del").submit(function( event ){
 
  event.preventDefault();
  key =   "del";
 
  $.ajax({
  // dataType: "json",
  type: "POST",
  url:  `model/repair.php?keyword=${key}`,
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
 
  
  
  }
  }); 
  
  
  
  
  });
  $("#tableaddoj tbody").on("mouseup","tr",function(e){
    var Name = $(this);
    
    if(Name.find('td:first').text()!="No data available in table"){
    
    
    $('.btn-edit ').prop( "disabled", false );
    // $('.form-add').hide();
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
    
    $('#id_repair').val(Name.find('td:first').text());
    $('#re_date_received').val(Name.find('td:nth-child(2)').text());
    $('#re_due_date').val(Name.find('td:nth-child(3)').text());
    $('#re_serial_number').val(Name.find('td:nth-child(4)').text());
    $('#re_type').val(Name.find('td:nth-child(5)').text());
    $('#re_model').val(Name.find('td:nth-child(6)').text());
    $('#re_breakdown').val(Name.find('td:nth-child(7)').text());
    $('#re_operation').val(Name.find('td:nth-child(8)').text());
    $('#re_agency').val(Name.find('td:nth-child(9)').text());
    $('#re_assessor').val(Name.find('td:nth-child(10)').text());
    $('#re_status').val(Name.find('td:nth-child(11)').text());
    $('#btn-pdf').val(Name.find('td:first').text());
    $('#myid').val(Name.find('td:first').text());
    $('#delname').html(`${Name.find('td:nth-child(6)').text()} (${Name.find('td:nth-child(4)').text()}) `);
    
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

$('#id_repair').val(Name.find('td:first').text());
$('#re_date_received').val(Name.find('td:nth-child(2)').text());
$('#re_due_date').val(Name.find('td:nth-child(3)').text());
$('#re_serial_number').val(Name.find('td:nth-child(4)').text());
$('#re_type').val(Name.find('td:nth-child(5)').text());
$('#re_model').val(Name.find('td:nth-child(6)').text());
$('#re_breakdown').val(Name.find('td:nth-child(7)').text());
$('#re_operation').val(Name.find('td:nth-child(8)').text());
$('#re_agency').val(Name.find('td:nth-child(9)').text());
$('#re_assessor').val(Name.find('td:nth-child(10)').text());
$('#re_status').val(Name.find('td:nth-child(11)').text());
$('#btn-pdf').val(Name.find('td:first').text());
$('#myid').val(Name.find('td:first').text());
$('#delname').html(`${Name.find('td:nth-child(6)').text()} (${Name.find('td:nth-child(4)').text()}) `);

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
$("#btn-pdf").on("click",function(){


  $("#show-pdf").prop("src","print/pdf.php?id_repair=" + $("#btn-pdf").val());
})

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
             $('#id').val("");
             $('#total').val("");
             $('#detal').val("");
             $(this).removeClass('selected');


   }  
   function ojclose(){
    $("#tableaddoj tbody").removeClass('selected');
        $('#tableaddoj tbody tr.selected').removeClass('selected');
           $('#addojModal').modal('toggle');
            // $('#id').val("");
            // $('#total').val("");
            // $('#detal').val("");
            $(this).removeClass('selected');


  }  
   function pdfclose(){
  
           $('#pdfModal').modal('toggle');
          


  } 
       $(".kulu-ok").on("click",function(){
       
             $('#re_serial_number').val($('#id').val())    ;
              $('#re_type').val($('#total').val())    ;
               $('#re_model').val($('#detal').val())    ;
         
               kuluclose();


       });

       $(".oj-ok").on("click",function(){
       
        $('#re_serial_number').val($('#id').val())    ;
         $('#re_type').val($('#total').val())    ;
          $('#re_model').val($('#detal').val())    ;
    
          ojclose();


  });

      $("#tablekulu tbody").on("mouseup","tr",function(e){
       
        
        var Name = $(this);
       
      if(Name.find('td:first').text()!="No data available in table"){
       
      if ( $(this).hasClass('selected') ) {
      
                  $(this).removeClass('selected');
                  $('#id').val("");
                 $('#total').val("");
                 $('#detal').val("");
              } else {
                $('#id').val(Name.find('td:nth-child(2)').text());
              
                    $('#total').val(Name.find('td:nth-child(3)').text());
                     $('#detal').val(Name.find('td:nth-child(4)').text());  
                  $('tr.selected').removeClass('selected');
              
               
              }
          
           
           
          }
      });
      $("#tableaddoj tbody").on("mouseup","tr",function(e){
        alert("sss");
        
        var Name = $(this);
       
      if(Name.find('td:first').text()!="No data available in table"){
        if ( $(this).hasClass('selected') ) {

        }
      
           
           
          }
      });