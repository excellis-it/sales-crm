<table id="myTable" class="dd table table-striped table-hover" style="width:100%">
    <thead>
        <tr>
            <th> SL No.</th>
            <th> Ip</th>
            <th> Action</th>
        </tr>
    </thead>
    <tbody>
        @if (count($ip) == 0)
            <tr>
                <td colspan="7" class="text-center">No IP found</td>
            </tr>
        @else
            @foreach ($ip as $key => $ips)
                <tr>
                    <td>{{$key+1}}</td>
                    <td class="edit-route" data-route="{{ route('ips.edit', $ips['id']) }}">{{ $ips->ip }}</td>

                    <td>
                        <a title="Delete Ip"
                            data-route="{{ route('ips.delete', ['id' => $ips->id]) }}"  class="delete_acma"
                            href="javascipt:void(0);" id="delete"><i
                                class="fas fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="7">
                    <div class="d-flex justify-content-center">
                        {!! $ip->links() !!}
                    </div>
                </td>
            </tr>
        @endif

    </tbody>
</table>
