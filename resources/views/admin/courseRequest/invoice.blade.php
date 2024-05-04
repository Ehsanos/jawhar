<html>
    <head>
        <meta charset="utf-8" />
        
        <title>Ashiaa</title>
        
        <link href="https://fonts.googleapis.com/css?family=Cairo:300,400,600,700" rel="stylesheet" type="text/css"/>
        <style>
        
       body
       {
           font-family:'Cairo' !important;
           font-size:12px;
           font-weight:bold;
       }
        
            .container {
                width: 500px;
                margin: auto;
                direction:rtl;
             }
            .text-center {
                text-align: center !important
            }
            .logo {
                text-align: center;
                overflow: hidden
            }
            .secHead {
                text-align:center;
                
            }
            .clearfix {
                clear: both
            }
            .titleBody h4 {
                margin-bottom: 0;
            }
            .titleBody p {
                margin: 0;
            }
            .secBody .titleBody, .secBody .tableBody{
                padding: 5px 10px;
           
            }
            #customers {
                border-collapse: collapse;
                width: 100%;
                font-size:12px !important;
            }
            #customers td, #customers th {
                border: 1px solid #ddd;
                padding: 8px;
                font-weight: bold
            }
            #customers th {
                padding-top: 12px;
                padding-bottom: 12px;
                text-align: center;
                color: #000;
            }
            .width50px {
                width: 60px
            }
            .text-right {
                text-align: right !important
            }
            .text-left {
                text-align: left !important;
                float: left
            }
            .rightF {
                float: right
            }
            .sectit {
                 padding: 0px 40px;
                text-align: center
            }
            .sectit p {
                text-align: justify
            }
        </style>
        
         
        </head>
        
        <body>
    
        <div class="header">
            <div class="container">
                <div class="logo">
                    <img src="{{$setting->logo}}" width="130" height="70" />
                </div>
                <div class="secHead">
                    <p>فاتورة استلام</p>
                </div>
            </div>
        </div>
        
        <div class="clearfix"></div>
        
        <div class="secBody">
           <div class="container">
            
            <div class="tableBody">
                <table id="customers">
                  <tr>
                     <th>{{__('cp.customer-details')}}</th>
                     <th>{{__('cp.order-details')}}</th>
                  </tr>
                  <tr>
                    <td valign="top">
                       {{__('cp.name')}}: <span>{{$order->user->name}}</span>
                        <br>
                        
                        {{__('cp.mobile')}} : <span>{{$order->user->mobile}}</span> 
                        <br>
                        
                        {{__('cp.city')}} : <span>{{$order->city->name}}</span> <br>
                         {{__('cp.address')}} : <span>{{$order->address_name}}</span> <br>
                         {{__('cp.fullAddress')}} : <span>{{$order->full_address}}</span> <br>
                         خط الطول والعرض : <span>{{$order->latitude}}</span> <br><span>{{$order->longitude}}</span> 
                    </td>
                    <td valign="top">
                        
                        {{__('cp.ordernumber')}}: <span>{{$order->id}}</span> 
                        <br>
                        
                         {{__('cp.date')}} :    <span>{{$order->created_at->toDateString()}} </span>
                        <br>
                                                 {{__('cp.note')}} : <span>{{$order->note}}</span> <br>

                         {{__('cp.time')}}:    <span>{{$order->created_at->toTimeString()}} </span>  <br> <br> <br>
                         {{__('cp.payment')}}:    <span> @if (@$order->payment == 1)
                                    عند الاستلام
                                        @endif
                                        @if (@$order->payment == 0)
                                    المحفظة
                                        @endif </span> 

                    </td>
              </table>

                <table id="customers">
                  <tr>
                     <th class="width50px">{{__('cp.number')}}</th>
                    <th> {{ucwords(__('cp.name_product'))}}</th>
                     <th class="width50px">{{__('cp.amount')}}</th>
                     
                     <th class="width50px">{{__('cp.price')}}</th>
                     <th class="width50px">{{__('cp.total')}}</th>
                     
                  </tr>
                    
                    @foreach($products as $item)
                    
                  <tr>
                        <td class="width50px"><span class="text-right">{{$loop->iteration}}</span></td>
                        
                        <td><span>{{$item->product->name}}</span></td>
                        <td class="width50px"><span class="text-right">{{$item->quantity}}</span></td>
                        <td class="width50px"><span class="text-right">{{$item->price}}</span></td>
                        <td class="width50px"><span class="text-right">{{$item->quantity * $item->price}}</span></td>
                  </tr>
                  @endforeach
            <tr>
                    <td colspan="5">{{__('cp.total')}}:</td>
                    <td class="text-right" colspan="1"><span>{{$order->total_price - $order->delivery_cost}}</span></td>
                  </tr>
                 
                  <tr>
                    <td colspan="5"> {{__('cp.invoice_discount')}}:</td>
                    <td class="text-right" colspan="1"><span>{{$order->invoice_discount}}</span></td>
                  </tr>
              
               
                  <tr>
                    <td colspan="5"> {{__('cp.delivery_cost')}}:</td>
                    <td class="text-right" colspan="1"><span>{{$order->delivery_cost}}</span></td>
                  </tr>
                  
                  <tr>
                    <td colspan="5"> {{__('cp.total')}}</td>
                    <td class="text-right" colspan="1"><span>{{@$order->total_price - @$order->invoice_discount}}</span></td>
                  </tr>
              </table>
           
            </div>
          </div>
        </div>
        <br>
        <center>---------------------------------------------------------------------------------------------------------------------------------</center>
        <center>{{$setting->title}}  <span>{{$setting->address}} </span> </center>
        <center>{{$setting->url}} <span>{{$setting->info_email}}</span> <span>{{$setting->mobile}}</span></center>
        <br>
        

        
    </body>
    </html>