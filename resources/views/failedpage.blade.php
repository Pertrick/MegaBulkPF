@include('partials.header')
    <style type="text/css">

    body
    {
        background:#f2f2f2;
    }

    .payment
	{
		border:1px solid #f2f2f2;
        border-radius:20px;
        background:#fff;
	}
   .payment_header
   {
	   background:#DC3545;
	   padding:20px;
       border-radius:20px 20px 0px 0px;
	   
   }
   
   .check
   {
	   margin:0px auto;
	   width:50px;
	   height:50px;
	   border-radius:100%;
	   background:#fff;
	   text-align:center;
   }
   
   .check i
   {
	   vertical-align:middle;
	   line-height:50px;
	   font-size:30px;
   }

    .content 
    {
        text-align:center;
    }

    .content h5{
        padding:10px 0;
    }
</style>
<body class="overflow-x-hidden">
    <div class="container">
   <div class="row">
      <div class="col-md-6 mx-auto mt-5">
         <div class="payment">
            <div class="payment_header">
               <div class="check"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></div>
            </div>
            <div class="content">
               <h2 class="pt-5">Payment Failed !</h2>
               <h5>Email: <span class="font-weight-bold">{{$payment->email}}</span></h5>
               <h5>Reference Id: <span class="font-weight-bold">{{$payment->reference_id}}</span></h5>
               <h5>Service: <span class="font-weight-bold">Bulk {{$payment->service}} </span></h5>
               <h5>Amount: &#8358; <span class="font-weight-bold">{{number_format($payment->amount,3)}}</span></h5>

               <a href="{{ url('/') }}" class="btn btn-success m-5">Return back Home</a>
            </div>
            
         </div>
      </div>
   </div>
</div>
</body>
</html>



