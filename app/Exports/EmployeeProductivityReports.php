<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeeProductivityReports implements FromCollection, WithHeadings, WithMapping
{
    protected $id;
    protected $from;
    protected $to;
    

    public function __construct($id,$from, $to)
    {
        $this->id = $id;
        $this->from = $from;
        $this->to = $to;
        
    }

    public function collection()
    { 
        return DB::table('leads')  
        // ->where('assign_employee_id',$this->id)
        ->where('leads.created_at', '>', $this->from)
        ->where('leads.created_at', '<', $this->to)
        ->groupBy(DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y')"))
        ->orderBy('created_at', 'asc')
        ->get();

        $totalsRow = [
            'Total', // Date column is "Total"
            // $this->totalInboxLeads,
            // $this->totalTotalProductivity,
            $this->newCount,
            $this->pendingCount,
            $this->customerCalledCount,
            $this->callNotAnsweredCount,
            $this->nextFollowupCount,
            $this->siteVisitConductedCount,
            $this->unableToConnectCount,
            $this->caseClosedEnquiryOnlyCount,
            $this->caseClosedNotInterestedCount,
            $this->caseClosedLowBudgetCount,
            $this->caseClosedAlreadyBookedCount,
            $this->caseClosedCommonPoolCount,
            $this->reallocateCommonPoolCount,
            $this->dealConfirmedCount,
            $this->dealCancelledCount,
            $this->visitMeetingPlannedCount,
            $this->oldLeadCount,
            // Add totals for other columns as needed
        ];
 
    
        $data->push($totalsRow);
        
        return $data;
    }

    public function headings(): array
    {
        return [
            'Date',
            'Inbox Leads',
            'Total Productivity',
            'New',
            'Pending',
            'Customer Called to Enquire',
            'Call Not Answered',
            'Next Follow-up',
            'Site Visit Conducted',
            'Unable to Connect',
            'Case Closed - Enquiry Only',
            'Case Closed - Not Interested',
            'Case Closed - Low Budget',
            'Case Closed - Already Booked',
            'Case Closed - For Common Pool',
            'Reallocate from Common Pool',
            'Deal Confirmed',
            'Deal Cancelled',
            'Visit / Meeting Planned',
            'Old Lead',
            'Total Productivity',
        ];
    }

    public function map($row): array
    {
        // Calculate each column's value for the given date
        $date = date("d-m-Y", strtotime($row->created_at));
 
        $inboxLeadsCount = ($this->getInboxLeadsCount($date) > 0) ? $this->getInboxLeadsCount($date) : "0";
        $totalProductivity = ($this->getTotalProductivity($date) > 0) ? $this->getTotalProductivity($date) : "0";
        $newCount = ($this->getNewCount($date) > 0) ? $this->getNewCount($date) : "0";
        $pendingCount = ($this->getPendingCount($date) > 0) ? $this->getPendingCount($date) : "0";
        $customerCalledCount = ($this->getCustomerCalledCount($date) > 0) ? $this->getCustomerCalledCount($date) : "0";
        $callNotAnsweredCount = ($this->getCallNotAnsweredCount($date) > 0) ? $this->getCallNotAnsweredCount($date) : "0"; 
        $nextFollowupCount = ($this->getNextFollowupCount($date) > 0) ? $this->getNextFollowupCount($date) : "0";
        $siteVisitConductedCount = ($this->getSiteVisitConductedCount($date) > 0) ? $this->getSiteVisitConductedCount($date) : "0";
        $unableToConnectCount = ($this->getUnableToConnectCount($date) > 0) ? $this->getUnableToConnectCount($date) : "0";
        $caseClosedEnquiryOnlyCount = ($this->getCaseClosedEnquiryOnlyCount($date) > 0) ? $this->getCaseClosedEnquiryOnlyCount($date) : "0";
        $caseClosedNotInterestedCount = ($this->getCaseClosedNotInterestedCount($date) > 0) ? $this->getCaseClosedNotInterestedCount($date) : "0";
        $caseClosedLowBudgetCount = ($this->getCaseClosedLowBudgetCount($date) > 0) ? $this->getCaseClosedLowBudgetCount($date) : "0";
        $caseClosedAlreadyBookedCount = ($this->getCaseClosedAlreadyBookedCount($date) > 0) ? $this->getCaseClosedAlreadyBookedCount($date) : "0";
        $caseClosedCommonPoolCount = ($this->getCaseClosedCommonPoolCount($date) > 0) ? $this->getCaseClosedCommonPoolCount($date) : "0";
        $reallocateCommonPoolCount = ($this->getReallocateCommonPoolCount($date) > 0) ? $this->getReallocateCommonPoolCount($date) : "0";
        $dealConfirmedCount = ($this->getDealConfirmedCount($date) > 0) ? $this->getDealConfirmedCount($date) : "0";
        $dealCancelledCount = ($this->getDealCancelledCount($date) > 0) ? $this->getDealCancelledCount($date) : "0";
        $visitMeetingPlannedCount = ($this->getVisitMeetingPlannedCount($date) > 0) ? $this->getVisitMeetingPlannedCount($date) : "0";
        $oldLeadCount = ($this->getOldLeadCount($date) > 0) ? $this->getOldLeadCount($date) : "0";


        $total =  $newCount +
        $pendingCount +
        $customerCalledCount +
        $callNotAnsweredCount + $nextFollowupCount + $siteVisitConductedCount + $unableToConnectCount + $caseClosedEnquiryOnlyCount + $caseClosedNotInterestedCount + $caseClosedLowBudgetCount + $caseClosedAlreadyBookedCount + $caseClosedCommonPoolCount + $reallocateCommonPoolCount + $dealConfirmedCount + $dealCancelledCount + $visitMeetingPlannedCount + $oldLeadCount;
    
        // Add values for other columns as needed
    
        return [
            $date,
            $inboxLeadsCount,
            $totalProductivity,
            $newCount,
            $pendingCount,
            $customerCalledCount,
            $callNotAnsweredCount,
            $nextFollowupCount,
            $siteVisitConductedCount,
            $unableToConnectCount,
            $caseClosedEnquiryOnlyCount,
            $caseClosedNotInterestedCount,
            $caseClosedLowBudgetCount,
            $caseClosedAlreadyBookedCount,
            $caseClosedCommonPoolCount,
            $reallocateCommonPoolCount,
            $dealConfirmedCount,
            $dealCancelledCount,
            $visitMeetingPlannedCount,
            $oldLeadCount,
            ($total > 0) ? $total : "0"
            // Add values for other columns
        ];
    }
    


    private function getInboxLeadsCount($date)
    {
        return DB::table('leads')
            ->whereDate('leads.created_at', date("Y-m-d", strtotime($date))) 
            ->whereNotIn('lead_status', [16])
            ->where('assign_employee_id',$this->id)
            // ->groupBy(DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y')"))
            ->count();
    }

    private function getTotalProductivity($date)
    { 
        return DB::table('leads') 
            ->whereDate('leads.created_at', date("Y-m-d", strtotime($date))) 
            ->whereNotIn('lead_status', [16])
            // ->groupBy(DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y')"))
            ->count();
    }
 

 

    private function getNewCount($date)
    {
        return DB::table('leads')
            ->where('assign_employee_id',$this->id)
            ->whereDate('leads.created_at', date("Y-m-d", strtotime($date)))
            ->where('lead_status', 1)
            ->whereNotIn('lead_status', [16])
            ->count();
    }

    private function getPendingCount($date)
    {
        return DB::table('leads')
            ->where('assign_employee_id',$this->id)
            ->whereDate('leads.created_at', date("Y-m-d", strtotime($date)))
            ->where('lead_status', 2)
            ->whereNotIn('lead_status', [16])
            ->count();
    }

    private function getCustomerCalledCount($date)
    {
        return DB::table('leads')
            ->where('assign_employee_id',$this->id)
            ->whereDate('leads.created_at', date("Y-m-d", strtotime($date)))
            ->where('lead_status', 3)
            ->whereNotIn('lead_status', [16])
            ->count();
    }

    private function getCallNotAnsweredCount($date)
    {
        return DB::table('leads')
            ->where('assign_employee_id',$this->id)
            ->whereDate('leads.created_at', date("Y-m-d", strtotime($date)))
            ->where('lead_status', 4)
            ->whereNotIn('lead_status', [16])
            ->count();
    }

    private function getNextFollowUpCount($date)
    {
        return DB::table('leads') 
            ->where('assign_employee_id',$this->id)
            ->whereDate('leads.created_at', date("Y-m-d", strtotime($date)))
            ->whereNotIn('lead_status', [16])
            ->where('lead_status', 5)
            ->count();
    }


    private function getSiteVisitConductedCount($date)
    {
        return DB::table('leads')
            ->where('assign_employee_id',$this->id)
            ->whereDate('leads.created_at', date("Y-m-d", strtotime($date)))
            ->where('lead_status', 6)
            ->whereNotIn('lead_status', [16])
            ->count();
    }

    private function getUnableToConnectCount($date)
    {
        return DB::table('leads')
            ->where('assign_employee_id',$this->id)
            ->whereDate('leads.created_at', date("Y-m-d", strtotime($date)))
            ->where('lead_status', 7)
            ->whereNotIn('lead_status', [16])
            ->count();
    }

    private function getCaseClosedEnquiryOnlyCount($date)
    {
        return DB::table('leads')
            ->where('assign_employee_id',$this->id)
            ->whereDate('leads.created_at', date("Y-m-d", strtotime($date)))
            ->where('lead_status', 8)
            ->whereNotIn('lead_status', [16])
            ->count();
    }

    private function getCaseClosedNotInterestedCount($date)
    {
        return DB::table('leads')
            ->where('assign_employee_id',$this->id)
            ->whereDate('leads.created_at', date("Y-m-d", strtotime($date)))
            ->where('lead_status', 9)
            ->whereNotIn('lead_status', [16])
            ->count();
    }

    private function getCaseClosedLowBudgetCount($date)
    {
        return DB::table('leads')
            ->where('assign_employee_id',$this->id)
            ->whereDate('leads.created_at', date("Y-m-d", strtotime($date)))
            ->where('lead_status', 10)
            ->whereNotIn('lead_status', [16])
            ->count();
    }

    private function getCaseClosedAlreadyBookedCount($date)
    {
        return DB::table('leads')
            ->where('assign_employee_id',$this->id)
            ->whereDate('leads.created_at', date("Y-m-d", strtotime($date)))
            ->where('lead_status', 11)
            ->whereNotIn('lead_status', [16])
            ->count();
    }

    private function getCaseClosedCommonPoolCount($date)
    {
    return DB::table('leads')
        ->where('assign_employee_id',$this->id)
        ->whereDate('leads.created_at', date("Y-m-d", strtotime($date)))
        ->where('lead_status', 12)
        ->whereNotIn('lead_status', [16])
        ->count();
    }

    private function getReallocateCommonPoolCount($date)
    {
        return DB::table('leads')
            ->where('assign_employee_id',$this->id)
            ->whereDate('leads.created_at', date("Y-m-d", strtotime($date)))
            ->where('lead_status', 13)
            ->whereNotIn('lead_status', [16])
            ->count();
    }

    private function getDealConfirmedCount($date)
    {
        return DB::table('leads')
            ->where('assign_employee_id',$this->id)
            ->whereDate('leads.created_at', date("Y-m-d", strtotime($date)))
            ->where('lead_status', 14)
            ->whereNotIn('lead_status', [16])
            ->count();
    }

    private function getDealCancelledCount($date)
    {
        return DB::table('leads')
            ->where('assign_employee_id',$this->id)
            ->whereDate('leads.created_at', date("Y-m-d", strtotime($date)))
            ->where('lead_status', 15)
            ->whereNotIn('lead_status', [16])
            ->orderBy('created_at', 'asc')
            ->count();
    }

    private function getVisitMeetingPlannedCount($date)
    {
        return DB::table('leads')
            ->where('assign_employee_id',$this->id)
            ->whereDate('leads.created_at', date("Y-m-d", strtotime($date)))
            ->where('lead_status', 17)
            ->whereNotIn('lead_status', [16])
            ->count();
    }

    private function getOldLeadCount($date)
    {
        return DB::table('leads')
            ->where('assign_employee_id',$this->id)
            ->whereDate('leads.created_at', date("Y-m-d", strtotime($date)))
            ->where('lead_status', 18)
            ->whereNotIn('lead_status', [16])
            ->count();
    }



// Define similar functions for other lead statuses following the same pattern

 
    
}
