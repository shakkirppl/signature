@extends('layouts.layout')
@section('content')
<style>


/* Adjust spacing between table rows */
#componentTable tbody tr {
    line-height: 1.2em;
    margin-bottom: 0.3em;
}

</style>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin createtable">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 col-md-6 col-sm-6 col-xs-12">
                            <h4 class="card-title">Bank Master</h4>
                        </div>
                        <div class="col-6 col-md-6 col-sm-6 col-xs-12 heading" style="text-align:end;">
                        <a href="{{ url('bank-master-create') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
                    </div>
                    </div>
                    @if ($errors->any())
            <div class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div><br />
            @endif

                    <form class="form-sample" action="{{ url('bank-master-store') }}" method="post">
                        {{ csrf_field() }}

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                <label class="col-sm-2 col-form-label required">Code:</label>
                                <div class="col-sm-9">
                                <input type="text" class="form-control" placeholder="Enter the code" name="code" required="true"  />

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label required">Bank Name</label>
                                    <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="Enter Your Bank Name" name="bank_name" required="true"  />

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label required">Account Name:</label>
                                    <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="Enter the Account Name" name="account_name"  />

                                    </div>
                                </div>
                            
                           
                            </div>
                           
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label required">Currency</label>
                                    <div class="col-sm-9">
                                    <select class="form-control" id="currency" name="currency">
                                          <option value="USD" selected>USD</option>
                                             <option value="Shilling">Shilling</option>
                                    </select>
                                    </div>
                                </div>
                            </div>
                           <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label required">Account No:</label>
                                    <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="Enter the Account No" name="account_no"  />

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label required">GL</label>
                                    <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="" name="gl"   />

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label required">Account Type</label>
                                    <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="Enter the Type" name="type"  />

                                    </div>
                                </div>
                            </div>
                           

                       
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="submitbutton">
                            <button type="submit" class="btn btn-primary mb-2 submit">Submit <i class="fas fa-save"></i></button>
                        </div>
                    </form>
               
@endsection