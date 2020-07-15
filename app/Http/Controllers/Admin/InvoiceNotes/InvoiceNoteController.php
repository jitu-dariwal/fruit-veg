<?php

namespace App\Http\Controllers\Admin\InvoiceNotes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Shop\InvoiceNotes\InvoiceNote;
use App\Shop\InvoiceNotes\Repositories\InvoiceNoteRepository;
use App\Shop\InvoiceNotes\Repositories\InvoiceNoteRepositoryInterface;
use App\Shop\InvoiceNotes\Requests\CreateInvoiceNoteRequest;
use App\Shop\InvoiceNotes\Requests\UpdateInvoiceNoteRequest;
use DB;
use Auth;

class InvoiceNoteController extends Controller
{
   /**
     * @var BrandRepositoryInterface
     */
    private $invoiceNotesRepo;
    
    /**
        * @var CategoryRepositoryInterface
    */
    private $invoicenote;

    /**
     * BrandController constructor.
     *
     * @param BrandRepositoryInterface $brandRepository
     */
    public function __construct(InvoiceNoteRepositoryInterface $invoiceNotesRepo, InvoiceNote $invoicenote)
    {
        $this->invoiceNotesRepo = $invoiceNotesRepo;
        $this->invoicenote = $invoicenote;
    }
	
	/**
     * Display Invoice notes
     *
     * @return \Illuminate\Http\Response
     */
	public function index($customer_id,$invoiceid)
    {
		$record_per_page = config('constants.RECORDS_PER_PAGE');
        $invoiceNotes = InvoiceNote::where('invoiceid',$invoiceid)->orderBy('id','desc')->paginate($record_per_page);
	    return view('admin.invoice-notes.list', compact('invoiceNotes','invoiceid','customer_id'));
    }
	
	/**
     * Create Invoice notes
     *
     * @return \Illuminate\Http\Response
     */
	public function create($customer_id,$invoiceid)
    {
	    return view('admin.invoice-notes.create', compact('invoiceid','customer_id'));
    }
	
	/**
     * Store Invoice notes
     *
     * @return \Illuminate\Http\Response
     */
	public function store(CreateInvoiceNoteRequest $request)
    {
		$data =[
		    'customer_id' =>  $request->customer_id,
		    'invoiceid'   =>  $request->invoice_id,
		    'notes'       =>  $request->notes,
		];
		$this->invoiceNotesRepo->createInvoiceNote($data);
		return redirect('admin/invoice-notes/view/'.$request->customer_id.'/'.$request->invoice_id)->with('message','Notes Added Successfully.');
    }
	
	/**
     * Edit Invoice notes
     *
     * @return \Illuminate\Http\Response
     */
	public function edit($invoiceid)
    {
		$invoiceNote = InvoiceNote::find($invoiceid);
	    return view('admin.invoice-notes.edit', compact('invoiceNote'));
    }
	
	/**
     * Update Invoice notes
     *
     * @return \Illuminate\Http\Response
     */
	public function update($invoiceid, UpdateInvoiceNoteRequest $request)
    {
		$checkInvoiceNote = InvoiceNote::find($invoiceid);
			$data=[
		      'notes' => $request->notes,
		    ];
		$update = new InvoiceNoteRepository($checkInvoiceNote);
		$update->updateInvoiceNote($data);
		return redirect('admin/invoice-notes/view/'.$checkInvoiceNote->customer_id.'/'.$checkInvoiceNote->invoiceid)->with('message','Notes Updated Successfully.');
    }
	
	/**
     * Destroy Invoice notes
     *
     * @return \Illuminate\Http\Response
     */
	public function destroy($invoiceid, Request $request)
    {
		$checkInvoiceNote = InvoiceNote::find($invoiceid);
		$note = $this->invoiceNotesRepo->findInvoiceNoteById($invoiceid);
        $noteRepo = new InvoiceNoteRepository($note);
        $noteRepo->deleteInvoiceNote();
	    return redirect('admin/invoice-notes/view/'.$checkInvoiceNote->customer_id.'/'.$checkInvoiceNote->invoiceid)->with('message','Notes Deleted Successfully.');
    }
}
