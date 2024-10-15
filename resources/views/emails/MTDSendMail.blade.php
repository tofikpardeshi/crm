<table class="table table-striped thead-default table-bordered">
    <thead>
        <tr>
            <th>Employee Name</th>
            <th>Leads Worked</th>
            <th>Leads Worked (MTD)</th>
            <th>CRM Used</th>
            <th>Total CRM Usage</th>
            <th>Total Login</th>
            <th>Last login</th>
            <th>MTD Leads Expired</th>
            <th>MTD Total Leads</th>
            <th>MTD New Leads</th>
            <th>MTD Pending Leads</th>
            <th>MTD Next Follow Up</th>
            <th>MTD Deal Confirmed</th>
            <th>MTD Deal Cancelled</th>
            <th>MTD Closed Leads</th>
            <th>MTD Old Leads</th>
        </tr>
    </thead>
    <tbody>
        
        @foreach ($MTDSendMail as $item)
        @php
                // dd(Carbon\Carbon::parse($item->created_at)->format('d-m-Y H:i'));
            
            
            // $yesterday = now()->subDay(1)->format('Y-m-d'); 
        //    dd($yesterday);
            $startOfMonth = now()->startOfMonth();

            $yesterday = now()->subDay(1)->format('Y-m-d');

            $lastLogin = DB::table('log')
                ->where('user_id', $item->user_id)
                ->whereDate('created_at', $yesterday)
                ->orderBy('created_at', 'desc') // latest method is not needed in this case
                ->first();

            
                $loginData = DB::table('log')
                    ->selectRaw('MIN(created_at) as first_login, MAX(created_at) as last_login')
                    ->where('user_id', $item->user_id)
                    ->whereDate('created_at', $yesterday)
                    ->first();

                

                if ($loginData) {
                    $firstLogin = \Carbon\Carbon::parse($loginData->first_login);
                    $lastLogin = \Carbon\Carbon::parse($loginData->last_login);

                   

                    // Calculate the difference in minutes
                    $totalLoginTime = $lastLogin->diffInMinutes($firstLogin); 
                    // Now $totalLoginTime contains the total login time in minutes
                    // You can convert it to hours, seconds, or any other format as needed
                    // For example, 
                    $totalLoginTimeInHours = number_format($totalLoginTime / 60, 2);
                }


            $expiredLead = DB::table('leads') 
            ->where('assign_employee_id', $item->assign_employee_id)
            ->whereDate('leads.created_at', $yesterday)
            ->where('common_pool_status',0) 
            ->where('lead_status','!=', 14)
            ->count();
            

            $newLeadStatusCount = DB::table('leads') 
            ->where('assign_employee_id', $item->assign_employee_id)
            ->where('lead_status', 1)
            ->whereDate('leads.created_at', $yesterday)
            ->where('common_pool_status',0)
            ->count(); 

            $AllLeadTime = DB::table('leads')
            ->where('assign_employee_id', $item->assign_employee_id)
            ->whereBetween('leads.created_at', [$startOfMonth, $yesterday])
            ->count();

            // dd($AllLeadTime);

            $TotalLeadCount = DB::table('leads') 
            ->where('assign_employee_id', $item->assign_employee_id) 
            ->whereDate('leads.created_at', $yesterday)
            ->where('common_pool_status',0)
            ->count(); 

            // dd($TotalLeadCount);

            $PendingLeadStatusCount = DB::table('leads') 
            ->where('assign_employee_id', $item->assign_employee_id)
            ->where('lead_status', 2)
            ->whereDate('leads.created_at', $yesterday)
            ->where('common_pool_status',0)
            ->count(); 

            $NextFollowUpLeadStatusCount = DB::table('leads') 
            ->where('assign_employee_id', $item->assign_employee_id)
            ->where('lead_status', 5)
            ->whereDate('leads.created_at', $yesterday)
            ->where('common_pool_status',0)
            ->count(); 

 
            $ConfimLeadStatusCount = DB::table('leads') 
            ->where('assign_employee_id', $item->assign_employee_id)
            ->where('lead_status', 14)
            ->whereDate('leads.created_at', $yesterday)
            ->where('common_pool_status',0)
            ->count();
            
            $CancelLeadStatusCount = DB::table('leads') 
            ->where('assign_employee_id', $item->assign_employee_id)
            ->where('lead_status', 15)
            ->whereDate('leads.created_at', $yesterday)
            ->where('common_pool_status',0)
            ->count();
 

            $CloseLeadStatusCount = DB::table('leads') 
            ->where('assign_employee_id', $item->assign_employee_id)
            ->whereIn('lead_status', [8,9,10,11,12])
            ->whereDate('leads.created_at', $yesterday)
            ->where('common_pool_status',1)
            ->count();

            // dd($CloseLeadStatusCount);

            $OldLeadStatusCount = DB::table('leads') 
            ->where('assign_employee_id', $item->assign_employee_id)
            ->where('lead_status',18)
            ->whereDate('leads.created_at', $yesterday)
            ->where('common_pool_status',0)
            ->count();

 

        @endphp
        <tr class="text-center">
            <td>{{ $item->employee_name }}</td>
            <td>{{ $TotalLeadCount }}</td>
            <td>{{ $AllLeadTime }}</td>
           <td>{{  $totalLoginTimeInHours }}</td> 
           <td>{{ $totalLoginTimeInHours  }}</td>  
             <td>{{ $lastLogin->format('d-m-Y H:i') }}</td> 
            <td>{{ Carbon\Carbon::parse($item->created_at)->format('d-m-Y H:i') }}</td>

            <td>{{  $expiredLead }}</td>
            <td>{{ $TotalLeadCount }}</td>
            <td>{{ $newLeadStatusCount}}</td>
            <td>{{ $PendingLeadStatusCount}}</td>
            <td>{{ $NextFollowUpLeadStatusCount }}</td>
            <td>{{ $ConfimLeadStatusCount }}</td>
            <td>{{ $CancelLeadStatusCount }}</td>
            <td>{{ $CloseLeadStatusCount }}</td>
            <td>{{ $OldLeadStatusCount }}</td>
        </tr>
        @endforeach 
    </tbody>
</table>
