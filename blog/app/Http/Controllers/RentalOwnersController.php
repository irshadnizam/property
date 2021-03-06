<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\RentalOwner;
use App\Model\Country;
use App\Model\Attachment;
use App\Model\DocumentMaster;
use App\Model\Property;
use Datatables;
use Illuminate\Support\Facades\DB;
use Debugbar;
use Sentinel;
use Redirect;
class RentalOwnersController extends Controller
{
    function index() {
    	$rentalowners = RentalOwner::all();
    	$countries = Country::all();
	    return view('rentalowners', [
	        'rentalowners' => $rentalowners,
	        'countries' => $countries,
	    ]);
    }
    
    function data(){
    	$t = DB::table('rentalowners')
    		->leftJoin('countries', 'rentalowners.country', '=', 'countries.id')
	    	->select('rentalownerID', 'firstName', 'lastName', 'dateOfBirth', 'email', 'phoneNumber', 'officeNumber', 'countries.countryName', 'address', 'city', 'comments');
    	return Datatables::of($t)->make(true);

    	// return Datatables::of(rentalowner::query())->make(true);
    }

    function create(Request $request) {
	    $rentalowner = new RentalOwner;
	    $rentalowner->firstName = $request->fname;
	    $rentalowner->lastName = $request->lname;
	    $rentalowner->email = $request->email;
	    $rentalowner->phoneNumber = $request->phone;
	    $rentalowner->officeNumber = $request->officephone;
	    $rentalowner->country = $request->country;
	    $rentalowner->address = $request->address;
	    $rentalowner->city = $request->city;
	    $rentalowner->comments = $request->comments;
	    $rentalowner->companyID = $request->company;
	    $rentalowner->companyID = Sentinel::getUser()->companyID;
	    $rentalowner->documentID = 2;

	    if($request->dob)
		    $rentalowner->dateOfBirth = date_create_from_format("j/m/Y", $request->dob)->format('Y-m-d');
	    		
	    $rentalowner->save();

	    return Redirect::to('rentalowners');
    }

    function edit(RentalOwner $rentalowner){
    	// Debugbar::info($rentalowner); 
    	$rentalowner = RentalOwner::find($rentalowner->rentalOwnerID);
    	$countries = Country::all();
    	$documentmaster = DocumentMaster::all();
    	$attachments = Attachment::where('documentAutoID', $rentalowner->rentalOwnerID)->where('documentID', 2)->get();
    	$ownedProperties = Property::where('rentalOwnerID', $rentalowner->rentalOwnerID)->get();
    	$countryName = (isset($rentalowner->country)) ? Country::find($rentalowner->country)->countryName : '';

	    return view('rentalowners_edit', [
	        'rentalowner' => $rentalowner,
	        'countries' => $countries,
	        'attachments' => $attachments,
	        'documentmaster' => $documentmaster,
	        'countryName' => $countryName,
	        'ownedProperties' => $ownedProperties,
	    ]);
    }

    function update(Request $request){	
    	$rentalowner = RentalOwner::find($request->rentalOwnerID);
		$rentalowner->firstName = $request->fname;
	    $rentalowner->lastName = $request->lname;
	    $rentalowner->email = $request->email;
	    $rentalowner->phoneNumber = $request->phone;
	    $rentalowner->officeNumber = $request->officephone;
	    $rentalowner->country = $request->country;
	    $rentalowner->address = $request->address;
	    $rentalowner->city = $request->city;
	    $rentalowner->comments = $request->comments;
	    $rentalowner->companyID = $request->company;

	    if($request->dob)
		    $rentalowner->dateOfBirth = date_create_from_format("j/m/Y", $request->dob)->format('Y-m-d');

	    $rentalowner->save();
	    return Redirect::to('rentalowners');
    }

    function delete(RentalOwner $rentalowner){
    	$rentalowner = RentalOwner::find($rentalowner->rentalOwnerID);
    	$rentalowner->delete();
	    return Redirect::to('rentalowners');
    }
}
 