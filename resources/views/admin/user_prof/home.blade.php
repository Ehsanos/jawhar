@extends('layout.adminLayout')
@section('title') {{__('توزيع الارباح')}}
@endsection
@section('css')
@endsection

@section('content')

    @if($setting->leader_profit_status == 1)

    <form method="post" id="jojo" action="{{url(app()->getLocale().'/admin/user_prof/store')}}"
          enctype="multipart/form-data" class="form-horizontal" role="form">
        {{ csrf_field() }}
    <div class="portlet light bordered">
        <div class="portlet-body">
            <div class="table-toolbar">
                <div class="row">

                    <div class="col-sm-12" style="text-align: center;padding: 10px">
                        <a href="@if(isset(request()->currency) && request()->currency == "dollar")
                        {{url(app()->getLocale().'/admin/user_prof')}}
                        @else
                        {{url(app()->getLocale().'/admin/user_prof?currency=dollar')}}
                        @endif" type="submit" class="btn sbold btn-default ">
                            @if(isset(request()->currency) && request()->currency == "dollar")
                               العملة تركي
                            @else
                                العملة دولار
                            @endif
                            <i class="fa fa-refresh"></i>
                        </a>
                        <br>
                        <br>
                        <button type="submit" class="btn green" onclick="return confirm('هل انت متأكد؟')">ترحيل</button>
                        <input type="hidden"  name="currency" value="{{$currency}}">
                        <br>
                        <br>
                        <div style="direction: rtl">
                                    أرباح كل اللاعبين الواجب ترحيلهم
                                    {{$all_sum }}
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-bordered table-hover order-column" id="toolsTable6">
                <thead>
                <tr>
                    <th width="20px">
                        <input type="checkbox" class="checkboxes chkBox" name="all" onclick="if(this.checked){$('.lolo').attr('disabled', false);$('.koko').prop('checked', this.checked);}else{$('.lolo').attr('disabled', true);$('.koko').prop('checked', this.checked);}"/>
                    </th>
                    <th> {{ucwords(__('الاب'))}}</th>
                    <th> {{ucwords(__('الاولاد'))}} ( {{ucwords(__('نسبة الربح'))}} {{$setting->leader_profit}}% )</th>
                    <th> {{ucwords(__('المجموع'))}}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($leaders as $leader)
                    <tr class="odd gradeX" id="tr-{{$leader->id}}">
                        <td>
                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                <input type="checkbox" class="koko checkboxes chkBox" name="ids[]" value="{{$leader->id}}" onclick="if(this.checked)$('#tr-'+{{$leader->id}}).find('.lolo').attr('disabled', false); else $('#tr-'+{{$leader->id}}).find('.lolo').attr('disabled', true);"/>
                                <span></span>
                            </label>
                            <input type="hidden" class="lolo" name="values[]" disabled value="{{$leader->sum}}">
                        </td>
                        <td>{{$leader->name}}</td>
                        <td style="direction: rtl">
                            @foreach($leader->rows as $child)
                                {{$child["child"]->name}} :
                                {{ucwords(__('ربح'))}} {{$child["child_profit"] ?? 0}} {{ucwords(__('نسبة ربح'))}} {{$child["leader_profit"] ?? 0}}
                                <hr style="margin: 2px" />
                            @endforeach
                        </td>
                        <td>{{$leader->sum}}</td>
                    </tr>
                @empty
                    {{__('cp.no')}}
                @endforelse
                </tbody>
            </table>

        </div>
    </div>
    </form>
    @else
        <div style="text-align: center"> الرجاء تفعيل خدمة ارباح اللاعبن من لوحة الاعدادات العامة</div>

    @endif
@endsection

@section('js')
   <script>
       $('#toolsTable6').DataTable({
           pageLength: 50,
           dom: 'Bfrtip',

           searching: true,
           "oLanguage": {
               "sSearch": "{{__('cp.search')}}"
           },
           bInfo: true, //Dont display info e.g. "Showing 1 to 4 of 4 entries"
           paging: true,//Dont want paging
           bPaginate: true,//Dont want paging
           responsive: true,
           buttons: [
              'excel', 'pdf',
           ]
       });
   </script>
@endsection

