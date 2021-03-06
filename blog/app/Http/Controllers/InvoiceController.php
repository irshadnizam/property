<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\JobCard;
use App\Model\Supplier;
use App\Model\Maintenance;
use App\Model\Company;
use App\Model\Property;
use App\Model\SupplierInvoice;
use App\Model\CustomerInvoice;
use Redirect;
use Sentinel;
use App;

class InvoiceController extends Controller
{
	/******************************************************************************************
	 * 
	 * Supplier Invoice
	 * 
	 *******************************************************************************************/


	function submitButtonHandler(Request $request){
		$this->groupingSuppliers($request);

		$this->createCustomerInvoice($request);

		// Change the isSubmitted flag everytime submit or reverse is clicked
		$jobcard = JobCard::find($request->jobcardID);
		$jobcard->isSubmitted = ($request->flag == '1') ? '0' : '1';
		$jobcard->save();

	    return Redirect::to('jobcard/edit/'.$request->jobcardID.'/maintenance');		
	}

	// Save the supplier invoice info to the db
	function createSuppliersInvoice($supplierID, $jobcardID, $total){
		$jobcard = Jobcard::find($jobcardID);
		$invoice = new SupplierInvoice();
		$invoice->supplierID = $supplierID;
		$invoice->jobcardID = $jobcardID;
		$invoice->unitID = $jobcard->unitID;
		$invoice->PropertiesID = $jobcard->PropertiesID;
		$invoice->description = 'invoice for '. $jobcard->jobCardCode;
		$invoice->amount = $total;
		$invoice->invoiceDate = date("Y-m-d H:i:s");
		$invoice->lastUpdatedByUserID = Sentinel::getUser()->id;

		$invoice->save();
	}

	// Adds all the supliers totals needed for the invoice table
	function groupingSuppliers(Request $request){
		// if 0 is returned from isSubmitted then generate the suppliers invoice 
		if($request->flag == '0'){
			// Generate supplier invoice per supplier

			// Get a list of unique supplier ids
			$supplierids = Maintenance::distinct()->get(['supplierID'])->pluck('supplierID');

			foreach ($supplierids as $supplierid) {
				// Get totals for each supplier
				$supplierTotal = Maintenance::where('supplierID', $supplierid)->sum('total');
				$this->createSuppliersInvoice($supplierid, $request->jobcardID, $supplierTotal);
			}
		}else{ //Delete all the invoices for that jobcard
			SupplierInvoice::where('jobcardID', $request->jobcardID)->delete();
		}

		// Change the isSubmitted flag everytime submit or reverse is clicked
		$jobcard = JobCard::find($request->jobcardID);
		$jobcard->isSubmitted = ($request->flag == '1') ? '0' : '1';
		$jobcard->save();
	}

	// Creates the supplier invoice page
	function supplierIndex($jobcard){
		$jobcard = JobCard::find($jobcard);
    	$supplierInvoices = SupplierInvoice::where('jobcardID', $jobcard->jobcardID)->get();
    	$customerInvoices = CustomerInvoice::where('jobcardID', $jobcard->jobcardID)->get();
    	
		return view('supplier_invoices', [
            'jobcard' => $jobcard,
            'supplierInvoices' => $supplierInvoices,
            'customerInvoices' => $customerInvoices,
	    ]);
	}

	// Returns a list of maintenace items belonging to a specific supplier and jobcard
	function getMaintenanceItems($supplierid, $jobcardID){
		$items = Maintenance::where('supplierID', $supplierid)->where('jobcardID', $jobcardID)->get();
		return $items;
	}

	// Function that generates the supplier invoice pdf 
	function supplierInvoicePDF($invoiceID){
		$pdf = App::make('dompdf.wrapper');
		$invoice = SupplierInvoice::find($invoiceID);
		$jobcard = JobCard::find($invoice->jobcardID);
    	$company = Company::find(Sentinel::getUser()->companyID);
		$items = $this->getMaintenanceItems($invoice->supplierID, $invoice->jobcardID);

		// dd($items);
		$data = array(
			'jobcard' => $jobcard,
            'company' => $company,
            'invoice' => $invoice,
            'items' => $items,
		);

		$pdf->loadView('invoice_supplier_pdf', $data , $data);
		return $pdf->stream();

		// return view('invoice_pdf', $data);
	}

	/******************************************************************************************
	 * 
	 * Customer Invoice
	 * 
	 *******************************************************************************************/
	function createCustomerInvoice(Request $request){
		$jobcard = Jobcard::find($request->jobcardID);
		$invoice = new CustomerInvoice();
		$invoice->jobcardID = $request->jobcardID;
		$invoice->unitID = $jobcard->unitID;
		$invoice->PropertiesID = $jobcard->PropertiesID;
		$invoice->propertyOwnerID = Property::find($jobcard->PropertiesID)->rentalOwnerID;
		$invoice->description = 'invoice for '. $jobcard->jobCardCode;
		$invoice->amount = $this->getJobcardGrandTotal($jobcard->jobcardID);
		$invoice->invoiceDate = date("Y-m-d H:i:s");
		$invoice->lastUpdatedByUserID = Sentinel::getUser()->id;

		$invoice->save();
	}

	// Function that generates the customer invoice pdf 
	function customerInvoicePDF($invoiceID){
		$pdf = App::make('dompdf.wrapper');
		$customerInvoice = CustomerInvoice::find($invoiceID);
		$jobcard = JobCard::find($customerInvoice->jobcardID);
    	$company = Company::find(Sentinel::getUser()->companyID);
    	$items = Maintenance::where('jobcardID', $customerInvoice->jobcardID)->get();


		// dd($items);
		$data = array(
			'jobcard' => $jobcard,
            'company' => $company,
            'customerInvoice' => $customerInvoice,
            'items' => $items,
		);

		$pdf->loadView('invoice_customer_pdf', $data , $data);
		return $pdf->stream();

		// return view('invoice_customer_pdf', $data);
		
	}

	// Returns to cost + Margin for a jobcard
	function getJobcardGrandTotal($jobcardID){
		return Maintenance::where('jobcardID', $jobcardID)->sum('netTotal');
	}



}