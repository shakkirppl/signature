@php
    $user = Auth::user();
@endphp

<nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="{{URL::to('dashboard')}}">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
           <!-- Masters -->
       @if($user->designation_id == 1 || $user->designation_id == 3)
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#masters" aria-expanded="false" aria-controls="charts">
            <i class="mdi mdi-group menu-icon"></i> 
              <span class="menu-title">Masters </span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="masters">
              <ul class="nav flex-column sub-menu">
             
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('supplier-index')}}">Suppliers</a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('customer-index')}}">Customers</a></li>
              
              
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('localcustomer-index')}}">Local Customers</a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('category-index')}}">Category</a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('product-index')}}">Products</a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('employee-index')}}">Employees</a></li>

        
              </ul>
            </div>
            
          </li>

        
          @endif
          <!-- Users and Currency update -->
          @if($user->designation_id == 1)
          <li class="nav-item">
  <a class="nav-link" href="{{URL::to('users-index')}}">
  <i class="mdi mdi-group menu-icon"></i> 
  <span class="menu-title">Users</span>
  </a>
</li> 

 
<li class="nav-item">
  <a class="nav-link" href="{{URL::to('/usd-to-shilling')}}">
  <i class="mdi mdi-group menu-icon"></i> 
  <span class="menu-title">Currency Update</span>
  </a>
</li> 

@endif
     
<!-- Accounts -->
@if($user->designation_id == 1 || $user->designation_id == 3 || $user->designation_id == 4)
<li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#accounts" aria-expanded="false" aria-controls="charts">
            <i class="mdi mdi-group menu-icon"></i> 
              <span class="menu-title">Accounts </span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="accounts">
              <ul class="nav flex-column sub-menu">
           
              @if($user->designation_id == 1 || $user->designation_id == 3 )
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('bank-master-index')}}">Bank Master</a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('supplier-payment-index')}}">Supplier Payment</a></li>

              <li class="nav-item"> <a class="nav-link" href="{{URL::to('customer-payment-index')}}">Customer Payment</a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('paymentvoucher-index')}}">payment Voucher </a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('airline-index')}}">Airline Payment</a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('receiptvoucher-index')}}">Receipt Voucher </a></li>  
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('expensevoucher-index')}}">Expense Voucher </a></li>  
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('/account-heads')}}">COA </a></li>  
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('ledger')}}"> Ledger </a></li>
              @endif
              @if($user->designation_id == 1 || $user->designation_id == 3 || $user->designation_id == 4)
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('supplier-advance-index')}}">Supplier Advance </a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('return-payment-index')}}">Return Payment </a></li>

              @endif

              </ul>
            </div>
      </li>   

@endif
<!-- Sales -->
 
@if($user->designation_id == 1 || $user->designation_id == 3|| $user->designation_id == 10)
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#sales" aria-expanded="false" aria-controls="charts">
            <i class="mdi mdi-group menu-icon"></i> 
              <span class="menu-title">Sales </span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="sales">
              <ul class="nav flex-column sub-menu">
              @if($user->designation_id == 1 || $user->designation_id == 3)
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('goodsout-order-index')}}">Sales order</a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('sales_payment-index')}}">Sales</a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('packinglist-index')}}">Packing List</a></li>
              @endif
              @if($user->designation_id == 1 || $user->designation_id == 10)
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('offal-sales-index')}}">Offal Sales</a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('offal-receive-index')}}">Offal Receive</a></li>
              @endif
              
              </ul>
            </div>
          </li>
         @endif 

    <!--  -->
         <!-- Animal Purchase Order -->

        @if($user->designation_id == 1 || $user->designation_id == 3|| $user->designation_id == 5|| $user->designation_id == 6|| $user->designation_id == 7|| $user->designation_id == 4 || $user->designation_id == 9)
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#animal-purchase-order" aria-expanded="false" aria-controls="charts">
            <i class="mdi mdi-group menu-icon"></i> 
              <span class="menu-title">Animal Purchase Order </span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="animal-purchase-order">
              <ul class="nav flex-column sub-menu">
              @if($user->designation_id == 1 || $user->designation_id == 3|| $user->designation_id == 5 || $user->designation_id == 4)

              <li class="nav-item"> <a class="nav-link" href="{{URL::to('purchase-order-index')}}">Purchase order</a></li> 
              @endif 
              @if($user->designation_id == 1 || $user->designation_id == 3|| $user->designation_id == 5)

              <li class="nav-item"> <a class="nav-link" href="{{URL::to('purchade-conformation-index')}}">Purchase Confirmation</a></li>
              @endif 
              @if($user->designation_id == 1 || $user->designation_id == 6 || $user->designation_id == 9 )

              <li class="nav-item"> <a class="nav-link" href="{{URL::to('inspection-index')}}">Inspection </a></li> 
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('/animal-receive-notes')}}">Animal Receive Notes </a></li> 

            

              <li class="nav-item"> <a class="nav-link" href="{{URL::to('/deathanimal')}}">Death Animal</a></li> 
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('/rejected-animal-report')}}">Rejected Animal Report</a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('antemortem-report-index')}}">Antemortem Report</a></li> 
            @endif
            @if($user->designation_id == 1 || $user->designation_id == 7 || $user->designation_id == 9)
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('postmortem-report-index')}}">Postmortem Report</a></li> 
             @endif
              </ul>
            </div>
          </li>

          @endif
<!-- Slaughter Schedule -->


</li> 
@if($user->designation_id == 1 || $user->designation_id == 2|| $user->designation_id == 23)
<li class="nav-item">
  <a class="nav-link" href="{{URL::to('slaughter-schedules-index')}}">
  <i class="mdi mdi-group menu-icon"></i> 
  <span class="menu-title">Slaughter Schedule</span>
  </a>
</li> 
@endif


<!--  -->
@if($user->designation_id == 1)
 
<li class="nav-item">
  <a class="nav-link" href="{{URL::to('index-new-scheduletime')}}">
  <i class="mdi mdi-group menu-icon"></i> 
  <span class="menu-title">Schedule Slaughter Time</span>
  </a>
</li>
@endif

<!--  -->
@if($user->designation_id == 1 || $user->designation_id == 9)
<li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#quality-department" aria-expanded="false" aria-controls="charts">
            <i class="mdi mdi-group menu-icon"></i> 
              <span class="menu-title">Quality Control Department </span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="quality-department">
              <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('gmp-index')}}">GMP</a></li> 
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('temperature-monitoring-index')}}">Temperature Monitoring</a></li>  
               <li class="nav-item"> <a class="nav-link" href="{{URL::to('cleaning-sanitation-index')}}">Cleaning Sanitation </a></li> 
               <li class="nav-item"> <a class="nav-link" href="{{URL::to('corrective-action-index')}}">Corrective Action Report</a></li> 
               <li class="nav-item"> <a class="nav-link" href="{{URL::to('customer-complaint-index')}}">Customer Complaint</a></li> 
               <li class="nav-item"> <a class="nav-link" href="{{URL::to('internal-auditchecklist-index')}}">Internal Audit Checklist</a></li> 
               <li class="nav-item"> <a class="nav-link" href="{{URL::to('calibration-record-create')}}">Calibration Record</a></li> 
              </ul>
            </div>
          </li> 
@endif
<!--  -->


@if($user->designation_id == 1 || $user->designation_id == 2|| $user->designation_id == 8 || $user->designation_id == 5 || $user->designation_id == 3)
<li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#production-department" aria-expanded="false" aria-controls="charts">
            <i class="mdi mdi-group menu-icon"></i> 
              <span class="menu-title">Production Department </span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="production-department">
              <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('production-record-index')}}">Production Record</a></li> 
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('dispatch-record-index')}}">Dispatch Record</a></li> 
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('chilling-room-index')}}">Chilling Room</a></li> 
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('breakdown-report-index')}}">Breakdown Report</a></li>
              </ul>
            </div>
          </li> 
      <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#production" aria-expanded="false" aria-controls="charts">
            <i class="mdi mdi-group menu-icon"></i> 
              <span class="menu-title">Production </span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="production">
              <ul class="nav flex-column sub-menu">
              @if($user->designation_id == 1 || $user->designation_id == 2 || $user->designation_id == 5|| $user->designation_id == 3)
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('skinning-index')}}">Skinning</a></li> 
              @endif
              @if($user->designation_id == 1 || $user->designation_id == 8 || $user->designation_id == 5|| $user->designation_id == 3)
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('/weight-calculator')}}">Weight Calculator</a></li> 
              @endif

              </ul>
            </div>
          </li>

   @endif
   <!--  -->

<!--  -->
           
 

<!--  -->
 <!--Shipemt -->
 @if($user->designation_id == 1 || $user->designation_id == 3)
       <li class="nav-item">
  <a class="nav-link" href="{{URL::to('shipment-index')}}">
  <i class="mdi mdi-group menu-icon"></i> 
  <span class="menu-title">Shipment</span>
  </a>
</li> 
@endif
@if($user->designation_id == 1 || $user->designation_id == 3|| $user->designation_id == 4|| $user->designation_id == 5)
<li class="nav-item">
   <a class="nav-link" href="{{URL::to('requesting-form-index')}}">
   <i class="mdi mdi-group menu-icon"></i> 
   <span class="menu-title">Requesting Form</span>
   </a>
 </li>
          @endif
<!--  -->

@if($user->designation_id == 1 || $user->designation_id == 3 || $user->designation_id == 5 || $user->designation_id == 4)
<li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#report" aria-expanded="false" aria-controls="charts">
            <i class="mdi mdi-group menu-icon"></i> 
              <span class="menu-title">Report </span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="report">
              <ul class="nav flex-column sub-menu">
              @if($user->designation_id == 1 || $user->designation_id == 3 )
              <!-- <li class="nav-item"> <a class="nav-link" href="{{URL::to('shipment-profit')}}"> Shipment Profit Report</a></li> -->
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('weight-calculator-report')}}">Weight Calculator</a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('purchase-conformation-report')}}">Purchase Confirmation </a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('inspection-report')}}">Inspection </a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('purchase-order-report')}}">Purchase Order </a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('sales_payment-report')}}">Sales </a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('goodsout-order-report')}}">Sales Order </a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('offal-sales-report')}}">Offal Sales  </a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('paymentvoucher-report')}}">Payment Voucher  </a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('receiptvoucher-report')}}">Receipt Voucher  </a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('supplier-payment-report')}}">Supplier Payment </a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('customer-payment-report')}}">Customer Payment  </a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('skinning-report')}}"> Skinning  </a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('customer-ledger')}}"> Customer Ledger  </a></li>
              <li class="nav-item"> <a class="nav-link" href="{{URL::to('customer-outstanding')}}"> Customer Outstanding  </a></li>
              @endif  
              @if($user->designation_id == 1 || $user->designation_id == 3 || $user->designation_id == 5 || $user->designation_id == 4)             
               <li class="nav-item"> <a class="nav-link" href="{{URL::to('supplier-outstanding')}}"> Supplier Outstanding  </a></li>
               <li class="nav-item"> <a class="nav-link" href="{{URL::to('supplier-ledger')}}"> Supplier Ledger  </a></li>
               <li class="nav-item"> <a class="nav-link" href="{{URL::to('shipment-report')}}"> Shipment Report</a></li>

              @endif 
              </ul>
            </div>
      </li>    

     @endif     
           
     @if($user->designation_id == 1||$user->designation_id == 23|| $user->designation_id == 9)
 
 <li class="nav-item">
   <a class="nav-link" href="{{URL::to('customer-feedback-create')}}">
   <i class="mdi mdi-group menu-icon"></i> 
   <span class="menu-title">Customer Feedback</span>
   </a>
 </li>
 
 @endif

 




  

        


        </ul>
      </nav>