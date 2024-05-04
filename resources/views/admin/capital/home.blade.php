@extends('layout.adminLayout')
@section('title') {{__('راس المال')}}
@endsection
@section('css')
@endsection

@section('content')

    <div class="portlet light bordered">
        <div class="portlet-body">
            <div class="table-toolbar">

            </div>
            <table class="table table-striped table-bordered table-hover table-checkable order-column" id="toolsTable1">
                <thead>
                <tr>
                    <th>
                        <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                            <input type="checkbox"  name="checkAll"/>
                            <span></span>
                        </label>
                    </th>
                    <th> {{ucwords(__('cp.title'))}}</th>
                    <th> {{ucwords(__('cp.details'))}}</th>
                    <th> {{ucwords(__('cp.total'))}}</th>
                    <th> {{ucwords(__('العملة'))}}</th>

                </tr>
                </thead>
                <tbody>
                @forelse(@$capital as $capitals)
                    <tr class="odd gradeX" id="tr-{{$capitals->id}}">
                        <td>
                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                <span></span>
                            </label>
                        </td>
                        <td>{{$capitals->titel}}</td>
                        <td>{{$capitals->details}}</td>
                        <td>{{$capitals->profit}}</td>
                        <td>{{$capitals->the_currency}}</td>
                    </tr>
                @empty
                    {{__('cp.no')}}
                @endforelse
                </tbody>
            </table>

        </div>
    </div>
@endsection

@section('js')
@endsection

