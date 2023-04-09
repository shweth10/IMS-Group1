@extends('layouts.main-template')
@section('title', isset($title) ? $title : 'Dashboard | Normal User')
@section('content')

<div class="row">
    <div class="col-md-12 mt-3">
    </div>
    @php
    $policies = App\Models\Policy::where('insurer_id', auth()->user()->id)->get();
    @endphp

    <h3>Policy Details</h3>
    <table class="table">
    <thead>
        <tr>
            <th>Policy ID</th>
            <th>Policy Type</th>
            <th>Coverage Information</th>
            <th>Coverage Amount</th>
            <th>Premium Amount</th>
            <th>Payment Period</th>
            <th>Policy Duration (Years)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($policies as $policy)
        <tr>
            <td>{{ $policy->id }}</td>
            <td>{{ $policy->policy_type }}</td>
            <td>
                @if(strlen($policy->coverage_information) > 20)
                    {{ substr($policy->coverage_information, 0, 20) }}...
                    <a href="#" data-toggle="modal" data-target="#coverageInfoModal{{ $policy->id }}">Read more</a>
                    <div class="modal fade" id="coverageInfoModal{{ $policy->id }}" tabindex="-1" role="dialog" aria-labelledby="coverageInfoModal{{ $policy->id }}Label" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="coverageInfoModal{{ $policy->id }}Label">Coverage Information</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    {{ $policy->coverage_information }}
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    {{ $policy->coverage_information }}
                @endif
            </td>
            <td>{{ $policy->coverage_amount }}</td>
            <td>{{ $policy->premium_amount }}</td>
            <td>{{ $policy->payment_period }}</td>
            <td>{{ $policy->policy_duration }}</td>
            <td>
                <a href="{{ route('policy.edit', $policy->id) }}" class="btn btn-primary"><i class="nav-icon fas fa-edit"></i></a>
            </td>
            <td>
                <form action="{{ route('policy.destroy', $policy->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"><i class="nav-icon fas fa-trash"></i></button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="text-center">
                <button class="btn btn-primary" data-toggle="modal" data-target="#addPolicyModal">Add New Policy Type</button>
            </div>
        </div>
    </div>

    <!-- Add Policy Modal -->
    <div class="modal fade" id="addPolicyModal" tabindex="-1" role="dialog" aria-labelledby="addPolicyModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPolicyModalLabel">Add Policy</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('policy.store') }}">
                        @csrf
                        <div class="form-group">
                        <label for="policy_type">Policy Type</label>
                        <input type="text" name="policy_type" class="form-control" id="policy_type" required>
                        </div>
                        <div class="form-group">
                        <label for="coverage_information">Coverage Information</label>
                        <input type="text" name="coverage_information" class="form-control" id="coverage_information" required>
                        </div>
                        <div class="form-group">
                        <label for="coverage_amount">Coverage Amount($)</label>
                        <input type="number" name="coverage_amount" class="form-control" id="coverage_amount" required>
                        </div>
                        <div class="form-group">
                        <label for="premium_amount">Premium Amount($)</label>
                        <input type="number" name="premium_amount" class="form-control" id="premium_amount" required>
                        </div>
                        <div class="form-group">
                            <label for="payment_period">Payment Period</label>
                            <select name="payment_period" class="form-control" id="payment_period" required>
                                <option value="monthly">Monthly</option>
                                <option value="quarterly">Quarterly</option>
                                <option value="annually">Annually</option>
                            </select>
                        </div>
                        <div class="form-group">
                        <label for="policy_duration">Policy Duration(Years)</label>
                        <input type="number" name="policy_duration" class="form-control" id="policy_duration" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

@endsection