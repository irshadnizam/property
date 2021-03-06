@extends('admin_template')

@section('content')
  <section class="content-header">
      <h4><b>TENANT</b></h4>
  </section>
  <br /><br />

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#view" aria-controls="view" role="tab" data-toggle="tab">View</a></li>
    <li role="presentation"><a href="#edit" aria-controls="edit" role="tab" data-toggle="tab">Edit</a></li>
    <li role="presentation"><a href="#attachments" aria-controls="attachments" role="tab" data-toggle="tab">Attachments</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="view">
     <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <!-- /.box-header -->
                <!-- form start -->
                    
                  <div class="box-body">
                      <div class="row">
                        <b><p class="col-sm-2 control-label">First name</p></b>
                        <p class='col-sm-10 conrol-label'>{{ $tenant->firstName}}</p>
                      </div><br/>  
                      <div class="row">
                        <b><p class="col-sm-2 control-label">Last name</p></b>
                        <p class='col-sm-10 conrol-label'>{{ $tenant->lastName}}</p>
                      </div><br/>  
                      <div class="row">
                        <b><p class="col-sm-2 control-label">Date of birth</p></b>
                        <p class='col-sm-10 conrol-label format-date'>{{ $tenant->dateOfBirth}}</p>
                      </div><br/>  
                      <div class="row">
                        <b><p class="col-sm-2 control-label">Email</p></b>
                        <p class='col-sm-10 conrol-label'>{{ $tenant->email}}</p>
                      </div><br/>  
                      <div class="row">
                        <b><p class="col-sm-2 control-label">Phone</p></b>
                        <p class='col-sm-10 conrol-label'>{{ $tenant->phoneNumber}}</p>
                      </div><br/>  
                      <div class="row">
                        <b><p class="col-sm-2 control-label">Office Phone</p></b>
                        <p class='col-sm-10 conrol-label'>{{ $tenant->officeNumber}}</p>
                      </div><br/>  
                      <div class="row">
                        <b><p class="col-sm-2 control-label">Country</p></b>
                        <p class='col-sm-10 conrol-label'>{{ $countryName}}</p>
                      </div><br/>  
                      <div class="row">
                        <b><p class="col-sm-2 control-label">Address</p></b>
                        <p class='col-sm-10 conrol-label'>{{ $tenant->address}}</p>
                      </div><br/>  
                    
                      <div class="row">
                        <b><p class="col-sm-2 control-label">City</p></b>
                        <p class='col-sm-10 conrol-label'>{{ $tenant->city}}</p>
                      </div><br/>  
                      <div class="row">
                        <b><p class="col-sm-2 control-label">Comments</p></b>
                        <p class='col-sm-10 conrol-label'>{{ $tenant->comments}}</p>
                      </div><br/>  
                    
                  </div>
            </div>
        </div>
      </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="edit">
     <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" action="/tenants/update" method="POST">
                    {{ csrf_field() }}
                  <div class="box-body">
                  <input type="hidden" name="tenantsID" value="{{ $tenant->tenantsID}}">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">First name</label>
                      <div class="col-sm-10">
                        <input type="text" name="fname" value="{{ $tenant->firstName}}" class="form-control input-req"  placeholder="First name">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Last name</label>
                      <div class="col-sm-10">
                        <input type="text" name="lname" value="{{ $tenant->lastName}}" class="form-control input-req"  placeholder="Last name">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Date of birth</label>
                      <div class="col-sm-10">
                        <input type="text" name="dob" value="{{(date_create_from_format('Y-m-d', $tenant->dateOfBirth)) ? date_create_from_format('Y-m-d', $tenant->dateOfBirth)->format('j/m/Y') : null }}" class="form-control datepicker"  placeholder="Date of birth">

                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Email</label>
                      <div class="col-sm-10">
                        <input type="email" name="email" value="{{ $tenant->email}}" class="form-control input-req"  placeholder="Email">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Phone</label>
                      <div class="col-sm-10">
                        <input type="tel" name="phone" value="{{ $tenant->phoneNumber}}" class="form-control input-req"  placeholder="Please enter 10 digits">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Office Phone</label>
                      <div class="col-sm-10">
                         <input type="tel" name="officephone" value="{{ $tenant->officeNumber}}" class="form-control"  placeholder="Please enter 10 digits">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Country</label>
                      <div class="col-sm-10">
                        <select name="country" class="form-control">
                          @foreach ($countries as $country)
                              <option value="{{$country->id}}">{{ $country->countryName }}</option>
                          @endforeach
                      </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Address</label>
                      <div class="col-sm-10">
                        <textarea class="form-control" name="address" rows="2" placeholder="Address"> {{ $tenant->address}}</textarea>
                      </div>
                    </div>
                    
                    <div class="form-group">
                      <label class="col-sm-2 control-label">City</label>
                      <div class="col-sm-10">
                        <input type="text" value="{{ $tenant->city}}" name="city" class="form-control"  placeholder="City">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Comments</label>
                      <div class="col-sm-10">
                        <textarea class="form-control" name="comments"  rows="2" placeholder="Comments">{{ $tenant->comments}}</textarea>
                      </div>
                    </div>                                 
                    
                  </div>
                  <!-- /.box-body -->
                  <div class="box-footer">
                    <div class="form-buttons">
                      <input type="reset" class="btn btn-default" value="Reset" />
                      <button type="submit" class="btn bg-green pull-right">Save</button>
                    </div>
                  </div>
                  <!-- /.box-footer -->
                </form>
            </div>
        </div>
      </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="attachments">
    <br/>
      <div class="container-fluid">
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary pull-right add-btn" data-toggle="modal" data-target="#myModal">
          <i class="fa fa-plus"></i> <b>Add Attachment</b>
        </button>
        <br/><br/><br/>
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Attachment</h4>
              </div>
              <div class="modal-body">
                <div class="box box-info">
                    <div class="box-header with-border">
                      <h3 class="box-title">Add Attachment</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form class="form-horizontal" action="/attachment" method="POST" enctype="multipart/form-data">
                      {{ csrf_field() }}
                      <div class="box-body">
                        <input type="hidden" name="documentAutoID" class="form-control" value="{{ $tenant->tenantsID}}"  placeholder="Subject">
                        <input type="hidden" name="documentID" class="form-control" value="4" >
                
                        <div class="form-group">
                          <label class="col-sm-2 control-label">File Name</label>
                          <div class="col-sm-10">
                            <input type="text" name="fileNameCustom" class="form-control input-req"  placeholder="Name">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label">File Description</label>
                          <div class="col-sm-10">
                            <textarea class="form-control" name="description" rows="2" value="" placeholder="Description"></textarea>
                          </div>
                        </div>  
                        <div class="form-group">
                          <label name="tenant" class="col-sm-2 control-label">Document</label>
                          <div class="col-sm-10">
                            <input type="file" name="attachmentFile" required="required" accept="image/*">
                          </div>
                        </div>
                      </div>
                      <!-- /.box-body -->
                      <div class="box-footer">
                        <input type="reset" class="btn btn-default" value="Reset" />
                        <button type="submit" class="btn bg-green pull-right">Save</button>
                      </div>
                      <!-- /.box-footer -->
                    </form>
                </div>
              </div>    
            </div>
          </div>
        </div>
        <div class="box box-info attachments-rows">
          @foreach ($attachments as $attachment)
              <div class="attacment-item ">
                <a href="/blog/storage/app/uploads/attachments/{{$attachment->fileNameSlug}}">{{$attachment->fileNameCustom}}</a>
                <p>{{$attachment->attachmentDescription}}</p>
                <div class="edit-button">
                  <button class="btn bg-green btn-sm edit-attachment" data-id='{{ $attachment->attachmentID }}' data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit </button>

                  <input type="hidden" class='data-defined' data-id='{{ $attachment->attachmentID }}' data-documentAutoID='{{ $tenant->tenantsID }}' data-description='{{ $attachment->attachmentDescription }}' data-fileNameCustom='{{ $attachment->fileNameCustom }}' data-fileNameSlug='{{ $attachment->fileNameSlug }}' data-documentID='{{ $attachment->documentID }}'>


                  <form class="delete-form" action="/attachment/{{ $attachment->attachmentID }}" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <!-- <input type="submit" value="Delete" class="btn btn-danger btn-small"> -->
                    <a href="" class="delete-btn btn btn-danger btn-sm button--winona">
                      <span><i class="fa fa-trash" aria-hidden="true"></i> Delete</span>
                      <span class="after">Sure?</span>
                    </a>
                  </form>
                </div>

              </div>
          @endforeach
        </div>
        @component('attachments_edit')

        @endcomponent
      </div>
    </div>
  </div>

@endsection