 <table class="table table-head-fixed text-nowrap text-dark table-hover table-striped table-bordered" id="dataTable" width="100%"  cellspacing="0">
    <thead>@php
       
    @endphp
        <tr>
            <th>Id</th>
            <th>image</th>
            <th>Category</th>
            <th class="text-center">action</th>
        </tr>
    </thead>
    <tbody>

        @if(count($data)>0)
        @foreach($data as $r)
        <tr>
            <td>{{$r['id']}}</td>
            <td><img src="{{$r['image']}}" alt="image" height="40px" width="40px;"></td>
            <td>{{$r['category']}}</td>
            <td class="text-center">
                <span style="cursor: pointer;" onclick="deletewallpapper({{$r['id']}})"><i class="fas fa-trash text-danger mr-1"></i></span>&nbsp;&nbsp;&nbsp;
                <span style="cursor: pointer;" onclick="editwallpapper(`{{$r['id']}}`,`{{$r['image']}}`,`{{$r['category']}}`,`{{$r['cat_id']}}`)";><i class="fas fa-edit text-primary ml-1"></i></span>
            </td>
            
        </tr>
        @endforeach
        @else
        <tr><td colspan="4">no record found</td></tr>
        @endif
    </tbody>
</table>
