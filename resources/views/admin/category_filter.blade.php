 <table class="table table-head-fixed text-nowrap text-dark table-hover table-striped table-bordered" id="dataTable" width="100%"  cellspacing="0">
    <thead>
        <tr>
            <th>id</th>
            <th>category</th>
            <th>image</th>
            <th class="text-center">action</th>
           
        </tr>
    </thead>
    <tbody>

        @if(count($data)>0)
        @foreach($data as $r)
        <tr>
            <td>{{$r['id']}}</td>
            <td>{{$r['name']}}</td>
            <td><img src="{{$r['image']}}" alt="image" height="50px" width="50px;"></td>
            <td class="text-center">
                <span style="cursor: pointer;" onclick="deleteCat({{$r['id']}})"><i class="fas fa-trash text-danger mr-1"></i></span>&nbsp;
                <span style="cursor: pointer;" onclick="editcat(`{{$r['id']}}`,`{{$r['name']}}`,`{{$r['image']}}`)";><i class="fas fa-edit text-primary ml-1"></i></span>
            </td>
            
        </tr>
        @endforeach
        @else
        <tr><td colspan="4">no record found</td></tr>
        @endif
    </tbody>
</table>
